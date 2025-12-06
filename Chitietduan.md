# VLUTE Innovation Hub — Chi tiết dự án (2025)

Tài liệu tổng hợp kiến trúc, module tính năng, CSDL, routes, hướng dẫn cài đặt/vận hành, và các cập nhật mới (Bản tin Nghiên cứu Khoa học + 5 Tính năng AI).

---

## 1) Tổng quan dự án

-   Nền tảng: Laravel 12 (PHP >= 8.2)
-   Frontend: Blade + TailwindCSS + Vite
-   Auth: Laravel Breeze (Email verification), phân quyền theo Role + Policy
-   CSDL: MySQL 8 (docker-compose cung cấp service mysql, port host 3307 → container 3306)
-   AI: Tích hợp Google Gemini API cho 5 tính năng AI
-   Mục tiêu: Kết nối sinh viên, giảng viên/mentor, trung tâm ĐMST, BGH, doanh nghiệp; quản lý ý tưởng, cuộc thi, bản tin nghiên cứu khoa học, mời thành viên, phản biện, quản trị, và hỗ trợ AI.

Thư mục chính:

-   app/Http/Controllers: Controller cho public, nội bộ, admin, API (AI)
-   app/Models: Eloquent models (Idea, ScientificNew, Competition, ...)
-   app/Services: GeminiService (tích hợp Google Gemini API)
-   resources/views: Giao diện Blade (layouts, ideas, scientific-news, admin, ...)
-   database/migrations: Lược đồ CSDL
-   database/seeders: Dữ liệu mẫu, tài khoản quản trị
-   routes/web.php: Tuyến đường web
-   routes/api.php: API routes cho các tính năng AI

---

## 2) Module và tính năng chính

### 2.1) Ngân hàng Ý tưởng (Public)

-   Danh sách ý tưởng đã duyệt công khai, lọc theo Khoa/Lĩnh vực/Tìm kiếm
-   Trang chi tiết ý tưởng, hiển thị like, liên hệ, thành viên
-   Like ý tưởng (yêu cầu đăng nhập)

### 2.2) Ý tưởng của tôi (Sinh viên)

-   CRUD ý tưởng (nháp → nộp)
-   Mời thành viên qua email (IdeaInvitation)
-   Bình luận nội bộ team-only với mentor

### 2.3) Phản biện & Duyệt

-   Hàng chờ review (Trung tâm ĐMST, BGH)
-   Trạng thái: draft → submitted_center → approved_center → submitted_board → approved_final
-   Nhánh cần chỉnh sửa: needs_change_center / needs_change_board

### 2.4) Cuộc thi & Sự kiện

-   Danh sách, chi tiết, đăng ký, nộp bài
-   Khu "Cuộc thi của tôi" cho sinh viên

### 2.5) Bản tin Nghiên cứu Khoa học

-   Danh sách + lọc theo chủ đề + tìm kiếm (title/description/content)
-   Chi tiết bản tin, ảnh, ngày đăng, tác giả, nguồn; sidebar bản tin mới
-   Trang chủ hiển thị lưới 4 bản tin mới nhất

### 2.6) Tìm kiếm tổng hợp

-   Ô tìm kiếm trên header: route search.index

### 2.7) **5 Tính năng AI (Mới - Tích hợp Google Gemini)**

#### **Tính năng 1: Review Insight (Phân tích ý tưởng)**
- **Mục đích**: Giúp sinh viên và giám khảo phân tích ý tưởng một cách chuyên nghiệp
- **Cách hoạt động**: Sử dụng Gemini AI để phân tích nội dung ý tưởng và trả về:
  - Điểm mạnh của ý tưởng
  - Điểm yếu cần cải thiện
  - Tiềm năng phát triển
  - Đánh giá trên thang 10
- **Định dạng**: Markdown, dễ đọc
- **API Endpoint**: `POST /api/ai/review-insight`
- **Sử dụng**: Trong trang review ý tưởng, giúp người phản biện có nhận xét nhanh

#### **Tính năng 2: Vision (Phân tích hình ảnh)**
- **Mục đích**: Đánh giá chất lượng Poster, Slide, hoặc hình ảnh minh họa của ý tưởng
- **Cách hoạt động**: Sử dụng Gemini Vision để:
  - Đánh giá tính thẩm mỹ (màu sắc, bố cục, cân bằng)
  - Phân tích nội dung hiển thị trên hình ảnh
  - Đưa ra lời khuyên cải thiện ngắn gọn
- **API Endpoint**: `POST /api/ai/analyze-visual`
- **Input**: Upload hình ảnh (tối đa 5MB)
- **Sử dụng**: Giúp sinh viên cải thiện chất lượng trình bày

#### **Tính năng 3: Check Duplicate (Kiểm tra trùng lặp)**
- **Mục đích**: Phát hiện ý tưởng trùng lặp hoặc tương tự trong hệ thống
- **Cách hoạt động**: 
  - Tạo Vector Embedding cho ý tưởng hiện tại
  - So sánh với tất cả ý tưởng đã duyệt trong hệ thống
  - Sử dụng Cosine Similarity để tính độ phù hợp
  - Ngưỡng: >= 75% được coi là trùng lặp
- **API Endpoint**: `POST /api/ai/check-duplicate`
- **Output**: Danh sách ý tưởng tương tự (tối đa 3 kết quả) với % độ phù hợp
- **Sử dụng**: Khi sinh viên nộp ý tưởng mới, tự động kiểm tra trùng

#### **Tính năng 4: Suggest Tech Stack (Đề xuất kiến trúc công nghệ)**
- **Mục đích**: Giúp sinh viên chọn công nghệ phù hợp cho dự án của mình
- **Cách hoạt động**: 
  - Sinh viên nhập mô tả ý tưởng
  - Gemini AI (đóng vai CTO) phân tích và đề xuất:
    - Frontend: công nghệ + lý do
    - Backend: công nghệ + lý do
    - Database: công nghệ phù hợp
    - Mobile: nếu cần
    - Hardware: nếu là dự án IoT/Phần cứng
    - Lời khuyên triển khai
- **API Endpoint**: `POST /api/ai/suggest-tech-stack`
- **Output**: JSON với các công nghệ được đề xuất
- **Sử dụng**: Trong trang tạo ý tưởng, giúp sinh viên lựa chọn công nghệ

#### **Tính năng 5: Scout Solutions (Thợ săn giải pháp)**
- **Mục đích**: Giúp doanh nghiệp tìm ý tưởng phù hợp với nhu cầu kinh doanh
- **Cách hoạt động**:
  - Doanh nghiệp nhập vấn đề cần giải quyết
  - Hệ thống tạo Vector Embedding cho vấn đề
  - Quét toàn bộ kho ý tưởng đã duyệt công khai
  - So sánh độ phù hợp bằng Cosine Similarity
  - Ngưỡng: >= 65% được coi là phù hợp
- **API Endpoint**: `POST /api/ai/scout-solutions`
- **Output**: Top 5 ý tưởng phù hợp nhất với:
  - Tiêu đề ý tưởng
  - Tác giả
  - Mô tả ngắn
  - % độ phù hợp
- **Sử dụng**: Trang riêng cho doanh nghiệp (Enterprise Scout)

### 2.8) Quản trị (Admin)

-   Duyệt tài khoản, gán quyền
-   Quản lý taxonomies (Khoa, Lĩnh vực, Tags)
-   Quản lý Cuộc thi, Bản tin KH

---

## 3) Vai trò & phân quyền

Các vai trò tiêu biểu: student (SV), staff (Giảng viên/Mentor), center (Trung tâm ĐMST), board (BGH), enterprise (DN), admin.

-   Middleware: verified.to.login, approved.to.login, is.admin, role checks
-   Policies: ví dụ IdeaPolicy kiểm soát view/update/delete/approve
-   Menu header đã liên kết tới các trang công khai và theo phiên đăng nhập

---

## 4) Cấu hình môi trường

### 4.1) Cấu hình Database

Sử dụng MySQL trong docker-compose:

file.env.md (tham khảo):

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3307 # host port
DB_DATABASE=vlute_innovation_hub
DB_USERNAME=sail
DB_PASSWORD=password
FORWARD_DB_PORT=3307
MYSQL_EXTRA_OPTIONS=
```

docker-compose.yml (chính):

-   mysql: map 3307:3306, user: sail/password, db: vlute_innovation_hub
-   phpmyadmin: http://localhost:8081 (PMA_HOST=mysql, PMA_PORT=3306)

### 4.2) Cấu hình Google Gemini API

Thêm vào file `.env`:

```
GEMINI_API_KEY=your_gemini_api_key_here
```

**Cách lấy API Key:**
1. Truy cập https://aistudio.google.com/app/apikeys
2. Tạo API Key mới
3. Copy và paste vào file `.env`

**Lưu ý**: 
- API Key không được commit vào git
- Gemini API có giới hạn request miễn phí hàng tháng
- Các model được sử dụng: gemini-2.0-flash, gemini-1.5-flash, text-embedding-004

### 4.3) Cấu hình Mail

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@vlute.edu.vn
MAIL_FROM_NAME="${APP_NAME}"
```

Admin seeder sử dụng ENV:

-   ADMIN_EMAIL (mặc định: admin@vlute.edu.vn)
-   ADMIN_PASSWORD (mặc định: Admin@123)

---

## 5) Cài đặt & chạy

### 5.1) Khởi động MySQL:

```bash
docker compose up -d
```

### 5.2) Cài dự án:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Cập nhật biến DB_* trong .env theo docker-compose, và thêm GEMINI_API_KEY

### 5.3) Build frontend:

```bash
npm install
npm run build  # hoặc npm run dev khi phát triển
```

### 5.4) Migrate & Seed:

```bash
php artisan migrate
php artisan db:seed
```

DatabaseSeeder đã gọi: RolesSeeder, AdminUserSeeder, ApprovedUsersSeeder, FeaturedIdeasSeeder, CompetitionSeeder, ScientificNewsSeeder

### 5.5) Chạy server dev:

```bash
php artisan serve  # mặc định http://127.0.0.1:8000
```

Lưu ý: Nếu môi trường bị coi là production, thêm cờ --force cho migrate/seed.

---

## 6) Dữ liệu mẫu mặc định

-   Admin: theo ADMIN_EMAIL/ADMIN_PASSWORD
-   ApprovedUsersSeeder: tạo sẵn một số tài khoản/phân quyền được duyệt
-   Ý tưởng nổi bật, cuộc thi mẫu, 10 bản tin nghiên cứu khoa học mẫu
-   Embedding Vector: Tự động tạo cho các ý tưởng khi seed

---

## 7) Lược đồ CSDL chính (rút gọn)

### 7.1) Ý tưởng & phân loại:

-   ideas (title, slug, summary, content, faculty_id, category_id, status, visibility, owner_id, like_count, **embedding_vector**, ...)
-   idea_members (idea_id, user_id, role_in_team)
-   idea_invitations (idea_id, email, token, status, expires_at)
-   categories, faculties, tags, idea_tag
-   idea_likes (idea_id, user_id)

**Lưu ý**: Cột `embedding_vector` được thêm vào để lưu trữ vector từ Gemini Embedding, hỗ trợ tính năng Check Duplicate và Scout Solutions.

### 7.2) Phản biện & duyệt:

-   review_assignments (idea_id, reviewer_id, review_level, status)
-   reviews (assignment_id, decision, overall_comment)
-   change_requests (review_id, idea_id, request_message, is_resolved)

### 7.3) Cuộc thi:

-   competitions (title, slug, description, start_date, end_date, status, created_by)
-   competition_registrations (competition_id, user_id, status, team_name)
-   competition_submissions (registration_id, title, abstract, submitted_at)

### 7.4) Bản tin NCKH:

-   scientific_news: id, title, description, content, author, source, image_url, published_date(date), category, timestamps

### 7.5) Tài khoản & hồ sơ:

-   users (role, approval_status/is_approved, is_active, email_verified_at, ...)
-   roles, role_user
-   profiles, organizations, attachments

---

## 8) Tuyến đường quan trọng (routes)

### 8.1) Công khai:

-   GET / → WelcomeController@index (trang chủ; có lưới 4 bản tin NCKH mới nhất)
-   GET /ideas → PublicIdeaController@index (Ngân hàng ý tưởng, lọc/tìm)
-   GET /ideas/{slug} → PublicIdeaController@show
-   POST /ideas/{id}/like → PublicIdeaController@like (auth)
-   GET /competitions → CompetitionController@index; /competitions/{competition:slug} → show
-   GET /events → EventsController@index
-   GET /scientific-news → ScientificNewsController@index
-   GET /scientific-news/{news} → ScientificNewsController@show
-   GET /search → SearchController@index
-   GET /enterprise/scout → Enterprise Scout (tìm giải pháp)

### 8.2) Nội bộ (đăng nhập + verified + approved):

-   /dashboard
-   /my-ideas (MyIdeasController) CRUD + submit + invite + comments
-   /manage/review-queue, /manage/review/{idea} (Review*)

### 8.3) Admin (/admin, middleware is.admin):

-   AdminHomeController@index, Approvals, Users, Taxonomies, Ideas (actions)
-   Resource: competitions, news (bản tin KH)

### 8.4) API Routes (AI Features):

-   POST /api/ai/review-insight → Phân tích ý tưởng
-   POST /api/ai/analyze-visual → Phân tích hình ảnh
-   POST /api/ai/check-duplicate → Kiểm tra trùng lặp
-   POST /api/ai/suggest-tech-stack → Đề xuất công nghệ
-   POST /api/ai/scout-solutions → Tìm giải pháp cho doanh nghiệp
-   POST /api/test/gemini/text → Test Gemini Text API
-   POST /api/test/gemini/image → Test Gemini Vision API
-   GET /api/test/gemini/config → Kiểm tra cấu hình API

---

## 8.1) Đường dẫn đầy đủ (Localhost)

Gợi ý: thay {id}, {slug}, {idea}, {registration}, {token} bằng giá trị thực tế.
Cơ sở: http://localhost:8000 (có thể dùng http://127.0.0.1:8000 tương đương).

### 8.1.1) Trang chủ

-   http://localhost:8000/
-   Giới thiệu: http://localhost:8000/about

### 8.1.2) Ngân hàng Ý tưởng (Public)

-   Danh sách: http://localhost:8000/ideas
-   Chi tiết: http://localhost:8000/ideas/{slug}
-   Like (POST): http://localhost:8000/ideas/{id}/like

### 8.1.3) Bản tin Nghiên cứu Khoa học

-   Danh sách: http://localhost:8000/scientific-news
-   Chi tiết: http://localhost:8000/scientific-news/{id}

### 8.1.4) Cuộc thi & Sự kiện

-   Danh sách cuộc thi: http://localhost:8000/competitions
-   Chi tiết cuộc thi: http://localhost:8000/competitions/{slug}
-   Đăng ký (POST): http://localhost:8000/competitions/{id}/register
-   Trang Sự kiện: http://localhost:8000/events

### 8.1.5) Tìm kiếm

-   http://localhost:8000/search?q=tu+khoa

### 8.1.6) Tính năng AI (Enterprise Scout)

-   Trang thợ săn giải pháp: http://localhost:8000/enterprise/scout

### 8.1.7) Khu nội bộ (đăng nhập + xác minh + được duyệt)

-   Dashboard: http://localhost:8000/dashboard
-   Hồ sơ cá nhân: http://localhost:8000/profile
-   Ý tưởng của tôi:
    -   Danh sách: http://localhost:8000/my-ideas
    -   Tạo mới: http://localhost:8000/my-ideas/create
    -   Chi tiết: http://localhost:8000/my-ideas/{idea}
    -   Sửa: http://localhost:8000/my-ideas/{idea}/edit
-   Hàng chờ phản biện:
    -   Danh sách: http://localhost:8000/manage/review-queue
    -   Biểu mẫu: http://localhost:8000/manage/review/{idea}
-   Đính kèm (tải): http://localhost:8000/attachments/{id}/download
-   Dự án đang hướng dẫn (Giảng viên): http://localhost:8000/mentored-ideas

### 8.1.8) Cuộc thi của tôi (Sinh viên)

-   Danh sách: http://localhost:8000/my-competitions
-   Nộp bài: http://localhost:8000/my-competitions/{registration}/submit

### 8.1.9) Lời mời tham gia ý tưởng

-   Chấp nhận: http://localhost:8000/invitations/accept/{token}
-   Từ chối: http://localhost:8000/invitations/decline/{token}

### 8.1.10) Khu Admin (/admin)

-   Bảng quản trị: http://localhost:8000/admin
-   Duyệt tài khoản: http://localhost:8000/admin/approvals
-   Người dùng: http://localhost:8000/admin/users
-   Cuộc thi (resource):
    -   Danh sách: http://localhost:8000/admin/competitions
    -   Tạo mới: http://localhost:8000/admin/competitions/create
    -   Sửa: http://localhost:8000/admin/competitions/{id}/edit
-   Bản tin KH (resource):
    -   Danh sách: http://localhost:8000/admin/news
    -   Tạo mới: http://localhost:8000/admin/news/create
    -   Sửa: http://localhost:8000/admin/news/{id}/edit

### 8.1.11) Xác thực (Breeze)

-   Đăng nhập: http://localhost:8000/login
-   Đăng ký: http://localhost:8000/register
-   Quên mật khẩu: http://localhost:8000/forgot-password
-   Đặt lại mật khẩu: http://localhost:8000/reset-password
-   Xác minh email: http://localhost:8000/verify-email
-   Xác minh (link ký): http://localhost:8000/verify-email/{id}/{hash}
-   Gửi lại email xác minh (POST): http://localhost:8000/email/verification-notification
-   Đổi mật khẩu (trong phiên, PUT): http://localhost:8000/password
-   Xác nhận mật khẩu: http://localhost:8000/confirm-password
-   Đăng xuất (POST): http://localhost:8000/logout
-   Đăng xuất (GET fallback): http://localhost:8000/logout

---

## 9) Bản tin Nghiên cứu Khoa học — chi tiết triển khai

-   Model: app/Models/ScientificNew.php
-   Migration: database/migrations/2025_11_13_054340_create_scientific_news_table.php
-   Seeder: database/seeders/ScientificNewsSeeder.php (10 bài mẫu)
-   Controller: app/Http/Controllers/ScientificNewsController.php
    -   index: lọc tìm theo search/category, phân trang 12, trả về danh sách chủ đề (distinct category)
    -   show: trang chi tiết; sidebar hiển thị 5 bài mới
-   Views:
    -   resources/views/scientific-news/index.blade.php: lưới bài viết, ảnh, chủ đề, ngày, mô tả, nút "Đọc thêm/Nguồn"
    -   resources/views/scientific-news/show.blade.php: hero + nội dung + sidebar
-   Trang chủ:
    -   WelcomeController@index: lấy 4 bài mới nhất theo published_date/created_at
    -   resources/views/welcome.blade.php: mục "Bản tin Nghiên cứu Khoa học" hiển thị lưới 4 bài mới
-   Menu header đã có link tới /scientific-news

---

## 10) Tính năng AI — Chi tiết triển khai

### 10.1) Kiến trúc AI

-   **Service**: `app/Services/GeminiService.php`
    -   `generateText()`: Gọi Gemini API để tạo văn bản
    -   `analyzeImage()`: Gọi Gemini Vision để phân tích hình ảnh
    -   `generateEmbedding()`: Tạo Vector Embedding cho text
    -   `callApi()`: Hàm gọi API chung, xử lý lỗi

-   **Controller**: `app/Http/Controllers/Api/AIController.php`
    -   `reviewInsight()`: Phân tích ý tưởng
    -   `analyzeVisual()`: Phân tích hình ảnh
    -   `checkDuplicate()`: Kiểm tra trùng lặp
    -   `suggestTechStack()`: Đề xuất công nghệ
    -   `scoutSolutions()`: Tìm giải pháp
    -   `seedEmbeddings()`: Tool tạo vector cho các ý tưởng cũ

### 10.2) Cách sử dụng API AI

**Test Review Insight:**
```bash
curl -X POST http://localhost:8000/api/ai/review-insight \
  -H "Content-Type: application/json" \
  -d '{"content":"Ý tưởng của tôi là..."}'
```

**Test Vision:**
```bash
curl -X POST http://localhost:8000/api/ai/analyze-visual \
  -F "image=@/path/to/image.jpg"
```

**Test Check Duplicate:**
```bash
curl -X POST http://localhost:8000/api/ai/check-duplicate \
  -H "Content-Type: application/json" \
  -d '{"content":"Nội dung ý tưởng...","current_id":1}'
```

**Test Suggest Tech Stack:**
```bash
curl -X POST http://localhost:8000/api/ai/suggest-tech-stack \
  -H "Content-Type: application/json" \
  -d '{"content":"Ý tưởng của tôi là..."}'
```

**Test Scout Solutions:**
```bash
curl -X POST http://localhost:8000/api/ai/scout-solutions \
  -H "Content-Type: application/json" \
  -d '{"problem":"Chúng tôi cần giải pháp để..."}'
```

### 10.3) Tích hợp Frontend

Các tính năng AI được gọi từ frontend thông qua AJAX:

-   **Review Insight**: Trong trang review ý tưởng, nút "Phân tích AI"
-   **Vision**: Trong trang tạo/sửa ý tưởng, upload hình ảnh → tự động phân tích
-   **Check Duplicate**: Khi nộp ý tưởng, tự động kiểm tra
-   **Tech Stack**: Nút "Đề xuất công nghệ" trong trang tạo ý tưởng
-   **Scout Solutions**: Trang riêng `/enterprise/scout` cho doanh nghiệp

---

## 11) Quy ước trạng thái ý tưởng & hiển thị

Trạng thái: draft → submitted_center → approved_center → submitted_board → approved_final

-   Nhánh chỉnh sửa: needs_change_center / needs_change_board
-   Hiển thị công khai khi: visibility = public và status = approved_final

---

## 12) Hướng dẫn triển khai (Deployment)

-   Thiết lập biến môi trường .env (APP_ENV=production, APP_KEY, DB_*, MAIL_*, GEMINI_API_KEY)
-   php artisan migrate --force
-   php artisan db:seed --force (tùy chọn dữ liệu mẫu)
-   npm run build
-   php artisan config:cache && php artisan route:cache && php artisan view:cache
-   php artisan storage:link (nếu dùng upload)
-   Kiểm tra quyền ghi storage/ bootstrap/cache

---

## 13) Kiểm thử & chẩn đoán

-   php artisan test
-   Log: storage/logs/laravel.log
-   Kiểm tra health DB (docker compose ps; phpMyAdmin http://localhost:8081)
-   Kiểm tra API Gemini: GET /api/test/gemini/config

---

## 14) Checklist nhanh

-   [ ] docker compose up -d (MySQL, phpMyAdmin)
-   [ ] Cập nhật .env DB_* khớp docker-compose.yml
-   [ ] Thêm GEMINI_API_KEY vào .env
-   [ ] composer install; php artisan key:generate
-   [ ] php artisan migrate; php artisan db:seed
-   [ ] npm install; npm run dev (hoặc build)
-   [ ] Mở http://127.0.0.1:8000
-   [ ] Đăng nhập Admin (ADMIN_EMAIL/ADMIN_PASSWORD)
-   [ ] Kiểm tra: Ngân hàng Ý tưởng, Cuộc thi, Bản tin NCKH, Tìm kiếm
-   [ ] Test API AI: GET /api/test/gemini/config
-   [ ] Test tính năng AI: Review Insight, Vision, Check Duplicate, Tech Stack, Scout Solutions

---

## 15) Ghi chú

-   Tất cả dữ liệu nhạy cảm cần được cấu hình qua biến môi trường.
-   Khi seed trên môi trường production, luôn dùng cờ --force và cân nhắc dữ liệu.
-   API Key Gemini không được commit vào git, luôn sử dụng .env
-   Embedding Vector được lưu dưới dạng JSON trong cột `embedding_vector` của bảng `ideas`
-   Các model Gemini được sử dụng: gemini-2.0-flash (ưu tiên), gemini-1.5-flash (fallback), gemini-pro (fallback)
-   Cosine Similarity threshold: 75% cho Check Duplicate, 65% cho Scout Solutions
