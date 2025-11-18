<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ScientificNew;
use Carbon\Carbon;

class ScientificNewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $scientificNews = [
            [
                'title' => 'Đột phá trong công nghệ AI tạo sinh: GPT-5 và khả năng suy luận vượt trội',
                'description' => 'Các nhà nghiên cứu đã phát triển thành công mô hình AI thế hệ mới với khả năng suy luận logic và hiểu ngữ cảnh vượt trội so với các phiên bản trước.',
                'content' => 'Trong một bước tiến đáng kể của công nghệ trí tuệ nhân tạo, các nhà khoa học tại OpenAI đã công bố mô hình GPT-5 với khả năng suy luận phức tạp và hiểu ngữ cảnh sâu sắc hơn nhiều so với các thế hệ trước. Mô hình mới này không chỉ có thể xử lý văn bản mà còn tích hợp đa phương thức, bao gồm hình ảnh, âm thanh và video. Điều đặc biệt là GPT-5 có khả năng tự kiểm tra và sửa lỗi trong quá trình suy luận, giúp giảm thiểu các "ảo giác" - một vấn đề thường gặp ở các mô hình AI trước đây. Nghiên cứu này mở ra nhiều ứng dụng tiềm năng trong y tế, giáo dục, và nghiên cứu khoa học.',
                'author' => 'TS. Nguyễn Văn An',
                'source' => 'Tạp chí Khoa học Công nghệ Việt Nam',
                'image_url' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=800',
                'published_date' => Carbon::now()->subDays(2),
                'category' => 'Trí tuệ nhân tạo',
            ],
            [
                'title' => 'Phát hiện loại vật liệu mới có khả năng lưu trữ năng lượng hiệu quả gấp 10 lần',
                'description' => 'Nhóm nghiên cứu quốc tế đã tổng hợp thành công vật liệu siêu tụ điện mới với mật độ năng lượng cao kỷ lục, hứa hẹn cách mạng hóa ngành pin và lưu trữ năng lượng.',
                'content' => 'Các nhà khoa học từ Đại học Stanford và MIT đã công bố một nghiên cứu đột phá về vật liệu graphene lai tạo có khả năng lưu trữ năng lượng gấp 10 lần so với pin lithium-ion hiện tại. Vật liệu mới này được tạo ra bằng cách kết hợp graphene với các hợp chất kim loại hiếm theo một cấu trúc nano đặc biệt. Điều đáng chú ý là vật liệu này không chỉ có mật độ năng lượng cao mà còn có khả năng sạc nhanh trong vòng vài phút và tuổi thọ lên đến 50.000 chu kỳ sạc. Phát hiện này có thể thay đổi hoàn toàn ngành công nghiệp xe điện và lưu trữ năng lượng tái tạo, giúp giải quyết một trong những thách thức lớn nhất trong chuyển đổi năng lượng xanh.',
                'author' => 'GS. Trần Thị Bình',
                'source' => 'Nature Energy',
                'image_url' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800',
                'published_date' => Carbon::now()->subDays(5),
                'category' => 'Vật liệu học',
            ],
            [
                'title' => 'Liệu pháp gen mới chữa khỏi hoàn toàn bệnh ung thư máu ở trẻ em',
                'description' => 'Thử nghiệm lâm sàng giai đoạn 3 cho thấy liệu pháp CAR-T thế hệ mới đạt tỷ lệ thành công 95% trong điều trị bạch cầu cấp tính ở trẻ em.',
                'content' => 'Một nghiên cứu đột phá được công bố trên tạp chí New England Journal of Medicine cho thấy liệu pháp gen CAR-T thế hệ mới đã chữa khỏi hoàn toàn bệnh ung thư máu (bạch cầu lympho cấp) cho 95% bệnh nhi tham gia thử nghiệm. Liệu pháp này hoạt động bằng cách lấy tế bào T từ bệnh nhân, biến đổi gen để chúng có khả năng nhận diện và tiêu diệt tế bào ung thư, sau đó đưa trở lại cơ thể. Điểm mới của liệu pháp này là việc sử dụng công nghệ CRISPR để tăng cường khả năng tồn tại và hoạt động của tế bào T, đồng thời giảm thiểu tác dụng phụ. Kết quả cho thấy 85% bệnh nhân vẫn không có dấu hiệu tái phát sau 5 năm theo dõi. Đây là một bước tiến vượt bậc trong điều trị ung thư, đặc biệt là ở trẻ em.',
                'author' => 'PGS. Lê Minh Châu',
                'source' => 'New England Journal of Medicine',
                'image_url' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800',
                'published_date' => Carbon::now()->subDays(7),
                'category' => 'Y học',
            ],
            [
                'title' => 'Khám phá hành tinh có khả năng sinh sống cách Trái Đất 40 năm ánh sáng',
                'description' => 'Kính viễn vọng James Webb phát hiện hành tinh ngoài hệ mặt trời có dấu hiệu của nước lỏng và khí quyển giống Trái Đất.',
                'content' => 'Kính viễn vọng không gian James Webb của NASA đã phát hiện một hành tinh ngoài hệ mặt trời có tên K2-18b, nằm trong "vùng sống" của ngôi sao mẹ, cách Trái Đất khoảng 40 năm ánh sáng. Phân tích quang phổ cho thấy hành tinh này có khí quyển chứa hơi nước, methane và carbon dioxide - các dấu hiệu quan trọng cho khả năng tồn tại sự sống. Đặc biệt, các nhà khoa học đã phát hiện dấu vết của dimethyl sulfide (DMS), một hợp chất trên Trái Đất chỉ được tạo ra bởi sinh vật sống. Nhiệt độ bề mặt ước tính khoảng 20-30°C, phù hợp cho nước tồn tại ở dạng lỏng. Mặc dù cần thêm nhiều nghiên cứu để xác nhận, nhưng đây là một trong những ứng cử viên hứa hẹn nhất cho việc tìm kiếm sự sống ngoài Trái Đất.',
                'author' => 'TS. Phạm Quốc Dũng',
                'source' => 'The Astrophysical Journal',
                'image_url' => 'https://images.unsplash.com/photo-1614730321146-b6fa6a46bcb4?w=800',
                'published_date' => Carbon::now()->subDays(10),
                'category' => 'Thiên văn học',
            ],
            [
                'title' => 'Robot nano y sinh có thể tiêu diệt tế bào ung thư từ bên trong',
                'description' => 'Các nhà khoa học đã phát triển thành công robot nano có thể di chuyển trong cơ thể, nhận diện và tiêu diệt tế bào ung thư mà không gây hại cho tế bào khỏe.',
                'content' => 'Trong một nghiên cứu được công bố trên tạp chí Science Robotics, các nhà khoa học từ Viện Công nghệ California (Caltech) đã tạo ra robot nano kích thước chỉ bằng 1/1000 sợi tóc, có khả năng di chuyển trong mạch máu và nhận diện chính xác tế bào ung thư. Robot này được làm từ DNA origami - một kỹ thuật gấp DNA thành các cấu trúc 3D phức tạp. Chúng được trang bị các phân tử nhận diện đặc hiệu với tế bào ung thư và mang theo thuốc độc chỉ giải phóng khi tiếp xúc với tế bào đích. Trong thử nghiệm trên chuột, robot nano đã giảm kích thước khối u lên đến 70% sau 4 tuần mà không gây tác dụng phụ đáng kể. Công nghệ này hứa hẹn mở ra kỷ nguyên mới trong điều trị ung thư chính xác và ít xâm lấn.',
                'author' => 'GS. Hoàng Văn Tuấn',
                'source' => 'Science Robotics',
                'image_url' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=800',
                'published_date' => Carbon::now()->subDays(14),
                'category' => 'Công nghệ sinh học',
            ],
        ];

        foreach ($scientificNews as $news) {
            ScientificNew::create($news);
        }
    }
}
