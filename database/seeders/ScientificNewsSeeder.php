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
                'source' => 'https://vjst.vn',
                'image_url' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=1200&q=80&auto=format&fit=crop',
                'published_date' => Carbon::now()->subDays(2),
                'category' => 'Trí tuệ nhân tạo',
            ],
            [
                'title' => 'Phát hiện loại vật liệu mới có khả năng lưu trữ năng lượng hiệu quả gấp 10 lần',
                'description' => 'Nhóm nghiên cứu quốc tế đã tổng hợp thành công vật liệu siêu tụ điện mới với mật độ năng lượng cao kỷ lục, hứa hẹn cách mạng hóa ngành pin và lưu trữ năng lượng.',
                'content' => 'Các nhà khoa học từ Đại học Stanford và MIT đã công bố một nghiên cứu đột phá về vật liệu graphene lai tạo có khả năng lưu trữ năng lượng gấp 10 lần so với pin lithium-ion hiện tại. Vật liệu mới này được tạo ra bằng cách kết hợp graphene với các hợp chất kim loại hiếm theo một cấu trúc nano đặc biệt. Điều đáng chú ý là vật liệu này không chỉ có mật độ năng lượng cao mà còn có khả năng sạc nhanh trong vòng vài phút và tuổi thọ lên đến 50.000 chu kỳ sạc.',
                'author' => 'GS. Trần Thị Bình',
                'source' => 'https://www.nature.com/nenergy',
                'image_url' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1200&q=80&auto=format&fit=crop',
                'published_date' => Carbon::now()->subDays(5),
                'category' => 'Vật liệu học',
            ],
            [
                'title' => 'Liệu pháp gen mới chữa khỏi hoàn toàn bệnh ung thư máu ở trẻ em',
                'description' => 'Thử nghiệm lâm sàng giai đoạn 3 cho thấy liệu pháp CAR-T thế hệ mới đạt tỷ lệ thành công 95% trong điều trị bạch cầu cấp tính ở trẻ em.',
                'content' => 'Một nghiên cứu đột phá được công bố trên tạp chí New England Journal of Medicine cho thấy liệu pháp gen CAR-T thế hệ mới đã chữa khỏi hoàn toàn bệnh ung thư máu cho 95% bệnh nhi tham gia thử nghiệm. Công nghệ CRISPR được sử dụng để tăng cường khả năng tồn tại của tế bào T và giảm tác dụng phụ.',
                'author' => 'PGS. Lê Minh Châu',
                'source' => 'https://www.nejm.org',
                'image_url' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1200&q=80&auto=format&fit=crop',
                'published_date' => Carbon::now()->subDays(7),
                'category' => 'Y học',
            ],
            [
                'title' => 'Khám phá hành tinh có khả năng sinh sống cách Trái Đất 40 năm ánh sáng',
                'description' => 'Kính viễn vọng James Webb phát hiện hành tinh ngoài hệ mặt trời có dấu hiệu của nước lỏng và khí quyển giống Trái Đất.',
                'content' => 'Kính viễn vọng James Webb phát hiện K2-18b nằm trong vùng có thể có sự sống. Phân tích quang phổ cho thấy sự hiện diện của hơi nước, methane và CO2. Đây là một trong các ứng viên hứa hẹn nhất cho việc tìm kiếm sự sống ngoài Trái Đất.',
                'author' => 'TS. Phạm Quốc Dũng',
                'source' => 'https://iopscience.iop.org/journal/0004-637X',
                'image_url' => 'https://images.unsplash.com/photo-1614730321146-b6fa6a46bcb4?w=1200&q=80&auto=format&fit=crop',
                'published_date' => Carbon::now()->subDays(10),
                'category' => 'Thiên văn học',
            ],
            [
                'title' => 'Robot nano y sinh có thể tiêu diệt tế bào ung thư từ bên trong',
                'description' => 'Các nhà khoa học đã phát triển robot nano di chuyển trong cơ thể, nhận diện và tiêu diệt tế bào ung thư mà không hại tế bào khỏe.',
                'content' => 'Nghiên cứu trên Science Robotics mô tả robot DNA origami kích thước nano, mang theo thuốc và giải phóng có điều kiện khi chạm tế bào đích. Thử nghiệm tiền lâm sàng cho thấy giảm kích thước khối u đáng kể mà ít tác dụng phụ.',
                'author' => 'GS. Hoàng Văn Tuấn',
                'source' => 'https://www.science.org/journal/scirobotics',
                'image_url' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=1200&q=80&auto=format&fit=crop',
                'published_date' => Carbon::now()->subDays(14),
                'category' => 'Công nghệ sinh học',
            ],
            [
                'title' => 'Thuật toán tối ưu lượng tử giúp giải bài toán logistics quy mô lớn',
                'description' => 'Một nhóm nghiên cứu đã trình diễn thuật toán lai lượng tử–cổ điển tối ưu tuyến đường vận tải với tốc độ vượt trội.',
                'content' => 'Thuật toán QAOA kết hợp heuristic cổ điển được áp dụng để giải bài toán Vehicle Routing Problem trên dữ liệu mô phỏng 10.000 điểm giao. Kết quả đạt chất lượng lời giải tương đương meta-heuristic tốt nhất hiện nay nhưng thời gian hội tụ nhanh hơn 4–6 lần trên bộ xử lý lượng tử noisy trung bình.',
                'author' => 'TS. Đỗ Quốc Hưng',
                'source' => 'https://arxiv.org',
                'image_url' => 'https://images.unsplash.com/photo-1518779578993-ec3579fee39f?w=1200&q=80&auto=format&fit=crop',
                'published_date' => Carbon::now()->subDays(16),
                'category' => 'Tính toán lượng tử',
            ],
            [
                'title' => 'Vaccine mRNA thế hệ mới ổn định ở nhiệt độ phòng 30 ngày',
                'description' => 'Cải tiến công thức hạt nano lipid giúp vaccine mRNA bền nhiệt, thuận tiện phân phối vùng sâu vùng xa.',
                'content' => 'Những thay đổi ở thành phần phospholipid và cholesterol tạo cấu trúc LNP ít nhạy cảm với thủy phân. Thử nghiệm lưu trữ 30 ngày ở 25°C cho thấy hiệu giá kháng thể duy trì trên 90% so với đối chứng bảo quản lạnh sâu.',
                'author' => 'GS. Nguyễn Hải Yến',
                'source' => 'https://www.science.org/journal/science',
                'image_url' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80&auto=format&fit=crop',
                'published_date' => Carbon::now()->subDays(18),
                'category' => 'Sinh học phân tử',
            ],
            [
                'title' => 'Vật liệu perovskite bền ẩm cho pin mặt trời hiệu suất 27,1%',
                'description' => 'Xử lý bề mặt bằng muối formamidinium iodide giúp giảm khuyết tật, nâng hiệu suất và độ bền perovskite.',
                'content' => 'Mẫu pin diện tích 1 cm² duy trì 95% hiệu suất sau 1.000 giờ trong môi trường 85% RH, 45°C. Kỹ thuật passivation này tương thích quy trình cuộn–ép, mở đường cho sản xuất quy mô lớn.',
                'author' => 'TS. Lâm Phương Nam',
                'source' => 'https://www.nature.com',
                'image_url' => 'https://images.unsplash.com/photo-1509395062183-67c5ad6faff9?w=1200&q=80&auto=format&fit=crop',
                'published_date' => Carbon::now()->subDays(20),
                'category' => 'Năng lượng tái tạo',
            ],
            [
                'title' => 'Mạng nơ-ron đồ thị dự báo đột biến protein gây bệnh hiếm',
                'description' => 'Mô hình GNN học cấu trúc 3D protein dự báo tính gây bệnh của đột biến điểm với AUC 0,93.',
                'content' => 'Bằng cách biểu diễn protein dưới dạng đồ thị atom–bond và tích hợp thông tin từ AlphaFold2, nhóm nghiên cứu huấn luyện GNN trên tập ClinVar và gỡ bỏ thiên lệch dữ liệu bằng kỹ thuật reweighting. Ứng dụng hỗ trợ nhà lâm sàng sàng lọc biến thể di truyền hiếm.',
                'author' => 'TS. Lý Trọng Nghĩa',
                'source' => 'https://www.cell.com',
                'image_url' => 'https://images.unsplash.com/photo-1581092921461-eab62e97a780?w=1200&q=80&auto=format&fit=crop',
                'published_date' => Carbon::now()->subDays(22),
                'category' => 'Tin-sinh học',
            ],
            [
                'title' => 'Sợi carbon tái chế từ rác thải composite đạt độ bền gần như vật liệu mới',
                'description' => 'Quy trình pyrolysis kiểm soát khí giúp tái chế sợi carbon từ cánh quạt gió đã qua sử dụng.',
                'content' => 'Nhóm nghiên cứu chứng minh mô-đun đàn hồi và độ bền kéo của sợi tái chế đạt 92–96% so với sợi nguyên sinh. Vật liệu được gia công thành tấm composite mới cho ngành ô tô với chi phí giảm 35%.',
                'author' => 'PGS. Võ Thanh Hòa',
                'source' => 'https://www.sciencedirect.com',
                'image_url' => 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?w=1200&q=80&auto=format&fit=crop',
                'published_date' => Carbon::now()->subDays(25),
                'category' => 'Kỹ thuật vật liệu',
            ],
        ];

        foreach ($scientificNews as $news) {
            ScientificNew::create($news);
        }
    }
}
