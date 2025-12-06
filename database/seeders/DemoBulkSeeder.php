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
        if ($students->count() < 5) {
            $this->command->warn('Chưa có đủ tài khoản sinh viên để gán cho ý tưởng.');
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

        // 2) Seed 10 Ideas (approved + public) owned by various students
        $ideaTitles = [
            'Nền tảng chia sẻ tài liệu học tập thông minh',
            'Ứng dụng theo dõi sức khỏe cho sinh viên',
            'Hệ thống khảo sát ý kiến giảng viên – sinh viên',
            'AI gợi ý lộ trình học lập trình',
            'Cổng thông tin việc làm bán thời gian',
            'IoT giám sát chất lượng không khí trong lớp',
            'Website học phát âm tiếng Anh bằng AI',
            'Quản lý câu lạc bộ và hoạt động ngoại khóa',
            'Sàn mua bán đồ cũ trong trường',
            'Chatbot giải đáp thủ tục học vụ',
            'Bản đồ phòng học và lịch trống theo thời gian thực',
            'Hệ thống điểm danh bằng nhận diện khuôn mặt',
        ];

        $createdIdeas = 0;
        foreach (array_slice($ideaTitles, 0, 12) as $i => $title) {
            if ($createdIdeas >= 10) break;
            $owner = $students->random();
            $slug = Str::slug($title);
            $idea = Idea::firstOrCreate(
                ['slug' => $slug],
                [
                    'owner_id' => $owner->id,
                    'title' => $title,
                    'summary' => 'Tóm tắt: ' . $title,
                    'description' => '<p>Mô tả chi tiết cho ý tưởng: '.e($title).'. Ứng dụng công nghệ hiện đại để giải quyết vấn đề thực tiễn của sinh viên.</p>',
                    'content' => 'Các module chức năng, kiến trúc hệ thống, kế hoạch triển khai và mở rộng...',
                    'status' => 'approved_final',
                    'visibility' => 'public',
                    'faculty_id' => $facultyIds ? $facultyIds[array_rand($facultyIds)] : null,
                    'category_id' => $categoryIds ? $categoryIds[array_rand($categoryIds)] : null,
                    'like_count' => rand(0, 200),
                ]
            );
            $createdIdeas++;
        }
        $this->command->info("✓ Đã tạo thêm {$createdIdeas} ý tưởng.");

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

