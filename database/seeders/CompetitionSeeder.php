<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy user admin hoặc user đầu tiên để làm created_by
        $admin = User::where('role', 'admin')->first()
            ?? User::where('email', env('ADMIN_EMAIL', 'admin@vlute.edu.vn'))->first()
            ?? User::first();

        if (!$admin) {
            $this->command->warn('Không có user nào trong database. Vui lòng chạy AdminUserSeeder trước.');
            return;
        }

        $competitions = [
            [
                'title' => 'Innovation Launchpad 2025',
                'slug' => 'innovation-launchpad-2025',
                'description' => '<p>Chương trình ươm tạo ý tưởng khởi nghiệp công nghệ dành cho sinh viên năm 2-4.</p><ul><li>Vòng 1: Pitch deck 10 slide + video 2 phút</li><li>Vòng 2: Prototype và phỏng vấn hội đồng</li><li>Mentor 1-1 từ doanh nghiệp</li></ul><p>Giải nhất nhận gói ươm tạo 6 tháng và kết nối nhà đầu tư thiên thần.</p>',
                'banner_url' => 'https://images.unsplash.com/photo-1552664730-0fd89f07c78b?w=1400&h=640&fit=crop',
                'start_date' => Carbon::now()->subDays(7),
                'end_date' => Carbon::now()->addDays(28),
                'status' => 'open',
            ],
            [
                'title' => 'AI for Campus Hackathon',
                'slug' => 'ai-for-campus-hackathon',
                'description' => '<p>48 giờ xây sản phẩm AI giải quyết bài toán trong trường đại học: quản lý học vụ, bãi xe, truy xuất tài liệu.</p><p>Đầu ra: demo live + báo cáo kỹ thuật ngắn, ưu tiên mô hình gọn nhẹ có thể triển khai on-prem.</p>',
                'banner_url' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1400&h=640&fit=crop',
                'start_date' => Carbon::now()->addDays(10),
                'end_date' => Carbon::now()->addDays(40),
                'status' => 'open',
            ],
            [
                'title' => 'Green Campus Challenge',
                'slug' => 'green-campus-challenge',
                'description' => '<p>Tìm kiếm giải pháp giảm phát thải, tối ưu năng lượng và quản lý rác thải trong khuôn viên trường.</p><p>Yêu cầu: baseline số liệu, kế hoạch đo lường sau triển khai, mô hình tài chính đơn giản.</p>',
                'banner_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=1400&h=640&fit=crop',
                'start_date' => Carbon::now()->subDays(3),
                'end_date' => Carbon::now()->addDays(22),
                'status' => 'open',
            ],
            [
                'title' => 'Digital Content Sprint',
                'slug' => 'digital-content-sprint',
                'description' => '<p>Thi sáng tạo video, podcast, infographic giáo dục theo chủ đề STEM và đổi mới sáng tạo.</p><p>Tiêu chí: thông điệp rõ ràng, tính lan tỏa, bản quyền nội dung.</p>',
                'banner_url' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1400&h=640&fit=crop',
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(32),
                'status' => 'draft',
            ],
            [
                'title' => 'Smart Mobility Makeathon',
                'slug' => 'smart-mobility-makeathon',
                'description' => '<p>Thiết kế thiết bị/ứng dụng hỗ trợ di chuyển an toàn, đỗ xe thông minh, tối ưu giao thông nội bộ.</p><p>Ưu tiên giải pháp kết hợp IoT + AI + năng lượng tái tạo.</p>',
                'banner_url' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=1400&h=640&fit=crop',
                'start_date' => Carbon::now()->addDays(2),
                'end_date' => Carbon::now()->addDays(50),
                'status' => 'open',
            ],
            [
                'title' => 'Fintech Student Cup',
                'slug' => 'fintech-student-cup',
                'description' => '<p>Phát triển sản phẩm tài chính số cho sinh viên: quản lý chi tiêu, tiết kiệm nhỏ, tín dụng học tập.</p><p>Yêu cầu: tuân thủ bảo mật dữ liệu, mô hình rủi ro đơn giản.</p>',
                'banner_url' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=1400&h=640&fit=crop',
                'start_date' => Carbon::now()->subDays(25),
                'end_date' => Carbon::now()->subDays(5),
                'status' => 'closed',
            ],
            [
                'title' => 'UI/UX for Public Services',
                'slug' => 'ui-ux-for-public-services',
                'description' => '<p>Thiết kế trải nghiệm người dùng cho dịch vụ công trực tuyến địa phương: đăng ký hồ sơ, tra cứu kết quả, phản ánh hiện trường.</p><p>Giao nộp: wireframe, prototype tương tác, guideline thiết kế.</p>',
                'banner_url' => 'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?w=1400&h=640&fit=crop',
                'start_date' => Carbon::now()->addDays(8),
                'end_date' => Carbon::now()->addDays(45),
                'status' => 'open',
            ],
            [
                'title' => 'Data for Social Good',
                'slug' => 'data-for-social-good',
                'description' => '<p>Cuộc thi phân tích dữ liệu mở về môi trường, y tế, giáo dục. Chấm điểm theo insight hành động và trực quan hóa.</p>',
                'banner_url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=1400&h=640&fit=crop',
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => Carbon::now()->addDays(5),
                'status' => 'open',
            ],
            [
                'title' => 'Product Design Lab',
                'slug' => 'product-design-lab',
                'description' => '<p>Thiết kế và in 3D mẫu thử cho sản phẩm cơ khí/điện tử, tập trung vào tính công năng và khả năng sản xuất.</p>',
                'banner_url' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=1400&h=640&fit=crop',
                'start_date' => Carbon::now()->addDays(1),
                'end_date' => Carbon::now()->addDays(30),
                'status' => 'open',
            ],
            [
                'title' => 'Research Poster Day',
                'slug' => 'research-poster-day',
                'description' => '<p>Trình bày poster nghiên cứu khoa học sinh viên. Hội đồng phản biện nhanh, góp ý cải thiện paper.</p>',
                'banner_url' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=1400&h=640&fit=crop',
                'start_date' => Carbon::now()->subDays(40),
                'end_date' => Carbon::now()->subDays(10),
                'status' => 'closed',
            ],
        ];

        foreach ($competitions as $competitionData) {
            Competition::firstOrCreate(
                ['slug' => $competitionData['slug']],
                array_merge($competitionData, [
                    'created_by' => $admin->id,
                ])
            );
        }

        $this->command->info('Đã tạo ' . count($competitions) . ' cuộc thi mẫu thành công!');
    }
}
