<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PlagiarismService;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Log;

class PlagiarismController extends Controller
{
    protected $service;
    protected $geminiService;

    public function __construct(PlagiarismService $service, GeminiService $geminiService)
    {
        $this->service = $service;
        $this->geminiService = $geminiService;
    }

    public function check(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:3',
            'description' => 'nullable|string'
        ]);

        $title = trim($request->input('title'));
        $description = $request->input('description') ?? '';
        
        // --- BƯỚC 1: DÙNG AI ĐỂ TẠO CÂU TRUY VẤN TỐI ƯU ---
        // Làm sạch mô tả (loại bỏ HTML tags và giới hạn độ dài)
        $cleanDescription = strip_tags($description);
        $cleanDescription = mb_substr($cleanDescription, 0, 200);
        
        $prompt = "Tôi muốn tìm kiếm xem ý tưởng này có bị đạo văn không.
Tiêu đề: \"$title\"
Mô tả: \"$cleanDescription\"

Hãy trích xuất cho tôi 3-4 từ khóa (keywords) quan trọng nhất mô tả công nghệ hoặc giải pháp cốt lõi của ý tưởng này.
Ví dụ: nếu ý tưởng là 'Hệ thống tưới cây dùng IoT', hãy trả về: 'tưới cây tự động IoT'.

Chỉ trả về chuỗi từ khóa, không giải thích gì thêm.";

        // Gọi AI (Nếu AI lỗi thì fallback về dùng Tiêu đề gốc)
        $smartQuery = $this->geminiService->generateText($prompt, false);
        
        // Kiểm tra xem kết quả có phải là lỗi không
        // GeminiService trả về các string lỗi với các prefix: "Lỗi:", "Lỗi kết nối", "AI từ chối", "Không có nội dung"
        $isError = false;
        if (is_string($smartQuery)) {
            $errorPrefixes = ['Lỗi:', 'Lỗi kết nối', 'AI từ chối', 'Không có nội dung', 'Lỗi hệ thống'];
            foreach ($errorPrefixes as $prefix) {
                if (str_starts_with($smartQuery, $prefix)) {
                    $isError = true;
                    break;
                }
            }
        }
        
        // Làm sạch kết quả từ AI (xóa dấu nháy, xuống dòng, và các ký tự không cần thiết)
        $searchQuery = $smartQuery ? trim(str_replace(['"', "'", "\n", "\r"], '', $smartQuery)) : $title;
        
        // Nếu kết quả từ AI là lỗi, quá dài, hoặc có vẻ không hợp lệ, fallback về logic cũ
        if ($isError || empty($searchQuery) || mb_strlen($searchQuery) > 100) {
            // Log lỗi nếu có để debug
            if ($isError) {
                Log::warning('Plagiarism: Gemini API error, using fallback', [
                    'error' => $smartQuery,
                    'title' => $title
                ]);
            }
            
            // Fallback: dùng logic cũ để tạo từ khóa
            $words = explode(' ', mb_strtolower($title));
            $stopWords = ['hệ', 'thống', 'với', 'cho', 'và', 'của', 'trong', 'trên', 'từ', 'đến', 'một', 'các', 'những', 'bằng', 'là'];
            $keywords = array_filter($words, function($word) use ($stopWords) {
                $word = trim($word);
                return !empty($word) && mb_strlen($word) > 2 && !in_array($word, $stopWords);
            });
            
            usort($keywords, function($a, $b) {
                return mb_strlen($b) - mb_strlen($a);
            });
            $mainKeywords = array_slice($keywords, 0, 5);
            $searchQuery = implode(' ', $mainKeywords);
        }
        
        // --- BƯỚC 2: TÌM KIẾM GOOGLE VỚI TỪ KHÓA ĐÃ TỐI ƯU ---
        $result = $this->service->checkPlagiarism($searchQuery, $title);

        // Kiểm tra cả success và error
        if (isset($result['success']) && $result['success'] === false) {
            return response()->json([
                'success' => false,
                'message' => $result['error'] ?? 'Lỗi không xác định.'
            ], 500);
        }

        // Trả về kết quả thành công (có thể là mảng rỗng nếu không tìm thấy)
        return response()->json([
            'success' => true,
            'search_query' => $searchQuery, // Trả về để hiện cho user biết đang tìm từ khóa gì
            'results' => $result['data'] ?? []
        ]);
    }
}

