<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.groq.com/openai/v1';
    protected string $model;
    protected ?string $openaiApiKey; // Dùng cho embedding nếu cần

    public function __construct()
    {
        $this->apiKey = (string) env('GROQ_API_KEY', '');
        $this->model = (string) env('GROQ_MODEL', 'llama-3.1-70b-versatile');
        $this->openaiApiKey = env('OPENAI_API_KEY'); // Optional, dùng cho embedding
    }

    /**
     * Tạo văn bản từ prompt (tương đương generateText của Gemini)
     * 
     * @param string $prompt Prompt đầu vào
     * @param bool $jsonMode Nếu true, cố gắng parse JSON từ response
     * @param float $temperature Độ sáng tạo (0.0 - 2.0)
     * @param int $maxTokens Số token tối đa (mặc định 4096 để có câu trả lời dài)
     * @return string|array Trả về string hoặc array nếu jsonMode = true
     */
    public function generateText(string $prompt, bool $jsonMode = false, float $temperature = 0.7, int $maxTokens = 4096)
    {
        if (empty($this->apiKey)) {
            Log::error('Groq API Key is not set');
            return $jsonMode ? ['error' => 'API Key không được cấu hình.'] : 'Lỗi: API Key không được cấu hình.';
        }

        $messages = [
            ['role' => 'system', 'content' => 'Bạn là một trợ lý AI thông minh và chuyên nghiệp. Hãy trả lời chi tiết, đầy đủ và hữu ích.'],
            ['role' => 'user', 'content' => $prompt]
        ];

        $payload = [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
        ];

        // Lưu ý: Groq có thể không hỗ trợ response_format như OpenAI
        // Nên chúng ta sẽ dựa vào prompt để yêu cầu JSON và parse sau
        // if ($jsonMode) {
        //     $payload['response_format'] = ['type' => 'json_object'];
        // }

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(60)
                ->post($this->baseUrl . '/chat/completions', $payload);

            if ($response->failed()) {
                $errorBody = $response->json();
                $errorDetail = $errorBody['error']['message'] ?? $response->body();
                $errorMsg = 'Lỗi kết nối API: ' . $response->status() . ' - ' . $errorDetail;
                Log::error('Groq API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'error' => $errorBody
                ]);
                return $jsonMode ? ['error' => $errorMsg] : $errorMsg;
            }

            $data = $response->json();
            $text = $data['choices'][0]['message']['content'] ?? '';

            if (empty($text)) {
                $errorMsg = 'Không có nội dung văn bản trả về.';
                return $jsonMode ? ['error' => $errorMsg] : $errorMsg;
            }

            // Nếu JSON Mode, decode luôn
            if ($jsonMode) {
                $decoded = json_decode($text, true);
                if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                    // Fallback: thử parse JSON từ text
                    $decoded = $this->parseJsonFromText($text);
                    if ($decoded === null) {
                        Log::error('Groq JSON Parse Error', ['text' => $text, 'error' => json_last_error_msg()]);
                        return ['error' => 'Lỗi phân tích JSON từ AI'];
                    }
                }
                return $decoded;
            }

            return $text;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $errorMsg = 'Lỗi kết nối: Phản hồi từ AI quá lâu (Timeout).';
            Log::error('Groq API Timeout', ['message' => $e->getMessage()]);
            return $jsonMode ? ['error' => $errorMsg] : $errorMsg;
        } catch (\Throwable $e) {
            $errorMsg = 'Lỗi hệ thống: ' . $e->getMessage();
            Log::error('Groq API Exception', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return $jsonMode ? ['error' => $errorMsg] : $errorMsg;
        }
    }

    /**
     * Phân tích hình ảnh (Vision) - tương đương analyzeImage của Gemini
     * 
     * @param string $prompt Prompt mô tả yêu cầu
     * @param string $imagePath Đường dẫn đến file ảnh
     * @return string Kết quả phân tích
     */
    public function analyzeImage(string $prompt, string $imagePath): string
    {
        if (!file_exists($imagePath)) {
            return 'Lỗi: File ảnh không tồn tại.';
        }

        if (empty($this->apiKey)) {
            Log::error('Groq API Key is not set');
            return 'Lỗi: API Key không được cấu hình.';
        }

        // Groq hỗ trợ vision qua base64
        $imageData = base64_encode(file_get_contents($imagePath));
        $mimeType = mime_content_type($imagePath) ?: 'image/png';

        $messages = [
            [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => $prompt
                    ],
                    [
                        'type' => 'image_url',
                        'image_url' => [
                            'url' => 'data:' . $mimeType . ';base64,' . $imageData
                        ]
                    ]
                ]
            ]
        ];

        // Lưu ý: Groq hiện tại có thể không hỗ trợ vision model trực tiếp
        // Nếu model vision không hoạt động, sẽ fallback về model text thông thường
        $visionModel = 'llama-3.2-11b-vision-preview';
        
        // Thử với vision model trước, nếu không được sẽ fallback
        $payload = [
            'model' => $visionModel,
            'messages' => $messages,
            'max_tokens' => 2048,
        ];

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(60)
                ->post($this->baseUrl . '/chat/completions', $payload);

            if ($response->failed()) {
                // Fallback: nếu vision model không hoạt động, thử với model text thông thường
                // và mô tả ảnh bằng text prompt
                Log::warning('Groq Vision API failed, trying fallback with text model');
                $fallbackPrompt = $prompt . "\n\nLưu ý: Hệ thống không thể phân tích trực tiếp hình ảnh. Vui lòng mô tả nội dung hình ảnh để nhận được phân tích chi tiết.";
                return $this->generateText($fallbackPrompt, false, 0.7, 2048);
            }

            $data = $response->json();
            $text = $data['choices'][0]['message']['content'] ?? '';

            if (empty($text)) {
                return 'Không có nội dung văn bản trả về.';
            }

            return $text;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $errorMsg = 'Lỗi kết nối: Phản hồi từ AI quá lâu (Timeout).';
            Log::error('Groq Vision API Timeout', ['message' => $e->getMessage()]);
            return $errorMsg;
        } catch (\Throwable $e) {
            $errorMsg = 'Lỗi hệ thống: ' . $e->getMessage();
            Log::error('Groq Vision API Exception', ['message' => $e->getMessage()]);
            return $errorMsg;
        }
    }

    /**
     * Tạo embedding vector
     * 
     * LƯU Ý: Groq không có embedding API riêng.
     * Nếu có OpenAI API key, sẽ sử dụng OpenAI embeddings (tùy chọn).
     * Nếu không có, trả về null (embedding bị vô hiệu hóa).
     * 
     * @param string $text Văn bản cần tạo embedding
     * @return array|null Vector embedding hoặc null nếu không khả dụng
     */
    public function generateEmbedding(string $text): ?array
    {
        // Nếu có OpenAI API key, sử dụng OpenAI embeddings (tùy chọn)
        if (!empty($this->openaiApiKey)) {
            return $this->generateOpenAIEmbedding($text);
        }

        // Groq không hỗ trợ embedding, trả về null
        // Tính năng embedding chỉ khả dụng nếu có OpenAI API key
        return null;
    }

    /**
     * Tạo embedding sử dụng OpenAI API
     * 
     * @param string $text Văn bản cần tạo embedding
     * @return array|null Vector embedding
     */
    protected function generateOpenAIEmbedding(string $text): ?array
    {
        try {
            $response = Http::withToken($this->openaiApiKey)
                ->timeout(30)
                ->post('https://api.openai.com/v1/embeddings', [
                    'model' => 'text-embedding-3-small', // Model nhỏ, rẻ, 1536 dimensions
                    'input' => $text
                ]);

            if ($response->successful()) {
                $json = $response->json();
                if (isset($json['data'][0]['embedding']) && is_array($json['data'][0]['embedding'])) {
                    return $json['data'][0]['embedding'];
                }
            }

            // Parse error message chi tiết hơn
            $errorBody = $response->json();
            $errorMessage = $errorBody['error']['message'] ?? $response->body();
            $errorCode = $errorBody['error']['code'] ?? 'unknown';
            
            Log::error('OpenAI Embedding Error', [
                'status' => $response->status(),
                'code' => $errorCode,
                'message' => $errorMessage,
                'body' => $response->body()
            ]);
            
            return null;
        } catch (\Throwable $e) {
            Log::error('OpenAI Embedding Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Hàm hỗ trợ parse JSON từ text (fallback)
     * Cải thiện để xử lý trường hợp Groq trả về JSON kèm text giải thích
     * 
     * @param string $text Text có thể chứa JSON
     * @return array|null JSON decoded hoặc null
     */
    public function parseJsonFromText(string $text): ?array
    {
        // Loại bỏ markdown code blocks nếu có
        $text = preg_replace('/```json\s*/i', '', $text);
        $text = preg_replace('/```\s*/', '', $text);
        $text = trim($text);
        
        // Thử parse trực tiếp trước
        $decoded = json_decode($text, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
        
        // Tìm chuỗi JSON nằm giữa { và } đầu tiên và cuối cùng
        if (preg_match('/\{[^{}]*(?:\{[^{}]*\}[^{}]*)*\}/s', $text, $matches)) {
            $jsonString = $matches[0];
            $decoded = json_decode($jsonString, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }
        
        // Thử tìm JSON object với regex mạnh hơn
        if (preg_match('/\{[\s\S]*\}/', $text, $matches)) {
            $jsonString = $matches[0];
            // Loại bỏ các ký tự không hợp lệ ở đầu/cuối
            $jsonString = trim($jsonString);
            $decoded = json_decode($jsonString, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }
        
        return null;
    }

    /**
     * Chat với messages array (phương thức chính cho chat)
     * 
     * @param array $messages Mảng messages theo format OpenAI
     * @param float $temperature Độ sáng tạo
     * @param int $maxTokens Số token tối đa
     * @return string Response text
     */
    public function chat(array $messages, float $temperature = 0.7, int $maxTokens = 4096): string
    {
        if (empty($this->apiKey)) {
            Log::error('Groq API Key is not set');
            return 'Lỗi: API Key không được cấu hình.';
        }

        $payload = [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
        ];

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(60)
                ->post($this->baseUrl . '/chat/completions', $payload);

            if ($response->failed()) {
                Log::error('Groq API Error: ' . $response->body());
                return 'Xin lỗi, AI đang bận. Vui lòng thử lại sau.';
            }

            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? 'Không có nội dung trả về.';

        } catch (\Exception $e) {
            Log::error('Groq Connection Error: ' . $e->getMessage());
            return 'Lỗi kết nối đến hệ thống AI.';
        }
    }
}

