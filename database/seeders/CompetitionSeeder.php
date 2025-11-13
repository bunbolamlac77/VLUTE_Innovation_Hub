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
                'title' => 'Cuộc thi Sáng tạo Khởi nghiệp 2025',
                'slug' => 'cuoc-thi-sang-tao-khoi-nghiep-2025',
                'description' => '<p>Cuộc thi Sáng tạo Khởi nghiệp 2025 là sân chơi dành cho các bạn sinh viên có đam mê khởi nghiệp và sáng tạo. Cuộc thi nhằm tìm kiếm và phát triển các ý tưởng kinh doanh tiềm năng.</p>
                <h3>Thể lệ cuộc thi:</h3>
                <ul>
                    <li>Đối tượng: Sinh viên các trường đại học, cao đẳng</li>
                    <li>Hình thức: Cá nhân hoặc nhóm (tối đa 5 người)</li>
                    <li>Hồ sơ: Ý tưởng kinh doanh, kế hoạch triển khai, video giới thiệu</li>
                </ul>
                <h3>Giải thưởng:</h3>
                <ul>
                    <li>Giải Nhất: 50.000.000 VNĐ + Hỗ trợ ươm tạo</li>
                    <li>Giải Nhì: 30.000.000 VNĐ</li>
                    <li>Giải Ba: 20.000.000 VNĐ</li>
                </ul>',
                'banner_url' => '/images/panel-truong.jpg',
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(30),
                'status' => 'open',
            ],
            [
                'title' => 'Hackathon Công nghệ Thông tin 2025',
                'slug' => 'hackathon-cong-nghe-thong-tin-2025',
                'description' => '<p>Hackathon Công nghệ Thông tin 2025 là cuộc thi lập trình marathon dành cho các lập trình viên, sinh viên CNTT. Tham gia để phát triển các giải pháp công nghệ sáng tạo trong 48 giờ.</p>
                <h3>Chủ đề:</h3>
                <ul>
                    <li>Giải pháp số hóa giáo dục</li>
                    <li>Ứng dụng AI trong cuộc sống</li>
                    <li>Blockchain và Fintech</li>
                    <li>IoT và Smart City</li>
                </ul>
                <h3>Yêu cầu:</h3>
                <ul>
                    <li>Nhóm 2-4 người</li>
                    <li>Code tại chỗ trong 48 giờ</li>
                    <li>Demo sản phẩm cuối cùng</li>
                </ul>',
                'banner_url' => '/images/panel-truong.jpg',
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(45),
                'status' => 'open',
            ],
            [
                'title' => 'Cuộc thi Thiết kế Sản phẩm Sáng tạo',
                'slug' => 'cuoc-thi-thiet-ke-san-pham-sang-tao',
                'description' => '<p>Cuộc thi tìm kiếm các thiết kế sản phẩm sáng tạo, có tính ứng dụng cao trong đời sống. Dành cho sinh viên các ngành thiết kế, kỹ thuật, và các ngành liên quan.</p>
                <h3>Hạng mục:</h3>
                <ul>
                    <li>Thiết kế sản phẩm công nghiệp</li>
                    <li>Thiết kế đồ họa</li>
                    <li>Thiết kế nội thất</li>
                    <li>Thiết kế thời trang</li>
                </ul>',
                'banner_url' => '/images/panel-truong.jpg',
                'start_date' => Carbon::now()->addDays(7),
                'end_date' => Carbon::now()->addDays(60),
                'status' => 'open',
            ],
            [
                'title' => 'Cuộc thi Nghiên cứu Khoa học Sinh viên',
                'slug' => 'cuoc-thi-nghien-cuu-khoa-hoc-sinh-vien',
                'description' => '<p>Cuộc thi khuyến khích sinh viên tham gia nghiên cứu khoa học, phát triển các đề tài nghiên cứu có giá trị khoa học và thực tiễn.</p>
                <h3>Lĩnh vực:</h3>
                <ul>
                    <li>Khoa học tự nhiên</li>
                    <li>Khoa học xã hội</li>
                    <li>Kỹ thuật và Công nghệ</li>
                    <li>Y tế và Sức khỏe</li>
                </ul>',
                'banner_url' => '/images/panel-truong.jpg',
                'start_date' => Carbon::now()->subDays(60),
                'end_date' => Carbon::now()->subDays(30),
                'status' => 'closed',
            ],
            [
                'title' => 'Cuộc thi Sáng tạo Video Marketing',
                'slug' => 'cuoc-thi-sang-tao-video-marketing',
                'description' => '<p>Cuộc thi tìm kiếm các video marketing sáng tạo, có khả năng lan tỏa và tạo tác động tích cực đến cộng đồng.</p>
                <h3>Yêu cầu:</h3>
                <ul>
                    <li>Video dài 30 giây - 3 phút</li>
                    <li>Nội dung tích cực, có ý nghĩa</li>
                    <li>Chất lượng HD trở lên</li>
                </ul>',
                'banner_url' => '/images/panel-truong.jpg',
                'start_date' => Carbon::now()->addDays(20),
                'end_date' => Carbon::now()->addDays(50),
                'status' => 'draft',
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
