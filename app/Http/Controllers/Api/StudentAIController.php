<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GroqService;
use Illuminate\Support\Facades\Log;

class StudentAIController extends Controller
{
    protected $groqService;

    public function __construct(GroqService $groqService)
    {
        $this->groqService = $groqService;
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
Bạn là một Chuyên gia Tư vấn Chiến lược Kinh doanh (Business Strategy Consultant) hàng đầu thế giới với hơn 20 năm kinh nghiệm tư vấn cho các startup và doanh nghiệp công nghệ.

Tôi là sinh viên có ý tưởng khởi nghiệp sau: \"$ideaContent\"

Hãy giúp tôi lập một BẢN KẾ HOẠCH KINH DOANH CHI TIẾT VÀ CHUYÊN NGHIỆP (Business Plan) để thuyết phục các doanh nghiệp đầu tư.

YÊU CẦU NỘI DUNG:
1. Phân tích phải RẤT CHI TIẾT, chuyên sâu, dài (ít nhất 1200-1500 từ, càng dài càng tốt).
2. Sử dụng ngôn ngữ kinh tế, chuyên nghiệp, thuyết phục, có số liệu và ví dụ cụ thể.
3. Trình bày dưới dạng HTML (chỉ thẻ p, ul, li, ol, h3, h4, strong, em, không dùng thẻ html/body).
4. Mỗi phần phải có ít nhất 3-5 đoạn văn chi tiết.

CẤU TRÚC BẮT BUỘC (mỗi phần phải chi tiết, dài):

<h3>1. Tóm tắt điều hành (Executive Summary)</h3>
<p>Mô tả lại ý tưởng dưới góc độ giá trị cốt lõi, tầm nhìn, sứ mệnh. Giải thích tại sao ý tưởng này có tiềm năng lớn và đáng đầu tư. Đưa ra các con số ấn tượng nếu có thể (quy mô thị trường, số lượng người dùng tiềm năng, doanh thu dự kiến).</p>

<h3>2. Phân tích Thị trường & Khách hàng (Market & Customer Analysis)</h3>
<ul>
    <li><strong>Khách hàng mục tiêu (Target Customer):</strong> Vẽ chân dung chi tiết về khách hàng mục tiêu: độ tuổi, giới tính, thu nhập, thói quen, nhu cầu, pain points. Phân tích tại sao họ cần sản phẩm/dịch vụ này.</li>
    <li><strong>Dung lượng thị trường (Market Size):</strong> Ước tính tiềm năng thị trường (TAM - Total Addressable Market, SAM - Serviceable Addressable Market, SOM - Serviceable Obtainable Market). Đưa ra số liệu cụ thể nếu có thể.</li>
    <li><strong>Xu hướng thị trường (Market Trends):</strong> Phân tích các xu hướng hiện tại và tương lai ảnh hưởng đến thị trường này.</li>
    <li><strong>Phân khúc thị trường (Market Segmentation):</strong> Chia nhỏ thị trường thành các phân khúc và chọn phân khúc phù hợp nhất.</li>
</ul>

<h3>3. Mô hình SWOT (Điểm mạnh - Điểm yếu - Cơ hội - Thách thức)</h3>
<p>Phân tích sâu từng yếu tố với ít nhất 3-5 điểm cho mỗi phần:</p>
<ul>
    <li><strong>Strengths (Điểm mạnh):</strong> Liệt kê và phân tích chi tiết các điểm mạnh của ý tưởng, đội ngũ, công nghệ, vị thế cạnh tranh.</li>
    <li><strong>Weaknesses (Điểm yếu):</strong> Thành thật chỉ ra các điểm yếu và cách khắc phục.</li>
    <li><strong>Opportunities (Cơ hội):</strong> Phân tích các cơ hội từ thị trường, công nghệ, chính sách, xu hướng.</li>
    <li><strong>Threats (Thách thức):</strong> Nhận diện các rủi ro và đối thủ cạnh tranh, cách đối phó.</li>
</ul>

<h3>4. Quy trình Vận hành & Công nghệ (Operations & Technology)</h3>
<p>Mô tả chi tiết cách hệ thống hoạt động từ A-Z:</p>
<ul>
    <li><strong>Quy trình vận hành:</strong> Mô tả từng bước từ khi khách hàng tiếp cận đến khi sử dụng sản phẩm/dịch vụ.</li>
    <li><strong>Công nghệ sử dụng:</strong> Liệt kê và giải thích các công nghệ chính, tại sao chọn công nghệ đó.</li>
    <li><strong>Hạ tầng kỹ thuật:</strong> Mô tả hạ tầng cần thiết (server, database, security, scalability).</li>
    <li><strong>Quy trình phát triển:</strong> Roadmap phát triển sản phẩm theo từng giai đoạn.</li>
</ul>

<h3>5. Chiến lược Tài chính & Doanh thu (Financial Strategy & Revenue Model)</h3>
<p>Phân tích chi tiết:</p>
<ul>
    <li><strong>Mô hình doanh thu (Revenue Model):</strong> Dự kiến nguồn thu từ đâu (Subscription, Ads, Freemium, Commission, Licensing, v.v.). Giải thích tại sao mô hình này phù hợp.</li>
    <li><strong>Dự toán tài chính:</strong> Ước tính chi phí phát triển, vận hành, marketing. Dự kiến doanh thu theo từng giai đoạn (6 tháng, 1 năm, 3 năm).</li>
    <li><strong>Điểm hòa vốn (Break-even Point):</strong> Phân tích khi nào dự án sẽ hòa vốn.</li>
    <li><strong>Nhu cầu vốn đầu tư:</strong> Cần bao nhiêu vốn, sử dụng vào mục đích gì, kế hoạch sử dụng vốn.</li>
</ul>

<h3>6. Chiến lược Marketing & Bán hàng (Marketing & Sales Strategy)</h3>
<p>Mô tả chi tiết:</p>
<ul>
    <li><strong>Kênh tiếp cận khách hàng:</strong> Các kênh marketing sẽ sử dụng (Social Media, SEO, Content Marketing, Partnership, v.v.).</li>
    <li><strong>Chiến lược giá:</strong> Mô hình định giá và lý do.</li>
    <li><strong>Kế hoạch tăng trưởng:</strong> Làm thế nào để đạt được số lượng người dùng/khách hàng mục tiêu.</li>
</ul>

<h3>7. Đội ngũ & Tổ chức (Team & Organization)</h3>
<p>Phân tích:</p>
<ul>
    <li><strong>Cơ cấu tổ chức:</strong> Các vị trí cần thiết và trách nhiệm.</li>
    <li><strong>Kế hoạch tuyển dụng:</strong> Khi nào và tuyển ai.</li>
    <li><strong>Văn hóa công ty:</strong> Giá trị cốt lõi và văn hóa muốn xây dựng.</li>
</ul>

<h3>8. Lời khuyên cho Doanh nghiệp Đầu tư (Investment Recommendation)</h3>
<p>Phân tích sâu tại sao ý tưởng này đáng giá đầu tư:</p>
<ul>
    <li><strong>Giá trị độc đáo:</strong> Điểm khác biệt và lợi thế cạnh tranh.</li>
    <li><strong>Tiềm năng tăng trưởng:</strong> Khả năng mở rộng và phát triển.</li>
    <li><strong>ROI dự kiến:</strong> Lợi nhuận và giá trị mang lại cho nhà đầu tư.</li>
    <li><strong>Rủi ro và cách quản lý:</strong> Các rủi ro chính và cách giảm thiểu.</li>
    <li><strong>Kết luận:</strong> Tóm tắt lại lý do tại sao đây là cơ hội đầu tư tốt.</li>
</ul>

Hãy viết thật chi tiết, chuyên nghiệp, và thuyết phục. Sử dụng ngôn ngữ kinh doanh, có số liệu và ví dụ cụ thể khi có thể.
        ";

        try {
            // Sử dụng generateText từ GroqService với maxTokens cao để có câu trả lời dài
            $result = $this->groqService->generateText($prompt, false, 0.7, 4096);

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

