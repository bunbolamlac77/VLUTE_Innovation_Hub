<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\CompetitionRegistration;
use App\Models\CompetitionSubmission;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompetitionRegistrationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả sinh viên
        $students = User::where('role', 'student')->get();
        
        if ($students->isEmpty()) {
            $this->command->warn('Không có sinh viên nào trong database. Vui lòng chạy ApprovedUsersSeeder hoặc DemoBulkSeeder trước.');
            return;
        }

        // Lấy tất cả cuộc thi
        $competitions = Competition::all();
        
        if ($competitions->isEmpty()) {
            $this->command->warn('Không có cuộc thi nào trong database. Vui lòng chạy CompetitionSeeder trước.');
            return;
        }

        $totalRegistrations = 0;
        $totalSubmissions = 0;

        // Mẫu dữ liệu bài nộp theo từng loại cuộc thi
        $submissionTemplates = [
            'innovation-launchpad' => [
                'title' => 'Ý tưởng khởi nghiệp công nghệ - {name}',
                'abstract' => 'Giải pháp {solution} sử dụng công nghệ {tech} để giải quyết vấn đề {problem}. Dự án hướng tới {target} với mô hình kinh doanh {model}. Kế hoạch triển khai trong {timeline} tháng với ngân sách dự kiến {budget} triệu đồng.',
            ],
            'ai-for-campus' => [
                'title' => 'Giải pháp AI cho {problem} - {name}',
                'abstract' => 'Hệ thống AI sử dụng {model} để {function}. Ứng dụng công nghệ {tech} với độ chính xác dự kiến {accuracy}%. Demo live tại {location} với dữ liệu thực tế từ {source}.',
            ],
            'green-campus' => [
                'title' => 'Giải pháp xanh cho campus - {name}',
                'abstract' => 'Dự án {project} nhằm giảm {metric} xuống {target}% trong {timeframe}. Sử dụng {technology} để đo lường và tối ưu hóa. Mô hình tài chính cho thấy ROI {roi}% sau {period} năm.',
            ],
            'digital-content' => [
                'title' => 'Nội dung số về {topic} - {name}',
                'abstract' => 'Sản phẩm {type} với thông điệp {message}. Nội dung bao gồm {content} với định dạng {format}. Kế hoạch lan tỏa qua {channels} với mục tiêu {target} lượt tiếp cận.',
            ],
            'smart-mobility' => [
                'title' => 'Giải pháp di chuyển thông minh - {name}',
                'abstract' => 'Hệ thống {system} kết hợp IoT, AI và {tech} để {function}. Thiết bị {device} với cảm biến {sensor} giúp {benefit}. Prototype đã được thử nghiệm tại {location} với kết quả {result}.',
            ],
            'fintech-student' => [
                'title' => 'Sản phẩm Fintech cho sinh viên - {name}',
                'abstract' => 'Ứng dụng {app} giúp sinh viên {function}. Tính năng chính: {features}. Mô hình rủi ro sử dụng {method} với tỷ lệ dự phòng {reserve}%. Tuân thủ {compliance} về bảo mật dữ liệu.',
            ],
            'ui-ux-public' => [
                'title' => 'Thiết kế UI/UX cho {service} - {name}',
                'abstract' => 'Wireframe và prototype cho {service} với {pages} màn hình chính. Nghiên cứu người dùng với {users} người tham gia. Guideline thiết kế tuân thủ {standards}. Kết quả test usability: {score}/10.',
            ],
            'data-social-good' => [
                'title' => 'Phân tích dữ liệu về {topic} - {name}',
                'abstract' => 'Phân tích dataset {dataset} với {records} bản ghi. Sử dụng {methods} để phát hiện {insights}. Trực quan hóa bằng {visualizations}. Kết quả cho thấy {findings} với đề xuất {recommendations}.',
            ],
            'product-design' => [
                'title' => 'Thiết kế sản phẩm {product} - {name}',
                'abstract' => 'Mẫu thử {prototype} cho sản phẩm {product} với vật liệu {material}. Thiết kế tập trung vào {focus}. Mô hình 3D đã được in thử với kết quả {result}. Khả năng sản xuất: {feasibility}.',
            ],
            'research-poster' => [
                'title' => 'Nghiên cứu về {topic} - {name}',
                'abstract' => 'Nghiên cứu {research} với phương pháp {method}. Kết quả thí nghiệm cho thấy {results}. Phân tích dữ liệu sử dụng {analysis}. Kết luận và hướng phát triển: {conclusion}.',
            ],
        ];

        // Dữ liệu mẫu để thay thế
        $solutionTypes = ['ứng dụng di động', 'nền tảng web', 'hệ thống IoT', 'mô hình AI', 'blockchain', 'AR/VR'];
        $techStack = ['React Native', 'Flutter', 'Laravel', 'Python', 'TensorFlow', 'Node.js', 'Vue.js', 'Django'];
        $problems = ['quản lý học vụ', 'bãi đỗ xe', 'truy xuất tài liệu', 'tiết kiệm năng lượng', 'phân loại rác', 'giao thông nội bộ'];
        $targets = ['sinh viên', 'giảng viên', 'nhân viên hành chính', 'ban giám hiệu', 'doanh nghiệp'];
        $models = ['B2C', 'B2B', 'freemium', 'subscription', 'marketplace'];
        $topics = ['STEM', 'đổi mới sáng tạo', 'công nghệ xanh', 'AI trong giáo dục', 'IoT', 'blockchain'];
        $formats = ['video', 'podcast', 'infographic', 'animation', 'interactive'];
        $channels = ['social media', 'youtube', 'website', 'app mobile'];
        $services = ['đăng ký hồ sơ', 'tra cứu kết quả', 'phản ánh hiện trường', 'dịch vụ công trực tuyến'];
        $datasets = ['môi trường', 'y tế', 'giáo dục', 'giao thông', 'năng lượng'];
        $products = ['thiết bị cơ khí', 'sản phẩm điện tử', 'dụng cụ học tập', 'thiết bị IoT'];
        $materials = ['nhựa PLA', 'kim loại', 'gỗ', 'composite'];
        $researches = ['machine learning', 'computer vision', 'natural language processing', 'robotics', 'IoT'];

        foreach ($students as $student) {
            foreach ($competitions as $competition) {
                // Tạo đăng ký cho mỗi sinh viên với mỗi cuộc thi
                $registration = CompetitionRegistration::firstOrCreate(
                    [
                        'competition_id' => $competition->id,
                        'user_id' => $student->id,
                    ],
                    [
                        'team_name' => 'Đội ' . $student->name,
                        'notes' => 'Đăng ký tham gia cuộc thi ' . $competition->title,
                        'status' => 'approved',
                    ]
                );

                if ($registration->wasRecentlyCreated) {
                    $totalRegistrations++;
                }

                // Tạo bài nộp cho mỗi đăng ký (chỉ tạo 1 lần nếu chưa có)
                if ($registration->submissions()->count() === 0) {
                    // Xác định template dựa trên slug của cuộc thi
                    $competitionSlug = $competition->slug;
                    $templateKey = null;
                    
                    // Tìm template phù hợp
                    foreach ($submissionTemplates as $key => $template) {
                        if (str_contains($competitionSlug, $key)) {
                            $templateKey = $key;
                            break;
                        }
                    }
                    
                    // Nếu không tìm thấy template phù hợp, dùng template mặc định
                    if (!$templateKey) {
                        $templateKey = 'innovation-launchpad';
                    }

                    $template = $submissionTemplates[$templateKey];

                    // Tạo dữ liệu giả phù hợp - tạo một lần và dùng cho cả title và abstract
                    $replacements = [
                        '{name}' => $student->name,
                        '{solution}' => $solutionTypes[array_rand($solutionTypes)],
                        '{tech}' => $techStack[array_rand($techStack)],
                        '{problem}' => $problems[array_rand($problems)],
                        '{target}' => $targets[array_rand($targets)],
                        '{model}' => $models[array_rand($models)],
                        '{timeline}' => rand(3, 12),
                        '{budget}' => rand(50, 500),
                        '{function}' => 'tự động hóa quy trình',
                        '{accuracy}' => rand(85, 98),
                        '{location}' => 'phòng thí nghiệm CNTT',
                        '{source}' => 'dữ liệu thực tế của trường',
                        '{project}' => 'tiết kiệm năng lượng',
                        '{metric}' => 'tiêu thụ điện',
                        '{timeframe}' => rand(6, 18) . ' tháng',
                        '{technology}' => 'cảm biến IoT',
                        '{roi}' => rand(20, 50),
                        '{period}' => rand(2, 5),
                        '{type}' => $formats[array_rand($formats)],
                        '{message}' => 'nâng cao nhận thức về ' . $topics[array_rand($topics)],
                        '{content}' => 'video, infographic, bài viết',
                        '{format}' => $formats[array_rand($formats)],
                        '{channels}' => implode(', ', array_slice($channels, 0, rand(2, 3))),
                        '{system}' => 'quản lý giao thông',
                        '{device}' => 'cảm biến thông minh',
                        '{sensor}' => 'cảm biến siêu âm',
                        '{benefit}' => 'tối ưu hóa lưu lượng',
                        '{result}' => 'giảm 30% thời gian tìm chỗ đỗ',
                        '{app}' => 'quản lý tài chính',
                        '{features}' => 'theo dõi chi tiêu, lập ngân sách, cảnh báo',
                        '{method}' => 'phân tích hành vi',
                        '{reserve}' => rand(5, 15),
                        '{compliance}' => 'GDPR và Luật An ninh mạng',
                        '{service}' => $services[array_rand($services)],
                        '{pages}' => rand(5, 15),
                        '{users}' => rand(20, 50),
                        '{standards}' => 'Material Design và WCAG 2.1',
                        '{score}' => rand(7, 9),
                        '{dataset}' => $datasets[array_rand($datasets)],
                        '{records}' => rand(1000, 10000),
                        '{methods}' => 'machine learning và statistical analysis',
                        '{insights}' => 'mối tương quan và xu hướng',
                        '{visualizations}' => 'biểu đồ, heatmap, dashboard',
                        '{findings}' => 'các vấn đề quan trọng',
                        '{recommendations}' => 'giải pháp cụ thể',
                        '{prototype}' => 'mẫu thử nghiệm',
                        '{product}' => $products[array_rand($products)],
                        '{material}' => $materials[array_rand($materials)],
                        '{focus}' => 'tính công năng và khả năng sản xuất',
                        '{feasibility}' => 'khả thi với công nghệ hiện tại',
                        '{research}' => $researches[array_rand($researches)],
                        '{analysis}' => 'phân tích thống kê và machine learning',
                        '{conclusion}' => 'kết quả đáng kể',
                        '{topic}' => $topics[array_rand($topics)],
                    ];

                    $title = str_replace(array_keys($replacements), array_values($replacements), $template['title']);
                    $abstract = str_replace(array_keys($replacements), array_values($replacements), $template['abstract']);

                    CompetitionSubmission::create([
                        'registration_id' => $registration->id,
                        'title' => $title,
                        'abstract' => $abstract,
                    ]);

                    $totalSubmissions++;
                }
            }
        }

        $this->command->info("✓ Đã tạo {$totalRegistrations} đăng ký cuộc thi và {$totalSubmissions} bài nộp.");
        $this->command->info("  - Mỗi sinh viên đã đăng ký tất cả {$competitions->count()} cuộc thi");
        $this->command->info("  - Mỗi đăng ký đã có 1 bài nộp với dữ liệu phù hợp");
    }
}

