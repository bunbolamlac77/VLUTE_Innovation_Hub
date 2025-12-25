<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PlagiarismService;

class PlagiarismController extends Controller
{
    protected $service;

    public function __construct(PlagiarismService $service)
    {
        $this->service = $service;
    }

    public function check(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:3',
        ]);

        // Lấy tiêu đề dự án làm từ khóa tìm kiếm
        $title = trim($request->input('title'));
        
        // Tạo query linh hoạt hơn: không dùng dấu ngoặc kép cho toàn bộ tiêu đề
        // Vì dấu ngoặc kép yêu cầu tìm chính xác cụm từ, có thể quá strict
        // Thay vào đó, tách tiêu đề thành các từ khóa quan trọng
        
        // Loại bỏ các từ quá ngắn và stop words
        $words = explode(' ', mb_strtolower($title));
        $stopWords = ['hệ', 'thống', 'với', 'cho', 'và', 'của', 'trong', 'trên', 'từ', 'đến', 'một', 'các', 'những', 'bằng', 'là'];
        $keywords = array_filter($words, function($word) use ($stopWords) {
            $word = trim($word);
            return !empty($word) && mb_strlen($word) > 2 && !in_array($word, $stopWords);
        });
        
        // Lấy 3-5 từ khóa quan trọng nhất (ưu tiên từ dài hơn)
        usort($keywords, function($a, $b) {
            return mb_strlen($b) - mb_strlen($a);
        });
        $mainKeywords = array_slice($keywords, 0, 5);
        
        // Tạo query: các từ khóa chính + từ khóa liên quan
        $query = implode(' ', $mainKeywords) . ' sáng chế bằng sáng chế dự án giải pháp';
        
        $result = $this->service->checkPlagiarism($query, $title);

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
            'results' => $result['data'] ?? []
        ]);
    }
}

