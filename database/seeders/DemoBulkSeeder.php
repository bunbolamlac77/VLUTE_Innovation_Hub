<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Challenge;
use App\Models\Competition;
use App\Models\Faculty;
use App\Models\Idea;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoBulkSeeder extends Seeder
{
    public function run(): void
    {
        // 0) Ensure we have enough students to assign as owners
        $defaultPassword = bcrypt('Password@123');
        $studentEmails = [];
        for ($i = 6; $i <= 15; $i++) { // create up to 10 more students if not exist
            $email = "student{$i}@st.vlute.edu.vn";
            $studentEmails[] = $email;
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Student '.str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                    'password' => $defaultPassword,
                    'role' => 'student',
                    'approval_status' => 'approved',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            )->syncRoles(['student']);
        }

        $students = User::where('role', 'student')->get();
        if ($students->isEmpty()) {
            $this->command->warn('Chưa có tài khoản sinh viên nào để gán ý tưởng.');
        }

        // 1) Ensure faculties and categories exist (reuse from FeaturedIdeasSeeder logic)
        $faculties = [
            ['code' => 'CNTT', 'name' => 'Khoa Công nghệ thông tin', 'description' => 'CNTT', 'sort_order' => 1],
            ['code' => 'DDT', 'name' => 'Khoa Điện - Điện tử', 'description' => 'Điện - Điện tử', 'sort_order' => 2],
            ['code' => 'CKD', 'name' => 'Khoa Cơ khí - Động lực', 'description' => 'Cơ khí - Động lực', 'sort_order' => 3],
            ['code' => 'KT', 'name' => 'Khoa Kinh tế', 'description' => 'Kinh tế', 'sort_order' => 4],
            ['code' => 'NN', 'name' => 'Khoa Ngoại ngữ', 'description' => 'Ngoại ngữ', 'sort_order' => 5],
        ];
        foreach ($faculties as $f) { Faculty::firstOrCreate(['code' => $f['code']], $f); }

        $categories = [
            ['slug' => 'cong-nghe-thong-tin', 'name' => 'Công nghệ thông tin', 'description' => 'CNTT', 'sort_order' => 1],
            ['slug' => 'dien-tu-tu-dong-hoa', 'name' => 'Điện tử - Tự động hóa', 'description' => 'IoT/Điện tử', 'sort_order' => 2],
            ['slug' => 'co-khi-che-tao', 'name' => 'Cơ khí - Chế tạo', 'description' => 'Cơ khí', 'sort_order' => 3],
            ['slug' => 'kinh-te-quan-ly', 'name' => 'Kinh tế - Quản lý', 'description' => 'Kinh tế', 'sort_order' => 4],
            ['slug' => 'giao-duc-dao-tao', 'name' => 'Giáo dục - Đào tạo', 'description' => 'Giáo dục', 'sort_order' => 5],
        ];
        foreach ($categories as $c) { Category::firstOrCreate(['slug' => $c['slug']], $c); }

        $facultyIds = Faculty::pluck('id')->all();
        $categoryIds = Category::pluck('id')->all();

        // Bộ ý tưởng mẫu giàu nội dung (AI-gợi ý) để gán cho từng sinh viên
        $ideaTemplates = [
            [
                'title' => 'Nền tảng gợi ý lộ trình học tập cá nhân hoá bằng AI',
                'summary' => 'Sử dụng mô hình gợi ý để xây dựng lộ trình học và chứng chỉ phù hợp từng sinh viên.',
                'description' => '<p>Ứng dụng phân tích điểm số, kỹ năng và mục tiêu nghề nghiệp để đề xuất học phần, khóa online và hoạt động ngoại khoá phù hợp.</p>',
                'content' => "### Mục tiêu\n- Tăng tỷ lệ hoàn thành môn học\n- Giảm thời gian tìm tài liệu\n- Gợi ý kỹ năng còn thiếu cho ngành nghề\n\n### Công nghệ\n- Recommendation system (Matrix Factorization / BERT embeddings)\n- Bảng điều khiển tiến độ realtime\n- Nhắc việc đa kênh (email, mobile push)\n\n### Lộ trình triển khai\n1) Thu thập dữ liệu học vụ và hành vi học tập\n2) Huấn luyện mô hình gợi ý\n3) Tích hợp chatbot giải đáp học vụ\n4) Đo lường mức độ cải thiện kết quả học tập",
                'image_url' => 'https://images.unsplash.com/photo-1523475472560-d2df97ec485c?w=900&h=600&fit=crop',
                'faculty_code' => 'CNTT',
                'category_slug' => 'giao-duc-dao-tao',
            ],
            [
                'title' => 'Hệ thống bãi đỗ xe thông minh dùng computer vision',
                'summary' => 'Nhận diện biển số, dự đoán chỗ trống và thanh toán không tiền mặt cho khuôn viên trường.',
                'description' => '<p>Kết hợp camera, AI và bảng LED để hiển thị chỗ trống; quản lý vé tháng, cảnh báo bất thường.</p>',
                'content' => "### Thành phần\n- Camera + mô hình YOLO/DeepSort đếm xe\n- API thanh toán QR/NFC\n- Ứng dụng quản trị giám sát realtime\n\n### Giá trị\n- Giảm ùn tắc giờ cao điểm\n- Minh bạch doanh thu bãi xe\n- Trải nghiệm vào/ra không cần dừng lâu",
                'image_url' => 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?w=900&h=600&fit=crop',
                'faculty_code' => 'DDT',
                'category_slug' => 'dien-tu-tu-dong-hoa',
            ],
            [
                'title' => 'Robot giao nhận tài liệu tự hành trong campus',
                'summary' => 'Robot cỡ nhỏ tự hành giao hồ sơ giữa các phòng ban với định tuyến tối ưu.',
                'description' => '<p>Ứng dụng SLAM để điều hướng, cảm biến tránh chướng ngại, khay chứa có khóa thông minh.</p>',
                'content' => "### Chức năng\n- Bản đồ 2D nội bộ, cập nhật chướng ngại động\n- Xác thực khi nhận/giao tài liệu\n- Dashboard theo dõi trạng thái robot\n\n### Kết quả kỳ vọng\n- Rút ngắn 40% thời gian luân chuyển hồ sơ\n- Hạn chế thất lạc chứng từ\n- Tự động hoá ca trực đơn giản",
                'image_url' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=900&h=600&fit=crop',
                'faculty_code' => 'CKD',
                'category_slug' => 'co-khi-che-tao',
            ],
            [
                'title' => 'Nền tảng kết nối dự án doanh nghiệp với nhóm sinh viên',
                'summary' => 'Doanh nghiệp đăng bài toán, sinh viên lập nhóm capstone giải quyết có mentor đồng hành.',
                'description' => '<p>Quản lý milestone, báo cáo tiến độ, đánh giá 360 độ và showcase sản phẩm.</p>',
                'content' => "### Luồng hoạt động\n1) Doanh nghiệp đăng đề bài và tiêu chí\n2) Sinh viên lập nhóm, nộp proposal\n3) Mentor phản hồi, duyệt kế hoạch\n4) Báo cáo sprint, demo, nhận chứng nhận\n\n### Lợi ích\n- Doanh nghiệp nhận prototype nhanh\n- Sinh viên có dự án thực tế cho CV\n- Trường có dữ liệu KPI hợp tác",
                'image_url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=900&h=600&fit=crop',
                'faculty_code' => 'KT',
                'category_slug' => 'kinh-te-quan-ly',
            ],
            [
                'title' => 'Ứng dụng luyện phát âm tiếng Anh với phản hồi tức thì',
                'summary' => 'Phân tích giọng nói, chấm điểm IPA và gợi ý bài luyện theo chủ đề kỹ thuật.',
                'description' => '<p>Sử dụng mô hình ASR để so khớp âm vị, hiển thị vùng phát âm sai và đề xuất bài tập bổ sung.</p>',
                'content' => "### Tính năng\n- Chấm điểm từng câu và từng âm\n- Lộ trình luyện tập theo kỹ năng yếu\n- Thử thách hằng ngày và bảng xếp hạng\n\n### Công nghệ\n- ASR + Forced Alignment\n- React Native + WebRTC\n- Bảng từ vựng chuyên ngành cơ khí/điện tử/CNTT",
                'image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=900&h=600&fit=crop',
                'faculty_code' => 'NN',
                'category_slug' => 'giao-duc-dao-tao',
            ],
            [
                'title' => 'Dashboard năng lượng xanh cho toà nhà học tập',
                'summary' => 'Thu thập dữ liệu điện năng, phát hiện bất thường và gợi ý tiết kiệm dựa trên AI.',
                'description' => '<p>Cảm biến IoT đo điện, nhiệt độ; mô hình dự báo tải và cảnh báo vượt ngưỡng theo thời gian thực.</p>',
                'content' => "### Thành phần\n- Gateway IoT + MQTT\n- Mô hình dự báo ARIMA/LSTM\n- Cảnh báo đa kênh + báo cáo PDF\n\n### KPI\n- Giảm 8-12% chi phí điện\n- Phát hiện sớm thiết bị lỗi\n- Dữ liệu mở cho sinh viên nghiên cứu",
                'image_url' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=900&h=600&fit=crop',
                'faculty_code' => 'DDT',
                'category_slug' => 'dien-tu-tu-dong-hoa',
            ],
            [
                'title' => 'Ứng dụng hỗ trợ sức khỏe tinh thần cho sinh viên',
                'summary' => 'Tự đánh giá trạng thái cảm xúc, nhận khuyến nghị hoạt động và kết nối chuyên gia.',
                'description' => '<p>Kết hợp bài test khoa học, chatbot hỗ trợ ẩn danh, thư viện bài tập thở và thiền ngắn.</p>',
                'content' => "### Luồng chính\n- Điểm số tâm trạng hằng ngày\n- Gợi ý hoạt động 10 phút giảm căng thẳng\n- Lịch hẹn với cố vấn tâm lý\n\n### Bảo mật & ẩn danh\n- Mã hóa dữ liệu nhạy cảm\n- Tuỳ chọn chia sẻ với cố vấn được chỉ định",
                'image_url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=900&h=600&fit=crop',
                'faculty_code' => 'KT',
                'category_slug' => 'kinh-te-quan-ly',
            ],
            [
                'title' => 'Hệ thống quản lý chuỗi lạnh cho nông sản địa phương',
                'summary' => 'Giám sát nhiệt độ/độ ẩm theo thời gian thực và cảnh báo phá vỡ chuỗi lạnh.',
                'description' => '<p>Cảm biến IoT tại kho và xe vận chuyển, dashboard theo lô hàng, báo cáo truy xuất nguồn gốc.</p>',
                'content' => "### Lợi ích\n- Giảm hư hỏng sau thu hoạch\n- Bằng chứng tuân thủ cho nhà phân phối\n- Dữ liệu phục vụ truy xuất nguồn gốc\n\n### Công nghệ\n- Cảm biến BLE/LoRa\n- Ví blockchain nhẹ cho log quan trọng\n- Ứng dụng mobile cho tài xế cập nhật sự cố",
                'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=900&h=600&fit=crop',
                'faculty_code' => 'CKD',
                'category_slug' => 'co-khi-che-tao',
            ],
            [
                'title' => 'Nền tảng chia sẻ tài liệu học tập có kiểm duyệt',
                'summary' => 'Kho tài liệu số có phân quyền, gợi ý tài liệu liên quan và kiểm duyệt cộng đồng.',
                'description' => '<p>Hỗ trợ upload slide, đề cương, đề thi; hệ thống báo cáo vi phạm và đánh giá chất lượng.</p>',
                'content' => "### Tính năng\n- OCR trích xuất tiêu đề, từ khóa\n- Gợi ý tài liệu tương tự bằng embeddings\n- Bộ lọc tài liệu cấm/vi phạm bản quyền\n\n### Chỉ số\n- Thời gian tìm tài liệu < 20 giây\n- Điểm hài lòng người dùng ≥ 4/5",
                'image_url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=900&h=600&fit=crop',
                'faculty_code' => 'CNTT',
                'category_slug' => 'cong-nghe-thong-tin',
            ],
            [
                'title' => 'Ứng dụng quản lý chi tiêu và mục tiêu tài chính cho sinh viên',
                'summary' => 'Theo dõi chi tiêu, lập ngân sách tuần và gợi ý cách tiết kiệm phù hợp.',
                'description' => '<p>Chia nhóm chi phí, cảnh báo vượt hạn mức, gợi ý mẹo tài chính và tích hợp ví điện tử.</p>',
                'content' => "### Chức năng\n- Quét hoá đơn, tự phân loại\n- Ngân sách tuần/tháng, nhắc nhở vượt ngưỡng\n- Mục tiêu tiết kiệm nhỏ (sách, thiết bị học)\n\n### Tác động\n- Hình thành thói quen tài chính lành mạnh\n- Giảm nợ xấu ở sinh viên năm cuối",
                'image_url' => 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?w=900&h=600&fit=crop',
                'faculty_code' => 'KT',
                'category_slug' => 'kinh-te-quan-ly',
            ],
            [
                'title' => 'Trợ lý chatbot trả lời FAQ học vụ và thủ tục',
                'summary' => 'Chatbot tiếng Việt trả lời quy chế, thủ tục và hướng dẫn giấy tờ theo khoa.',
                'description' => '<p>Khai thác dữ liệu văn bản học vụ, tích hợp livechat khi cần chuyển tiếp cho cán bộ.</p>',
                'content' => "### Pipeline\n- Thu thập và làm sạch văn bản quy định\n- Tạo embedding + search semantic\n- Routing sang cán bộ khi câu hỏi phức tạp\n\n### Kết quả kỳ vọng\n- Giảm 50% lượt hỏi lặp lại\n- Rút ngắn thời gian phản hồi xuống < 30s",
                'image_url' => 'https://images.unsplash.com/photo-1557814286-afa42c3f0f49?w=900&h=600&fit=crop',
                'faculty_code' => 'CNTT',
                'category_slug' => 'cong-nghe-thong-tin',
            ],
            [
                'title' => 'Ứng dụng đặt phòng học, thiết bị dùng chung',
                'summary' => 'Đặt phòng, thiết bị thí nghiệm, kèm quy trình duyệt và chấm điểm tuân thủ.',
                'description' => '<p>Cho phép giảng viên và câu lạc bộ đặt lịch; tự động gợi ý khung giờ rảnh và nhắc hoàn trả.</p>',
                'content' => "### Tính năng\n- Lịch phòng/thiết bị realtime\n- Quy trình duyệt theo khoa\n- Báo cáo sử dụng, tỷ lệ hủy\n\n### Công nghệ\n- Calendaring API + Noti email/push\n- Check-in bằng QR",
                'image_url' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=900&h=600&fit=crop',
                'faculty_code' => 'CNTT',
                'category_slug' => 'cong-nghe-thong-tin',
            ],
            [
                'title' => 'Giải pháp phân loại rác thông minh cho khuôn viên trường',
                'summary' => 'Thùng rác thông minh nhận diện loại rác, gamification điểm thưởng đổi quà.',
                'description' => '<p>Sử dụng camera/ cảm biến trọng lượng, hiển thị hướng dẫn phân loại và ghi nhận đóng góp sinh viên.</p>',
                'content' => "### Lợi ích\n- Tăng tỷ lệ phân loại đúng\n- Dữ liệu cho chiến dịch xanh\n- Khuyến khích bằng điểm thưởng đổi quà\n\n### Công nghệ\n- Computer vision cơ bản\n- Ứng dụng di động theo dõi điểm xanh",
                'image_url' => 'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?w=900&h=600&fit=crop',
                'faculty_code' => 'DDT',
                'category_slug' => 'dien-tu-tu-dong-hoa',
            ],
        ];

        // Map helper để lấy id nhanh theo code/slug
        $facultyByCode = Faculty::pluck('id', 'code');
        $categoryBySlug = Category::pluck('id', 'slug');

        // 2) Với MỖI tài khoản sinh viên: tạo 3 ý tưởng công khai, trạng thái đã duyệt
        $totalCreatedIdeas = 0;
        $templateIndex = 0;
        foreach ($students as $student) {
            for ($i = 1; $i <= 3; $i++) {
                $template = $ideaTemplates[$templateIndex % count($ideaTemplates)];
                $templateIndex++;

                // Đảm bảo slug là duy nhất theo từng sinh viên
                $baseSlug = 'y-tuong-sinh-vien-'.$student->id.'-'.$i;
                $slug = Str::slug($baseSlug);
                $title = $template['title'].' - '.$student->name;

                $idea = Idea::firstOrCreate(
                    ['slug' => $slug],
                    [
                        'owner_id' => $student->id,
                        'title' => $title,
                        'summary' => $template['summary'],
                        'description' => $template['description'],
                        'content' => $template['content'],
                        'status' => 'approved_final',
                        'visibility' => 'public',
                        'faculty_id' => $facultyByCode[$template['faculty_code']] ?? ($facultyIds ? $facultyIds[array_rand($facultyIds)] : null),
                        'category_id' => $categoryBySlug[$template['category_slug']] ?? ($categoryIds ? $categoryIds[array_rand($categoryIds)] : null),
                        'image_url' => $template['image_url'],
                        'like_count' => rand(5, 120),
                    ]
                );

                if ($idea->wasRecentlyCreated) {
                    $totalCreatedIdeas++;
                }
            }
        }
        $this->command->info("✓ Đã tạo thêm {$totalCreatedIdeas} ý tưởng (3 ý tưởng công khai giàu nội dung cho mỗi tài khoản sinh viên).");

        // 3) Seed 10 Competitions
        $admin = User::where('role', 'admin')->first() ?? User::first();
        $compTitles = [
            'Hackathon Sáng tạo số', 'Cuộc thi Khởi nghiệp xanh', 'Makeathon IoT 2025', 'AI for Education Challenge',
            'Thiết kế 3D CAD 2025', 'Smart City Innovation', 'Fintech Student Cup', 'Robotics Cup VLUTE',
            'Digital Marketing Arena', 'Website Accessibility Contest', 'Open Data Hack 2025',
        ];
        $compCount = 0;
        foreach (array_slice($compTitles, 0, 11) as $idx => $t) {
            if ($compCount >= 10) break;
            $start = Carbon::now()->subDays(rand(0, 20));
            $end = (clone $start)->addDays(rand(15, 60));
            $status = rand(0, 10) < 8 ? 'open' : 'closed';
            Competition::firstOrCreate(
                ['slug' => Str::slug($t)],
                [
                    'title' => $t,
                    'description' => '<p>Mô tả cuộc thi: '.e($t).'</p>',
                    'banner_url' => '/images/panel-truong.jpg',
                    'start_date' => $start,
                    'end_date' => $end,
                    'status' => $status,
                    'created_by' => optional($admin)->id,
                ]
            );
            $compCount++;
        }
        $this->command->info("✓ Đã tạo thêm {$compCount} cuộc thi.");

        // 4) Seed 10 Challenges (Enterprise problems)
        $orgSamples = [
            ['name' => 'ACME Corp', 'type' => 'company', 'description' => 'Doanh nghiệp lĩnh vực bán lẻ.'],
            ['name' => 'GreenTech VN', 'type' => 'company', 'description' => 'Công nghệ xanh.'],
            ['name' => 'EduAI Labs', 'type' => 'company', 'description' => 'AI cho giáo dục.'],
            ['name' => 'CityWorks', 'type' => 'company', 'description' => 'Hạ tầng đô thị.'],
            ['name' => 'MediCare', 'type' => 'company', 'description' => 'Y tế số.'],
        ];
        $orgIds = [];
        foreach ($orgSamples as $o) {
            $org = Organization::firstOrCreate(['name' => $o['name']], $o);
            $orgIds[] = $org->id;
        }

        $challengeTitles = [
            'Tối ưu lịch dạy học và phòng học',
            'Giảm thất thoát điện năng trong toà nhà',
            'Theo dõi chuỗi lạnh cho nông sản',
            'Hệ thống quản lý bãi xe thông minh',
            'Chatbot hỗ trợ tuyển dụng',
            'Phân loại rác thải bằng thị giác máy tính',
            'Giám sát chất lượng nước nuôi trồng',
            'Dashboard realtime cho nhà máy',
            'Dự báo nhu cầu vật tư y tế',
            'Quản lý tài sản số hoá bằng QR/NFC',
            'Truy xuất nguồn gốc thuỷ sản',
        ];
        $chalCount = 0;
        foreach (array_slice($challengeTitles, 0, 11) as $ct) {
            if ($chalCount >= 10) break;
            $deadline = rand(0, 1) ? Carbon::now()->addDays(rand(10, 60)) : null;
            $status = $deadline && $deadline->isFuture() ? 'open' : (rand(0, 1) ? 'closed' : 'draft');
            Challenge::firstOrCreate(
                ['title' => $ct, 'organization_id' => $orgIds[array_rand($orgIds)]],
                [
                    'description' => 'Mô tả: '.e($ct).'.',
                    'problem_statement' => 'Bối cảnh và vấn đề chi tiết cần giải quyết.',
                    'requirements' => 'Yêu cầu tối thiểu: mô tả giải pháp, lộ trình, sơ đồ kỹ thuật.',
                    'deadline' => $deadline,
                    'reward' => rand(0,1) ? (rand(10,50)*1000000).' VND' : null,
                    'status' => $status,
                ]
            );
            $chalCount++;
        }
        $this->command->info("✓ Đã tạo thêm {$chalCount} thử thách doanh nghiệp.

Lưu ý: Nếu embedding cho các ý tưởng chưa được tạo kịp, hãy chạy route /ai/seed hoặc nhấn seedEmbeddings để cập nhật.");
    }
}

