<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GroqService;
use App\Models\Idea;
use Illuminate\Http\Request;

class AIController extends Controller
{
    protected GroqService $groq;

    public function __construct(GroqService $groq)
    {
        $this->groq = $groq;
    }

    // 1) Review Insight - Phân tích chi tiết ý tưởng cho Giám khảo
    public function reviewInsight(Request $request)
    {
        $content = (string) $request->input('content', '');
        
        if (empty(trim($content)) || strlen(trim($content)) < 10) {
            return response()->json([
                'error' => 'Nội dung quá ngắn. Vui lòng cung cấp ít nhất 10 ký tự.'
            ], 400);
        }
        
        $prompt = "Bạn là một Giám khảo chuyên nghiệp và giàu kinh nghiệm trong các cuộc thi khởi nghiệp và đổi mới sáng tạo. 
        
Hãy phân tích CHI TIẾT và TOÀN DIỆN ý tưởng sau đây:

\"{$content}\"

YÊU CẦU PHÂN TÍCH (trả lời dài, đầy đủ, ít nhất 500-800 từ):

**1. ĐIỂM MẠNH (Strengths):**
- Liệt kê và phân tích ít nhất 3-5 điểm mạnh nổi bật của ý tưởng
- Giải thích tại sao những điểm này là thế mạnh
- Đưa ra ví dụ cụ thể nếu có thể

**2. ĐIỂM YẾU & RỦI RO (Weaknesses & Risks):**
- Chỉ ra ít nhất 3-5 điểm yếu hoặc rủi ro tiềm ẩn
- Phân tích mức độ nghiêm trọng của từng rủi ro
- Đề xuất cách khắc phục hoặc giảm thiểu rủi ro

**3. TIỀM NĂNG THỊ TRƯỜNG (Market Potential):**
- Đánh giá quy mô thị trường mục tiêu (TAM, SAM, SOM)
- Phân tích đối thủ cạnh tranh hiện tại và tiềm năng
- Đánh giá khả năng mở rộng (Scalability)

**4. KHẢ THI CÔNG NGHỆ (Technical Feasibility):**
- Đánh giá độ phức tạp kỹ thuật
- Phân tích công nghệ cần thiết và tính khả thi
- Đánh giá thời gian và chi phí phát triển

**5. ĐIỂM SỐ TỔNG THỂ (Overall Score):**
- Chấm điểm từng tiêu chí trên thang 10:
  * Sáng tạo & Đổi mới: /10
  * Khả thi kỹ thuật: /10
  * Tiềm năng thị trường: /10
  * Tính thực tế: /10
  * Khả năng triển khai: /10
- Điểm tổng thể: /50
- Nhận xét tổng kết và khuyến nghị

Hãy trình bày dưới dạng Markdown với các tiêu đề rõ ràng, dễ đọc. Phân tích phải sâu sắc, chuyên nghiệp và mang tính xây dựng.";

        try {
            $result = $this->groq->generateText($prompt, false, 0.7, 4096);
            
            if (empty($result) || str_starts_with($result, 'Lỗi')) {
                return response()->json([
                    'error' => $result ?: 'Không thể tạo phân tích. Vui lòng thử lại sau.'
                ], 500);
            }
            
            return response()->json(['result' => $result]);
        } catch (\Exception $e) {
            \Log::error('Review Insight AI Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Có lỗi xảy ra khi xử lý yêu cầu. Vui lòng thử lại sau.'
            ], 500);
        }
    }

    // 2) Vision - Phân tích hình ảnh Poster/Slide
    public function analyzeVisual(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|max:5120',
            'image_url' => 'nullable|url'
        ]);

        $path = null;
        $tempFile = null;

        // Xử lý file upload hoặc URL
        if ($request->hasFile('image')) {
            $path = $request->file('image')->getRealPath();
        } elseif ($request->filled('image_url')) {
            $imageUrl = $request->input('image_url');
            
            // Tải ảnh từ URL và lưu tạm
            try {
                $imageContent = @file_get_contents($imageUrl);
                if ($imageContent === false) {
                    return response()->json([
                        'error' => 'Không thể tải hình ảnh từ URL. Vui lòng kiểm tra lại đường dẫn.'
                    ], 400);
                }
                
                // Tạo file tạm
                $tempFile = tempnam(sys_get_temp_dir(), 'vision_');
                file_put_contents($tempFile, $imageContent);
                $path = $tempFile;
            } catch (\Exception $e) {
                \Log::error('Error downloading image from URL: ' . $e->getMessage());
                return response()->json([
                    'error' => 'Lỗi khi tải hình ảnh từ URL: ' . $e->getMessage()
                ], 400);
            }
        } else {
            return response()->json([
                'error' => 'Vui lòng cung cấp file ảnh hoặc URL ảnh.'
            ], 400);
        }
        
        $prompt = "Bạn là một chuyên gia thiết kế đồ họa và marketing chuyên nghiệp. Hãy phân tích CHI TIẾT và TOÀN DIỆN poster/slide này.

YÊU CẦU PHÂN TÍCH (trả lời dài, đầy đủ, ít nhất 400-600 từ):

**1. ĐÁNH GIÁ TÍNH THẨM MỸ (Aesthetic Evaluation):**
- Màu sắc: Phân tích bảng màu, độ tương phản, sự hài hòa
- Bố cục (Layout): Đánh giá cách sắp xếp các elements, khoảng trắng, hierarchy
- Typography: Phân tích font chữ, kích thước, readability
- Hình ảnh/Icon: Đánh giá chất lượng, sự phù hợp, tính nhất quán
- Tổng thể: Đánh giá sự chuyên nghiệp và thu hút

**2. ĐÁNH GIÁ NỘI DUNG (Content Evaluation):**
- Thông điệp chính: Rõ ràng hay mơ hồ?
- Cấu trúc thông tin: Logic, dễ hiểu?
- Call-to-action: Có rõ ràng và thuyết phục?
- Thông tin quan trọng: Đầy đủ hay thiếu sót?

**3. ĐÁNH GIÁ HIỆU QUẢ TRUYỀN ĐẠT (Communication Effectiveness):**
- Khả năng thu hút sự chú ý
- Khả năng truyền đạt thông điệp
- Phù hợp với đối tượng mục tiêu

**4. LỜI KHUYÊN CẢI THIỆN (Improvement Recommendations):**
- Liệt kê ít nhất 5-7 điểm cần cải thiện cụ thể
- Đưa ra giải pháp cụ thể cho từng điểm
- Ưu tiên các cải thiện quan trọng nhất
- Đề xuất best practices nếu có

**5. ĐIỂM SỐ (Scoring):**
- Thẩm mỹ: /10
- Nội dung: /10
- Hiệu quả truyền đạt: /10
- Tổng điểm: /30

Hãy trình bày dưới dạng Markdown với các tiêu đề rõ ràng. Phân tích phải chi tiết, chuyên nghiệp và mang tính xây dựng.";

        try {
            $result = $this->groq->analyzeImage($prompt, $path);
            
            if (empty($result) || str_starts_with($result, 'Lỗi')) {
                return response()->json([
                    'error' => $result ?: 'Không thể phân tích hình ảnh. Vui lòng thử lại sau.'
                ], 500);
            }
            
            return response()->json(['result' => $result]);
        } catch (\Exception $e) {
            \Log::error('Vision AI Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Có lỗi xảy ra khi xử lý hình ảnh. Vui lòng thử lại sau.'
            ], 500);
        } finally {
            // Xóa file tạm nếu có
            if ($tempFile && file_exists($tempFile)) {
                @unlink($tempFile);
            }
        }
    }

    // 3) Check Duplicate by Embedding Cosine Similarity
    public function checkDuplicate(Request $request)
    {
        $currentText = (string) $request->input('content', '');
        $currentId = $request->input('current_id');
        
        // Tạo vector cho ý tưởng mới
        $currentVector = $this->groq->generateEmbedding($currentText);
        
        if (!$currentVector) {
            return response()->json([
                'is_duplicate' => false, 
                'message' => 'Tính năng kiểm tra trùng lặp yêu cầu GEMINI_API_KEY hoặc OPENAI_API_KEY (Groq không hỗ trợ embedding). Vui lòng thêm một trong hai key vào .env để sử dụng tính năng này.',
                'requires_openai' => true
            ], 200);
        }

        $matches = [];
        
        // Tối ưu: Chunking - Xử lý từng lô 100 bản ghi để tránh tràn RAM
        Idea::whereNotNull('embedding_vector')
            ->select('id', 'title', 'slug', 'embedding_vector')
            ->chunk(100, function ($ideas) use ($currentVector, $currentId, &$matches) {
                foreach ($ideas as $idea) {
                    if (!empty($currentId) && (string)$idea->id === (string)$currentId) {
                        continue;
                    }
                    
                    $storedVector = json_decode($idea->embedding_vector, true);
                    
                    // Kiểm tra vector dimension phải khớp
                    if (is_array($storedVector) && count($storedVector) === count($currentVector)) {
                        $score = $this->cosineSimilarity($currentVector, $storedVector);
                        if ($score >= 0.85) { // Ngưỡng trùng lặp cao (85%)
                            $matches[] = [
                                'id' => $idea->id,
                                'title' => $idea->title ?? ('Ý tưởng #' . $idea->id),
                                'slug' => $idea->slug ?? null,
                                'score' => round($score * 100, 1) . '%',
                                'raw_score' => $score // Dùng để sort
                            ];
                        }
                    }
                }
            });

        // Sắp xếp giảm dần theo độ giống
        usort($matches, fn($a, $b) => $b['raw_score'] <=> $a['raw_score']);

        return response()->json([
            'is_duplicate' => count($matches) > 0,
            'matches' => array_slice($matches, 0, 3), // Chỉ lấy top 3
        ]);
    }

    private function cosineSimilarity(array $vecA, array $vecB): float
    {
        $dot = 0.0; $magA = 0.0; $magB = 0.0;
        $len = min(count($vecA), count($vecB));
        for ($i = 0; $i < $len; $i++) {
            $a = (float) $vecA[$i];
            $b = (float) $vecB[$i];
            $dot += $a * $b;
            $magA += $a * $a;
            $magB += $b * $b;
        }
        if ($magA <= 0 || $magB <= 0) return 0.0;
        return $dot / (sqrt($magA) * sqrt($magB));
    }

    // TOOL: Seed embeddings for older ideas
    public function seedEmbeddings()
    {
        $ideas = Idea::whereNull('embedding_vector')->limit(10)->get();
        $count = 0;
        foreach ($ideas as $idea) {
            $text = trim(($idea->title ?? '') . '. ' . ($idea->summary ?? '') . ' ' . ($idea->description ?? '') . ' ' . ($idea->content ?? ''));
            if ($text === '') continue;
            $vec = $this->groq->generateEmbedding($text);
            if ($vec) {
                $idea->update(['embedding_vector' => json_encode($vec)]);
                $count++;
            }
        }
        return "Đã cập nhật vector cho {$count} ý tưởng.";
    }

    // --- TÍNH NĂNG A: KIẾN TRÚC SƯ CÔNG NGHỆ (CHO SINH VIÊN) ---
    public function suggestTechStack(Request $request)
    {
        $request->validate([
            'content' => 'required|string|min:20',
        ]);

        $content = $request->input('content');

        $prompt = "Bạn là một CTO (Giám đốc Công nghệ) hàng đầu với hơn 15 năm kinh nghiệm trong việc xây dựng và triển khai các hệ thống công nghệ quy mô lớn.

Sinh viên có ý tưởng sau: \"$content\"

Hãy phân tích CHI TIẾT và đề xuất một bộ công nghệ (Tech Stack) TỐI ƯU và PHÙ HỢP NHẤT để xây dựng dự án này.

YÊU CẦU:
1. Phân tích yêu cầu kỹ thuật của dự án
2. Đề xuất tech stack cho từng layer với LÝ DO CHI TIẾT
3. Xem xét tính khả thi, chi phí, và khả năng mở rộng
4. Đưa ra lời khuyên triển khai cụ thể

QUAN TRỌNG: Bạn PHẢI trả về CHỈ JSON thuần túy, không có text nào khác trước hoặc sau JSON. Định dạng JSON bắt buộc:

{
  \"frontend\": \"Tên công nghệ + lý do chi tiết tại sao chọn công nghệ này, ưu điểm, phù hợp với dự án như thế nào\",
  \"backend\": \"Tên công nghệ + lý do chi tiết, xử lý được những yêu cầu gì của dự án\",
  \"database\": \"Tên công nghệ + lý do chi tiết, phù hợp với loại dữ liệu nào\",
  \"mobile\": \"Tên công nghệ (nếu cần) + lý do, hoặc 'Không cần' nếu không phù hợp\",
  \"hardware\": \"Tên thiết bị/công nghệ (nếu là dự án IoT/Phần cứng) + lý do, hoặc 'Không áp dụng' nếu không phù hợp\",
  \"cloud_infrastructure\": \"Đề xuất hạ tầng cloud (AWS/Azure/GCP) + lý do\",
  \"devops_tools\": \"Công cụ CI/CD và deployment đề xuất\",
  \"advice\": \"Lời khuyên triển khai chi tiết, roadmap ngắn hạn và dài hạn, các lưu ý quan trọng khi phát triển\",
  \"estimated_complexity\": \"Đánh giá độ phức tạp: Thấp/Trung bình/Cao\",
  \"estimated_timeline\": \"Ước tính thời gian phát triển (nếu có thể)\"
}

Lưu ý: Chỉ trả về JSON, không có text giải thích thêm.";

        try {
            // Gọi hàm với tham số jsonMode = true để sử dụng JSON Mode
            $result = $this->groq->generateText($prompt, true, 0.7, 4096);

            if (isset($result['error'])) {
                \Log::error('Tech Stack AI Error', ['error' => $result['error']]);
                return response()->json([
                    'error' => $result['error']
                ], 500);
            }

            // Kiểm tra xem result có phải là array hợp lệ không
            if (!is_array($result)) {
                \Log::error('Tech Stack AI: Result is not array', ['result' => $result]);
                return response()->json([
                    'error' => 'Dữ liệu trả về không đúng định dạng. Vui lòng thử lại.'
                ], 500);
            }

            // Đảm bảo có ít nhất các trường cơ bản
            $defaultFields = [
                'frontend' => 'Chưa có gợi ý',
                'backend' => 'Chưa có gợi ý',
                'database' => 'Chưa có gợi ý',
                'mobile' => 'Không cần',
                'hardware' => 'Không áp dụng',
                'advice' => 'Không có lời khuyên'
            ];
            
            $result = array_merge($defaultFields, $result);

            // Khi jsonMode = true, result đã là array (JSON decoded)
            return response()->json(['data' => $result]);
        } catch (\Exception $e) {
            \Log::error('Tech Stack AI Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Có lỗi xảy ra khi xử lý yêu cầu: ' . $e->getMessage()
            ], 500);
        }
    }

    // --- TÍNH NĂNG B: THỢ SĂN GIẢI PHÁP (CHO DOANH NGHIỆP) ---
    public function scoutSolutions(Request $request)
    {
        $problem = $request->input('problem'); // Doanh nghiệp nhập "Vấn đề cần tìm giải pháp"

        if (empty($problem)) {
            return response()->json(['message' => 'Vui lòng nhập vấn đề cần tìm giải pháp.'], 400);
        }

        // 1. Tạo vector cho vấn đề của doanh nghiệp
        $queryVector = $this->groq->generateEmbedding($problem);

        if (!$queryVector) {
            return response()->json([
                'message' => 'Tính năng tìm kiếm ngữ nghĩa yêu cầu GEMINI_API_KEY hoặc OPENAI_API_KEY (Groq không hỗ trợ embedding). Vui lòng thêm một trong hai key vào .env để sử dụng tính năng này.',
                'requires_openai' => true
            ], 400);
        }

        $matches = [];

        // 2. Tối ưu: Chunking - Xử lý từng lô 100 bản ghi để tránh tràn RAM và Timeout
        Idea::publicApproved()
            ->select('id', 'title', 'slug', 'summary', 'description', 'content', 'embedding_vector', 'owner_id')
            ->with('owner:id,name') // Load relationship owner với chỉ các trường cần thiết
            ->whereNotNull('embedding_vector')
            ->chunk(100, function ($ideas) use ($queryVector, &$matches) {
                foreach ($ideas as $idea) {
                    $ideaVector = json_decode($idea->embedding_vector, true);
                    
                    // Kiểm tra vector dimension phải khớp
                    if (is_array($ideaVector) && count($ideaVector) === count($queryVector)) {
                        // Tái sử dụng hàm cosineSimilarity
                        $score = $this->cosineSimilarity($queryVector, $ideaVector);

                        // Nếu độ phù hợp > 65% (Ngưỡng tìm kiếm ngữ nghĩa)
                        if ($score >= 0.65) {
                            $matches[] = [
                                'id' => $idea->id,
                                'title' => $idea->title,
                                'slug' => $idea->slug,
                                'abstract' => \Illuminate\Support\Str::limit(\Illuminate\Support\Str::of(strip_tags($idea->summary ?? $idea->description ?? $idea->content ?? ''))->squish(), 140),
                                'author' => optional($idea->owner)->name ?? 'Ẩn danh', // Tác giả (owner)
                                'score' => round($score * 100, 1) // Điểm phù hợp
                            ];
                        }
                    }
                }
            });

        // Sắp xếp: Phù hợp nhất lên đầu
        usort($matches, fn($a, $b) => $b['score'] <=> $a['score']);

        return response()->json([
            'found' => count($matches),
            'results' => array_slice($matches, 0, 5) // Trả về top 5
        ]);
    }

    // DEBUG: Kiểm tra cấu hình API
    public function debugInfo()
    {
        $groqApiKey = env('GROQ_API_KEY');
        $openaiApiKey = env('OPENAI_API_KEY');
        return response()->json([
            'groq_api_key_set' => !empty($groqApiKey),
            'groq_api_key_length' => strlen($groqApiKey ?? ''),
            'groq_api_key_preview' => !empty($groqApiKey) ? substr($groqApiKey, 0, 10) . '...' : 'NOT SET',
            'groq_model' => env('GROQ_MODEL', 'llama-3.1-70b-versatile'),
            'openai_api_key_set' => !empty($openaiApiKey),
            'openai_api_key_preview' => !empty($openaiApiKey) ? substr($openaiApiKey, 0, 10) . '...' : 'NOT SET (Groq không hỗ trợ embedding - chỉ cần OpenAI nếu muốn dùng)',
            'base_url' => 'https://api.groq.com/openai/v1',
            'models' => [
                'text' => env('GROQ_MODEL', 'llama-3.1-70b-versatile'),
                'vision' => 'llama-3.2-11b-vision-preview',
                'embedding' => 'text-embedding-3-small (OpenAI)'
            ]
        ]);
    }

    // --- TÍNH NĂNG MỚI: TẠO KẾ HOẠCH KINH DOANH TỪ Ý TƯỞNG (Business Plan Generator) ---
    public function createBusinessPlan(Request $request)
    {
        $request->validate([
            'idea' => 'required|string|min:10', // Ý tưởng đầu vào (VD: App tìm trọ)
        ]);

        $userIdea = $request->input('idea');

        // Prompt hướng dẫn AI viết theo cấu trúc file Word mẫu
        // Tôi đã đưa các tiêu đề và hướng dẫn chi tiết từ file Word của bạn vào đây
        $prompt = "Bạn là một chuyên gia viết dự án khởi nghiệp (Startup Founder) tài năng.
Nhiệm vụ của bạn là viết một bản 'THUYẾT MINH KẾ HOẠCH Ý TƯỞNG' (Business Project Master Plan) hoàn chỉnh, chuyên nghiệp cho ý tưởng sau:

\"{$userIdea}\"

YÊU CẦU: Hãy viết chi tiết, dài và có chiều sâu, tuân thủ CHÍNH XÁC cấu trúc 12 phần sau đây (giống mẫu dự án VLUTE Innovation Hub):

1. TÓM TẮT DỰ ÁN (Business Model Canvas):
   - Trình bày dạng liệt kê các yếu tố: Đối tác chính, Hoạt động chính, Giải pháp giá trị, Quan hệ khách hàng, Phân khúc khách hàng, Tài nguyên chính, Kênh thông tin, Cơ cấu chi phí, Dòng doanh thu.

2. BỐI CẢNH THỊ TRƯỜNG & CĂN CỨ:
   - Phân tích cơ hội bên ngoài (Thị trường đang cần gì? Xu hướng chuyển đổi số ra sao?).
   - Phân tích lợi thế bên trong (Điểm mạnh của team, công nghệ nắm giữ).
   - Công nghệ ứng dụng (Frontend, Backend, Database, AI/Big Data...).

3. VỊ TRÍ DỰ KIẾN:
   - Hạ tầng máy chủ (Cloud/VPS).
   - Tên miền/Địa chỉ hiện diện.

4. PHÂN TÍCH PHÁP LUẬT:
   - Các rủi ro pháp lý (Sở hữu trí tuệ, Bảo mật dữ liệu, An ninh mạng).
   - Giải pháp tuân thủ (Trích dẫn luật liên quan nếu biết).

5. NGHIÊN CỨU & ĐÁNH GIÁ THỊ TRƯỜNG:
   - Số liệu khách hàng tiềm năng.
   - Bảng so sánh với đối thủ cạnh tranh (Điểm yếu của đối thủ vs Điểm mạnh của dự án).

6. KẾ HOẠCH MARKETING (4P & 4C):
   - Marketing Mix 4P: Product (Sản phẩm cốt lõi), Price (Chiến lược giá), Place (Kênh phân phối), Promotion (Xúc tiến).
   - Marketing Mix 4C: Customer Solution, Customer Cost, Convenience, Communication.

7. QUY TRÌNH HOẠT ĐỘNG:
   - Mô tả luồng đi (Flow) của người dùng trên hệ thống (Bước 1, Bước 2...).

8. TỔ CHỨC NHÂN LỰC:
   - Cơ cấu team (CEO, CTO, Marketing, Sales...) và nhiệm vụ.

9. KẾ HOẠCH TÀI CHÍNH:
   - Vốn đầu tư ban đầu (CAPEX).
   - Vốn lưu động (OPEX).

10. TIẾN ĐỘ TRIỂN KHAI:
    - Chia 4 giai đoạn: Nghiên cứu -> MVP -> Pilot -> Growth.

11. QUẢN TRỊ RỦI RO:
    - Rủi ro kỹ thuật, Rủi ro nội dung/vận hành, Rủi ro thị trường.
    - Phương án ứng phó cụ thể cho từng cái.

12. KẾT LUẬN & CAM KẾT:
    - Lời kết đầy cảm hứng về tầm nhìn của dự án.

LƯU Ý QUAN TRỌNG:
- Trình bày định dạng Markdown đẹp mắt (Dùng Bold, List, Table nếu cần).
- Giọng văn: Chuyên nghiệp, thuyết phục, nhiệt huyết (như đang đi thi Startup).
- Tự động 'bịa' ra các chi tiết hợp lý (số liệu giả định, tên công nghệ...) để bài viết đầy đủ nhất.";

        try {
            // Tăng max_tokens lên cao (8192) vì bài văn này rất dài
            // Temperature 0.7 để AI sáng tạo thêm chi tiết
            $result = $this->groq->generateText($prompt, false, 0.7, 8192); 

            if (empty($result) || str_starts_with($result, 'Lỗi')) {
                return response()->json([
                    'error' => $result ?: 'Không thể tạo kế hoạch lúc này. Vui lòng thử lại.'
                ], 500);
            }

            return response()->json(['result' => $result]);
        } catch (\Exception $e) {
            \Log::error('Business Plan Gen Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Có lỗi xảy ra khi xử lý yêu cầu.'
            ], 500);
        }
    }
}

