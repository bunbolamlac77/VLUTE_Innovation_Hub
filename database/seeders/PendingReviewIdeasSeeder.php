<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Faculty;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PendingReviewIdeasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy sinh viên để làm owner (ưu tiên lấy sinh viên đã có sẵn)
        $students = User::where('role', 'student')->where('approval_status', 'approved')->get();
        
        if ($students->isEmpty()) {
            $this->command->warn('Không có sinh viên nào trong database. Vui lòng chạy ApprovedUsersSeeder trước.');
            return;
        }

        // Đảm bảo có faculties và categories
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

        // 5 ý tưởng trong hàng chờ phản biện với các trạng thái khác nhau
        $pendingIdeas = [
            // 1. Chờ duyệt Trung tâm ĐMST (submitted_center)
            [
                'title' => 'Hệ thống quản lý thư viện số thông minh với AI',
                'slug' => 'he-thong-quan-ly-thu-vien-so-thong-minh-voi-ai',
                'summary' => 'Xây dựng hệ thống thư viện số tích hợp AI để gợi ý sách, quản lý mượn trả tự động và phân tích xu hướng đọc của sinh viên.',
                'description' => '<p>Hệ thống sử dụng AI để phân tích sở thích đọc, gợi ý sách phù hợp, quản lý mượn trả tự động qua QR code và thống kê xu hướng đọc trong trường.</p>',
                'content' => '## Tổng quan dự án

Hệ thống quản lý thư viện số thông minh là giải pháp toàn diện giúp nâng cao trải nghiệm đọc sách và quản lý thư viện hiệu quả hơn.

## Các tính năng chính

### 1. Gợi ý sách thông minh
- AI phân tích lịch sử đọc và sở thích
- Đề xuất sách liên quan đến môn học đang học
- Gợi ý theo chủ đề và tác giả yêu thích

### 2. Quản lý mượn trả tự động
- Quét QR code để mượn/trả sách
- Nhắc nhở hạn trả tự động
- Theo dõi lịch sử mượn trả

### 3. Phân tích và báo cáo
- Thống kê sách được mượn nhiều nhất
- Phân tích xu hướng đọc theo khoa
- Báo cáo tỷ lệ sử dụng thư viện

## Công nghệ sử dụng
- **Frontend**: React.js
- **Backend**: Laravel API
- **AI/ML**: Recommendation system với collaborative filtering
- **Database**: PostgreSQL',
                'status' => 'submitted_center',
                'visibility' => 'public',
                'faculty' => $facultyCNTT,
                'category' => $categoryCNTT,
                'image_url' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800&h=600&fit=crop',
            ],
            // 2. Chờ duyệt Trung tâm ĐMST (submitted_center)
            [
                'title' => 'Ứng dụng quản lý thực tập và việc làm cho sinh viên',
                'slug' => 'ung-dung-quan-ly-thuc-tap-va-viec-lam-cho-sinh-vien',
                'summary' => 'Nền tảng kết nối sinh viên với doanh nghiệp, quản lý quy trình thực tập, đánh giá và cơ hội việc làm sau tốt nghiệp.',
                'description' => '<p>Hệ thống giúp sinh viên tìm kiếm cơ hội thực tập, quản lý hồ sơ, theo dõi tiến độ và nhận feedback từ doanh nghiệp.</p>',
                'content' => '## Tổng quan dự án

Ứng dụng quản lý thực tập và việc làm là cầu nối giữa sinh viên và doanh nghiệp, giúp tối ưu hóa quy trình tuyển dụng và thực tập.

## Các tính năng chính

### 1. Quản lý hồ sơ
- Tạo và cập nhật CV online
- Portfolio dự án và thành tích
- Chứng chỉ và kỹ năng

### 2. Tìm kiếm và matching
- Tìm kiếm cơ hội thực tập/việc làm
- AI matching dựa trên profile
- Đề xuất vị trí phù hợp

### 3. Quản lý quy trình
- Theo dõi đơn ứng tuyển
- Lịch phỏng vấn và nhắc nhở
- Đánh giá và feedback

## Công nghệ sử dụng
- **Mobile**: React Native
- **Backend**: Laravel API
- **AI/ML**: Matching algorithm
- **Database**: PostgreSQL',
                'status' => 'submitted_center',
                'visibility' => 'public',
                'faculty' => $facultyKT,
                'category' => $categoryKT,
                'image_url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&h=600&fit=crop',
            ],
            // 3. Chờ duyệt Ban giám hiệu (submitted_board)
            [
                'title' => 'Hệ thống giám sát môi trường thông minh cho khuôn viên trường',
                'slug' => 'he-thong-giam-sat-moi-truong-thong-minh-cho-khuon-vien-truong',
                'summary' => 'Triển khai mạng lưới cảm biến IoT để giám sát chất lượng không khí, tiếng ồn, nhiệt độ và độ ẩm trong khuôn viên trường.',
                'description' => '<p>Hệ thống sử dụng cảm biến IoT phân tán để thu thập dữ liệu môi trường, phân tích và cảnh báo khi có vấn đề, hỗ trợ quyết định quản lý môi trường.</p>',
                'content' => '## Tổng quan dự án

Hệ thống giám sát môi trường thông minh giúp nhà trường theo dõi và quản lý chất lượng môi trường trong khuôn viên một cách hiệu quả.

## Các tính năng chính

### 1. Giám sát thời gian thực
- Cảm biến đo chất lượng không khí (PM2.5, PM10, CO2)
- Đo tiếng ồn và độ rung
- Theo dõi nhiệt độ và độ ẩm

### 2. Phân tích và cảnh báo
- Dashboard hiển thị dữ liệu real-time
- Cảnh báo khi vượt ngưỡng cho phép
- Phân tích xu hướng theo thời gian

### 3. Báo cáo và xuất dữ liệu
- Báo cáo định kỳ về chất lượng môi trường
- Xuất dữ liệu để phân tích sâu
- So sánh với tiêu chuẩn quốc gia

## Công nghệ sử dụng
- **IoT**: Raspberry Pi, Arduino, các loại cảm biến
- **Communication**: LoRaWAN, WiFi
- **Backend**: Laravel API
- **Frontend**: React.js dashboard
- **Database**: InfluxDB cho time-series data',
                'status' => 'submitted_board',
                'visibility' => 'public',
                'faculty' => $facultyDDT,
                'category' => $categoryDDT,
                'image_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=800&h=600&fit=crop',
            ],
            // 4. Chờ duyệt Ban giám hiệu (submitted_board)
            [
                'title' => 'Nền tảng học tập trực tuyến tích hợp AI cho giáo dục đại học',
                'slug' => 'nen-tang-hoc-tap-truc-tuyen-tich-hop-ai-cho-giao-duc-dai-hoc',
                'summary' => 'Xây dựng nền tảng học tập trực tuyến với AI tutor, tự động chấm bài, gợi ý học tập cá nhân hóa và phân tích hiệu quả học tập.',
                'description' => '<p>Hệ thống cung cấp trải nghiệm học tập cá nhân hóa với AI tutor, tự động chấm bài trắc nghiệm, gợi ý nội dung học và phân tích điểm mạnh/yếu của sinh viên.</p>',
                'content' => '## Tổng quan dự án

Nền tảng học tập trực tuyến tích hợp AI là giải pháp toàn diện cho giáo dục đại học trong kỷ nguyên số.

## Các tính năng chính

### 1. AI Tutor
- Chatbot trả lời câu hỏi về bài học
- Giải thích khái niệm khó hiểu
- Gợi ý tài liệu học tập liên quan

### 2. Tự động chấm bài
- Chấm bài trắc nghiệm tự động
- Phân tích câu trả lời tự luận
- Đưa ra feedback chi tiết

### 3. Học tập cá nhân hóa
- Gợi ý nội dung học phù hợp với trình độ
- Lộ trình học tập tùy chỉnh
- Phân tích điểm mạnh/yếu

### 4. Phân tích hiệu quả
- Dashboard theo dõi tiến độ học tập
- Báo cáo điểm số và tham gia
- Dự đoán kết quả học tập

## Công nghệ sử dụng
- **Frontend**: React.js với video streaming
- **Backend**: Laravel API
- **AI/ML**: NLP cho chatbot, ML cho gợi ý
- **Video**: WebRTC hoặc streaming service
- **Database**: PostgreSQL',
                'status' => 'submitted_board',
                'visibility' => 'public',
                'faculty' => $facultyCNTT,
                'category' => $categoryGD,
                'image_url' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&h=600&fit=crop',
            ],
            // 5. Cần sửa ở cấp Trung tâm (needs_change_center)
            [
                'title' => 'Robot phục vụ tự động cho căng tin trường học',
                'slug' => 'robot-phuc-vu-tu-dong-cho-cang-tin-truong-hoc',
                'summary' => 'Thiết kế và chế tạo robot phục vụ tự động có thể di chuyển, nhận order, giao đồ ăn và thanh toán tự động tại căng tin.',
                'description' => '<p>Robot được trang bị khả năng điều hướng tự động, nhận diện khách hàng, giao đồ ăn và xử lý thanh toán qua QR code hoặc NFC.</p>',
                'content' => '## Tổng quan dự án

Robot phục vụ tự động là giải pháp công nghệ giúp nâng cao trải nghiệm ăn uống và giảm thời gian chờ đợi tại căng tin.

## Các tính năng chính

### 1. Điều hướng tự động
- SLAM để lập bản đồ và định vị
- Tránh chướng ngại vật
- Tối ưu đường đi

### 2. Nhận order và giao hàng
- Nhận order qua app hoặc màn hình cảm ứng
- Tự động lấy đồ ăn từ bếp
- Giao đến bàn khách hàng

### 3. Thanh toán tự động
- Quét QR code để thanh toán
- Hỗ trợ NFC
- Tích hợp ví điện tử

## Công nghệ sử dụng
- **Hardware**: Robot platform, camera, LiDAR
- **Software**: ROS (Robot Operating System)
- **AI/ML**: Computer vision cho nhận diện
- **Mobile App**: React Native để đặt hàng
- **Backend**: Laravel API',
                'status' => 'needs_change_center',
                'visibility' => 'public',
                'faculty' => $facultyCKD,
                'category' => $categoryCKD,
                'image_url' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=800&h=600&fit=crop',
            ],
        ];

        $createdCount = 0;
        foreach ($pendingIdeas as $index => $ideaData) {
            // Phân bổ owner ngẫu nhiên từ danh sách sinh viên
            $owner = $students->random();
            
            // Tạo slug nếu chưa có
            $slug = $ideaData['slug'] ?? Str::slug($ideaData['title']);
            
            $idea = Idea::firstOrCreate(
                ['slug' => $slug],
                [
                    'owner_id' => $owner->id,
                    'title' => $ideaData['title'],
                    'summary' => $ideaData['summary'],
                    'description' => $ideaData['description'],
                    'content' => $ideaData['content'] ?? '',
                    'status' => $ideaData['status'],
                    'visibility' => $ideaData['visibility'],
                    'faculty_id' => $ideaData['faculty']?->id,
                    'category_id' => $ideaData['category']?->id,
                    'image_url' => $ideaData['image_url'] ?? null,
                ]
            );

            if ($idea->wasRecentlyCreated) {
                $createdCount++;
                $this->command->info('✓ Đã tạo: ' . $idea->title . ' (Status: ' . $idea->status . ')');
            }
        }

        $this->command->info(PHP_EOL . 'Đã tạo thành công ' . $createdCount . ' ý tưởng trong hàng chờ phản biện!');
        $this->command->info('- 2 ý tưởng chờ duyệt Trung tâm ĐMST (submitted_center)');
        $this->command->info('- 2 ý tưởng chờ duyệt Ban giám hiệu (submitted_board)');
        $this->command->info('- 1 ý tưởng cần sửa ở cấp Trung tâm (needs_change_center)');
    }
}
