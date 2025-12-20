<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct()
    {
        $this->apiKey = (string) env('GEMINI_API_KEY');
    }

    // TÍNH NĂNG 3: Phân tích văn bản (Review Insight)
    // jsonMode = true: Trả về array (JSON decoded) hoặc array['error']
    // jsonMode = false: Trả về string (backward compatible)
    public function generateText(string $prompt, bool $jsonMode = false)
    {
        // Sử dụng model gemini-1.5-flash (model mới được hỗ trợ, nhanh và ổn định)
        $model = 'gemini-1.5-flash';
        $url = $this->baseUrl . $model . ':generateContent?key=' . $this->apiKey;

        // Kiểm tra API key
        if (empty($this->apiKey)) {
            Log::error('Gemini API Key is not set');
            return $jsonMode ? ['error' => 'API Key không được cấu hình.'] : 'Lỗi: API Key không được cấu hình.';
        }

        $payload = [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ],
            // Cấu hình Safety để tránh bị block oan
            'safetySettings' => [
                ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
            ]
        ];

        // Kích hoạt JSON Mode nếu cần (Dành cho chức năng 4)
        if ($jsonMode) {
            $payload['generationConfig'] = [
                'responseMimeType' => 'application/json'
            ];
        }

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                            ->timeout(60)
                            ->post($url, $payload);

            if ($response->failed()) {
                $errorMsg = 'Lỗi kết nối API: ' . $response->status();
                Log::error('Gemini API Error: ' . $response->body());
                return $jsonMode ? ['error' => $errorMsg] : $errorMsg;
            }

            $data = $response->json();

            // Kiểm tra xem có bị block không (Safety Filter)
            if (isset($data['candidates'][0]['finishReason']) && $data['candidates'][0]['finishReason'] !== 'STOP') {
                $errorMsg = 'AI từ chối trả lời do vi phạm chính sách an toàn (Safety Filter).';
                Log::warning('Gemini Safety Filter Blocked', ['finishReason' => $data['candidates'][0]['finishReason']]);
                return $jsonMode ? ['error' => $errorMsg] : $errorMsg;
            }

            // Lấy text từ response
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            
            if (empty($text)) {
                $errorMsg = 'Không có nội dung văn bản trả về.';
                return $jsonMode ? ['error' => $errorMsg] : $errorMsg;
            }

            // Nếu JSON Mode, decode luôn
            if ($jsonMode) {
                $decoded = json_decode($text, true);
                if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                    Log::error('Gemini JSON Parse Error', ['text' => $text, 'error' => json_last_error_msg()]);
                    return ['error' => 'Lỗi phân tích JSON từ AI'];
                }
                return $decoded;
            }

            return $text;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $errorMsg = 'Lỗi kết nối: Phản hồi từ AI quá lâu (Timeout).';
            Log::error('Gemini API Timeout', ['message' => $e->getMessage()]);
            return $jsonMode ? ['error' => $errorMsg] : $errorMsg;
        } catch (\Throwable $e) {
            $errorMsg = 'Lỗi hệ thống: ' . $e->getMessage();
            Log::error('Gemini API Exception', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return $jsonMode ? ['error' => $errorMsg] : $errorMsg;
        }
    }

    // TÍNH NĂNG 5: Phân tích hình ảnh (Vision)
    public function analyzeImage(string $prompt, string $imagePath): string
    {
        // Kiểm tra file tồn tại
        if (!file_exists($imagePath)) {
            return 'Lỗi: File ảnh không tồn tại.';
        }

        // Kiểm tra API key
        if (empty($this->apiKey)) {
            Log::error('Gemini API Key is not set');
            return 'Lỗi: API Key không được cấu hình.';
        }

        // Sử dụng model gemini-1.5-flash (model mới được hỗ trợ, nhanh và ổn định)
        $model = 'gemini-1.5-flash';
        $url = $this->baseUrl . $model . ':generateContent?key=' . $this->apiKey;
        
        $imageData = base64_encode(file_get_contents($imagePath));
        $mimeType = mime_content_type($imagePath) ?: 'image/png';
        
        $payload = [
            'contents' => [[
                'parts' => [
                    ['text' => $prompt],
                    ['inline_data' => ['mime_type' => $mimeType, 'data' => $imageData]]
                ]
            ]],
            // Cấu hình Safety để tránh bị block oan
            'safetySettings' => [
                ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
            ]
        ];

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                            ->timeout(60)
                            ->post($url, $payload);

            if ($response->failed()) {
                $errorMsg = 'Lỗi kết nối API: ' . $response->status();
                Log::error('Gemini Vision API Error: ' . $response->body());
                return $errorMsg;
            }

            $data = $response->json();

            // Kiểm tra xem có bị block không (Safety Filter)
            if (isset($data['candidates'][0]['finishReason']) && $data['candidates'][0]['finishReason'] !== 'STOP') {
                $errorMsg = 'AI từ chối trả lời do vi phạm chính sách an toàn (Safety Filter).';
                Log::warning('Gemini Vision Safety Filter Blocked', ['finishReason' => $data['candidates'][0]['finishReason']]);
                return $errorMsg;
            }

            // Lấy text từ response
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            
            if (empty($text)) {
                return 'Không có nội dung văn bản trả về.';
            }

            return $text;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $errorMsg = 'Lỗi kết nối: Phản hồi từ AI quá lâu (Timeout).';
            Log::error('Gemini Vision API Timeout', ['message' => $e->getMessage()]);
            return $errorMsg;
        } catch (\Throwable $e) {
            $errorMsg = 'Lỗi hệ thống: ' . $e->getMessage();
            Log::error('Gemini Vision API Exception', ['message' => $e->getMessage()]);
            return $errorMsg;
        }
    }

    // TÍNH NĂNG 4: Tạo Vector (Embedding)
    // Đảm bảo cố định model để tránh Vector Dimension Mismatch
    public function generateEmbedding(string $text): ?array
    {
        // Dùng model chuyên dụng cho embedding, không dùng model chat
        // text-embedding-004 có 768 dimensions
        $model = 'text-embedding-004';
        $url = $this->baseUrl . $model . ':embedContent?key=' . $this->apiKey;

        if (empty($this->apiKey)) {
            Log::error('Gemini API Key is not set');
            return null;
        }

        $body = [
            'model' => "models/$model",
            'content' => ['parts' => [['text' => $text]]]
        ];

        try {
            $response = Http::timeout(30)->post($url, $body);
            
            if ($response->successful()) {
                $json = $response->json();
                // Google returns {embedding: {values: [...]}}
                if (isset($json['embedding']['values']) && is_array($json['embedding']['values'])) {
                    return $json['embedding']['values'];
                }
                // Some SDKs return { embeddings: [{values: [...]}] }
                if (isset($json['embeddings'][0]['values']) && is_array($json['embeddings'][0]['values'])) {
                    return $json['embeddings'][0]['values'];
                }
            }
            
            Log::error('Gemini Embedding Error: ' . $response->body());
            return null;
        } catch (\Throwable $e) {
            Log::error('Gemini Embedding Exception: ' . $e->getMessage());
            return null;
        }
    }

    // Hàm hỗ trợ làm sạch JSON cho Tính năng 4 (Fallback nếu JSON Mode không hoạt động)
    public function parseJsonFromText(string $text): ?array
    {
        // 1. Tìm chuỗi nằm giữa { và } đầu tiên và cuối cùng
        if (preg_match('/\{.*\}/s', $text, $matches)) {
            $jsonString = $matches[0];
            $decoded = json_decode($jsonString, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }
        return null; // Trả về null nếu không tìm thấy JSON hợp lệ
    }
}

