<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Faculty;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DiverseIdeasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy user đầu tiên làm owner
        $owner = User::first();

        if (!$owner) {
            $this->command->warn('Không có user nào trong database. Vui lòng chạy AdminUserSeeder trước.');
            return;
        }

        // Lấy các khoa và danh mục
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

        // 50 ý tưởng đa dạng
        $ideas = [
            // Công nghệ thông tin - AI & Machine Learning
            [
                'title' => 'Hệ thống nhận diện và phân loại rác thải thông minh bằng AI',
                'summary' => 'Phát triển hệ thống sử dụng computer vision và machine learning để nhận diện, phân loại rác thải tự động, hỗ trợ tái chế hiệu quả.',
                'description' => 'Hệ thống tích hợp camera và AI để nhận diện các loại rác thải, tự động phân loại và hướng dẫn người dùng bỏ rác đúng nơi quy định, góp phần bảo vệ môi trường.',
                'image_url' => 'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống nhận diện và phân loại rác thải thông minh là giải pháp công nghệ sử dụng trí tuệ nhân tạo để tự động phân loại rác thải, giúp tăng tỷ lệ tái chế và giảm thiểu ô nhiễm môi trường.

## Vấn đề cần giải quyết

Hiện tại, việc phân loại rác thải tại nguồn còn gặp nhiều khó khăn do thiếu kiến thức và thói quen của người dân. Hệ thống này sẽ hỗ trợ người dân phân loại rác một cách chính xác và thuận tiện.

## Các tính năng chính

### 1. Nhận diện rác thải bằng AI
- Sử dụng camera để chụp ảnh rác thải
- AI phân tích và nhận diện loại rác (nhựa, giấy, kim loại, thủy tinh, hữu cơ...)
- Độ chính xác cao nhờ deep learning models

### 2. Hướng dẫn phân loại
- Hiển thị thông tin chi tiết về loại rác
- Hướng dẫn cách xử lý và bỏ rác đúng nơi quy định
- Tính điểm và phần thưởng cho hành vi đúng

### 3. Tích hợp IoT
- Thùng rác thông minh với cảm biến
- Tự động mở nắp khi phát hiện đúng loại rác
- Cảnh báo khi thùng rác đầy

### 4. Ứng dụng di động
- Quét mã QR để mở thùng rác
- Theo dõi lịch sử phân loại rác
- Thống kê và báo cáo cá nhân

## Công nghệ sử dụng

- **AI/ML**: TensorFlow, PyTorch cho image classification
- **Computer Vision**: OpenCV, YOLO cho object detection
- **Mobile**: React Native hoặc Flutter
- **Backend**: Laravel API với real-time processing
- **IoT**: Raspberry Pi, camera module, sensors
- **Database**: PostgreSQL lưu trữ dữ liệu hình ảnh và phân loại',
                'faculty' => $facultyCNTT,
                'category' => $categoryCNTT,
            ],
            [
                'title' => 'Nền tảng học lập trình tương tác với AI tutor cá nhân hóa',
                'summary' => 'Xây dựng nền tảng học lập trình online với AI tutor thông minh, cá nhân hóa lộ trình học và hỗ trợ giải đáp thắc mắc 24/7.',
                'description' => 'Nền tảng tích hợp AI để tạo trải nghiệm học lập trình cá nhân hóa, tự động điều chỉnh độ khó bài tập, giải thích code và đưa ra gợi ý cải thiện.',
                'image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Nền tảng học lập trình với AI tutor là giải pháp giáo dục công nghệ giúp người học lập trình có trải nghiệm học tập cá nhân hóa và hiệu quả hơn.

## Các tính năng chính

### 1. AI Tutor thông minh
- Chatbot AI giải đáp thắc mắc về lập trình
- Phân tích code và đưa ra gợi ý cải thiện
- Giải thích khái niệm phức tạp một cách dễ hiểu

### 2. Lộ trình học cá nhân hóa
- Đánh giá trình độ ban đầu
- Tự động điều chỉnh độ khó bài tập
- Gợi ý bài học tiếp theo phù hợp

### 3. Thực hành coding
- Code editor tích hợp với syntax highlighting
- Chạy code trực tiếp trên trình duyệt
- Tự động chấm điểm và feedback

### 4. Gamification
- Hệ thống điểm, badge, achievement
- Leaderboard và cạnh tranh lành mạnh
- Thử thách hàng ngày và tuần

## Công nghệ sử dụng

- **Frontend**: React.js với Monaco Editor
- **Backend**: Laravel API
- **AI/ML**: GPT-4 hoặc Claude cho AI tutor
- **Code Execution**: Docker containers cho sandbox
- **Database**: PostgreSQL lưu trữ bài học và tiến độ',
                'faculty' => $facultyCNTT,
                'category' => $categoryCNTT,
            ],
            [
                'title' => 'Hệ thống quản lý thư viện số với AI tìm kiếm và đề xuất',
                'summary' => 'Phát triển hệ thống thư viện số thông minh sử dụng AI để tìm kiếm, phân loại và đề xuất tài liệu phù hợp với nhu cầu người dùng.',
                'description' => 'Hệ thống tích hợp AI để tối ưu hóa trải nghiệm tìm kiếm tài liệu, tự động phân loại và đề xuất các tài liệu liên quan dựa trên lịch sử đọc và sở thích.',
                'image_url' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống quản lý thư viện số với AI là giải pháp hiện đại giúp người dùng dễ dàng tìm kiếm, truy cập và khám phá tài liệu trong thư viện số.

## Các tính năng chính

### 1. Tìm kiếm thông minh
- Semantic search dựa trên ý nghĩa, không chỉ từ khóa
- Tìm kiếm bằng hình ảnh (tìm sách có bìa tương tự)
- Tìm kiếm bằng giọng nói

### 2. AI đề xuất
- Gợi ý tài liệu dựa trên lịch sử đọc
- Phân tích nội dung và đề xuất tài liệu liên quan
- Tạo danh sách đọc cá nhân hóa

### 3. Quản lý tài liệu
- Upload và phân loại tài liệu tự động
- OCR để tìm kiếm trong PDF, hình ảnh
- Metadata extraction tự động

### 4. Trải nghiệm người dùng
- Giao diện hiện đại, dễ sử dụng
- Đọc sách online với highlight và note
- Đồng bộ đọc trên nhiều thiết bị

## Công nghệ sử dụng

- **Frontend**: React.js với PDF.js
- **Backend**: Laravel API
- **AI/ML**: NLP cho semantic search, recommendation system
- **OCR**: Tesseract hoặc Google Cloud Vision
- **Search**: Elasticsearch cho full-text search
- **Database**: PostgreSQL lưu trữ metadata',
                'faculty' => $facultyCNTT,
                'category' => $categoryCNTT,
            ],
            [
                'title' => 'Ứng dụng quản lý dự án xây dựng với AR và BIM',
                'summary' => 'Phát triển ứng dụng quản lý dự án xây dựng tích hợp công nghệ AR (Augmented Reality) và BIM (Building Information Modeling) để trực quan hóa và quản lý tiến độ.',
                'description' => 'Ứng dụng sử dụng AR để hiển thị mô hình 3D của công trình tại hiện trường, kết hợp với BIM để quản lý thông tin chi tiết về vật liệu, tiến độ và chi phí.',
                'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Ứng dụng quản lý dự án xây dựng với AR và BIM là giải pháp công nghệ hiện đại giúp các nhà quản lý dự án xây dựng trực quan hóa và quản lý công trình hiệu quả hơn.

## Các tính năng chính

### 1. AR Visualization
- Hiển thị mô hình 3D công trình tại hiện trường
- So sánh thiết kế với thực tế
- Phát hiện sai lệch và cảnh báo

### 2. BIM Integration
- Quản lý thông tin chi tiết về vật liệu
- Theo dõi tiến độ thi công
- Quản lý chi phí và ngân sách

### 3. Quản lý tiến độ
- Cập nhật tiến độ real-time
- Gán nhiệm vụ cho nhân viên
- Báo cáo và thống kê tự động

### 4. Mobile App
- Ứng dụng di động cho kỹ sư hiện trường
- Chụp ảnh và ghi chú trực tiếp
- Đồng bộ dữ liệu real-time

## Công nghệ sử dụng

- **AR**: ARCore (Android) / ARKit (iOS)
- **3D Rendering**: Three.js, Unity
- **BIM**: IFC file format support
- **Mobile**: React Native hoặc Flutter
- **Backend**: Laravel API
- **Database**: PostgreSQL với PostGIS cho dữ liệu địa lý',
                'faculty' => $facultyCNTT,
                'category' => $categoryCNTT,
            ],
            [
                'title' => 'Hệ thống phát hiện gian lận trong giao dịch tài chính bằng AI',
                'summary' => 'Xây dựng hệ thống sử dụng machine learning để phát hiện các giao dịch gian lận, bất thường trong hệ thống ngân hàng và tài chính.',
                'description' => 'Hệ thống phân tích hàng triệu giao dịch mỗi ngày, sử dụng AI để phát hiện các mẫu hành vi bất thường và cảnh báo gian lận trong thời gian thực.',
                'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống phát hiện gian lận tài chính bằng AI là giải pháp bảo mật quan trọng giúp các tổ chức tài chính phát hiện và ngăn chặn gian lận một cách nhanh chóng và chính xác.

## Các tính năng chính

### 1. Phát hiện bất thường
- Phân tích hành vi giao dịch
- Phát hiện mẫu gian lận phức tạp
- Cảnh báo real-time

### 2. Machine Learning Models
- Anomaly detection algorithms
- Pattern recognition
- Risk scoring tự động

### 3. Dashboard và báo cáo
- Trực quan hóa dữ liệu gian lận
- Báo cáo chi tiết và thống kê
- Phân tích xu hướng

### 4. Tích hợp hệ thống
- API để tích hợp với hệ thống ngân hàng
- Real-time processing
- Batch processing cho dữ liệu lịch sử

## Công nghệ sử dụng

- **AI/ML**: TensorFlow, scikit-learn cho anomaly detection
- **Big Data**: Apache Spark cho xử lý dữ liệu lớn
- **Backend**: Python (FastAPI) hoặc Laravel
- **Database**: PostgreSQL, Redis cho caching
- **Real-time**: Apache Kafka cho stream processing',
                'faculty' => $facultyCNTT,
                'category' => $categoryCNTT,
            ],
            // Điện tử - IoT & Tự động hóa
            [
                'title' => 'Hệ thống điều khiển nhà thông minh tích hợp AI và IoT',
                'summary' => 'Phát triển hệ thống nhà thông minh sử dụng IoT và AI để tự động điều khiển đèn, điều hòa, an ninh và các thiết bị gia đình.',
                'description' => 'Hệ thống tích hợp nhiều cảm biến IoT, sử dụng AI để học thói quen người dùng và tự động điều chỉnh môi trường sống cho phù hợp, tiết kiệm năng lượng.',
                'image_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống nhà thông minh với AI và IoT là giải pháp tự động hóa toàn diện cho ngôi nhà hiện đại, giúp nâng cao chất lượng cuộc sống và tiết kiệm năng lượng.

## Các tính năng chính

### 1. Điều khiển tự động
- Tự động bật/tắt đèn theo lịch trình
- Điều chỉnh nhiệt độ phòng thông minh
- Quản lý hệ thống tưới cây tự động

### 2. AI Learning
- Học thói quen sinh hoạt của gia đình
- Tự động điều chỉnh theo sở thích
- Dự đoán nhu cầu sử dụng

### 3. An ninh thông minh
- Camera giám sát với AI phát hiện người
- Cảnh báo khi có người lạ
- Khóa cửa tự động

### 4. Ứng dụng di động
- Điều khiển từ xa qua smartphone
- Giọng nói điều khiển (voice control)
- Cảnh báo và thông báo real-time

## Công nghệ sử dụng

- **IoT**: ESP32, Raspberry Pi, Zigbee, Z-Wave
- **AI/ML**: TensorFlow Lite cho edge computing
- **Voice Control**: Google Assistant, Amazon Alexa
- **Mobile**: React Native app
- **Backend**: Laravel API với MQTT
- **Database**: PostgreSQL, InfluxDB cho time-series data',
                'faculty' => $facultyDDT,
                'category' => $categoryDDT,
            ],
            [
                'title' => 'Hệ thống giám sát chất lượng không khí đô thị với IoT',
                'summary' => 'Xây dựng mạng lưới cảm biến IoT để giám sát chất lượng không khí tại các điểm khác nhau trong thành phố, cung cấp dữ liệu real-time.',
                'description' => 'Hệ thống sử dụng mạng lưới cảm biến IoT phân tán để đo các chỉ số chất lượng không khí như PM2.5, PM10, CO2, NO2, hiển thị trên bản đồ real-time.',
                'image_url' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống giám sát chất lượng không khí đô thị là giải pháp IoT giúp người dân và chính quyền theo dõi chất lượng không khí tại các khu vực khác nhau trong thành phố.

## Các tính năng chính

### 1. Mạng lưới cảm biến
- Cảm biến đo PM2.5, PM10, CO2, NO2, O3
- Kết nối LoRaWAN hoặc WiFi
- Pin năng lượng mặt trời

### 2. Bản đồ real-time
- Hiển thị chất lượng không khí trên bản đồ
- Màu sắc cảnh báo theo mức độ ô nhiễm
- Dự báo chất lượng không khí

### 3. Ứng dụng di động
- Cảnh báo khi chất lượng không khí kém
- Gợi ý tuyến đường ít ô nhiễm
- Lịch sử và thống kê

### 4. Dashboard quản lý
- Báo cáo chi tiết cho chính quyền
- Phân tích xu hướng ô nhiễm
- Cảnh báo sớm

## Công nghệ sử dụng

- **IoT**: ESP32, LoRaWAN modules
- **Sensors**: Air quality sensors (MQ series)
- **Communication**: LoRaWAN, WiFi, 4G
- **Backend**: Laravel API với MQTT broker
- **Database**: InfluxDB cho time-series data
- **Frontend**: React.js với Leaflet maps
- **Mobile**: React Native app',
                'faculty' => $facultyDDT,
                'category' => $categoryDDT,
            ],
            [
                'title' => 'Robot phục vụ nhà hàng tự động với AI navigation',
                'summary' => 'Thiết kế và chế tạo robot phục vụ nhà hàng có khả năng tự động di chuyển, mang đồ ăn đến bàn và thu dọn bàn sau khi khách ăn xong.',
                'description' => 'Robot tích hợp AI để nhận diện bàn, tránh chướng ngại vật, giao tiếp với khách hàng và tự động quay về trạm sạc khi hết pin.',
                'image_url' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Robot phục vụ nhà hàng là giải pháp tự động hóa giúp các nhà hàng nâng cao hiệu quả phục vụ và giảm chi phí nhân lực.

## Các tính năng chính

### 1. Navigation thông minh
- SLAM (Simultaneous Localization and Mapping)
- Tránh chướng ngại vật tự động
- Lập kế hoạch đường đi tối ưu

### 2. Nhận diện và giao tiếp
- Nhận diện bàn và vị trí khách
- Giao tiếp bằng giọng nói
- Hiển thị thông tin trên màn hình

### 3. Vận chuyển đồ ăn
- Khay đựng đồ ăn an toàn
- Giữ nhiệt độ đồ ăn
- Cân bằng khi di chuyển

### 4. Tự động hóa
- Tự động quay về trạm sạc
- Thu dọn bàn sau khi khách ăn xong
- Tích hợp với hệ thống đặt món

## Công nghệ sử dụng

- **Hardware**: Raspberry Pi, Arduino, motors, sensors
- **AI/ML**: TensorFlow cho object detection
- **Navigation**: ROS (Robot Operating System)
- **SLAM**: ORB-SLAM2 hoặc RTAB-Map
- **Voice**: Google Speech API
- **Backend**: Laravel API để quản lý đơn hàng',
                'faculty' => $facultyDDT,
                'category' => $categoryDDT,
            ],
            [
                'title' => 'Hệ thống tưới tiêu thông minh cho nông nghiệp sử dụng IoT',
                'summary' => 'Phát triển hệ thống tưới tiêu tự động sử dụng cảm biến IoT để đo độ ẩm đất, dự báo thời tiết và tự động tưới nước khi cần thiết.',
                'description' => 'Hệ thống tích hợp cảm biến độ ẩm đất, cảm biến thời tiết và AI để tính toán lượng nước tưới phù hợp, tiết kiệm nước và tăng năng suất cây trồng.',
                'image_url' => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống tưới tiêu thông minh là giải pháp IoT giúp nông dân quản lý tưới tiêu hiệu quả, tiết kiệm nước và tăng năng suất cây trồng.

## Các tính năng chính

### 1. Cảm biến IoT
- Cảm biến độ ẩm đất
- Cảm biến nhiệt độ và độ ẩm không khí
- Cảm biến ánh sáng và mưa

### 2. Tự động hóa tưới tiêu
- Tự động bật/tắt hệ thống tưới
- Điều chỉnh lượng nước theo nhu cầu
- Lập lịch tưới thông minh

### 3. AI và dự báo
- Dự báo thời tiết tích hợp
- Tính toán lượng nước tối ưu
- Cảnh báo khi cần tưới

### 4. Ứng dụng di động
- Điều khiển từ xa
- Giám sát real-time
- Báo cáo và thống kê

## Công nghệ sử dụng

- **IoT**: ESP32, LoRaWAN
- **Sensors**: Soil moisture, temperature, humidity
- **Actuators**: Solenoid valves, pumps
- **AI/ML**: Dự đoán nhu cầu nước
- **Mobile**: React Native app
- **Backend**: Laravel API với MQTT
- **Database**: InfluxDB cho time-series data',
                'faculty' => $facultyDDT,
                'category' => $categoryDDT,
            ],
            [
                'title' => 'Hệ thống đèn đường thông minh tiết kiệm năng lượng',
                'summary' => 'Thiết kế hệ thống đèn đường LED thông minh sử dụng cảm biến chuyển động và AI để tự động điều chỉnh độ sáng, tiết kiệm năng lượng.',
                'description' => 'Hệ thống đèn đường tích hợp cảm biến PIR để phát hiện người và xe, tự động tăng độ sáng khi có người và giảm độ sáng khi không có người, tiết kiệm điện năng.',
                'image_url' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống đèn đường thông minh là giải pháp tiết kiệm năng lượng cho thành phố, giúp giảm chi phí điện năng và bảo vệ môi trường.

## Các tính năng chính

### 1. Điều khiển thông minh
- Tự động điều chỉnh độ sáng theo lịch trình
- Phát hiện chuyển động và tăng độ sáng
- Giảm độ sáng khi không có người

### 2. Cảm biến và IoT
- Cảm biến PIR phát hiện chuyển động
- Cảm biến ánh sáng tự nhiên
- Kết nối mạng để quản lý tập trung

### 3. Quản lý từ xa
- Dashboard quản lý tất cả đèn
- Báo cáo tiêu thụ năng lượng
- Cảnh báo khi đèn hỏng

### 4. Tiết kiệm năng lượng
- LED tiết kiệm năng lượng
- Tự động tắt khi không cần thiết
- Giảm 50-70% chi phí điện

## Công nghệ sử dụng

- **Hardware**: LED drivers, PIR sensors, ESP32
- **Communication**: LoRaWAN, WiFi
- **Backend**: Laravel API với MQTT
- **Database**: InfluxDB cho time-series data
- **Dashboard**: React.js để quản lý',
                'faculty' => $facultyDDT,
                'category' => $categoryDDT,
            ],
            // Cơ khí - Chế tạo
            [
                'title' => 'Máy cắt laser CNC đa năng cho xưởng sản xuất nhỏ',
                'summary' => 'Thiết kế và chế tạo máy cắt laser CNC giá rẻ, dễ sử dụng cho các xưởng sản xuất nhỏ, hỗ trợ cắt nhiều loại vật liệu.',
                'description' => 'Máy cắt laser CNC tích hợp phần mềm điều khiển dễ sử dụng, có thể cắt gỗ, nhựa, da, vải với độ chính xác cao, phù hợp cho các xưởng sản xuất nhỏ và vừa.',
                'image_url' => 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Máy cắt laser CNC đa năng là giải pháp công nghệ giúp các xưởng sản xuất nhỏ có thể tự động hóa quy trình cắt vật liệu với chi phí hợp lý.

## Các tính năng chính

### 1. Cắt đa vật liệu
- Cắt gỗ, nhựa, da, vải
- Độ chính xác cao (±0.1mm)
- Tốc độ cắt nhanh

### 2. Phần mềm điều khiển
- Giao diện dễ sử dụng
- Hỗ trợ file DXF, SVG
- Xem trước đường cắt

### 3. An toàn
- Hệ thống khóa an toàn
- Cảnh báo khi mở cửa
- Tự động dừng khi có sự cố

### 4. Giá cả hợp lý
- Chi phí thấp hơn máy công nghiệp
- Dễ bảo trì và sửa chữa
- Phù hợp cho xưởng nhỏ

## Công nghệ sử dụng

- **Mechanical**: Linear rails, stepper motors, laser module
- **Electronics**: Arduino hoặc Raspberry Pi
- **Software**: GRBL firmware, LaserGRBL
- **Laser**: CO2 hoặc fiber laser tùy ứng dụng',
                'faculty' => $facultyCKD,
                'category' => $categoryCKD,
            ],
            [
                'title' => 'Máy in 3D giá rẻ cho giáo dục và khởi nghiệp',
                'summary' => 'Thiết kế máy in 3D FDM giá rẻ, dễ lắp ráp và sử dụng, phù hợp cho môi trường giáo dục và các dự án khởi nghiệp.',
                'description' => 'Máy in 3D với thiết kế mở, dễ lắp ráp và tùy biến, hỗ trợ nhiều loại vật liệu nhựa, có độ chính xác tốt và giá cả phù hợp với sinh viên và người khởi nghiệp.',
                'image_url' => 'https://images.unsplash.com/photo-1631540575402-4c8e0b8c4c8c?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Máy in 3D giá rẻ là công cụ quan trọng giúp sinh viên và người khởi nghiệp có thể tạo ra các mẫu sản phẩm nhanh chóng với chi phí thấp.

## Các tính năng chính

### 1. Thiết kế mở
- Dễ lắp ráp và tùy biến
- Tài liệu hướng dẫn chi tiết
- Cộng đồng hỗ trợ

### 2. Độ chính xác
- Độ phân giải in cao
- Kích thước in lớn
- Hỗ trợ nhiều vật liệu

### 3. Dễ sử dụng
- Phần mềm slicing đơn giản
- Hỗ trợ file STL, OBJ
- In từ USB hoặc WiFi

### 4. Giá cả hợp lý
- Chi phí thấp
- Vật liệu in rẻ
- Dễ bảo trì

## Công nghệ sử dụng

- **Mechanical**: Linear rails, stepper motors, extruder
- **Electronics**: Arduino với Marlin firmware
- **Software**: Cura, PrusaSlicer
- **Materials**: PLA, ABS, PETG, TPU',
                'faculty' => $facultyCKD,
                'category' => $categoryCKD,
            ],
            [
                'title' => 'Hệ thống băng tải thông minh cho kho bãi tự động',
                'summary' => 'Thiết kế hệ thống băng tải tự động với khả năng phân loại, đếm và sắp xếp hàng hóa trong kho bãi.',
                'description' => 'Hệ thống băng tải tích hợp cảm biến, camera và AI để tự động phân loại hàng hóa theo kích thước, trọng lượng và loại, tối ưu hóa quy trình kho bãi.',
                'image_url' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống băng tải thông minh là giải pháp tự động hóa kho bãi giúp tăng hiệu quả xử lý hàng hóa và giảm chi phí lao động.

## Các tính năng chính

### 1. Phân loại tự động
- Camera và AI nhận diện hàng hóa
- Tự động phân loại theo loại và kích thước
- Đếm và kiểm kê tự động

### 2. Vận chuyển thông minh
- Điều khiển tốc độ băng tải
- Định tuyến hàng hóa đến đúng vị trí
- Tránh tắc nghẽn

### 3. Tích hợp hệ thống
- Kết nối với hệ thống quản lý kho
- Cập nhật tồn kho real-time
- Báo cáo và thống kê

### 4. An toàn
- Cảm biến an toàn
- Tự động dừng khi có sự cố
- Bảo vệ người lao động

## Công nghệ sử dụng

- **Mechanical**: Conveyor belts, motors, actuators
- **AI/ML**: Computer vision cho nhận diện hàng hóa
- **Sensors**: Weight sensors, proximity sensors
- **Control**: PLC hoặc Raspberry Pi
- **Software**: SCADA system để quản lý',
                'faculty' => $facultyCKD,
                'category' => $categoryCKD,
            ],
            [
                'title' => 'Máy ép rác thải nhựa thành viên nén tự động',
                'summary' => 'Thiết kế máy ép rác thải nhựa thành viên nén để dễ dàng vận chuyển và tái chế, giảm thể tích rác thải.',
                'description' => 'Máy sử dụng hệ thống thủy lực để ép rác thải nhựa thành viên nén có kích thước đồng nhất, giúp giảm thể tích rác thải và thuận tiện cho việc vận chuyển đến nhà máy tái chế.',
                'image_url' => 'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Máy ép rác thải nhựa là giải pháp xử lý rác thải hiệu quả, giúp giảm thể tích rác thải và thuận tiện cho việc tái chế.

## Các tính năng chính

### 1. Ép tự động
- Hệ thống thủy lực mạnh mẽ
- Ép rác thành viên nén đồng nhất
- Tự động đóng gói sau khi ép

### 2. An toàn
- Hệ thống khóa an toàn
- Tự động dừng khi có sự cố
- Bảo vệ người vận hành

### 3. Hiệu quả
- Giảm 80-90% thể tích rác
- Tiết kiệm chi phí vận chuyển
- Dễ dàng tái chế

### 4. Dễ sử dụng
- Điều khiển đơn giản
- Bảo trì dễ dàng
- Chi phí vận hành thấp

## Công nghệ sử dụng

- **Mechanical**: Hydraulic system, compression chamber
- **Electronics**: PLC hoặc Arduino
- **Safety**: Safety interlocks, emergency stop
- **Materials**: High-strength steel cho khung máy',
                'faculty' => $facultyCKD,
                'category' => $categoryCKD,
            ],
            [
                'title' => 'Hệ thống phun sương làm mát không gian ngoài trời',
                'summary' => 'Thiết kế hệ thống phun sương tự động để làm mát không gian ngoài trời như sân vườn, quán cà phê, giảm nhiệt độ 3-5 độ C.',
                'description' => 'Hệ thống phun sương sử dụng cảm biến nhiệt độ và độ ẩm để tự động bật/tắt, tạo môi trường mát mẻ và dễ chịu cho không gian ngoài trời.',
                'image_url' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống phun sương làm mát là giải pháp hiệu quả để tạo môi trường mát mẻ cho không gian ngoài trời mà không tốn nhiều năng lượng.

## Các tính năng chính

### 1. Tự động hóa
- Cảm biến nhiệt độ và độ ẩm
- Tự động bật/tắt theo điều kiện
- Lập lịch hoạt động

### 2. Làm mát hiệu quả
- Giảm nhiệt độ 3-5 độ C
- Tăng độ ẩm không khí
- Tạo môi trường dễ chịu

### 3. Tiết kiệm nước
- Sử dụng nước hiệu quả
- Tự động tắt khi đủ độ ẩm
- Giảm lãng phí nước

### 4. Dễ lắp đặt
- Thiết kế đơn giản
- Dễ bảo trì
- Chi phí thấp

## Công nghệ sử dụng

- **Mechanical**: High-pressure pump, nozzles, pipes
- **Electronics**: ESP32 với sensors
- **Sensors**: Temperature, humidity sensors
- **Control**: Automatic timer, manual override',
                'faculty' => $facultyCKD,
                'category' => $categoryCKD,
            ],
            // Thú y - Chăn nuôi
            [
                'title' => 'Hệ thống theo dõi sức khỏe gia súc bằng wearable sensors',
                'summary' => 'Phát triển hệ thống theo dõi sức khỏe gia súc sử dụng cảm biến đeo trên cổ để đo nhịp tim, nhiệt độ và hoạt động.',
                'description' => 'Hệ thống sử dụng cảm biến IoT đeo trên cổ gia súc để theo dõi sức khỏe real-time, phát hiện sớm bệnh tật và cảnh báo cho người chăn nuôi.',
                'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống theo dõi sức khỏe gia súc bằng wearable sensors là giải pháp công nghệ giúp người chăn nuôi quản lý sức khỏe đàn vật nuôi hiệu quả hơn.

## Các tính năng chính

### 1. Wearable Sensors
- Cảm biến nhịp tim
- Cảm biến nhiệt độ cơ thể
- Cảm biến hoạt động (accelerometer)

### 2. Theo dõi real-time
- Gửi dữ liệu lên cloud
- Cảnh báo khi có bất thường
- Lịch sử sức khỏe

### 3. Phát hiện bệnh sớm
- AI phân tích dữ liệu
- Cảnh báo khi có dấu hiệu bệnh
- Gợi ý điều trị

### 4. Ứng dụng di động
- Xem thông tin sức khỏe từng con
- Cảnh báo và thông báo
- Báo cáo và thống kê

## Công nghệ sử dụng

- **IoT**: ESP32, LoRaWAN
- **Sensors**: Heart rate, temperature, accelerometer
- **AI/ML**: Anomaly detection cho phát hiện bệnh
- **Mobile**: React Native app
- **Backend**: Laravel API
- **Database**: PostgreSQL, InfluxDB',
                'faculty' => $facultyTY,
                'category' => $categoryTY,
            ],
            [
                'title' => 'Hệ thống cho ăn tự động cho gia cầm với AI',
                'summary' => 'Thiết kế hệ thống cho ăn tự động cho gia cầm sử dụng AI để tính toán lượng thức ăn phù hợp dựa trên tuổi, trọng lượng và giai đoạn phát triển.',
                'description' => 'Hệ thống tích hợp cân điện tử, máy cho ăn tự động và AI để tự động tính toán và phân phối lượng thức ăn phù hợp cho từng nhóm gia cầm, tối ưu hóa tăng trưởng.',
                'image_url' => 'https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống cho ăn tự động cho gia cầm là giải pháp tự động hóa giúp người chăn nuôi quản lý thức ăn hiệu quả và tối ưu hóa tăng trưởng.

## Các tính năng chính

### 1. Cho ăn tự động
- Tự động phân phối thức ăn
- Điều chỉnh lượng thức ăn theo nhu cầu
- Lập lịch cho ăn

### 2. AI tính toán
- Tính toán lượng thức ăn tối ưu
- Điều chỉnh theo tuổi và trọng lượng
- Tối ưu hóa chi phí thức ăn

### 3. Quản lý
- Theo dõi lượng thức ăn tiêu thụ
- Cảnh báo khi hết thức ăn
- Báo cáo và thống kê

### 4. Tích hợp
- Kết nối với hệ thống quản lý trang trại
- Đồng bộ dữ liệu
- Báo cáo tự động

## Công nghệ sử dụng

- **Mechanical**: Feed dispensers, weighing system
- **Electronics**: ESP32, load cells
- **AI/ML**: Machine learning cho tính toán tối ưu
- **Backend**: Laravel API
- **Mobile**: React Native app
- **Database**: PostgreSQL',
                'faculty' => $facultyTY,
                'category' => $categoryTY,
            ],
            [
                'title' => 'Hệ thống phát hiện bệnh trên cây trồng bằng AI và camera',
                'summary' => 'Phát triển hệ thống sử dụng camera và AI để phát hiện sớm bệnh trên cây trồng, giúp nông dân xử lý kịp thời.',
                'description' => 'Hệ thống sử dụng camera trên drone hoặc robot để chụp ảnh cây trồng, AI phân tích và phát hiện các dấu hiệu bệnh, đưa ra cảnh báo và gợi ý điều trị.',
                'image_url' => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống phát hiện bệnh trên cây trồng bằng AI là giải pháp công nghệ giúp nông dân phát hiện và xử lý bệnh trên cây trồng sớm, giảm thiểu thiệt hại.

## Các tính năng chính

### 1. Chụp ảnh tự động
- Camera trên drone hoặc robot
- Chụp ảnh định kỳ
- Ảnh độ phân giải cao

### 2. AI phân tích
- Nhận diện các loại bệnh
- Phân tích mức độ nghiêm trọng
- Gợi ý phương pháp điều trị

### 3. Bản đồ bệnh
- Hiển thị vị trí cây bị bệnh
- Phân tích xu hướng lây lan
- Dự đoán rủi ro

### 4. Ứng dụng di động
- Cảnh báo khi phát hiện bệnh
- Xem ảnh và phân tích
- Hướng dẫn điều trị

## Công nghệ sử dụng

- **Hardware**: Drone hoặc robot, camera
- **AI/ML**: TensorFlow cho image classification
- **Computer Vision**: OpenCV, YOLO
- **Backend**: Laravel API
- **Mobile**: React Native app
- **Database**: PostgreSQL lưu trữ ảnh và phân tích',
                'faculty' => $facultyTY,
                'category' => $categoryTY,
            ],
            [
                'title' => 'Hệ thống quản lý chuồng trại thông minh với IoT',
                'summary' => 'Xây dựng hệ thống quản lý chuồng trại tích hợp cảm biến IoT để giám sát môi trường, tự động điều chỉnh nhiệt độ, độ ẩm và thông gió.',
                'description' => 'Hệ thống sử dụng mạng lưới cảm biến IoT để giám sát môi trường chuồng trại, tự động điều chỉnh nhiệt độ, độ ẩm, thông gió và ánh sáng để tạo môi trường tối ưu cho vật nuôi.',
                'image_url' => 'https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống quản lý chuồng trại thông minh là giải pháp IoT giúp người chăn nuôi tạo môi trường tối ưu cho vật nuôi, tăng năng suất và giảm bệnh tật.

## Các tính năng chính

### 1. Giám sát môi trường
- Cảm biến nhiệt độ, độ ẩm
- Cảm biến CO2, NH3
- Cảm biến ánh sáng

### 2. Tự động điều chỉnh
- Tự động bật/tắt quạt thông gió
- Điều chỉnh nhiệt độ
- Điều khiển hệ thống sưởi/làm mát

### 3. Cảnh báo
- Cảnh báo khi môi trường bất thường
- Thông báo qua ứng dụng
- Lịch sử dữ liệu

### 4. Quản lý
- Dashboard quản lý
- Báo cáo và thống kê
- Phân tích xu hướng

## Công nghệ sử dụng

- **IoT**: ESP32, LoRaWAN
- **Sensors**: Temperature, humidity, gas sensors
- **Actuators**: Fans, heaters, lights
- **Backend**: Laravel API với MQTT
- **Database**: InfluxDB cho time-series data
- **Mobile**: React Native app',
                'faculty' => $facultyTY,
                'category' => $categoryTY,
            ],
            [
                'title' => 'Hệ thống theo dõi sinh sản và động dục của gia súc',
                'summary' => 'Phát triển hệ thống sử dụng cảm biến và AI để theo dõi chu kỳ sinh sản, phát hiện động dục và dự đoán thời điểm thụ tinh tối ưu.',
                'description' => 'Hệ thống tích hợp cảm biến hoạt động, nhiệt độ và AI để theo dõi chu kỳ sinh sản của gia súc, phát hiện động dục và cảnh báo thời điểm thụ tinh phù hợp.',
                'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống theo dõi sinh sản và động dục là giải pháp công nghệ giúp người chăn nuôi quản lý sinh sản hiệu quả, tăng tỷ lệ thụ thai và năng suất.

## Các tính năng chính

### 1. Theo dõi hoạt động
- Cảm biến hoạt động (accelerometer)
- Theo dõi hành vi bất thường
- Phát hiện động dục

### 2. AI phân tích
- Phân tích chu kỳ sinh sản
- Dự đoán thời điểm động dục
- Gợi ý thời điểm thụ tinh

### 3. Cảnh báo
- Thông báo khi phát hiện động dục
- Nhắc nhở lịch thụ tinh
- Theo dõi thai kỳ

### 4. Quản lý
- Lịch sử sinh sản
- Báo cáo và thống kê
- Phân tích hiệu quả

## Công nghệ sử dụng

- **IoT**: ESP32 với accelerometer
- **AI/ML**: Machine learning cho dự đoán
- **Backend**: Laravel API
- **Mobile**: React Native app
- **Database**: PostgreSQL',
                'faculty' => $facultyTY,
                'category' => $categoryTY,
            ],
            // Kinh tế - Quản lý
            [
                'title' => 'Nền tảng thương mại điện tử địa phương cho nông sản',
                'summary' => 'Xây dựng nền tảng thương mại điện tử kết nối nông dân địa phương với người tiêu dùng, hỗ trợ bán nông sản trực tiếp.',
                'description' => 'Nền tảng tích hợp thanh toán, vận chuyển và đánh giá để giúp nông dân bán nông sản trực tiếp cho người tiêu dùng, tăng thu nhập và giảm chi phí trung gian.',
                'image_url' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Nền tảng thương mại điện tử địa phương là giải pháp kết nối nông dân với người tiêu dùng, tạo chuỗi cung ứng ngắn và hiệu quả.

## Các tính năng chính

### 1. Quản lý sản phẩm
- Đăng bán nông sản
- Quản lý kho hàng
- Cập nhật giá và số lượng

### 2. Đặt hàng và thanh toán
- Đặt hàng online
- Thanh toán đa dạng
- Theo dõi đơn hàng

### 3. Vận chuyển
- Tích hợp đối tác vận chuyển
- Theo dõi vận chuyển
- Tính phí vận chuyển

### 4. Đánh giá và phản hồi
- Đánh giá sản phẩm
- Phản hồi khách hàng
- Xây dựng uy tín

## Công nghệ sử dụng

- **Frontend**: React.js hoặc Vue.js
- **Backend**: Laravel API
- **Payment**: Stripe, PayPal, VNPay
- **Mobile**: React Native app
- **Database**: PostgreSQL',
                'faculty' => $facultyKT,
                'category' => $categoryKT,
            ],
            [
                'title' => 'Ứng dụng quản lý tài chính cá nhân với AI tư vấn',
                'summary' => 'Phát triển ứng dụng quản lý tài chính cá nhân tích hợp AI để phân tích chi tiêu, đưa ra lời khuyên và gợi ý tiết kiệm.',
                'description' => 'Ứng dụng sử dụng AI để phân tích thói quen chi tiêu, phân loại giao dịch tự động, đưa ra ngân sách đề xuất và gợi ý cách tiết kiệm hiệu quả.',
                'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Ứng dụng quản lý tài chính cá nhân với AI là công cụ hỗ trợ người dùng quản lý tiền bạc hiệu quả và hình thành thói quen tài chính lành mạnh.

## Các tính năng chính

### 1. Quản lý chi tiêu
- Ghi chép chi tiêu tự động
- Phân loại giao dịch bằng AI
- Theo dõi ngân sách

### 2. AI tư vấn
- Phân tích thói quen chi tiêu
- Đưa ra lời khuyên tài chính
- Gợi ý cách tiết kiệm

### 3. Báo cáo và thống kê
- Biểu đồ chi tiêu
- Phân tích xu hướng
- Dự báo tài chính

### 4. Mục tiêu tài chính
- Đặt mục tiêu tiết kiệm
- Theo dõi tiến độ
- Cảnh báo khi vượt ngân sách

## Công nghệ sử dụng

- **Mobile**: React Native hoặc Flutter
- **Backend**: Laravel API
- **AI/ML**: Machine learning cho phân tích
- **Banking API**: Tích hợp ngân hàng (nếu có)
- **Database**: PostgreSQL',
                'faculty' => $facultyKT,
                'category' => $categoryKT,
            ],
            [
                'title' => 'Nền tảng chia sẻ xe đạp điện trong khuôn viên trường',
                'summary' => 'Xây dựng nền tảng chia sẻ xe đạp điện cho sinh viên trong khuôn viên trường, hỗ trợ di chuyển nhanh chóng và thân thiện môi trường.',
                'description' => 'Nền tảng tích hợp ứng dụng di động, hệ thống khóa thông minh và thanh toán để cho phép sinh viên thuê xe đạp điện theo giờ, di chuyển thuận tiện trong khuôn viên.',
                'image_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Nền tảng chia sẻ xe đạp điện là giải pháp giao thông xanh cho khuôn viên trường, giúp sinh viên di chuyển nhanh chóng và thân thiện môi trường.

## Các tính năng chính

### 1. Thuê xe
- Quét QR code để mở khóa
- Thuê theo giờ hoặc ngày
- Tìm xe gần nhất

### 2. Thanh toán
- Thanh toán qua ứng dụng
- Nhiều phương thức thanh toán
- Hóa đơn điện tử

### 3. Quản lý
- Theo dõi vị trí xe
- Quản lý trạng thái xe
- Bảo trì và sửa chữa

### 4. An toàn
- Khóa thông minh
- Bảo hiểm tai nạn
- Hỗ trợ 24/7

## Công nghệ sử dụng

- **Mobile**: React Native app
- **Backend**: Laravel API
- **IoT**: Smart locks, GPS tracking
- **Payment**: Stripe, VNPay
- **Database**: PostgreSQL
- **Maps**: Google Maps API',
                'faculty' => $facultyKT,
                'category' => $categoryKT,
            ],
            [
                'title' => 'Hệ thống quản lý nhà trọ thông minh cho sinh viên',
                'summary' => 'Phát triển hệ thống quản lý nhà trọ tích hợp thanh toán online, bảo trì và dịch vụ tiện ích cho chủ trọ và người thuê.',
                'description' => 'Hệ thống giúp chủ trọ quản lý phòng trọ, thu tiền, bảo trì hiệu quả, đồng thời giúp sinh viên tìm phòng, thanh toán và yêu cầu dịch vụ dễ dàng.',
                'image_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống quản lý nhà trọ thông minh là giải pháp số hóa giúp chủ trọ và người thuê quản lý nhà trọ hiệu quả và thuận tiện hơn.

## Các tính năng chính

### 1. Quản lý phòng
- Đăng tin cho thuê
- Quản lý hợp đồng
- Theo dõi tình trạng phòng

### 2. Thanh toán
- Thanh toán tiền trọ online
- Nhắc nhở thanh toán
- Hóa đơn điện tử

### 3. Bảo trì
- Yêu cầu sửa chữa
- Theo dõi tiến độ
- Đánh giá dịch vụ

### 4. Tiện ích
- Đăng ký internet, điện, nước
- Thanh toán tiện ích
- Thông báo và nhắc nhở

## Công nghệ sử dụng

- **Frontend**: React.js
- **Backend**: Laravel API
- **Mobile**: React Native app
- **Payment**: Stripe, VNPay
- **Database**: PostgreSQL',
                'faculty' => $facultyKT,
                'category' => $categoryKT,
            ],
            [
                'title' => 'Nền tảng đặt món và giao hàng cho nhà hàng địa phương',
                'summary' => 'Xây dựng nền tảng đặt món và giao hàng kết nối nhà hàng địa phương với khách hàng, hỗ trợ đặt món online và giao hàng tận nơi.',
                'description' => 'Nền tảng tích hợp đặt món, thanh toán và giao hàng để giúp nhà hàng địa phương mở rộng kênh bán hàng, khách hàng đặt món và nhận giao hàng thuận tiện.',
                'image_url' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Nền tảng đặt món và giao hàng là giải pháp giúp nhà hàng địa phương số hóa hoạt động kinh doanh và mở rộng phạm vi phục vụ.

## Các tính năng chính

### 1. Đặt món
- Xem menu online
- Đặt món và thanh toán
- Theo dõi đơn hàng

### 2. Giao hàng
- Tích hợp đối tác giao hàng
- Theo dõi vị trí shipper
- Đánh giá dịch vụ

### 3. Quản lý nhà hàng
- Quản lý menu
- Xử lý đơn hàng
- Thống kê doanh thu

### 4. Khuyến mãi
- Mã giảm giá
- Chương trình tích điểm
- Ưu đãi thành viên

## Công nghệ sử dụng

- **Frontend**: React.js
- **Backend**: Laravel API
- **Mobile**: React Native app
- **Payment**: Stripe, VNPay
- **Maps**: Google Maps API
- **Database**: PostgreSQL',
                'faculty' => $facultyKT,
                'category' => $categoryKT,
            ],
            // Giáo dục - Đào tạo
            [
                'title' => 'Nền tảng học trực tuyến tương tác với VR/AR',
                'summary' => 'Phát triển nền tảng học trực tuyến tích hợp công nghệ VR/AR để tạo trải nghiệm học tập immersive và tương tác.',
                'description' => 'Nền tảng sử dụng VR/AR để tạo môi trường học tập 3D, giúp học sinh trải nghiệm các khái niệm phức tạp một cách trực quan và dễ hiểu hơn.',
                'image_url' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Nền tảng học trực tuyến với VR/AR là giải pháp giáo dục hiện đại tạo trải nghiệm học tập immersive và tương tác cao.

## Các tính năng chính

### 1. VR/AR Learning
- Môi trường học tập 3D
- Tương tác với đối tượng ảo
- Thực hành trong không gian ảo

### 2. Nội dung đa dạng
- Bài giảng video
- Bài tập tương tác
- Kiểm tra và đánh giá

### 3. Tương tác
- Học nhóm trong VR
- Thảo luận real-time
- Giáo viên hướng dẫn

### 4. Theo dõi tiến độ
- Dashboard cá nhân
- Báo cáo học tập
- Gợi ý cải thiện

## Công nghệ sử dụng

- **VR/AR**: Unity, Unreal Engine
- **Frontend**: React.js
- **Backend**: Laravel API
- **3D Models**: Blender, 3ds Max
- **Database**: PostgreSQL',
                'faculty' => $facultyNN,
                'category' => $categoryGD,
            ],
            [
                'title' => 'Ứng dụng học ngoại ngữ với AI conversation partner',
                'summary' => 'Phát triển ứng dụng học ngoại ngữ với AI conversation partner, giúp người học luyện nói và giao tiếp tự nhiên.',
                'description' => 'Ứng dụng sử dụng AI để tạo đối tác trò chuyện ảo, giúp người học luyện nói, phát âm và giao tiếp bằng ngoại ngữ một cách tự nhiên và hiệu quả.',
                'image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Ứng dụng học ngoại ngữ với AI conversation partner là công cụ hỗ trợ người học luyện nói và giao tiếp bằng ngoại ngữ hiệu quả.

## Các tính năng chính

### 1. AI Conversation
- Chatbot AI trò chuyện tự nhiên
- Nhiều tình huống giao tiếp
- Điều chỉnh độ khó theo trình độ

### 2. Speech Recognition
- Nhận diện giọng nói
- Đánh giá phát âm
- Sửa lỗi phát âm

### 3. Nội dung học tập
- Từ vựng và ngữ pháp
- Bài tập thực hành
- Kiểm tra và đánh giá

### 4. Theo dõi tiến độ
- Lịch sử học tập
- Điểm số và thành tích
- Gợi ý cải thiện

## Công nghệ sử dụng

- **AI/ML**: GPT-4, Whisper cho speech recognition
- **Mobile: React Native hoặc Flutter
- **Backend: Laravel API
- **Database**: PostgreSQL',
                'faculty' => $facultyNN,
                'category' => $categoryGD,
            ],
            [
                'title' => 'Hệ thống quản lý và chấm bài thi tự động bằng AI',
                'summary' => 'Xây dựng hệ thống chấm bài thi tự động sử dụng AI để chấm bài trắc nghiệm và tự luận, tiết kiệm thời gian cho giáo viên.',
                'description' => 'Hệ thống sử dụng OCR để đọc bài thi, AI để chấm bài tự luận và tự động tính điểm, tạo báo cáo kết quả cho giáo viên và học sinh.',
                'image_url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống chấm bài thi tự động là giải pháp công nghệ giúp giáo viên tiết kiệm thời gian chấm bài và đánh giá kết quả học tập chính xác hơn.

## Các tính năng chính

### 1. OCR và nhận diện
- Quét và đọc bài thi
- Nhận diện chữ viết tay
- Phân loại câu trả lời

### 2. Chấm bài tự động
- Chấm bài trắc nghiệm
- Chấm bài tự luận bằng AI
- Tính điểm tự động

### 3. Phân tích kết quả
- Thống kê điểm số
- Phân tích lỗi thường gặp
- Báo cáo chi tiết

### 4. Quản lý
- Quản lý đề thi
- Lưu trữ bài thi
- Xuất báo cáo

## Công nghệ sử dụng

- **OCR: Tesseract, Google Cloud Vision
- **AI/ML: NLP cho chấm bài tự luận
- **Backend: Laravel API
- **Frontend: React.js
- **Database**: PostgreSQL',
                'faculty' => $facultyNN,
                'category' => $categoryGD,
            ],
            [
                'title' => 'Nền tảng học nhóm trực tuyến với whiteboard tương tác',
                'summary' => 'Phát triển nền tảng học nhóm trực tuyến với whiteboard tương tác, video call và chia sẻ màn hình để học tập hiệu quả.',
                'description' => 'Nền tảng tích hợp whiteboard tương tác, video call, chat và chia sẻ tài liệu để tạo môi trường học nhóm trực tuyến hiệu quả và tương tác cao.',
                'image_url' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Nền tảng học nhóm trực tuyến là giải pháp giúp học sinh học nhóm hiệu quả từ xa với các công cụ tương tác đầy đủ.

## Các tính năng chính

### 1. Whiteboard tương tác
- Vẽ và viết cùng nhau
- Chia sẻ hình ảnh và tài liệu
- Lưu và xuất bảng trắng

### 2. Video call
- Video call nhóm
- Chia sẻ màn hình
- Ghi lại buổi học

### 3. Chat và tài liệu
- Chat real-time
- Chia sẻ file
- Quản lý tài liệu

### 4. Quản lý nhóm
- Tạo và quản lý nhóm
- Lịch học nhóm
- Theo dõi tiến độ

## Công nghệ sử dụng

- **Frontend: React.js với WebRTC
- **Backend: Laravel API với WebSocket
- **Video: WebRTC, Janus Gateway
- **Whiteboard: Fabric.js, Excalidraw
- **Database**: PostgreSQL',
                'faculty' => $facultyNN,
                'category' => $categoryGD,
            ],
            [
                'title' => 'Hệ thống đánh giá và phản hồi học tập với AI',
                'summary' => 'Xây dựng hệ thống sử dụng AI để đánh giá và đưa ra phản hồi chi tiết về bài làm của học sinh, giúp cải thiện học tập.',
                'description' => 'Hệ thống sử dụng AI để phân tích bài làm của học sinh, đưa ra nhận xét chi tiết, gợi ý cải thiện và tạo lộ trình học tập cá nhân hóa.',
                'image_url' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống đánh giá và phản hồi học tập với AI là giải pháp giáo dục giúp học sinh nhận được phản hồi chi tiết và cá nhân hóa để cải thiện học tập.

## Các tính năng chính

### 1. AI đánh giá
- Phân tích bài làm
- Đánh giá điểm mạnh và yếu
- Đưa ra nhận xét chi tiết

### 2. Phản hồi thông minh
- Gợi ý cải thiện cụ thể
- Tài liệu tham khảo
- Bài tập bổ trợ

### 3. Lộ trình học tập
- Tạo lộ trình cá nhân hóa
- Theo dõi tiến độ
- Điều chỉnh lộ trình

### 4. Báo cáo
- Báo cáo học tập
- Phân tích xu hướng
- So sánh với bạn bè

## Công nghệ sử dụng

- **AI/ML: GPT-4, Claude cho đánh giá
- **Backend: Laravel API
- **Frontend: React.js
- **Database**: PostgreSQL',
                'faculty' => $facultyNN,
                'category' => $categoryGD,
            ],
            // Thêm 25 ý tưởng đa dạng khác
            [
                'title' => 'Hệ thống quản lý bãi đỗ xe thông minh với AI và IoT',
                'summary' => 'Phát triển hệ thống quản lý bãi đỗ xe sử dụng camera AI để phát hiện chỗ trống, hướng dẫn người dùng và thanh toán tự động.',
                'description' => 'Hệ thống tích hợp camera AI, cảm biến IoT và ứng dụng di động để quản lý bãi đỗ xe, phát hiện chỗ trống, hướng dẫn đỗ xe và thanh toán tự động.',
                'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống quản lý bãi đỗ xe thông minh là giải pháp công nghệ giúp tối ưu hóa việc sử dụng không gian đỗ xe và cải thiện trải nghiệm người dùng.

## Các tính năng chính

### 1. Phát hiện chỗ trống
- Camera AI phát hiện chỗ đỗ trống
- Cảm biến IoT xác nhận
- Hiển thị trên bản đồ real-time

### 2. Hướng dẫn đỗ xe
- Ứng dụng hướng dẫn đến chỗ trống
- Đếm ngược thời gian giữ chỗ
- Cảnh báo khi hết thời gian

### 3. Thanh toán tự động
- Thanh toán qua ứng dụng
- Tự động tính phí
- Hóa đơn điện tử

### 4. Quản lý
- Dashboard quản lý bãi đỗ
- Thống kê sử dụng
- Báo cáo doanh thu

## Công nghệ sử dụng

- **AI/ML**: Computer vision cho phát hiện xe
- **IoT**: Sensors, cameras
- **Mobile**: React Native app
- **Backend**: Laravel API
- **Maps: Google Maps API
- **Database**: PostgreSQL',
                'faculty' => $facultyCNTT,
                'category' => $categoryCNTT,
            ],
            [
                'title' => 'Nền tảng kết nối freelancer với khách hàng địa phương',
                'summary' => 'Xây dựng nền tảng kết nối freelancer địa phương với khách hàng cần dịch vụ, hỗ trợ thanh toán và đánh giá.',
                'description' => 'Nền tảng giúp freelancer tìm dự án phù hợp và khách hàng tìm người làm việc, tích hợp thanh toán, chat và hệ thống đánh giá uy tín.',
                'image_url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Nền tảng kết nối freelancer là giải pháp giúp tạo cơ hội việc làm cho freelancer và tìm nhân tài cho khách hàng.

## Các tính năng chính

### 1. Tìm kiếm và matching
- Tìm freelancer theo kỹ năng
- Đề xuất dự án phù hợp
- AI matching thông minh

### 2. Quản lý dự án
- Tạo và quản lý dự án
- Theo dõi tiến độ
- Chat và trao đổi

### 3. Thanh toán
- Thanh toán an toàn
- Escrow system
- Tự động giải phóng thanh toán

### 4. Đánh giá
- Hệ thống đánh giá và review
- Xây dựng portfolio
- Xây dựng uy tín

## Công nghệ sử dụng

- **Frontend: React.js
- **Backend: Laravel API
- **Mobile: React Native app
- **Payment: Stripe, VNPay
- **Database**: PostgreSQL',
                'faculty' => $facultyKT,
                'category' => $categoryKT,
            ],
            [
                'title' => 'Hệ thống chấm công bằng nhận diện khuôn mặt và AI',
                'summary' => 'Phát triển hệ thống chấm công sử dụng nhận diện khuôn mặt và AI để xác thực nhân viên, chống gian lận và tự động tính lương.',
                'description' => 'Hệ thống sử dụng camera và AI để nhận diện khuôn mặt nhân viên, tự động chấm công, phát hiện gian lận và tích hợp với hệ thống tính lương.',
                'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống chấm công bằng nhận diện khuôn mặt là giải pháp hiện đại giúp doanh nghiệp quản lý chấm công chính xác và chống gian lận.

## Các tính năng chính

### 1. Nhận diện khuôn mặt
- Nhận diện khuôn mặt chính xác
- Chống gian lận (ảnh, video)
- Xác thực nhanh chóng

### 2. Chấm công tự động
- Tự động ghi nhận giờ vào/ra
- Tính giờ làm việc
- Phát hiện làm thêm giờ

### 3. Quản lý
- Dashboard quản lý
- Báo cáo chấm công
- Tích hợp tính lương

### 4. Ứng dụng di động
- Xem lịch sử chấm công
- Yêu cầu nghỉ phép
- Thông báo và nhắc nhở

## Công nghệ sử dụng

- **AI/ML: Face recognition (FaceNet, ArcFace)
- **Hardware: Camera, Raspberry Pi
- **Backend: Laravel API
- **Mobile: React Native app
- **Database**: PostgreSQL',
                'faculty' => $facultyCNTT,
                'category' => $categoryCNTT,
            ],
            [
                'title' => 'Hệ thống giám sát an ninh thông minh với AI phát hiện đột nhập',
                'summary' => 'Xây dựng hệ thống giám sát an ninh sử dụng camera AI để phát hiện đột nhập, hành vi bất thường và cảnh báo real-time.',
                'description' => 'Hệ thống tích hợp camera AI, cảm biến và AI để giám sát an ninh, phát hiện đột nhập, hành vi bất thường và gửi cảnh báo ngay lập tức.',
                'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống giám sát an ninh thông minh là giải pháp bảo vệ hiện đại giúp phát hiện và ngăn chặn các mối đe dọa an ninh.

## Các tính năng chính

### 1. Phát hiện đột nhập
- AI phát hiện người lạ
- Phát hiện hành vi bất thường
- Cảnh báo real-time

### 2. Giám sát 24/7
- Camera giám sát liên tục
- Ghi lại video
- Lưu trữ đám mây

### 3. Cảnh báo
- Thông báo qua ứng dụng
- Cảnh báo qua SMS/Email
- Tích hợp hệ thống báo động

### 4. Phân tích
- Phân tích hành vi
- Báo cáo an ninh
- Dự đoán rủi ro

## Công nghệ sử dụng

- **AI/ML: YOLO, OpenPose cho object detection
- **Hardware: IP cameras, NVR
- **Backend: Laravel API
- **Mobile: React Native app
- **Storage: Cloud storage cho video
- **Database**: PostgreSQL',
                'faculty' => $facultyDDT,
                'category' => $categoryDDT,
            ],
            [
                'title' => 'Ứng dụng quản lý sức khỏe cá nhân với AI tư vấn',
                'summary' => 'Phát triển ứng dụng quản lý sức khỏe cá nhân tích hợp AI để theo dõi sức khỏe, đưa ra lời khuyên và cảnh báo sớm.',
                'description' => 'Ứng dụng sử dụng AI để phân tích dữ liệu sức khỏe từ wearable devices, đưa ra lời khuyên cá nhân hóa và cảnh báo khi có vấn đề sức khỏe.',
                'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Ứng dụng quản lý sức khỏe cá nhân với AI là công cụ hỗ trợ người dùng theo dõi và cải thiện sức khỏe một cách thông minh.

## Các tính năng chính

### 1. Theo dõi sức khỏe
- Kết nối wearable devices
- Theo dõi nhịp tim, huyết áp
- Theo dõi hoạt động thể chất

### 2. AI tư vấn
- Phân tích dữ liệu sức khỏe
- Đưa ra lời khuyên cá nhân hóa
- Cảnh báo khi có vấn đề

### 3. Quản lý
- Lịch sử sức khỏe
- Báo cáo và thống kê
- Mục tiêu sức khỏe

### 4. Tích hợp
- Kết nối với bác sĩ
- Đặt lịch khám
- Quản lý thuốc

## Công nghệ sử dụng

- **Mobile: React Native hoặc Flutter
- **Backend: Laravel API
- **AI/ML: Machine learning cho phân tích
- **Wearable API: HealthKit, Google Fit
- **Database**: PostgreSQL',
                'faculty' => $facultyCNTT,
                'category' => $categoryCNTT,
            ],
            [
                'title' => 'Hệ thống quản lý rác thải thông minh với IoT',
                'summary' => 'Xây dựng hệ thống quản lý rác thải sử dụng cảm biến IoT để giám sát mức độ đầy thùng rác, tối ưu hóa lộ trình thu gom.',
                'description' => 'Hệ thống tích hợp cảm biến IoT trên thùng rác để đo mức độ đầy, tự động lập lộ trình thu gom tối ưu và cảnh báo khi thùng rác đầy.',
                'image_url' => 'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống quản lý rác thải thông minh là giải pháp IoT giúp tối ưu hóa quy trình thu gom rác và giảm chi phí vận hành.

## Các tính năng chính

### 1. Giám sát thùng rác
- Cảm biến đo mức độ đầy
- GPS tracking vị trí
- Gửi dữ liệu real-time

### 2. Tối ưu lộ trình
- AI tính toán lộ trình tối ưu
- Giảm thời gian và chi phí
- Tăng hiệu quả thu gom

### 3. Quản lý
- Dashboard quản lý
- Báo cáo và thống kê
- Cảnh báo khi thùng đầy

### 4. Ứng dụng
- Ứng dụng cho người dân
- Báo cáo thùng rác đầy
- Theo dõi lịch thu gom

## Công nghệ sử dụng

- **IoT: ESP32, LoRaWAN
- **Sensors: Ultrasonic sensors
- **AI/ML: Route optimization algorithms
- **Backend: Laravel API
- **Mobile: React Native app
- **Database**: PostgreSQL, InfluxDB',
                'faculty' => $facultyDDT,
                'category' => $categoryDDT,
            ],
            [
                'title' => 'Nền tảng chia sẻ không gian làm việc chung (Co-working)',
                'summary' => 'Phát triển nền tảng quản lý và đặt chỗ không gian làm việc chung, hỗ trợ thanh toán và quản lý thành viên.',
                'description' => 'Nền tảng giúp người dùng tìm và đặt chỗ không gian làm việc chung, quản lý thành viên, thanh toán và sử dụng tiện ích.',
                'image_url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Nền tảng chia sẻ không gian làm việc chung là giải pháp giúp tối ưu hóa việc sử dụng không gian làm việc và tạo cộng đồng làm việc.

## Các tính năng chính

### 1. Đặt chỗ
- Xem không gian có sẵn
- Đặt chỗ online
- Quản lý lịch đặt chỗ

### 2. Quản lý
- Quản lý thành viên
- Quản lý không gian
- Quản lý tiện ích

### 3. Thanh toán
- Thanh toán online
- Gói thành viên
- Hóa đơn điện tử

### 4. Cộng đồng
- Kết nối thành viên
- Sự kiện và workshop
- Chia sẻ tài nguyên

## Công nghệ sử dụng

- **Frontend: React.js
- **Backend: Laravel API
- **Mobile: React Native app
- **Payment: Stripe, VNPay
- **Database**: PostgreSQL',
                'faculty' => $facultyKT,
                'category' => $categoryKT,
            ],
            [
                'title' => 'Hệ thống phát hiện và cảnh báo cháy rừng bằng AI và drone',
                'summary' => 'Xây dựng hệ thống sử dụng drone và AI để giám sát rừng, phát hiện sớm cháy rừng và cảnh báo cho cơ quan chức năng.',
                'description' => 'Hệ thống sử dụng drone bay tự động, camera nhiệt và AI để giám sát rừng, phát hiện sớm cháy rừng và gửi cảnh báo real-time.',
                'image_url' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống phát hiện cháy rừng bằng AI và drone là giải pháp công nghệ giúp phát hiện và ngăn chặn cháy rừng sớm, bảo vệ tài nguyên rừng.

## Các tính năng chính

### 1. Giám sát bằng drone
- Drone bay tự động
- Camera nhiệt phát hiện nhiệt độ cao
- Chụp ảnh và video

### 2. AI phát hiện
- AI phân tích ảnh
- Phát hiện khói và lửa
- Xác định vị trí cháy

### 3. Cảnh báo
- Cảnh báo real-time
- Gửi thông tin vị trí
- Tích hợp với cơ quan chức năng

### 4. Quản lý
- Dashboard quản lý
- Lịch sử giám sát
- Báo cáo và thống kê

## Công nghệ sử dụng

- **Hardware: Drone, thermal camera
- **AI/ML: Computer vision cho phát hiện cháy
- **Backend: Laravel API
- **Maps: Google Maps API
- **Database**: PostgreSQL',
                'faculty' => $facultyDDT,
                'category' => $categoryDDT,
            ],
            [
                'title' => 'Ứng dụng tìm kiếm và đặt phòng khách sạn địa phương',
                'summary' => 'Phát triển ứng dụng tìm kiếm và đặt phòng khách sạn địa phương với thanh toán online và đánh giá.',
                'description' => 'Ứng dụng giúp khách du lịch tìm và đặt phòng khách sạn địa phương, thanh toán online, xem đánh giá và quản lý đặt phòng.',
                'image_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Ứng dụng đặt phòng khách sạn địa phương là giải pháp giúp khách du lịch tìm và đặt phòng thuận tiện, đồng thời giúp khách sạn quản lý đặt phòng hiệu quả.

## Các tính năng chính

### 1. Tìm kiếm
- Tìm khách sạn theo vị trí
- Lọc theo giá, tiện ích
- Xem ảnh và đánh giá

### 2. Đặt phòng
- Đặt phòng online
- Thanh toán an toàn
- Xác nhận đặt phòng

### 3. Quản lý
- Quản lý đặt phòng
- Lịch sử đặt phòng
- Hủy và đổi phòng

### 4. Đánh giá
- Đánh giá khách sạn
- Xem đánh giá của người khác
- Xây dựng uy tín

## Công nghệ sử dụng

- **Frontend: React.js
- **Backend: Laravel API
- **Mobile: React Native app
- **Payment: Stripe, VNPay
- **Maps: Google Maps API
- **Database**: PostgreSQL',
                'faculty' => $facultyKT,
                'category' => $categoryKT,
            ],
            [
                'title' => 'Hệ thống quản lý năng lượng thông minh cho tòa nhà',
                'summary' => 'Xây dựng hệ thống quản lý năng lượng sử dụng IoT và AI để giám sát, tối ưu hóa tiêu thụ năng lượng trong tòa nhà.',
                'description' => 'Hệ thống tích hợp cảm biến IoT và AI để giám sát tiêu thụ năng lượng, tự động điều chỉnh thiết bị và tối ưu hóa chi phí năng lượng.',
                'image_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống quản lý năng lượng thông minh là giải pháp giúp tòa nhà tiết kiệm năng lượng và giảm chi phí vận hành.

## Các tính năng chính

### 1. Giám sát năng lượng
- Đo tiêu thụ điện năng
- Phân tích theo khu vực
- Phát hiện thiết bị tiêu thụ nhiều

### 2. Tối ưu hóa
- AI tự động điều chỉnh
- Tắt thiết bị không cần thiết
- Tối ưu hóa lịch sử dụng

### 3. Cảnh báo
- Cảnh báo khi tiêu thụ cao
- Dự báo hóa đơn
- Gợi ý tiết kiệm

### 4. Báo cáo
- Báo cáo tiêu thụ
- Phân tích xu hướng
- So sánh với mức trung bình

## Công nghệ sử dụng

- **IoT: Smart meters, sensors
- **AI/ML: Machine learning cho tối ưu hóa
- **Backend: Laravel API
- **Dashboard: React.js
- **Database**: InfluxDB cho time-series data',
                'faculty' => $facultyDDT,
                'category' => $categoryDDT,
            ],
            [
                'title' => 'Nền tảng học kỹ năng mềm trực tuyến với AI coach',
                'summary' => 'Phát triển nền tảng học kỹ năng mềm trực tuyến với AI coach cá nhân hóa, giúp người học cải thiện kỹ năng giao tiếp, lãnh đạo.',
                'description' => 'Nền tảng sử dụng AI để tạo coach ảo cá nhân hóa, đánh giá kỹ năng, đưa ra bài tập thực hành và phản hồi để cải thiện kỹ năng mềm.',
                'image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Nền tảng học kỹ năng mềm với AI coach là giải pháp giáo dục giúp người học cải thiện kỹ năng mềm một cách hiệu quả và cá nhân hóa.

## Các tính năng chính

### 1. AI Coach
- Coach ảo cá nhân hóa
- Đánh giá kỹ năng hiện tại
- Đưa ra lộ trình học tập

### 2. Nội dung học tập
- Video bài giảng
- Bài tập thực hành
- Tình huống mô phỏng

### 3. Thực hành
- Luyện tập với AI
- Nhận phản hồi chi tiết
- Theo dõi tiến độ

### 4. Đánh giá
- Đánh giá kỹ năng
- Chứng chỉ hoàn thành
- Portfolio kỹ năng

## Công nghệ sử dụng

- **AI/ML: GPT-4, Claude cho AI coach
- **Frontend: React.js
- **Backend: Laravel API
- **Video: Video streaming platform
- **Database**: PostgreSQL',
                'faculty' => $facultyNN,
                'category' => $categoryGD,
            ],
            [
                'title' => 'Hệ thống quản lý vườn cây ăn trái thông minh với IoT',
                'summary' => 'Xây dựng hệ thống quản lý vườn cây ăn trái sử dụng IoT để giám sát đất, nước, thời tiết và tự động tưới tiêu.',
                'description' => 'Hệ thống tích hợp cảm biến IoT để giám sát điều kiện đất, nước, thời tiết, tự động tưới tiêu và cảnh báo khi cần chăm sóc.',
                'image_url' => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống quản lý vườn cây ăn trái thông minh là giải pháp IoT giúp nông dân quản lý vườn cây hiệu quả và tăng năng suất.

## Các tính năng chính

### 1. Giám sát môi trường
- Cảm biến độ ẩm đất
- Cảm biến nhiệt độ, độ ẩm không khí
- Cảm biến ánh sáng

### 2. Tự động tưới tiêu
- Tự động tưới khi cần
- Điều chỉnh lượng nước
- Lập lịch tưới

### 3. Cảnh báo
- Cảnh báo khi cần chăm sóc
- Dự báo thời tiết
- Gợi ý chăm sóc

### 4. Quản lý
- Dashboard quản lý
- Báo cáo và thống kê
- Lịch sử chăm sóc

## Công nghệ sử dụng

- **IoT: ESP32, LoRaWAN
- **Sensors: Soil moisture, temperature, humidity
- **Backend: Laravel API với MQTT
- **Mobile: React Native app
- **Database**: InfluxDB cho time-series data',
                'faculty' => $facultyDDT,
                'category' => $categoryDDT,
            ],
            [
                'title' => 'Ứng dụng tìm kiếm và đặt dịch vụ sửa chữa tại nhà',
                'summary' => 'Phát triển ứng dụng kết nối khách hàng với thợ sửa chữa, hỗ trợ đặt dịch vụ, thanh toán và đánh giá.',
                'description' => 'Ứng dụng giúp khách hàng tìm và đặt dịch vụ sửa chữa tại nhà, kết nối với thợ sửa chữa, thanh toán và đánh giá dịch vụ.',
                'image_url' => 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Ứng dụng tìm kiếm dịch vụ sửa chữa là giải pháp kết nối khách hàng với thợ sửa chữa, tạo cơ hội việc làm và cải thiện dịch vụ.

## Các tính năng chính

### 1. Tìm kiếm
- Tìm thợ sửa chữa gần nhất
- Lọc theo loại dịch vụ
- Xem đánh giá và giá cả

### 2. Đặt dịch vụ
- Đặt dịch vụ online
- Chat với thợ
- Theo dõi tiến độ

### 3. Thanh toán
- Thanh toán online
- Thanh toán sau khi hoàn thành
- Hóa đơn điện tử

### 4. Đánh giá
- Đánh giá dịch vụ
- Xây dựng uy tín
- Phản hồi và cải thiện

## Công nghệ sử dụng

- **Frontend: React.js
- **Backend: Laravel API
- **Mobile: React Native app
- **Payment: Stripe, VNPay
- **Maps: Google Maps API
- **Database**: PostgreSQL',
                'faculty' => $facultyKT,
                'category' => $categoryKT,
            ],
            [
                'title' => 'Hệ thống quản lý giao thông thông minh với AI',
                'summary' => 'Xây dựng hệ thống quản lý giao thông sử dụng AI để phân tích lưu lượng, tối ưu hóa đèn giao thông và giảm ùn tắc.',
                'description' => 'Hệ thống sử dụng camera AI và cảm biến để phân tích lưu lượng giao thông, tự động điều chỉnh đèn giao thông và tối ưu hóa luồng giao thông.',
                'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống quản lý giao thông thông minh là giải pháp công nghệ giúp giảm ùn tắc giao thông và cải thiện hiệu quả giao thông đô thị.

## Các tính năng chính

### 1. Phân tích lưu lượng
- Camera AI đếm phương tiện
- Phân tích mật độ giao thông
- Dự đoán ùn tắc

### 2. Điều khiển đèn giao thông
- Tự động điều chỉnh thời gian đèn
- Tối ưu hóa luồng giao thông
- Giảm thời gian chờ đợi

### 3. Cảnh báo
- Cảnh báo ùn tắc
- Gợi ý tuyến đường thay thế
- Thông báo real-time

### 4. Quản lý
- Dashboard quản lý
- Báo cáo giao thông
- Phân tích xu hướng

## Công nghệ sử dụng

- **AI/ML: Computer vision, traffic flow analysis
- **Hardware: Cameras, traffic lights controllers
- **Backend: Laravel API
- **Dashboard: React.js
- **Database**: PostgreSQL, InfluxDB',
                'faculty' => $facultyDDT,
                'category' => $categoryDDT,
            ],
            [
                'title' => 'Nền tảng học nhạc trực tuyến với AI đánh giá',
                'summary' => 'Phát triển nền tảng học nhạc trực tuyến với AI để đánh giá phát âm, gợi ý cải thiện và tạo lộ trình học tập cá nhân hóa.',
                'description' => 'Nền tảng sử dụng AI để phân tích phát âm nhạc, đánh giá kỹ năng, đưa ra gợi ý cải thiện và tạo lộ trình học tập phù hợp với từng người.',
                'image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Nền tảng học nhạc trực tuyến với AI là giải pháp giáo dục giúp người học cải thiện kỹ năng âm nhạc một cách hiệu quả và cá nhân hóa.

## Các tính năng chính

### 1. AI đánh giá
- Phân tích phát âm
- Đánh giá nhịp điệu
- Gợi ý cải thiện

### 2. Nội dung học tập
- Video bài giảng
- Bài tập thực hành
- Nhạc cụ ảo

### 3. Lộ trình học tập
- Lộ trình cá nhân hóa
- Theo dõi tiến độ
- Điều chỉnh lộ trình

### 4. Cộng đồng
- Chia sẻ bài hát
- Kết nối với người học khác
- Cuộc thi và thử thách

## Công nghệ sử dụng

- **AI/ML: Audio analysis, pitch detection
- **Frontend: React.js
- **Backend: Laravel API
- **Audio: Web Audio API
- **Database**: PostgreSQL',
                'faculty' => $facultyNN,
                'category' => $categoryGD,
            ],
            [
                'title' => 'Hệ thống quản lý bể bơi thông minh với IoT',
                'summary' => 'Xây dựng hệ thống quản lý bể bơi sử dụng IoT để giám sát chất lượng nước, tự động xử lý và cảnh báo.',
                'description' => 'Hệ thống tích hợp cảm biến IoT để giám sát chất lượng nước, pH, clo, tự động điều chỉnh và cảnh báo khi cần xử lý.',
                'image_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống quản lý bể bơi thông minh là giải pháp IoT giúp quản lý chất lượng nước bể bơi tự động và hiệu quả.

## Các tính năng chính

### 1. Giám sát chất lượng nước
- Cảm biến pH, clo
- Đo nhiệt độ nước
- Phát hiện tạp chất

### 2. Tự động xử lý
- Tự động thêm hóa chất
- Điều chỉnh pH
- Lọc nước tự động

### 3. Cảnh báo
- Cảnh báo khi chất lượng nước kém
- Thông báo qua ứng dụng
- Lịch sử dữ liệu

### 4. Quản lý
- Dashboard quản lý
- Báo cáo chất lượng nước
- Phân tích xu hướng

## Công nghệ sử dụng

- **IoT: ESP32, sensors
- **Sensors: pH, chlorine, temperature sensors
- **Actuators: Chemical dispensers, pumps
- **Backend: Laravel API với MQTT
- **Mobile: React Native app
- **Database**: InfluxDB cho time-series data',
                'faculty' => $facultyDDT,
                'category' => $categoryDDT,
            ],
            [
                'title' => 'Ứng dụng quản lý sự kiện và bán vé online',
                'summary' => 'Phát triển ứng dụng quản lý sự kiện và bán vé online, hỗ trợ thanh toán, check-in và quản lý khách mời.',
                'description' => 'Ứng dụng giúp tổ chức sự kiện quản lý sự kiện, bán vé online, thanh toán, check-in và quản lý khách mời một cách hiệu quả.',
                'image_url' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Ứng dụng quản lý sự kiện và bán vé là giải pháp giúp tổ chức sự kiện quản lý và bán vé hiệu quả, cải thiện trải nghiệm khách hàng.

## Các tính năng chính

### 1. Quản lý sự kiện
- Tạo và quản lý sự kiện
- Thiết kế vé
- Quản lý khách mời

### 2. Bán vé
- Bán vé online
- Thanh toán an toàn
- QR code check-in

### 3. Check-in
- Check-in bằng QR code
- Quản lý danh sách khách
- Thống kê tham dự

### 4. Báo cáo
- Báo cáo doanh thu
- Thống kê tham dự
- Phân tích sự kiện

## Công nghệ sử dụng

- **Frontend: React.js
- **Backend: Laravel API
- **Mobile: React Native app
- **Payment: Stripe, VNPay
- **QR Code: QR code generation
- **Database**: PostgreSQL',
                'faculty' => $facultyKT,
                'category' => $categoryKT,
            ],
            [
                'title' => 'Hệ thống phát hiện và cảnh báo lũ lụt bằng IoT',
                'summary' => 'Xây dựng hệ thống phát hiện lũ lụt sử dụng cảm biến IoT để đo mực nước, cảnh báo sớm và gửi thông báo.',
                'description' => 'Hệ thống tích hợp cảm biến IoT để đo mực nước sông, suối, tự động cảnh báo khi mực nước dâng cao và gửi thông báo cho người dân.',
                'image_url' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống phát hiện và cảnh báo lũ lụt là giải pháp IoT giúp phát hiện sớm lũ lụt và cảnh báo người dân, giảm thiểu thiệt hại.

## Các tính năng chính

### 1. Giám sát mực nước
- Cảm biến đo mực nước
- GPS tracking vị trí
- Gửi dữ liệu real-time

### 2. Cảnh báo sớm
- Cảnh báo khi mực nước dâng cao
- Dự báo lũ lụt
- Thông báo qua ứng dụng

### 3. Bản đồ
- Hiển thị vị trí cảm biến
- Mực nước real-time
- Khu vực nguy hiểm

### 4. Quản lý
- Dashboard quản lý
- Lịch sử dữ liệu
- Báo cáo và thống kê

## Công nghệ sử dụng

- **IoT: ESP32, LoRaWAN
- **Sensors: Water level sensors
- **Backend: Laravel API với MQTT
- **Mobile: React Native app
- **Maps: Google Maps API
- **Database**: InfluxDB cho time-series data',
                'faculty' => $facultyDDT,
                'category' => $categoryDDT,
            ],
            [
                'title' => 'Nền tảng học thiết kế đồ họa với AI hỗ trợ',
                'summary' => 'Phát triển nền tảng học thiết kế đồ họa với AI để đánh giá thiết kế, gợi ý cải thiện và tạo mẫu tự động.',
                'description' => 'Nền tảng sử dụng AI để phân tích thiết kế, đánh giá chất lượng, đưa ra gợi ý cải thiện và tạo mẫu thiết kế tự động.',
                'image_url' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Nền tảng học thiết kế đồ họa với AI là giải pháp giáo dục giúp người học cải thiện kỹ năng thiết kế một cách hiệu quả.

## Các tính năng chính

### 1. AI đánh giá
- Phân tích thiết kế
- Đánh giá màu sắc, bố cục
- Gợi ý cải thiện

### 2. Nội dung học tập
- Video hướng dẫn
- Bài tập thực hành
- Template và mẫu

### 3. AI tạo mẫu
- Tạo mẫu thiết kế tự động
- Gợi ý ý tưởng
- Tối ưu hóa thiết kế

### 4. Portfolio
- Tạo portfolio online
- Chia sẻ tác phẩm
- Nhận phản hồi

## Công nghệ sử dụng

- **AI/ML: Computer vision, GAN cho tạo mẫu
- **Frontend: React.js
- **Backend: Laravel API
- **Design Tools: Canvas API, Fabric.js
- **Database**: PostgreSQL',
                'faculty' => $facultyNN,
                'category' => $categoryGD,
            ],
            [
                'title' => 'Hệ thống quản lý kho hàng tự động với robot',
                'summary' => 'Xây dựng hệ thống quản lý kho hàng sử dụng robot tự động để vận chuyển, sắp xếp và quản lý hàng hóa.',
                'description' => 'Hệ thống tích hợp robot tự động, AI và hệ thống quản lý kho để vận chuyển, sắp xếp và quản lý hàng hóa một cách hiệu quả.',
                'image_url' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Hệ thống quản lý kho hàng tự động với robot là giải pháp tự động hóa giúp tăng hiệu quả quản lý kho và giảm chi phí lao động.

## Các tính năng chính

### 1. Robot vận chuyển
- Robot tự động vận chuyển
- Tránh chướng ngại vật
- Tối ưu hóa đường đi

### 2. Quản lý hàng hóa
- Tự động sắp xếp
- Quản lý vị trí
- Kiểm kê tự động

### 3. AI tối ưu hóa
- Tối ưu hóa bố trí kho
- Dự đoán nhu cầu
- Quản lý tồn kho

### 4. Quản lý
- Dashboard quản lý
- Báo cáo và thống kê
- Giám sát robot

## Công nghệ sử dụng

- **Robotics: AGV robots, SLAM
- **AI/ML: Path planning, inventory optimization
- **Backend: Laravel API
- **Control System: ROS (Robot Operating System)
- **Database**: PostgreSQL',
                'faculty' => $facultyCKD,
                'category' => $categoryCKD,
            ],
            [
                'title' => 'Ứng dụng tìm kiếm và đặt dịch vụ làm đẹp',
                'summary' => 'Phát triển ứng dụng kết nối khách hàng với các dịch vụ làm đẹp, hỗ trợ đặt lịch, thanh toán và đánh giá.',
                'description' => 'Ứng dụng giúp khách hàng tìm và đặt dịch vụ làm đẹp, kết nối với salon, spa, thanh toán và đánh giá dịch vụ.',
                'image_url' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&h=600&fit=crop',
                'content' => '## Tổng quan dự án

Ứng dụng tìm kiếm dịch vụ làm đẹp là giải pháp kết nối khách hàng với các dịch vụ làm đẹp, cải thiện trải nghiệm khách hàng.

## Các tính năng chính

### 1. Tìm kiếm
- Tìm salon, spa gần nhất
- Lọc theo dịch vụ
- Xem đánh giá và giá cả

### 2. Đặt lịch
- Đặt lịch online
- Chọn thợ làm đẹp
- Nhắc nhở lịch hẹn

### 3. Thanh toán
- Thanh toán online
- Mã giảm giá
- Tích điểm

### 4. Đánh giá
- Đánh giá dịch vụ
- Chia sẻ hình ảnh
- Xây dựng uy tín

## Công nghệ sử dụng

- **Frontend: React.js
- **Backend: Laravel API
- **Mobile: React Native app
- **Payment: Stripe, VNPay
- **Maps: Google Maps API
- **Database**: PostgreSQL',
                'faculty' => $facultyKT,
                'category' => $categoryKT,
            ],
        ];

        // Tạo các ý tưởng
        $count = 0;
        foreach ($ideas as $ideaData) {
            if (!$ideaData['faculty'] || !$ideaData['category']) {
                continue;
            }

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
                    'image_url' => $ideaData['image_url'] ?? null,
                ]
            );

            if ($idea->wasRecentlyCreated) {
                $count++;
                $this->command->info('✓ Đã tạo: ' . $idea->title);
            }
        }

        $this->command->info(PHP_EOL . "Đã tạo thành công {$count} ý tưởng mới từ tổng số " . count($ideas) . " ý tưởng!");
    }
}

