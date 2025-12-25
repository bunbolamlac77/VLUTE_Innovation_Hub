<?php

namespace Database\Seeders;

use App\Models\Challenge;
use App\Models\ChallengeSubmission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ChallengeSubmissionsSeeder extends Seeder
{
    /**
     * Tạo giải pháp ảo của sinh viên cho các challenges
     */
    public function run(): void
    {
        // Lấy tất cả sinh viên
        $students = User::where('role', 'student')->get();
        
        if ($students->isEmpty()) {
            $this->command->warn('Không có sinh viên nào trong database. Vui lòng chạy ApprovedUsersSeeder hoặc DemoBulkSeeder trước.');
            return;
        }

        // Lấy tất cả challenges
        $challenges = Challenge::all();
        
        if ($challenges->isEmpty()) {
            $this->command->warn('Không có challenge nào trong database. Vui lòng chạy ChallengeSeeder trước.');
            return;
        }

        $totalSubmissions = 0;

        // Mẫu tiêu đề và mô tả giải pháp theo từng loại challenge
        $solutionTemplates = [
            'Tối ưu lịch dạy học' => [
                'title' => 'Hệ thống tối ưu lịch dạy học tự động - {name}',
                'description' => 'Giải pháp sử dụng thuật toán {algorithm} để tự động sắp xếp lịch dạy học và phân bổ phòng học. Hệ thống xem xét các yếu tố: thời khóa biểu của giảng viên, số lượng sinh viên, yêu cầu thiết bị đặc biệt, và khoảng cách giữa các phòng. Kết quả dự kiến: giảm {percent}% thời gian xung đột và tối ưu {usage}% mức sử dụng phòng học.',
            ],
            'Giảm thất thoát điện' => [
                'title' => 'Hệ thống giám sát và tối ưu năng lượng - {name}',
                'description' => 'Giải pháp IoT sử dụng cảm biến {sensor} để theo dõi tiêu thụ điện năng theo thời gian thực. Hệ thống phát hiện bất thường, dự báo tải điện bằng {model}, và tự động điều chỉnh thiết bị để tiết kiệm năng lượng. Dự kiến giảm {saving}% chi phí điện và phát hiện sớm {issues} vấn đề về thiết bị.',
            ],
            'Theo dõi chuỗi lạnh' => [
                'title' => 'Hệ thống giám sát chuỗi lạnh thông minh - {name}',
                'description' => 'Giải pháp sử dụng cảm biến nhiệt độ và độ ẩm {sensor} kết hợp với {tech} để theo dõi liên tục điều kiện bảo quản nông sản. Dữ liệu được ghi nhận trên blockchain nhẹ để đảm bảo tính minh bạch. Hệ thống cảnh báo ngay khi phát hiện vỡ chuỗi lạnh và tạo báo cáo truy xuất nguồn gốc tự động.',
            ],
            'Hệ thống quản lý bãi xe' => [
                'title' => 'Hệ thống bãi đỗ xe thông minh - {name}',
                'description' => 'Giải pháp kết hợp computer vision và {tech} để nhận diện biển số xe, đếm chỗ trống, và dự đoán lưu lượng. Ứng dụng mobile cho phép đặt chỗ trước, thanh toán không tiền mặt, và nhận thông báo khi có chỗ trống. Dự kiến giảm {time}% thời gian tìm chỗ đỗ và tăng {revenue}% doanh thu.',
            ],
            'Chatbot hỗ trợ tuyển dụng' => [
                'title' => 'Chatbot AI hỗ trợ tuyển dụng - {name}',
                'description' => 'Giải pháp chatbot sử dụng {model} để trả lời câu hỏi ứng viên, sàng lọc hồ sơ ban đầu, và lên lịch phỏng vấn tự động. Chatbot tích hợp với hệ thống ATS, hỗ trợ đa ngôn ngữ, và học hỏi từ tương tác để cải thiện chất lượng phản hồi. Dự kiến giảm {workload}% khối lượng công việc của HR.',
            ],
            'Phân loại rác thải' => [
                'title' => 'Hệ thống phân loại rác thông minh - {name}',
                'description' => 'Giải pháp sử dụng computer vision với mô hình {model} để nhận diện và phân loại rác tự động. Thùng rác thông minh có cảm biến trọng lượng, hiển thị hướng dẫn phân loại, và tích điểm thưởng cho người dùng. Dữ liệu được gửi lên dashboard để theo dõi và phân tích.',
            ],
            'Giám sát chất lượng nước' => [
                'title' => 'Hệ thống giám sát chất lượng nước nuôi trồng - {name}',
                'description' => 'Giải pháp IoT sử dụng cảm biến {sensor} để đo pH, nhiệt độ, độ mặn, và oxy hòa tan. Dữ liệu được truyền realtime qua {protocol}, phân tích bằng AI để phát hiện bất thường, và tự động điều chỉnh hệ thống sục khí. Cảnh báo ngay khi phát hiện vấn đề.',
            ],
            'Dashboard realtime' => [
                'title' => 'Dashboard giám sát nhà máy realtime - {name}',
                'description' => 'Giải pháp dashboard tích hợp dữ liệu từ nhiều nguồn: cảm biến IoT, hệ thống ERP, và camera giám sát. Sử dụng {tech} để hiển thị KPI, biểu đồ xu hướng, và cảnh báo khi vượt ngưỡng. Hỗ trợ xem trên mobile và desktop, với khả năng xuất báo cáo tự động.',
            ],
            'Dự báo nhu cầu' => [
                'title' => 'Hệ thống dự báo nhu cầu vật tư y tế - {name}',
                'description' => 'Giải pháp sử dụng machine learning với mô hình {model} để dự báo nhu cầu vật tư y tế dựa trên lịch sử sử dụng, mùa dịch bệnh, và dữ liệu bệnh viện. Hệ thống tự động đề xuất đơn hàng, tối ưu tồn kho, và cảnh báo khi sắp hết hàng. Dự kiến giảm {waste}% lãng phí và {shortage}% thiếu hụt.',
            ],
            'Quản lý tài sản' => [
                'title' => 'Hệ thống quản lý tài sản số hóa - {name}',
                'description' => 'Giải pháp sử dụng QR/NFC để gắn tag cho từng tài sản, cho phép quét nhanh để xem thông tin, lịch sử bảo trì, và vị trí hiện tại. Ứng dụng mobile hỗ trợ kiểm kê, báo cáo hỏng hóc, và theo dõi di chuyển. Tích hợp với hệ thống kế toán để tự động cập nhật giá trị tài sản.',
            ],
        ];

        // Dữ liệu mẫu để thay thế
        $algorithms = ['genetic algorithm', 'simulated annealing', 'constraint programming', 'greedy algorithm'];
        $sensors = ['DHT22', 'DS18B20', 'BME280', 'SHT30', 'LM35'];
        $techs = ['IoT', 'blockchain', 'AI', 'machine learning', 'edge computing'];
        $models = ['LSTM', 'Random Forest', 'XGBoost', 'Neural Network', 'ARIMA'];
        $protocols = ['LoRaWAN', 'NB-IoT', 'WiFi', 'Bluetooth Low Energy'];
        $classes = ['CNTT21A', 'DDT21B', 'CKD21C', 'KT21D', 'NN21E'];
        $schools = ['VLUTE', 'Đại học Bách khoa', 'Đại học Công nghệ'];

        foreach ($challenges as $challenge) {
            // Mỗi challenge sẽ có 3-8 sinh viên nộp giải pháp (ngẫu nhiên)
            $numSubmissions = rand(3, 8);
            $selectedStudents = $students->random(min($numSubmissions, $students->count()));

            // Tìm template phù hợp dựa trên title của challenge
            $templateKey = null;
            foreach ($solutionTemplates as $key => $template) {
                if (str_contains($challenge->title, $key)) {
                    $templateKey = $key;
                    break;
                }
            }

            // Nếu không tìm thấy, dùng template mặc định
            if (!$templateKey) {
                $templateKey = array_key_first($solutionTemplates);
            }

            $template = $solutionTemplates[$templateKey];

            foreach ($selectedStudents as $student) {
                // Kiểm tra xem sinh viên đã nộp cho challenge này chưa
                $existing = ChallengeSubmission::where('challenge_id', $challenge->id)
                    ->where('user_id', $student->id)
                    ->exists();

                if ($existing) {
                    continue; // Bỏ qua nếu đã nộp
                }

                // Tạo dữ liệu thay thế
                $replacements = [
                    '{name}' => $student->name,
                    '{algorithm}' => $algorithms[array_rand($algorithms)],
                    '{percent}' => rand(30, 60),
                    '{usage}' => rand(75, 95),
                    '{sensor}' => $sensors[array_rand($sensors)],
                    '{model}' => $models[array_rand($models)],
                    '{saving}' => rand(15, 30),
                    '{issues}' => rand(5, 15),
                    '{tech}' => $techs[array_rand($techs)],
                    '{time}' => rand(40, 60),
                    '{revenue}' => rand(10, 25),
                    '{workload}' => rand(30, 50),
                    '{protocol}' => $protocols[array_rand($protocols)],
                    '{waste}' => rand(20, 40),
                    '{shortage}' => rand(10, 30),
                ];

                $title = str_replace(array_keys($replacements), array_values($replacements), $template['title']);
                $description = str_replace(array_keys($replacements), array_values($replacements), $template['description']);

                // Lấy thông tin profile của sinh viên nếu có
                $profile = $student->profile;

                ChallengeSubmission::create([
                    'challenge_id' => $challenge->id,
                    'user_id' => $student->id,
                    'idea_id' => null, // Có thể liên kết với idea sau
                    'title' => $title,
                    'solution_description' => $description,
                    'submitted_at' => Carbon::now()->subDays(rand(0, 30)), // Nộp trong 30 ngày qua
                    'full_name' => $student->name,
                    'phone' => $profile->phone ?? '0' . rand(900000000, 999999999),
                    'address' => $profile->address ?? 'Vĩnh Long',
                    'class_name' => $profile->class_name ?? $classes[array_rand($classes)],
                    'school_name' => $schools[array_rand($schools)],
                    'team_members' => null, // Có thể thêm sau
                    'mentor_name' => null, // Có thể thêm sau
                ]);

                $totalSubmissions++;
            }
        }

        $this->command->info("✓ Đã tạo {$totalSubmissions} giải pháp của sinh viên cho các challenges");
        $this->command->info("  - Mỗi challenge có từ 3-8 giải pháp");
        $this->command->info("  - Tất cả giải pháp đều có thông tin đầy đủ");
    }
}

