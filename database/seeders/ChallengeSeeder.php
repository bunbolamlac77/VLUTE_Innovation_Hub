<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\Challenge;
use Carbon\Carbon;

class ChallengeSeeder extends Seeder
{
    public function run(): void
    {
        // Seed some organizations (if not existing)
        $orgs = [
            ['name' => 'ACME Retail', 'type' => 'company', 'website' => 'https://acme.example.com', 'contact_email' => 'innovation@acme.example.com', 'description' => 'Tập đoàn bán lẻ chuỗi siêu thị, cần tối ưu vận hành.'],
            ['name' => 'GreenTech VN', 'type' => 'company', 'website' => 'https://greentech.example.com', 'contact_email' => 'labs@greentech.example.com', 'description' => 'Công ty giải pháp năng lượng xanh và IoT.'],
            ['name' => 'EduAI Labs', 'type' => 'company', 'website' => 'https://eduai.example.com', 'contact_email' => 'partnership@eduai.example.com', 'description' => 'Phòng thí nghiệm AI cho giáo dục và EdTech.'],
            ['name' => 'CityWorks', 'type' => 'company', 'website' => 'https://cityworks.example.com', 'contact_email' => 'innovation@cityworks.example.com', 'description' => 'Giải pháp hạ tầng đô thị thông minh.'],
            ['name' => 'AgriTrace', 'type' => 'company', 'website' => 'https://agritrace.example.com', 'contact_email' => 'hello@agritrace.example.com', 'description' => 'Chuỗi cung ứng và truy xuất nguồn gốc nông sản.'],
        ];

        $orgIds = [];
        foreach ($orgs as $o) {
            $org = Organization::firstOrCreate(['name' => $o['name']], $o);
            $orgIds[] = $org->id;
        }

        if (empty($orgIds)) {
            return;
        }

        $samples = [
            [
                'title' => 'Tối ưu chuỗi cung ứng bán lẻ bằng AI Explainable',
                'description' => 'Xây dựng mô hình dự báo nhu cầu theo mùa vụ, giải thích được, giúp điều phối kho và vận chuyển.',
                'organization_id' => $orgIds[0],
                'deadline' => Carbon::now()->addDays(35),
                'reward' => '30.000.000 VND + thực tập 3 tháng',
                'status' => 'open',
                'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1100&h=700&fit=crop',
            ],
            [
                'title' => 'Giám sát năng lượng toà nhà học tập',
                'description' => 'Thu thập dữ liệu điện, dự báo tải, cảnh báo bất thường và gợi ý tiết kiệm theo giờ cao điểm.',
                'organization_id' => $orgIds[1],
                'deadline' => Carbon::now()->addDays(25),
                'reward' => 'Thiết bị IoT + hỗ trợ POC thực tế',
                'status' => 'open',
                'image_url' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=1100&h=700&fit=crop',
            ],
            [
                'title' => 'AI trợ lý học tập cá nhân hóa',
                'description' => 'Chatbot gợi ý lộ trình học, đồng bộ thời khóa biểu, theo dõi tiến độ và nhắc deadline.',
                'organization_id' => $orgIds[2],
                'deadline' => Carbon::now()->addDays(60),
                'reward' => '20.000.000 VND + mentor kỹ thuật',
                'status' => 'open',
                'image_url' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=1100&h=700&fit=crop',
            ],
            [
                'title' => 'Truy xuất nguồn gốc nông sản & chuỗi lạnh',
                'description' => 'Ghi nhận nhiệt độ/độ ẩm, chứng thực sự kiện vận chuyển bằng QR/NFC, cảnh báo vỡ chuỗi lạnh.',
                'organization_id' => $orgIds[4],
                'deadline' => Carbon::now()->subDays(2),
                'reward' => 'Thiết bị demo + kết nối hợp tác',
                'status' => 'closed',
                'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1100&h=700&fit=crop',
            ],
            [
                'title' => 'Bãi đỗ xe thông minh cho khuôn viên trường',
                'description' => 'Nhận diện biển số, dự đoán chỗ trống, cảnh báo gian lận vé tháng, tích hợp thanh toán không tiền mặt.',
                'organization_id' => $orgIds[3],
                'deadline' => null,
                'reward' => '10.000.000 VND',
                'status' => 'draft',
                'image_url' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=1100&h=700&fit=crop',
            ],
            [
                'title' => 'Phân tích dữ liệu khách hàng cho cửa hàng tiện lợi',
                'description' => 'Thu thập lịch sử bán hàng, giỏ hàng và hành vi mua lặp lại để gợi ý combo, tối ưu tồn kho.',
                'organization_id' => $orgIds[0],
                'deadline' => Carbon::now()->addDays(50),
                'reward' => 'Gói tư vấn triển khai miễn phí',
                'status' => 'open',
                'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1100&h=700&fit=crop',
            ],
            [
                'title' => 'Quản lý rác thải thông minh trong campus',
                'description' => 'Thùng rác cảm biến, nhận diện phân loại, dashboard thu gom và gamification điểm xanh.',
                'organization_id' => $orgIds[1],
                'deadline' => Carbon::now()->addDays(18),
                'reward' => 'Hỗ trợ triển khai thử nghiệm tại trường',
                'status' => 'open',
                'image_url' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=1100&h=700&fit=crop',
            ],
            [
                'title' => 'Trợ lý ảo cho cố vấn học tập',
                'description' => 'Cố vấn ảo nhắc lịch, gợi ý môn học, tổng hợp cảnh báo điểm danh và tiến độ của sinh viên.',
                'organization_id' => $orgIds[2],
                'deadline' => Carbon::now()->addDays(90),
                'reward' => 'Cơ hội thương mại hóa cùng EduAI Labs',
                'status' => 'open',
                'image_url' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=1100&h=700&fit=crop',
            ],
            [
                'title' => 'Đặt phòng học và thiết bị dùng chung',
                'description' => 'Giải quyết xung đột lịch phòng, check-in bằng QR, thống kê mức sử dụng để tối ưu phân bổ.',
                'organization_id' => $orgIds[3],
                'deadline' => null,
                'reward' => '15.000.000 VND',
                'status' => 'draft',
                'image_url' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1100&h=700&fit=crop',
            ],
            [
                'title' => 'Ứng dụng hỗ trợ sức khỏe tinh thần sinh viên',
                'description' => 'Tự đánh giá trạng thái, gợi ý bài tập thở/nghỉ, kết nối counselor và phòng CTCT.',
                'organization_id' => $orgIds[2],
                'deadline' => Carbon::now()->subDays(5),
                'reward' => 'Gói mentor UX + chuyên gia tâm lý',
                'status' => 'closed',
                'image_url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=1100&h=700&fit=crop',
            ],
        ];

        foreach ($samples as $s) {
            Challenge::firstOrCreate([
                'title' => $s['title'],
                'organization_id' => $s['organization_id'],
            ], $s);
        }
    }
}

