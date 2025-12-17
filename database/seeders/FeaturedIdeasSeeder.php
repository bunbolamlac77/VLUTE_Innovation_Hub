<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Faculty;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FeaturedIdeasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::first();
        if (!$owner) {
            $this->command->warn('Không có user nào trong database. Vui lòng chạy AdminUserSeeder trước.');
            return;
        }

        // Đảm bảo có sẵn một số khoa & danh mục cơ bản
        $faculties = [
            ['code' => 'CNTT', 'name' => 'Khoa Công nghệ thông tin', 'description' => 'CNTT', 'sort_order' => 1],
            ['code' => 'DDT', 'name' => 'Khoa Điện - Điện tử', 'description' => 'Điện - Điện tử', 'sort_order' => 2],
            ['code' => 'CKD', 'name' => 'Khoa Cơ khí - Động lực', 'description' => 'Cơ khí - Động lực', 'sort_order' => 3],
            ['code' => 'KT', 'name' => 'Khoa Kinh tế', 'description' => 'Kinh tế', 'sort_order' => 4],
            ['code' => 'NN', 'name' => 'Khoa Ngoại ngữ', 'description' => 'Ngoại ngữ', 'sort_order' => 5],
        ];
        foreach ($faculties as $f) {
            Faculty::firstOrCreate(['code' => $f['code']], $f);
        }

        $categories = [
            ['slug' => 'cong-nghe-thong-tin', 'name' => 'Công nghệ thông tin', 'description' => 'CNTT', 'sort_order' => 1],
            ['slug' => 'dien-tu-tu-dong-hoa', 'name' => 'Điện tử - Tự động hóa', 'description' => 'IoT/Điện tử', 'sort_order' => 2],
            ['slug' => 'co-khi-che-tao', 'name' => 'Cơ khí - Chế tạo', 'description' => 'Cơ khí', 'sort_order' => 3],
            ['slug' => 'kinh-te-quan-ly', 'name' => 'Kinh tế - Quản lý', 'description' => 'Kinh tế', 'sort_order' => 4],
            ['slug' => 'giao-duc-dao-tao', 'name' => 'Giáo dục - Đào tạo', 'description' => 'Giáo dục', 'sort_order' => 5],
        ];
        foreach ($categories as $c) {
            Category::firstOrCreate(['slug' => $c['slug']], $c);
        }

        $facultyCNTT = Faculty::where('code', 'CNTT')->first();
        $facultyDDT = Faculty::where('code', 'DDT')->first();
        $facultyCKD = Faculty::where('code', 'CKD')->first();
        $facultyKT = Faculty::where('code', 'KT')->first();
        $facultyNN = Faculty::where('code', 'NN')->first();

        $categoryCNTT = Category::where('slug', 'cong-nghe-thong-tin')->first();
        $categoryDDT = Category::where('slug', 'dien-tu-tu-dong-hoa')->first();
        $categoryCKD = Category::where('slug', 'co-khi-che-tao')->first();
        $categoryKT = Category::where('slug', 'kinh-te-quan-ly')->first();
        $categoryGD = Category::where('slug', 'giao-duc-dao-tao')->first();

        $ideas = [
            [
                'title' => 'Nền tảng AI trợ lý học thuật cho sinh viên VLUTE',
                'summary' => 'Trợ lý học thuật cá nhân hóa sử dụng AI giúp sinh viên lập kế hoạch học tập, nhắc deadline và gợi ý tài liệu.',
                'description' => 'Ứng dụng web/mobile dùng AI để lập kế hoạch học tập, nhắc lịch thi, gợi ý tài liệu phù hợp từng học phần.',
                'content' => 'Các tính năng: lập kế hoạch, nhắc việc, gợi ý tài liệu, chatbot FAQ học vụ, tích hợp lịch Google.',
                'faculty' => $facultyCNTT,
                'category' => $categoryCNTT,
                'like_count' => 128,
                'image_url' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Hệ thống bãi giữ xe thông minh bằng thị giác máy tính',
                'summary' => 'Nhận diện biển số và phát hiện chỗ trống thời gian thực cho khuôn viên trường.',
                'description' => 'Sử dụng camera + AI nhận diện, hiển thị bản đồ chỗ trống và hỗ trợ thanh toán không tiền mặt.',
                'content' => 'Module: nhận diện biển số, đếm xe, cảnh báo, bảng LED hướng dẫn, ứng dụng quản trị.',
                'faculty' => $facultyDDT,
                'category' => $categoryDDT,
                'like_count' => 96,
                'image_url' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Robot giao nhận tài liệu nội bộ tự hành',
                'summary' => 'Robot nhỏ tự hành giao nhận hồ sơ giữa các phòng ban trong trường.',
                'description' => 'Tối ưu tuyến đường, nhận diện chướng ngại vật, giao nhận có xác thực.',
                'content' => 'Phần cứng cơ khí + định vị SLAM, phần mềm lập lịch và định tuyến.',
                'faculty' => $facultyCKD,
                'category' => $categoryCKD,
                'like_count' => 87,
                'image_url' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Nền tảng kết nối dự án doanh nghiệp với nhóm sinh viên thực hiện',
                'summary' => 'Kết nối doanh nghiệp đăng bài toán thực tế với nhóm sinh viên giải quyết theo mô hình capstone.',
                'description' => 'Chấm điểm, mentor, milestone, báo cáo tiến độ, đánh giá 360 độ.',
                'content' => 'Module: đăng bài toán, lập nhóm, quản lý tiến độ, kho kết quả và showcase.',
                'faculty' => $facultyKT,
                'category' => $categoryKT,
                'like_count' => 142,
                'image_url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Ứng dụng luyện phát âm tiếng Anh với phản hồi thời gian thực',
                'summary' => 'Đánh giá phát âm bằng AI, chấm điểm IPA, gợi ý luyện tập phù hợp.',
                'description' => 'Dùng mô hình nhận dạng giọng nói để so khớp và chấm điểm theo âm vị.',
                'content' => 'Bài học theo chủ đề kỹ thuật, bảng điểm chi tiết, lộ trình cá nhân hóa.',
                'faculty' => $facultyNN,
                'category' => $categoryGD,
                'like_count' => 153,
                'image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=600&fit=crop',
            ],
        ];

        foreach ($ideas as $data) {
            $slug = Str::slug($data['title']);
            $idea = Idea::firstOrCreate(
                ['slug' => $slug],
                [
                    'owner_id' => $owner->id,
                    'title' => $data['title'],
                    'summary' => $data['summary'],
                    'description' => $data['description'],
                    'content' => $data['content'],
                    'status' => 'approved_final',
                    'visibility' => 'public',
                    'faculty_id' => optional($data['faculty'])->id,
                    'category_id' => optional($data['category'])->id,
                    'like_count' => $data['like_count'] ?? 0,
                    'image_url' => $data['image_url'] ?? null,
                ]
            );

            $this->command->info('✓ Đã tạo ý tưởng nổi bật: ' . $idea->title);
        }

        $this->command->info('Đã thêm 5 ý tưởng nổi bật thành công.');
    }
}

