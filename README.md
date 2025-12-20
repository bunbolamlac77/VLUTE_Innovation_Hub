# VLUTE Innovation Hub

Nền tảng phục vụ Đổi mới Sáng tạo tại Trường Đại học Sư phạm Kỹ thuật Vĩnh Long (VLUTE). Kết nối sinh viên – giảng viên (mentor) – doanh nghiệp/đối tác – trung tâm để hình thành, ươm tạo và triển khai ý tưởng. **Tích hợp 5 tính năng AI sử dụng Groq API và Google Gemini API.**

## 1) Kiến trúc & Công nghệ

- **Backend**: PHP 8.2+ · Laravel 12
- **Frontend**: Blade Templates · Vite
- **CSS Framework**: Tailwind CSS (100% utilities – xem thêm `TAILWIND.md`)
- **JavaScript**: Alpine.js
- **Database**: 
  - Mặc định: SQLite (development)
  - Production: MySQL 8.0 (Docker)
- **AI Services**: 
  - Groq API (Text, Vision) - Model: llama-3.1-70b-versatile
  - Google Gemini API (Embedding) - Model: text-embedding-004
  - OpenAI API (Embedding fallback) - Model: text-embedding-3-small

## 2) Tính năng chính

### 2.1) Công khai

- Trang chủ với số liệu tổng quan động (ý tưởng công khai đã duyệt, mentor, đối tác, cuộc thi đang mở)
- Ngân hàng ý tưởng (danh sách/chi tiết theo slug, like cần đăng nhập, bình luận công khai)
- Cuộc thi & sự kiện (danh sách/chi tiết, đăng ký)
- Thử thách (Challenges) - Doanh nghiệp đăng thử thách, sinh viên nộp giải pháp
- Bản tin Nghiên cứu Khoa học (route: `scientific-news.index`)
- Tìm kiếm tổng hợp

### 2.2) Đã đăng nhập

- Hồ sơ cá nhân (avatar, thông tin cơ bản; kiểm tra hoàn thiện hồ sơ)
- Ý tưởng của tôi (tạo/cập nhật/xoá, mời thành viên, nhận xét nội bộ team-only, nộp duyệt)
- Đăng ký cuộc thi, nộp bài
- Nộp giải pháp cho thử thách từ doanh nghiệp
- Xem dự án đang hướng dẫn (cho giảng viên)
- Quản lý thử thách (cho doanh nghiệp)

### 2.3) Quản trị

- Phê duyệt tài khoản; Khoá/Mở khoá
- Phân quyền/đổi vai (student/staff/center/board/reviewer/admin)
- Quản trị Phân loại: Khoa, Danh mục, Thẻ
- Gán người phản biện (reviewer), đổi trạng thái ý tưởng
- Quản lý Cuộc thi, Thử thách, Bản tin NCKH, Banner

### 2.4) **5 Tính năng AI**

#### **1. Review Insight — Phân tích ý tưởng**
Sử dụng Groq AI để phân tích nội dung ý tưởng và cung cấp đánh giá chuyên nghiệp:
- Điểm mạnh (Strengths)
- Điểm yếu & Rủi ro (Weaknesses & Risks)
- Tiềm năng thị trường (Market Potential)
- Khả thi công nghệ (Technical Feasibility)
- Điểm số tổng thể trên thang 50

**Sử dụng**: Giúp sinh viên và giám khảo có nhận xét chuyên nghiệp về ý tưởng.

**API Endpoint**: `POST /ai/review-insight`

#### **2. Vision — Phân tích hình ảnh**
Đánh giá chất lượng Poster, Slide hoặc hình ảnh minh họa:
- Đánh giá tính thẩm mỹ (màu sắc, bố cục, typography)
- Phân tích nội dung hiển thị
- Đánh giá hiệu quả truyền đạt
- Lời khuyên cải thiện (5-7 điểm cụ thể)
- Điểm số trên thang 30

**Sử dụng**: Giúp sinh viên cải thiện chất lượng trình bày ý tưởng.

**API Endpoint**: `POST /ai/vision`

#### **3. Check Duplicate — Kiểm tra trùng lặp**
Phát hiện ý tưởng trùng lặp hoặc tương tự:
- Tạo Vector Embedding cho ý tưởng (Gemini/OpenAI)
- So sánh với kho ý tưởng đã duyệt
- Sử dụng Cosine Similarity (ngưỡng: 85%)
- Trả về danh sách top 3 ý tưởng tương tự

**Sử dụng**: Tự động kiểm tra khi sinh viên nộp ý tưởng mới.

**API Endpoint**: `POST /ai/check-duplicate`

**Lưu ý**: Yêu cầu `GEMINI_API_KEY` hoặc `OPENAI_API_KEY` (Groq không hỗ trợ embedding).

#### **4. Suggest Tech Stack — Đề xuất công nghệ**
Giúp sinh viên chọn công nghệ phù hợp:
- Frontend: công nghệ + lý do
- Backend: công nghệ + lý do
- Database: công nghệ
- Mobile: nếu cần
- Hardware: nếu là dự án IoT
- Lời khuyên triển khai

**Sử dụng**: Trong trang tạo ý tưởng, giúp sinh viên lựa chọn công nghệ.

**API Endpoint**: `POST /ai/suggest-tech`

#### **5. Scout Solutions — Thợ săn giải pháp**
Giúp doanh nghiệp tìm ý tưởng phù hợp:
- Doanh nghiệp nhập vấn đề cần giải quyết
- Hệ thống tìm kiếm ngữ nghĩa (Semantic Search)
- Trả về Top 5 ý tưởng phù hợp nhất
- Hiển thị % độ phù hợp (ngưỡng: 65%)

**Sử dụng**: Trang riêng `/enterprise/scout` cho doanh nghiệp tìm giải pháp.

**API Endpoint**: `POST /ai/scout-solutions`

---

## 3) Cài đặt nhanh

### Yêu cầu

- PHP ≥ 8.2, Composer
- Node ≥ 18, npm
- SQLite (mặc định) hoặc MySQL Docker (tùy chọn)
- **Groq API Key** (để sử dụng tính năng AI Text/Vision)
- **Google Gemini API Key** hoặc **OpenAI API Key** (để sử dụng tính năng Embedding)

### Bước 1. Cài dependencies

```bash
composer install
npm install
```

### Bước 2. Tạo .env và khoá ứng dụng

```bash
cp .env.example .env  # nếu .env chưa có
php artisan key:generate
```

### Bước 3. Chọn DB

**Mặc định (khuyên dùng khi dev)**: SQLite

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

```bash
touch database/database.sqlite
```

**Tuỳ chọn MySQL Docker** (docker-compose.yml có sẵn)

```bash
docker compose up -d mysql
```

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=vlute_innovation_hub
DB_USERNAME=sail
DB_PASSWORD=password
```

### Bước 4. Cấu hình AI API Keys

Thêm vào `.env`:

```env
# Groq API (bắt buộc cho Text/Vision)
GROQ_API_KEY=your_groq_api_key_here
GROQ_MODEL=llama-3.1-70b-versatile

# Gemini API (khuyến nghị cho Embedding)
GEMINI_API_KEY=your_gemini_api_key_here

# Hoặc OpenAI API (fallback cho Embedding)
OPENAI_API_KEY=your_openai_api_key_here
```

**Cách lấy API Keys:**
1. **Groq**: https://console.groq.com/keys
2. **Gemini**: https://aistudio.google.com/app/apikeys
3. **OpenAI**: https://platform.openai.com/api-keys

**Lưu ý**: 
- Groq API có giới hạn request miễn phí hàng ngày
- Gemini API có giới hạn request miễn phí hàng tháng
- Không commit API Keys vào git

### Bước 5. Migrate & seed

```bash
php artisan migrate --seed
```

Seeder sẽ tạo:

- Roles mặc định (student, staff, center, board, enterprise, reviewer, admin)
- Tài khoản Admin (email: `env(ADMIN_EMAIL,'admin@vlute.edu.vn')`, mật khẩu: `env(ADMIN_PASSWORD,'Admin@123')`)
- Tài khoản mẫu đã duyệt:
  - 15 tài khoản sinh viên (mật khẩu: `Password@123`)
  - 5 giảng viên theo 5 khoa (mật khẩu: `Password@123`)
  - Trung tâm ĐMST, Ban giám hiệu, Doanh nghiệp (mật khẩu: `Password@123`)
- Dữ liệu mẫu:
  - Mỗi tài khoản sinh viên có **3 ý tưởng công khai, đã duyệt**
  - **10+ cuộc thi** mẫu
  - **10+ thử thách (challenges)** mẫu
  - **10 bản tin nghiên cứu khoa học**
- Embedding Vector cho các ý tưởng mẫu (nếu GEMINI_API_KEY hoặc OPENAI_API_KEY được cấu hình)

### Bước 6. Chạy ứng dụng

**Dev 2 cửa sổ:**

```bash
php artisan serve         # http://127.0.0.1:8000
npm run dev               # Vite dev server
```

**Hoặc build production:**

```bash
npm run build
```

---

## 4) Tailwind CSS

- Dự án đã chuyển hoàn toàn sang Tailwind utilities.
- `resources/css/app.css` chỉ giữ @layer base/components cho các tinh chỉnh nhịp layout và lớp tương thích tạm thời cho các trang admin cũ.
- Token (màu/đổ bóng/radius/container) đã cấu hình trong `tailwind.config.js`.
- Tài liệu nội bộ: xem `TAILWIND.md`.

---

## 5) Số liệu động trên trang chủ

Controller: `App\Http\Controllers\WelcomeController@index`

- Ý tưởng đã nộp (`$ideaCount`): `Idea::publicApproved()->count()`
- Mentor (`$mentorCount`): người dùng có `role = 'staff'` hoặc trong pivot `roles.slug in ['staff','reviewer']`
- Đối tác (`$partnerCount`): `Organization::count()` nếu có dữ liệu; nếu chưa thì mặc định 13
- Cuộc thi đang mở (`$openCompetitionsCount`): `Competition::where('status','open')->where(end_date>now or null)`

> Lưu ý: Các số hiển thị khác (ví dụ khối Counters) cũng đã bind bằng các biến này.

---

## 6) Luồng xác thực & phê duyệt

Middleware:

- `auth`: yêu cầu đăng nhập
- `verified.to.login`: yêu cầu xác thực email
- `approved.to.login`: yêu cầu admin phê duyệt (đối với một số vai)
- `is.admin`: chỉ cho phép admin

Trang đăng nhập/đăng ký/đặt lại mật khẩu/verify đã được làm lại bằng Tailwind utilities, có modal thông báo cho trường hợp chưa verified/approved.

---

## 7) Lược đồ CSDL chính

- `users`, `roles`, `role_user` (pivot)
- `ideas`, `idea_members`, `idea_invitations`, `idea_likes`, `attachments`
  - **Mới**: Cột `embedding_vector` (JSON) để lưu trữ Vector từ Gemini/OpenAI
- `reviews`, `review_assignments`, `change_requests`
- `faculties`, `categories`, `tags`, `idea_tag`
- `competitions`, `competition_registrations`, `competition_submissions`
- `challenges`, `challenge_submissions` (Thử thách từ doanh nghiệp)
- (Tuỳ chọn) `organizations` cho đối tác
- `scientific_news` (Bản tin Nghiên cứu Khoa học)
- `banners` (Banner quảng cáo)

---

## 8) Tuyến (routes) tiêu biểu

### 8.1) Công khai

- `/` Trang chủ (welcome)
- `/ideas`, `/ideas/{slug}` Ngân hàng ý tưởng
- `/events` & `/competitions` (danh sách/chi tiết)
- `/challenges` Thử thách từ doanh nghiệp (danh sách/chi tiết)
- `/scientific-news` Bản tin Nghiên cứu
- `/enterprise/scout` **Thợ săn giải pháp (AI)**

### 8.2) Authenticated

- `/dashboard`, `/profile`
- `/my-ideas/*` (CRUD ý tưởng, mời, nộp duyệt)
- `/my-competitions/*` (đăng ký & nộp bài)
- `/challenges/{challenge}/submit` (nộp giải pháp cho thử thách)
- `/manage/review-queue` (hàng chờ phản biện)
- `/mentored-ideas` (dự án đang hướng dẫn - cho giảng viên)
- `/enterprise/*` (quản lý thử thách - cho doanh nghiệp)

### 8.3) Admin (đã login + verified + approved + is.admin)

- `/admin` – một trang nhiều tab (approvals, users, ideas, taxonomies, logs)

### 8.4) API Routes (AI Features)

**Authenticated Routes** (yêu cầu đăng nhập):
- `POST /ai/review-insight` – Phân tích ý tưởng
- `POST /ai/vision` – Phân tích hình ảnh
- `POST /ai/check-duplicate` – Kiểm tra trùng lặp
- `POST /ai/suggest-tech` – Đề xuất công nghệ
- `POST /ai/scout-solutions` – Tìm giải pháp
- `GET /ai/seed` – Tạo embedding vector cho ý tưởng

**Public Test Routes** (không cần đăng nhập):
- `POST /api/test/groq/text` – Test Groq Text API
- `POST /api/test/groq/image` – Test Groq Vision API
- `GET /api/test/groq/config` – Kiểm tra cấu hình API

---

## 9) Email

Cấu hình trong `.env` (ví dụ với Mailtrap):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@vlute.edu.vn
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 10) Scripts tiện ích

```bash
# Thiết lập nhanh (gợi ý):
composer install && npm install && php artisan key:generate && php artisan migrate --seed && npm run dev

# Dọn cache
php artisan cache:clear && php artisan config:clear && php artisan view:clear

# Format code (Pint)
./vendor/bin/pint

# Test API Groq
curl http://localhost:8000/api/test/groq/config
```

---

## 11) Ghi chú phát triển

- Nếu triển khai dưới subpath, dùng `asset('...')` cho ảnh nền trong Blade (đã áp dụng ở hero) thay vì bg-[url(...)] để tránh lỗi đường dẫn.
- Một số trang quản trị còn dùng lớp tương thích `.card/.btn/.tbl…` trong `app.css`. Khi refactor hoàn tất admin sang utilities thuần, có thể gỡ bỏ các lớp tương thích này.
- **AI Features**: 
  - Embedding Vector được lưu dưới dạng JSON trong cột `embedding_vector` của bảng `ideas`
  - Groq Model: llama-3.1-70b-versatile (Text/Vision)
  - Gemini Model: text-embedding-004 (Embedding, 768 dimensions)
  - OpenAI Model: text-embedding-3-small (Embedding fallback, 1536 dimensions)
  - Cosine Similarity threshold: 85% cho Check Duplicate, 65% cho Scout Solutions
  - Tất cả API AI yêu cầu authentication (trừ test routes)

---

## 12) Troubleshooting

### Lỗi API Groq/Gemini

**Lỗi 404**: API Key không hợp lệ hoặc model không tồn tại
- Kiểm tra `GROQ_API_KEY`, `GEMINI_API_KEY` trong `.env`
- Truy cập console tương ứng để xác nhận API Key

**Lỗi 429**: Quá nhiều yêu cầu
- Chờ một lúc rồi thử lại
- Kiểm tra giới hạn request của Groq/Gemini API

**Lỗi Embedding Vector**:
- Chạy `php artisan tinker` rồi gọi `app(\App\Http\Controllers\Api\AIController::class)->seedEmbeddings()`
- Hoặc chạy seeder lại: `php artisan db:seed --class=SampleIdeaSeeder`

---

## 13) Liên hệ & Hỗ trợ

**Liên hệ**: spktvl@vlute.edu.vn · Website: https://vlute.edu.vn · Địa chỉ: Số 73 Nguyễn Huệ, P. Long Châu, TP. Vĩnh Long

**Phát triển bởi**: Sinh viên Khoa Khoa học Máy tính – VLUTE

**Tính năng AI**: Tích hợp Groq API (Text/Vision) và Google Gemini API / OpenAI API (Embedding)
