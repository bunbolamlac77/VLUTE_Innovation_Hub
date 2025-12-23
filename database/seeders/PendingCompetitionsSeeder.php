<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PendingCompetitionsSeeder extends Seeder
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

        // 5 cuộc thi đang chờ duyệt
        // 3 cuộc thi chờ duyệt ở cấp Trung tâm ĐMST
        // 2 cuộc thi chờ duyệt ở cấp Ban giám hiệu
        $pendingCompetitions = [
            // Chờ duyệt Trung tâm ĐMST (3 cuộc thi)
            [
                'title' => 'Blockchain Innovation Challenge 2025',
                'slug' => 'blockchain-innovation-challenge-2025',
                'description' => '<p>Cuộc thi phát triển ứng dụng blockchain cho giáo dục và quản lý tài sản số.</p><p>Yêu cầu: Xây dựng prototype trên nền tảng blockchain công khai hoặc private, giải quyết bài toán thực tế trong trường học.</p><p>Giải thưởng: 50 triệu đồng cho đội nhất, 30 triệu cho đội nhì, 20 triệu cho đội ba.</p>',
                'banner_url' => 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?w=1400&h=640&fit=crop',
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(60),
                'status' => 'draft',
                'approval_level' => 'pending_center',
            ],
            [
                'title' => 'Smart Agriculture Tech Contest',
                'slug' => 'smart-agriculture-tech-contest',
                'description' => '<p>Thiết kế và phát triển giải pháp công nghệ cho nông nghiệp thông minh: IoT, AI, drone, cảm biến.</p><p>Mục tiêu: Tối ưu hóa sản xuất, giảm chi phí, tăng năng suất cho nông dân địa phương.</p><p>Hỗ trợ: Mentoring từ chuyên gia nông nghiệp, kết nối với hợp tác xã nông nghiệp.</p>',
                'banner_url' => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=1400&h=640&fit=crop',
                'start_date' => Carbon::now()->addDays(20),
                'end_date' => Carbon::now()->addDays(75),
                'status' => 'draft',
                'approval_level' => 'pending_center',
            ],
            [
                'title' => 'E-Learning Platform Development',
                'slug' => 'e-learning-platform-development',
                'description' => '<p>Xây dựng nền tảng học tập trực tuyến với tính năng: video streaming, quiz tương tác, AI tutor, gamification.</p><p>Yêu cầu kỹ thuật: Responsive design, hỗ trợ đa ngôn ngữ, tích hợp payment gateway.</p><p>Đối tượng: Sinh viên năm 3-4, có thể làm theo nhóm 3-5 người.</p>',
                'banner_url' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1400&h=640&fit=crop',
                'start_date' => Carbon::now()->addDays(25),
                'end_date' => Carbon::now()->addDays(90),
                'status' => 'draft',
                'approval_level' => 'pending_center',
            ],
            // Chờ duyệt Ban giám hiệu (2 cuộc thi)
            [
                'title' => 'VLUTE Startup Incubator Program',
                'slug' => 'vlute-startup-incubator-program',
                'description' => '<p>Chương trình ươm tạo khởi nghiệp toàn diện dành cho sinh viên và cựu sinh viên VLUTE.</p><p>Bao gồm: Đào tạo 6 tháng, mentor 1-1, không gian làm việc, kết nối nhà đầu tư, hỗ trợ pháp lý.</p><p>Yêu cầu: Có ý tưởng khởi nghiệp rõ ràng, team ít nhất 2 người, cam kết tham gia đầy đủ chương trình.</p><p>Ngân sách: 500 triệu đồng từ quỹ đổi mới sáng tạo của trường.</p>',
                'banner_url' => 'https://images.unsplash.com/photo-1552664730-0fd89f07c78b?w=1400&h=640&fit=crop',
                'start_date' => Carbon::now()->addDays(30),
                'end_date' => Carbon::now()->addDays(210), // 7 tháng
                'status' => 'draft',
                'approval_level' => 'pending_board',
            ],
            [
                'title' => 'International Innovation Exchange 2025',
                'slug' => 'international-innovation-exchange-2025',
                'description' => '<p>Chương trình trao đổi và hợp tác đổi mới sáng tạo quốc tế giữa VLUTE và các đối tác nước ngoài.</p><p>Hoạt động: Hackathon 48h, workshop quốc tế, pitch competition, networking với startup nước ngoài.</p><p>Đối tác: 5 trường đại học từ Singapore, Thái Lan, Malaysia, Philippines, Indonesia.</p><p>Yêu cầu: Sinh viên năm 3-4, IELTS 6.0 trở lên, có ý tưởng dự án cụ thể.</p><p>Hỗ trợ: Vé máy bay, chỗ ở, ăn uống trong thời gian chương trình (7 ngày).</p>',
                'banner_url' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=1400&h=640&fit=crop',
                'start_date' => Carbon::now()->addDays(45),
                'end_date' => Carbon::now()->addDays(52), // 1 tuần
                'status' => 'draft',
                'approval_level' => 'pending_board',
            ],
        ];

        foreach ($pendingCompetitions as $competitionData) {
            Competition::firstOrCreate(
                ['slug' => $competitionData['slug']],
                array_merge($competitionData, [
                    'created_by' => $admin->id,
                ])
            );
        }

        $this->command->info('Đã tạo ' . count($pendingCompetitions) . ' cuộc thi đang chờ duyệt thành công!');
        $this->command->info('- 3 cuộc thi chờ duyệt Trung tâm ĐMST');
        $this->command->info('- 2 cuộc thi chờ duyệt Ban giám hiệu');
    }
}
