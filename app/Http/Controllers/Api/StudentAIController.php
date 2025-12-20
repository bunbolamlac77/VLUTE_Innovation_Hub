<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Log;

class StudentAIController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * AI Cố vấn Kinh doanh: Phân tích sâu ý tưởng để phục vụ Doanh nghiệp
     */
    public function analyzeBusinessPlan(Request $request)
    {
        $request->validate([
            'content' => 'required|string|min:20',
        ]);

        $ideaContent = $request->input('content');

        // Prompt được thiết kế để tạo ra nội dung dài, chi tiết, chuyên nghiệp
        $prompt = "
            Đóng vai là một Chuyên gia Tư vấn Chiến lược Kinh doanh (Business Strategy Consultant) hàng đầu thế giới.
            Tôi là sinh viên có ý tưởng khởi nghiệp sau: \"$ideaContent\"

            Hãy giúp tôi lập một BẢN KẾ HOẠCH KINH DOANH CHI TIẾT (Business Plan) để thuyết phục các doanh nghiệp đầu tư.
            
            Yêu cầu nội dung:
            1. Phân tích phải RẤT CHI TIẾT, chuyên sâu, dài (khoảng 800-1000 từ).
            2. Sử dụng ngôn ngữ kinh tế, chuyên nghiệp, thuyết phục.
            3. Trình bày dưới dạng HTML (chỉ thẻ p, ul, li, h3, h4, strong, không dùng thẻ html/body).

            Cấu trúc bắt buộc:
            <h3>1. Tóm tắt điều hành (Executive Summary)</h3>
            <p>Mô tả lại ý tưởng dưới góc độ giá trị cốt lõi...</p>

            <h3>2. Phân tích Thị trường & Khách hàng</h3>
            <ul>
                <li><strong>Khách hàng mục tiêu:</strong> Vẽ chân dung chi tiết...</li>
                <li><strong>Dung lượng thị trường:</strong> Ước tính tiềm năng...</li>
            </ul>

            <h3>3. Mô hình SWOT (Điểm mạnh - Điểm yếu - Cơ hội - Thách thức)</h3>
            <p>Phân tích sâu từng yếu tố...</p>

            <h3>4. Quy trình Vận hành & Công nghệ</h3>
            <p>Mô tả cách hệ thống hoạt động từ A-Z...</p>

            <h3>5. Chiến lược Tài chính & Doanh thu</h3>
            <p>Dự kiến nguồn thu từ đâu (Subscription, Ads, Freemium...)?</p>

            <h3>6. Lời khuyên cho Doanh nghiệp Đầu tư</h3>
            <p>Tại sao ý tưởng này đáng giá triệu đô?</p>
        ";

        try {
            // Sử dụng generateText từ GeminiService
            $result = $this->geminiService->generateText($prompt);

            if ($result && !str_starts_with($result, 'Lỗi')) {
                return response()->json([
                    'success' => true,
                    'html' => $result // Trả về HTML đã được format sẵn
                ]);
            }

            return response()->json(['success' => false, 'error' => 'AI đang bận suy nghĩ chiến lược, vui lòng thử lại sau.'], 500);

        } catch (\Exception $e) {
            Log::error('Student AI Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}

