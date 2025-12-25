<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlagiarismService
{
    protected $apiKey;
    protected $cx;

    public function __construct()
    {
        $this->apiKey = env('GOOGLE_SEARCH_API_KEY');
        $this->cx = env('GOOGLE_SEARCH_ENGINE_ID');
    }

    /**
     * Tìm kiếm ý tưởng trùng lặp trên internet
     */
    public function checkPlagiarism($query, $originalTitle = null)
    {
        if (empty($this->apiKey) || empty($this->cx)) {
            return ['success' => false, 'error' => 'Chưa cấu hình Google API Key.'];
        }

        try {
            // Gọi Google Custom Search API
            // Tăng số lượng kết quả để có nhiều lựa chọn hơn khi lọc
            $response = Http::timeout(30)
                ->get('https://www.googleapis.com/customsearch/v1', [
                    'key' => $this->apiKey,
                    'cx' => $this->cx,
                    'q' => $query,
                    'num' => 10, // Lấy 10 kết quả để có nhiều lựa chọn khi lọc
                ]);

            // Kiểm tra HTTP status code
            // Laravel Http facade trả về Response object với các methods: successful(), body(), status(), json()
            if (!$response->successful()) {
                $errorBody = $response->body();
                $statusCode = $response->status();
                Log::error('Google Search HTTP Error', [
                    'status' => $statusCode,
                    'body' => $errorBody
                ]);
                return ['success' => false, 'error' => 'Lỗi kết nối Google API (HTTP ' . $statusCode . ').'];
            }

            $data = $response->json();

            // Kiểm tra xem có lỗi trong response body không (Google API có thể trả về 200 nhưng có error)
            if (isset($data['error'])) {
                $errorMessage = $data['error']['message'] ?? 'Lỗi không xác định từ Google API.';
                $errorCode = $data['error']['code'] ?? 'UNKNOWN';
                Log::error('Google Search API Error', [
                    'code' => $errorCode,
                    'message' => $errorMessage,
                    'errors' => $data['error']['errors'] ?? []
                ]);
                return ['success' => false, 'error' => 'Lỗi Google API: ' . $errorMessage . ' (Code: ' . $errorCode . ')'];
            }

            // Lấy items từ response
            $items = $data['items'] ?? [];

            // Nếu không có items, có thể là không tìm thấy kết quả (không phải lỗi)
            if (empty($items)) {
                return ['success' => true, 'data' => []];
            }

            // Lọc và sắp xếp kết quả theo độ liên quan
            if ($originalTitle) {
                // Tách tiêu đề thành các từ khóa quan trọng
                $titleWords = array_filter(explode(' ', mb_strtolower($originalTitle)), function($w) {
                    $w = trim($w);
                    // Giữ lại các từ có độ dài > 2 (bao gồm cả "AI", "IoT" nếu có)
                    // Loại bỏ stop words
                    $stopWords = ['hệ', 'thống', 'với', 'cho', 'và', 'của', 'trong', 'trên', 'từ', 'đến', 'một', 'các', 'những', 'bằng', 'là'];
                    return mb_strlen($w) > 2 && !in_array($w, $stopWords);
                });
                
                // Tính điểm liên quan cho mỗi kết quả
                $scoredItems = array_map(function($item) use ($titleWords) {
                    $title = mb_strtolower($item['title'] ?? '');
                    $snippet = mb_strtolower($item['snippet'] ?? '');
                    $text = $title . ' ' . $snippet;
                    
                    // Tính điểm: số từ khóa xuất hiện
                    // Ưu tiên từ khóa xuất hiện trong title (2 điểm) hơn snippet (1 điểm)
                    $score = 0;
                    foreach ($titleWords as $word) {
                        if (mb_strpos($title, $word) !== false) {
                            $score += 2; // Từ khóa trong title quan trọng hơn
                        } elseif (mb_strpos($snippet, $word) !== false) {
                            $score += 1; // Từ khóa trong snippet
                        }
                    }
                    
                    return [
                        'item' => $item,
                        'score' => $score
                    ];
                }, $items);
                
                // Sắp xếp theo điểm giảm dần
                usort($scoredItems, function($a, $b) {
                    return $b['score'] - $a['score'];
                });
                
                // Lọc: chỉ giữ lại các kết quả có điểm > 0 (có ít nhất 1 từ khóa khớp)
                // Ưu tiên các kết quả có điểm cao hơn
                $filteredItems = array_filter($scoredItems, function($scored) {
                    return $scored['score'] > 0;
                });
                
                // Nếu có kết quả sau khi lọc, dùng kết quả đã lọc và sắp xếp
                // Nếu không, trả về tất cả kết quả (để người dùng tự đánh giá)
                if (!empty($filteredItems)) {
                    $items = array_map(function($scored) {
                        return $scored['item'];
                    }, array_slice($filteredItems, 0, 5)); // Lấy top 5 kết quả có điểm cao nhất
                } else {
                    // Nếu không có kết quả nào khớp, vẫn trả về top 5 kết quả từ Google
                    // (có thể không liên quan nhưng vẫn hiển thị để người dùng tự đánh giá)
                    $items = array_slice($items, 0, 5);
                }
            } else {
                // Nếu không có originalTitle, chỉ lấy 5 kết quả đầu tiên
                $items = array_slice($items, 0, 5);
            }

            // Định dạng lại kết quả cho đẹp
            $formattedResults = array_map(function($item) {
                return [
                    'title' => $item['title'] ?? 'Không có tiêu đề',
                    'link' => $item['link'] ?? '#',
                    'snippet' => $item['snippet'] ?? '', // Mô tả ngắn
                    'source' => parse_url($item['link'] ?? '', PHP_URL_HOST) ?? 'unknown' // Lấy tên miền nguồn
                ];
            }, array_slice($items, 0, 5)); // Chỉ lấy 5 kết quả đầu tiên

            return ['success' => true, 'data' => $formattedResults];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Google Search Connection Exception', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => 'Lỗi kết nối: Không thể kết nối đến Google API. Vui lòng thử lại sau.'];
        } catch (\Exception $e) {
            Log::error('Plagiarism Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'error' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }
}

