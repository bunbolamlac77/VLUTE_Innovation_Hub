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
            ['name' => 'ACME Corp', 'type' => 'company', 'website' => null, 'contact_email' => null, 'description' => 'Doanh nghiệp lĩnh vực bán lẻ.'],
            ['name' => 'GreenTech VN', 'type' => 'company', 'website' => null, 'contact_email' => null, 'description' => 'Doanh nghiệp công nghệ xanh.'],
            ['name' => 'EduAI Labs', 'type' => 'company', 'website' => null, 'contact_email' => null, 'description' => 'Phòng thí nghiệm AI cho giáo dục.'],
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
                'title' => 'Tối ưu chuỗi cung ứng cho ngành bán lẻ',
                'description' => 'Xây dựng giải pháp dự báo nhu cầu và tối ưu tồn kho theo mùa vụ. Ưu tiên mô hình AI explainable.',
                'organization_id' => $orgIds[0],
                'deadline' => Carbon::now()->addDays(30),
                'reward' => '30.000.000 VND + thực tập 3 tháng',
                'status' => 'open',
            ],
            [
                'title' => 'Giải pháp giám sát năng lượng cho toà nhà',
                'description' => 'Thu thập dữ liệu IoT, cảnh báo bất thường, dashboard realtime, đề xuất tiết kiệm năng lượng.',
                'organization_id' => $orgIds[1],
                'deadline' => Carbon::now()->addDays(20),
                'reward' => 'Thiết bị IoT + Hỗ trợ thương mại hoá',
                'status' => 'open',
            ],
            [
                'title' => 'AI trợ lý học tập cho sinh viên',
                'description' => 'Thiết kế chatbot gợi ý lộ trình học tập cá nhân hoá, tích hợp timetable & nhiệm vụ học tập.',
                'organization_id' => $orgIds[2],
                'deadline' => Carbon::now()->addDays(45),
                'reward' => '20.000.000 VND',
                'status' => 'open',
            ],
            [
                'title' => 'Truy vết chất lượng sản phẩm nông nghiệp',
                'description' => 'Xây dựng hệ thống truy xuất nguồn gốc và tiêu chuẩn chất lượng sử dụng QR và blockchain đơn giản.',
                'organization_id' => $orgIds[1],
                'deadline' => Carbon::now()->subDays(2),
                'reward' => null,
                'status' => 'closed',
            ],
            [
                'title' => 'Hệ thống quản lý bãi đỗ xe thông minh',
                'description' => 'Nhận diện biển số, dự báo chỗ trống, thanh toán không tiếp xúc.',
                'organization_id' => $orgIds[0],
                'deadline' => null,
                'reward' => '10.000.000 VND',
                'status' => 'draft',
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

