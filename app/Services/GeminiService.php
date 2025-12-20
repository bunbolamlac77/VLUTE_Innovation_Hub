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
    public function generateText(string $prompt): string
    {
        // Ưu tiên bản ổn định 1.5 trước, bản 2.0 experimental để sau
        $models = [
            'gemini-1.5-flash',
            'gemini-2.0-flash-exp',
            'gemini-2.0-flash',
            'gemini-pro'
        ];
        
        foreach ($models as $model) {
            $url = $this->baseUrl . $model . ':generateContent?key=' . $this->apiKey;
            $body = ['contents' => [['parts' => [['text' => $prompt]]]]];
            $result = $this->callApi($url, $body);
            
            // Nếu thành công, trả về kết quả
            if (!str_contains($result, 'Lỗi API: 404')) {
                return $result;
            }
        }
        
        // Nếu tất cả model đều fail, trả về lỗi cuối cùng
        return $result;
    }

    // TÍNH NĂNG 5: Phân tích hình ảnh (Vision)
    public function analyzeImage(string $prompt, string $imagePath): string
    {
        // Kiểm tra file tồn tại
        if (!file_exists($imagePath)) {
            return 'Lỗi: File ảnh không tồn tại.';
        }

        // Ưu tiên bản ổn định 1.5 trước, bản 2.0 experimental để sau
        $models = [
            'gemini-1.5-flash',
            'gemini-2.0-flash-exp',
            'gemini-2.0-flash',
            'gemini-pro-vision'
        ];
        
        $imageData = base64_encode(file_get_contents($imagePath));
        $mimeType = mime_content_type($imagePath) ?: 'image/png';
        
        foreach ($models as $model) {
            $url = $this->baseUrl . $model . ':generateContent?key=' . $this->apiKey;
            
            $body = [
                'contents' => [[
                    'parts' => [
                        ['text' => $prompt],
                        ['inline_data' => ['mime_type' => $mimeType, 'data' => $imageData]]
                    ]
                ]]
            ];
            
            $result = $this->callApi($url, $body);
            
            // Nếu thành công, trả về kết quả
            if (!str_contains($result, 'Lỗi API: 404')) {
                return $result;
            }
        }
        
        // Nếu tất cả model đều fail, trả về lỗi cuối cùng
        return $result;
    }

    // TÍNH NĂNG 4: Tạo Vector (Embedding)
    public function generateEmbedding(string $text): ?array
    {
        $url = $this->baseUrl . 'text-embedding-004:embedContent?key=' . $this->apiKey;

        $body = [
            'model' => 'models/text-embedding-004',
            'content' => ['parts' => [['text' => $text]]]
        ];
        try {
            $response = Http::post($url, $body);
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

    // Hàm gọi API chung
    private function callApi(string $url, array $body): string
    {
        try {
            // Kiểm tra API key
            if (empty($this->apiKey)) {
                Log::error('Gemini API Key is not set');
                return 'Lỗi: API Key không được cấu hình.';
            }

            // FIX: Thêm timeout(60) giây để chờ AI xử lý
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->timeout(60)
                ->post($url, $body);

            // Log response cho debug
            Log::info('Gemini API Response', [
                'status' => $response->status(),
                'url' => $url,
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $json = $response->json();
                // Try standard path
                $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if (is_string($text) && $text !== '') {
                    return $text;
                }
                // Bổ sung: Handle trường hợp AI trả về cấu trúc lạ
                if (isset($json['candidates'][0]['content']['parts'][0])) {
                    $part = $json['candidates'][0]['content']['parts'][0];
                    if (is_string($part)) {
                        return $part;
                    }
                }
                // Fallback to aggregated text pieces
                if (isset($json['candidates'][0]['content']['parts']) && is_array($json['candidates'][0]['content']['parts'])) {
                    $parts = $json['candidates'][0]['content']['parts'];
                    $texts = [];
                    foreach ($parts as $p) {
                        if (isset($p['text'])) $texts[] = $p['text'];
                    }
                    if ($texts) {
                        return implode("\n\n", $texts);
                    }
                }
                return 'Không có nội dung văn bản trả về.';
            }

            // Xử lý lỗi chi tiết
            $statusCode = $response->status();
            $errorBody = $response->body();
            
            Log::error('Gemini API Error', [
                'status' => $statusCode,
                'body' => $errorBody,
                'url' => $url
            ]);

            if ($statusCode === 404) {
                return 'Lỗi API: 404 - Endpoint không tồn tại hoặc API Key không hợp lệ.';
            } elseif ($statusCode === 401) {
                return 'Lỗi API: 401 - API Key không hợp lệ.';
            } elseif ($statusCode === 429) {
                return 'Lỗi API: 429 - Quá nhiều yêu cầu. Vui lòng thử lại sau.';
            } elseif ($response->serverError()) {
                // Bổ sung: Handle lỗi server quá tải (503/500)
                return 'Lỗi hệ thống AI (Server Error). Vui lòng thử lại.';
            } else {
                return 'Lỗi API: ' . $statusCode;
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Bắt lỗi timeout cụ thể
            Log::error('Gemini API Timeout', [
                'message' => $e->getMessage(),
                'url' => $url
            ]);
            return 'Lỗi kết nối: Phản hồi từ AI quá lâu (Timeout).';
        } catch (\Throwable $e) {
            Log::error('Gemini API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 'Lỗi hệ thống: ' . $e->getMessage();
        }
    }
}

