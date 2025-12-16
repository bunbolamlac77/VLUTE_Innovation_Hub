<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Faculty;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleIdeaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy hoặc tạo một user để làm owner (lấy user đầu tiên có sẵn)
        $owner = User::first();

        if (!$owner) {
            $this->command->warn('Không có user nào trong database. Vui lòng chạy AdminUserSeeder trước.');
            return;
        }

        // Tạo các khoa
        $faculties = [
            [
                'code' => 'CNTT',
                'name' => 'Khoa Công nghệ thông tin',
                'description' => 'Khoa Công nghệ thông tin - Đào tạo các chuyên ngành về CNTT',
                'sort_order' => 1,
            ],
            [
                'code' => 'DDT',
                'name' => 'Khoa Điện - Điện tử',
                'description' => 'Khoa Điện - Điện tử - Đào tạo các chuyên ngành về điện, điện tử, tự động hóa',
                'sort_order' => 2,
            ],
            [
                'code' => 'CKD',
                'name' => 'Khoa Cơ khí - Động lực',
                'description' => 'Khoa Cơ khí - Động lực - Đào tạo các chuyên ngành về cơ khí, chế tạo máy',
                'sort_order' => 3,
            ],
            [
                'code' => 'TY',
                'name' => 'Khoa Thú y',
                'description' => 'Khoa Thú y - Đào tạo các chuyên ngành về thú y, chăn nuôi, bảo vệ sức khỏe động vật',
                'sort_order' => 4,
            ],
            [
                'code' => 'KT',
                'name' => 'Khoa Kinh tế',
                'description' => 'Khoa Kinh tế - Đào tạo các chuyên ngành về kinh tế, quản lý',
                'sort_order' => 5,
            ],
            [
                'code' => 'NN',
                'name' => 'Khoa Ngoại ngữ',
                'description' => 'Khoa Ngoại ngữ - Đào tạo các chuyên ngành về ngôn ngữ, văn hóa',
                'sort_order' => 6,
            ],
        ];

        foreach ($faculties as $facultyData) {
            Faculty::firstOrCreate(
                ['code' => $facultyData['code']],
                $facultyData
            );
        }

        // Tạo các danh mục
        $categories = [
            [
                'slug' => 'cong-nghe-thong-tin',
                'name' => 'Công nghệ thông tin',
                'description' => 'Các ý tưởng về công nghệ thông tin, phần mềm, hệ thống',
                'sort_order' => 1,
            ],
            [
                'slug' => 'dien-tu-tu-dong-hoa',
                'name' => 'Điện tử - Tự động hóa',
                'description' => 'Các ý tưởng về điện tử, tự động hóa, IoT',
                'sort_order' => 2,
            ],
            [
                'slug' => 'co-khi-che-tao',
                'name' => 'Cơ khí - Chế tạo',
                'description' => 'Các ý tưởng về cơ khí, chế tạo, sản xuất',
                'sort_order' => 3,
            ],
            [
                'slug' => 'thu-y-chan-nuoi',
                'name' => 'Thú y - Chăn nuôi',
                'description' => 'Các ý tưởng về thú y, chăn nuôi, bảo vệ sức khỏe động vật',
                'sort_order' => 4,
            ],
            [
                'slug' => 'kinh-te-quan-ly',
                'name' => 'Kinh tế - Quản lý',
                'description' => 'Các ý tưởng về kinh tế, quản lý, khởi nghiệp',
                'sort_order' => 5,
            ],
            [
                'slug' => 'giao-duc-dao-tao',
                'name' => 'Giáo dục - Đào tạo',
                'description' => 'Các ý tưởng về giáo dục, đào tạo, phương pháp học tập',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Lấy lại các khoa và danh mục đã tạo
        $facultyCNTT = Faculty::where('code', 'CNTT')->first();
        $facultyDDT = Faculty::where('code', 'DDT')->first();
        $facultyCKD = Faculty::where('code', 'CKD')->first();
        $facultyTY = Faculty::where('code', 'TY')->first();
        $facultyKT = Faculty::where('code', 'KT')->first();
        $facultyNN = Faculty::where('code', 'NN')->first();

        $categoryCNTT = Category::where('slug', 'cong-nghe-thong-tin')->first();
        $categoryDDT = Category::where('slug', 'dien-tu-tu-dong-hoa')->first();
        $categoryCKD = Category::where('slug', 'co-khi-che-tao')->first();
        $categoryTY = Category::where('slug', 'thu-y-chan-nuoi')->first();
        $categoryKT = Category::where('slug', 'kinh-te-quan-ly')->first();
        $categoryGD = Category::where('slug', 'giao-duc-dao-tao')->first();

        // Tạo ít nhất 10 ý tưởng mẫu trong \"ngân hàng ý tưởng\"
        $ideas = [
            [
                'title' => 'Hệ thống quản lý đổi mới sáng tạo dựa trên AI và Big Data cho các trường đại học',
                'summary' => 'Xây dựng nền tảng quản lý và hỗ trợ các hoạt động đổi mới sáng tạo trong trường đại học bằng công nghệ AI và phân tích dữ liệu lớn.',
                'description' => 'Ý tưởng tập trung vào việc phát triển một hệ thống tích hợp sử dụng trí tuệ nhân tạo và phân tích dữ liệu lớn để quản lý, đánh giá và hỗ trợ các dự án đổi mới sáng tạo trong môi trường giáo dục đại học.',
                'content' => '## Tổng quan dự án

Hệ thống quản lý đổi mới sáng tạo dựa trên AI và Big Data là một nền tảng toàn diện được thiết kế để hỗ trợ các trường đại học trong việc quản lý, đánh giá và phát triển các ý tưởng đổi mới sáng tạo từ sinh viên và giảng viên.

## Các tính năng chính

### 1. Quản lý ý tưởng thông minh
- Hệ thống tự động phân loại và gắn thẻ ý tưởng dựa trên nội dung
- Đề xuất các ý tưởng tương tự hoặc bổ trợ
- Phân tích tiềm năng thương mại hóa

### 2. Đánh giá tự động
- Sử dụng AI để đánh giá sơ bộ chất lượng ý tưởng
- Phân tích tính khả thi về mặt kỹ thuật và thị trường
- Gợi ý các chuyên gia phù hợp để đánh giá chuyên sâu

### 3. Phân tích dữ liệu
- Thống kê và báo cáo tự động về xu hướng ý tưởng
- Phân tích hiệu quả các chương trình đổi mới sáng tạo
- Dự đoán xu hướng công nghệ trong tương lai

## Công nghệ sử dụng

- **Frontend**: React.js hoặc Vue.js
- **Backend**: Laravel với API RESTful
- **AI/ML**: TensorFlow hoặc PyTorch cho các mô hình phân loại và đề xuất
- **Big Data**: Apache Spark hoặc Hadoop cho xử lý dữ liệu lớn
- **Database**: PostgreSQL với hỗ trợ full-text search',
                'faculty' => $facultyCNTT,
                'category' => $categoryCNTT,
            ],
            [
                'title' => 'Hệ thống giám sát và điều khiển thông minh cho nhà kính nông nghiệp công nghệ cao',
                'summary' => 'Phát triển hệ thống tự động hóa quản lý nhiệt độ, độ ẩm, ánh sáng và tưới tiêu cho nhà kính sử dụng IoT và trí tuệ nhân tạo.',
                'description' => 'Ý tưởng về một hệ thống giám sát thông minh tích hợp cảm biến IoT, điều khiển tự động và ứng dụng AI để tối ưu hóa điều kiện môi trường cho cây trồng trong nhà kính.',
                'content' => '## Tổng quan dự án

Hệ thống giám sát và điều khiển thông minh cho nhà kính là giải pháp tích hợp IoT, AI và tự động hóa để quản lý tối ưu môi trường canh tác trong nhà kính nông nghiệp công nghệ cao.

## Các tính năng chính

### 1. Giám sát thời gian thực
- Cảm biến đo nhiệt độ, độ ẩm không khí và đất
- Đo cường độ ánh sáng và CO2
- Camera giám sát sức khỏe cây trồng

### 2. Điều khiển tự động
- Tự động điều chỉnh nhiệt độ và độ ẩm
- Hệ thống tưới tiêu thông minh
- Điều khiển rèm che và quạt thông gió

### 3. Phân tích dữ liệu AI
- Dự đoán nhu cầu nước và chất dinh dưỡng
- Cảnh báo sớm về sâu bệnh
- Tối ưu hóa năng suất cây trồng

## Công nghệ sử dụng

- **Hardware**: Raspberry Pi, Arduino, cảm biến IoT
- **Communication**: LoRaWAN, WiFi, Bluetooth
- **AI/ML**: Machine Learning cho dự đoán và tối ưu hóa
- **Mobile App**: React Native để điều khiển từ xa',
                'faculty' => $facultyDDT,
                'category' => $categoryDDT,
            ],
            [
                'title' => 'Máy cắt cỏ tự động sử dụng năng lượng mặt trời cho khu vực công cộng',
                'summary' => 'Thiết kế và chế tạo robot cắt cỏ tự động chạy bằng năng lượng mặt trời, tích hợp GPS và cảm biến để làm việc độc lập tại các khu vực công cộng.',
                'description' => 'Phát triển một hệ thống robot cắt cỏ thông minh với khả năng tự động định vị, lập kế hoạch đường đi và hoạt động hoàn toàn bằng năng lượng mặt trời.',
                'content' => '## Tổng quan dự án

Máy cắt cỏ tự động là một giải pháp thân thiện với môi trường, sử dụng năng lượng mặt trời để vận hành, giúp giảm chi phí và bảo vệ môi trường trong việc chăm sóc cảnh quan công cộng.

## Các tính năng chính

### 1. Tự động hóa hoàn toàn
- Lập kế hoạch đường đi thông minh
- Tránh chướng ngại vật tự động
- Quay về trạm sạc khi hết pin

### 2. Năng lượng mặt trời
- Tấm pin mặt trời tích hợp
- Pin dự phòng cho ngày không nắng
- Tiết kiệm năng lượng tối đa

### 3. An toàn và giám sát
- Cảm biến dừng khẩn cấp
- GPS tracking và giám sát từ xa
- Camera phát hiện người và động vật

## Công nghệ sử dụng

- **Mechanical**: Khung robot, động cơ DC, lưỡi cắt
- **Electronics**: Arduino/Raspberry Pi, GPS module, cảm biến
- **Energy**: Tấm pin mặt trời, bộ sạc thông minh
- **Software**: Thuật toán path planning, computer vision',
                'faculty' => $facultyCKD,
                'category' => $categoryCKD,
            ],
            [
                'title' => 'Hệ thống quản lý sức khỏe và phòng bệnh cho gia súc, gia cầm sử dụng IoT và AI',
                'summary' => 'Phát triển hệ thống giám sát sức khỏe động vật thông minh sử dụng cảm biến IoT và trí tuệ nhân tạo để phát hiện sớm bệnh tật, theo dõi tăng trưởng và tối ưu hóa chế độ chăn nuôi.',
                'description' => 'Ý tưởng về một hệ thống tích hợp cảm biến IoT, AI và mobile app để quản lý sức khỏe đàn gia súc, gia cầm một cách hiệu quả, giúp người chăn nuôi phát hiện và xử lý bệnh tật kịp thời.',
                'content' => '## Tổng quan dự án

Hệ thống quản lý sức khỏe và phòng bệnh cho gia súc, gia cầm là giải pháp công nghệ toàn diện giúp người chăn nuôi quản lý đàn vật nuôi hiệu quả hơn, giảm thiểu rủi ro bệnh tật và tăng năng suất chăn nuôi.

## Các tính năng chính

### 1. Giám sát sức khỏe thời gian thực
- Cảm biến đo nhiệt độ cơ thể, nhịp tim
- Theo dõi hoạt động và hành vi động vật
- Phát hiện bất thường tự động

### 2. Phát hiện bệnh sớm bằng AI
- Machine Learning phân tích dấu hiệu bệnh
- Cảnh báo sớm về các bệnh truyền nhiễm
- Gợi ý phương pháp điều trị phù hợp

### 3. Quản lý đàn và lịch tiêm phòng
- Quản lý thông tin từng con vật
- Lịch tiêm phòng và khám sức khỏe định kỳ
- Theo dõi tăng trưởng và năng suất

### 4. Ứng dụng di động
- Thông báo cảnh báo bệnh tật
- Quản lý từ xa qua smartphone
- Báo cáo và thống kê trực quan

## Công nghệ sử dụng

- **IoT Sensors**: Cảm biến nhiệt độ, nhịp tim, hoạt động
- **AI/ML**: Machine Learning cho phát hiện bệnh và dự đoán
- **Mobile App**: React Native để quản lý từ xa
- **Backend**: Laravel API với real-time notifications
- **Database**: PostgreSQL lưu trữ dữ liệu lịch sử',
                'faculty' => $facultyTY,
                'category' => $categoryTY,
            ],
            [
                'title' => 'Nền tảng kết nối sinh viên với doanh nghiệp để thực tập và việc làm',
                'summary' => 'Xây dựng nền tảng số kết nối sinh viên với các doanh nghiệp địa phương, hỗ trợ tìm kiếm cơ hội thực tập, việc làm bán thời gian và việc làm chính thức.',
                'description' => 'Phát triển một hệ thống matching thông minh sử dụng AI để kết nối sinh viên phù hợp với các vị trí thực tập và việc làm tại các doanh nghiệp trong khu vực Đồng bằng sông Cửu Long.',
                'content' => '## Tổng quan dự án

Nền tảng kết nối sinh viên với doanh nghiệp là cầu nối quan trọng giúp sinh viên tìm được cơ hội thực tập và việc làm phù hợp, đồng thời giúp doanh nghiệp tìm được nhân tài tiềm năng.

## Các tính năng chính

### 1. Hệ thống matching thông minh
- AI phân tích profile và yêu cầu công việc
- Đề xuất các vị trí phù hợp
- Đánh giá độ phù hợp và điểm số

### 2. Quản lý hồ sơ
- Tạo và quản lý CV online
- Portfolio dự án và thành tích
- Chứng chỉ và kỹ năng

### 3. Tích hợp đầy đủ
- Hệ thống apply và tracking
- Lịch phỏng vấn và nhắc nhở
- Đánh giá và feedback

## Công nghệ sử dụng

- **Frontend**: React.js với UI/UX hiện đại
- **Backend**: Laravel API
- **AI/ML**: Machine Learning cho matching algorithm
- **Database**: PostgreSQL với full-text search
- **Mobile**: React Native app',
                'faculty' => $facultyKT,
                'category' => $categoryKT,
            ],
            [
                'title' => 'Ứng dụng học tiếng Anh thông qua trò chơi và AI cho sinh viên kỹ thuật',
                'summary' => 'Phát triển ứng dụng mobile học tiếng Anh chuyên ngành kỹ thuật thông qua gamification và trợ lý AI, giúp sinh viên cải thiện kỹ năng ngôn ngữ một cách thú vị và hiệu quả.',
                'description' => 'Ứng dụng tích hợp game-based learning, AI conversation practice và từ vựng chuyên ngành kỹ thuật để giúp sinh viên kỹ thuật học tiếng Anh hiệu quả hơn.',
                'content' => '## Tổng quan dự án

Ứng dụng học tiếng Anh chuyên ngành kỹ thuật là công cụ hỗ trợ sinh viên kỹ thuật cải thiện khả năng tiếng Anh thông qua phương pháp học tập thú vị và tương tác.

## Các tính năng chính

### 1. Game-based Learning
- Trò chơi từ vựng và ngữ pháp
- Thử thách hàng ngày và thành tích
- Leaderboard và phần thưởng

### 2. AI Conversation Practice
- Chatbot AI để luyện hội thoại
- Phát âm và sửa lỗi tự động
- Tình huống thực tế chuyên ngành

### 3. Nội dung chuyên ngành
- Từ vựng kỹ thuật theo ngành
- Bài đọc và nghe hiểu
- Video và podcast chuyên ngành

## Công nghệ sử dụng

- **Mobile**: React Native hoặc Flutter
- **AI/ML**: Natural Language Processing, Speech Recognition
- **Backend**: Node.js hoặc Laravel API
- **Gamification**: Hệ thống điểm, badge, achievement',
                'faculty' => $facultyNN,
                'category' => $categoryGD,
            ],
            [
                'title' => 'Nền tảng quản lý câu lạc bộ và hoạt động ngoại khóa trong trường đại học',
                'summary' => 'Xây dựng hệ thống giúp quản lý câu lạc bộ, sự kiện, điểm rèn luyện và tham gia hoạt động ngoại khóa của sinh viên.',
                'description' => 'Giải quyết bài toán phân tán thông tin về hoạt động ngoại khóa, giúp nhà trường và sinh viên theo dõi được mức độ tham gia, minh bạch điểm rèn luyện.',
                'content' => '## Mục tiêu

Hỗ trợ phòng công tác sinh viên và các câu lạc bộ quản lý toàn bộ vòng đời sự kiện: tạo sự kiện, đăng ký, điểm danh, đánh giá và tổng hợp báo cáo.',
                'faculty' => $facultyKT,
                'category' => $categoryKT,
            ],
            [
                'title' => 'Hệ thống gợi ý lộ trình học tập cá nhân hóa cho sinh viên',
                'summary' => 'Ứng dụng phân tích kết quả học tập, sở thích và mục tiêu nghề nghiệp để gợi ý lộ trình học phù hợp.',
                'description' => 'Sử dụng dữ liệu kết quả học tập, phản hồi của sinh viên và yêu cầu thị trường lao động để đề xuất học phần, kỹ năng và hoạt động nên tham gia.',
                'content' => '## Tính năng chính

- Gợi ý học phần theo năng lực
- Gợi ý khóa học online, chứng chỉ bổ trợ
- Dashboard theo dõi tiến độ và mục tiêu cá nhân',
                'faculty' => $facultyCNTT,
                'category' => $categoryGD,
            ],
            [
                'title' => 'Ứng dụng quản lý chi tiêu và tài chính cá nhân cho sinh viên',
                'summary' => 'Phát triển ứng dụng mobile hỗ trợ sinh viên quản lý chi tiêu, lập ngân sách, tiết kiệm và theo dõi mục tiêu tài chính.',
                'description' => 'Tập trung vào nhóm sinh viên có thu nhập hạn chế, giúp hình thành thói quen tài chính lành mạnh, hạn chế nợ xấu và chi tiêu mất kiểm soát.',
                'content' => '## Chức năng chính

- Ghi chép chi tiêu nhanh
- Phân loại chi tiêu theo nhóm
- Báo cáo trực quan và nhắc nhở vượt ngân sách',
                'faculty' => $facultyKT,
                'category' => $categoryKT,
            ],
            [
                'title' => 'Hệ thống chia sẻ tài liệu học tập giữa sinh viên các khoa',
                'summary' => 'Xây dựng kho tài liệu số nơi sinh viên có thể chia sẻ slide, đề cương, đề thi và ghi chú học tập.',
                'description' => 'Giải quyết vấn đề tài liệu bị phân tán trên nhiều nhóm mạng xã hội, khó tìm kiếm và khó kiểm soát chất lượng.',
                'content' => '## Ý tưởng

Tạo một nền tảng có phân quyền, gợi ý tài liệu liên quan, hệ thống đánh giá và báo cáo nội dung vi phạm.',
                'faculty' => $facultyCNTT,
                'category' => $categoryCNTT,
            ],
        ];

        foreach ($ideas as $ideaData) {
            $slug = Str::slug($ideaData['title']);
            $idea = Idea::firstOrCreate(
                ['slug' => $slug],
                [
                    'owner_id' => $owner->id,
                    'title' => $ideaData['title'],
                    'summary' => $ideaData['summary'],
                    'description' => $ideaData['description'],
                    'content' => $ideaData['content'],
                    'status' => 'approved_final',
                    'visibility' => 'public',
                    'faculty_id' => $ideaData['faculty']->id,
                    'category_id' => $ideaData['category']->id,
                ]
            );

            $this->command->info('✓ Đã tạo: ' . $idea->title . ' (Khoa: ' . $ideaData['faculty']->name . ')');
        }

        $this->command->info(PHP_EOL . 'Đã tạo thành công ' . count($ideas) . ' ý tưởng với các khoa và danh mục khác nhau!');
    }
}
