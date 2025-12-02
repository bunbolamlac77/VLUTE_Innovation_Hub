# VLUTE Innovation Hub — Chi tiết dự án (2025)

Tài liệu tổng hợp kiến trúc, module tính năng, CSDL, routes, hướng dẫn cài đặt/vận hành, và các cập nhật mới (Bản tin Nghiên cứu Khoa học).

---

## 1) Tổng quan dự án

-   Nền tảng: Laravel 12 (PHP >= 8.2)
-   Frontend: Blade + TailwindCSS + Vite
-   Auth: Laravel Breeze (Email verification), phân quyền theo Role + Policy
-   CSDL: MySQL 8 (docker-compose cung cấp service mysql, port host 3307 → container 3306)
-   Mục tiêu: Kết nối sinh viên, giảng viên/mentor, trung tâm ĐMST, BGH, doanh nghiệp; quản lý ý tưởng, cuộc thi, bản tin nghiên cứu khoa học, mời thành viên, phản biện, quản trị.

Thư mục chính:

-   app/Http/Controllers: Controller cho public, nội bộ, admin
-   app/Models: Eloquent models (Idea, ScientificNew, Competition, ...)
-   resources/views: Giao diện Blade (layouts, ideas, scientific-news, admin, ...)
-   database/migrations: Lược đồ CSDL
-   database/seeders: Dữ liệu mẫu, tài khoản quản trị
-   routes/web.php: Tuyến đường web

---

## 2) Module và tính năng chính

1. Ngân hàng Ý tưởng (Public)

-   Danh sách ý tưởng đã duyệt công khai, lọc theo Khoa/Lĩnh vực/Tìm kiếm
-   Trang chi tiết ý tưởng, hiển thị like, liên hệ, thành viên
-   Like ý tưởng (yêu cầu đăng nhập)

2. Ý tưởng của tôi (Sinh viên)

-   CRUD ý tưởng (nháp → nộp)
-   Mời thành viên qua email (IdeaInvitation)
-   Bình luận nội bộ team-only với mentor

3. Phản biện & Duyệt

-   Hàng chờ review (Trung tâm ĐMST, BGH)
-   Trạng thái: draft → submitted_center → approved_center → submitted_board → approved_final
-   Nhánh cần chỉnh sửa: needs_change_center / needs_change_board

4. Cuộc thi & Sự kiện

-   Danh sách, chi tiết, đăng ký, nộp bài
-   Khu “Cuộc thi của tôi” cho sinh viên

5. Bản tin Nghiên cứu Khoa học (Mới)

-   Danh sách + lọc theo chủ đề + tìm kiếm (title/description/content)
-   Chi tiết bản tin, ảnh, ngày đăng, tác giả, nguồn; sidebar bản tin mới
-   Trang chủ hiển thị lưới 4 bản tin mới nhất

6. Tìm kiếm tổng hợp

-   Ô tìm kiếm trên header: route search.index

7. Quản trị (Admin)

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

Mail (ví dụ, cần chỉnh lại theo thực tế):

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

1. Khởi động MySQL:

-   docker compose up -d

2. Cài dự án:

-   composer install
-   cp .env.example .env (hoặc đảm bảo .env tồn tại)
-   php artisan key:generate
-   Cập nhật biến DB\_ trong .env theo docker-compose

3. Build frontend:

-   npm install
-   npm run build (hoặc npm run dev khi phát triển)

4. Migrate & Seed:

-   php artisan migrate
-   php artisan db:seed
    -   DatabaseSeeder đã gọi: RolesSeeder, AdminUserSeeder, ApprovedUsersSeeder, FeaturedIdeasSeeder, CompetitionSeeder, ScientificNewsSeeder

5. Chạy server dev:

-   php artisan serve (mặc định http://127.0.0.1:8000)

Lưu ý: Nếu môi trường bị coi là production, thêm cờ --force cho migrate/seed.

---

## 6) Dữ liệu mẫu mặc định

-   Admin: theo ADMIN_EMAIL/ADMIN_PASSWORD
-   ApprovedUsersSeeder: tạo sẵn một số tài khoản/phân quyền được duyệt
-   Ý tưởng nổi bật, cuộc thi mẫu, 10 bản tin nghiên cứu khoa học mẫu

---

## 7) Lược đồ CSDL chính (rút gọn)

Ý tưởng & phân loại:

-   ideas (title, slug, summary, content, faculty_id, category_id, status, visibility, owner_id, like_count, ...)
-   idea_members (idea_id, user_id, role_in_team)
-   idea_invitations (idea_id, email, token, status, expires_at)
-   categories, faculties, tags, idea_tag
-   idea_likes (idea_id, user_id)

Phản biện & duyệt:

-   review_assignments (idea_id, reviewer_id, review_level, status)
-   reviews (assignment_id, decision, overall_comment)
-   change_requests (review_id, idea_id, request_message, is_resolved)

Cuộc thi:

-   competitions (title, slug, description, start_date, end_date, status, created_by)
-   competition_registrations (competition_id, user_id, status, team_name)
-   competition_submissions (registration_id, title, abstract, submitted_at)

Bản tin NCKH (mới):

-   scientific_news: id, title, description, content, author, source, image_url, published_date(date), category, timestamps

Tài khoản & hồ sơ:

-   users (role, approval_status/is_approved, is_active, email_verified_at, ...)
-   roles, role_user
-   profiles, organizations, attachments

---

## 8) Tuyến đường quan trọng (routes)

Công khai:

-   GET / → WelcomeController@index (trang chủ; có lưới 4 bản tin NCKH mới nhất)
-   GET /ideas → PublicIdeaController@index (Ngân hàng ý tưởng, lọc/tìm)
-   GET /ideas/{slug} → PublicIdeaController@show
-   POST /ideas/{id}/like → PublicIdeaController@like (auth)
-   GET /competitions → CompetitionController@index; /competitions/{competition:slug} → show
-   GET /events → EventsController@index
-   GET /scientific-news → ScientificNewsController@index
-   GET /scientific-news/{news} → ScientificNewsController@show
-   GET /search → SearchController@index

Nội bộ (đăng nhập + verified + approved):

-   /dashboard
-   /my-ideas (MyIdeasController) CRUD + submit + invite + comments
-   /manage/review-queue, /manage/review/{idea} (Review\*)

Admin (/admin, middleware is.admin):

-   AdminHomeController@index, Approvals, Users, Taxonomies, Ideas (actions),
-   Resource: competitions, news (bản tin KH)

---

## 8.1) Đường dẫn đầy đủ (Localhost)

Gợi ý: thay {id}, {slug}, {idea}, {registration}, {token} bằng giá trị thực tế.
Cơ sở: http://localhost:8000 (có thể dùng http://127.0.0.1:8000 tương đương).

-   Trang chủ

    -   http://localhost:8000/
    -   Giới thiệu: http://localhost:8000/about

-   Ngân hàng Ý tưởng (Public)

    -   Danh sách: http://localhost:8000/ideas
    -   Chi tiết: http://localhost:8000/ideas/{slug}
    -   Like (POST): http://localhost:8000/ideas/{id}/like

-   Bản tin Nghiên cứu Khoa học

    -   Danh sách: http://localhost:8000/scientific-news
    -   Chi tiết: http://localhost:8000/scientific-news/{id}

-   Cuộc thi & Sự kiện

    -   Danh sách cuộc thi: http://localhost:8000/competitions
    -   Chi tiết cuộc thi: http://localhost:8000/competitions/{slug}
    -   Đăng ký (POST): http://localhost:8000/competitions/{id}/register
    -   Trang Sự kiện: http://localhost:8000/events

-   Tìm kiếm

    -   http://localhost:8000/search?q=tu+khoa

-   Khu nội bộ (đăng nhập + xác minh + được duyệt)

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

-   Cuộc thi của tôi (Sinh viên)

    -   Danh sách: http://localhost:8000/my-competitions
    -   Nộp bài: http://localhost:8000/my-competitions/{registration}/submit

-   Lời mời tham gia ý tưởng

    -   Chấp nhận: http://localhost:8000/invitations/accept/{token}
    -   Từ chối: http://localhost:8000/invitations/decline/{token}

-   Khu Admin (/admin)

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

-   Xác thực (Breeze)
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

## 9) Bản tin Nghiên cứu Khoa học — chi tiết triển khai

-   Model: app/Models/ScientificNew.php
-   Migration: database/migrations/2025_11_13_054340_create_scientific_news_table.php
-   Seeder: database/seeders/ScientificNewsSeeder.php (10 bài mẫu)
-   Controller: app/Http/Controllers/ScientificNewsController.php
    -   index: lọc tìm theo search/category, phân trang 12, trả về danh sách chủ đề (distinct category)
    -   show: trang chi tiết; sidebar hiển thị 5 bài mới
-   Views:
    -   resources/views/scientific-news/index.blade.php: lưới bài viết, ảnh, chủ đề, ngày, mô tả, nút “Đọc thêm/Nguồn”
    -   resources/views/scientific-news/show.blade.php: hero + nội dung + sidebar
-   Trang chủ:
    -   WelcomeController@index: lấy 4 bài mới nhất theo published_date/created_at
    -   resources/views/welcome.blade.php: mục “Bản tin Nghiên cứu Khoa học” hiển thị lưới 4 bài mới
-   Menu header đã có link tới /scientific-news

---

## 10) Quy ước trạng thái ý tưởng & hiển thị

Trạng thái: draft → submitted_center → approved_center → submitted_board → approved_final

-   Nhánh chỉnh sửa: needs_change_center / needs_change_board
    Hiển thị công khai khi: visibility = public và status = approved_final

---

## 11) Hướng dẫn triển khai (Deployment)

-   Thiết lập biến môi trường .env (APP*ENV=production, APP_KEY, DB*\_, MAIL\_\_)
-   php artisan migrate --force
-   php artisan db:seed --force (tùy chọn dữ liệu mẫu)
-   npm run build
-   php artisan config:cache && php artisan route:cache && php artisan view:cache
-   php artisan storage:link (nếu dùng upload)
-   Kiểm tra quyền ghi storage/ bootstrap/cache

---

## 12) Kiểm thử & chẩn đoán

-   php artisan test
-   Log: storage/logs/laravel.log
-   Kiểm tra health DB (docker compose ps; phpMyAdmin http://localhost:8081)

---

## 13) Checklist nhanh

-   [ ] docker compose up -d (MySQL, phpMyAdmin)
-   [ ] Cập nhật .env DB\_\* khớp docker-compose.yml
-   [ ] composer install; php artisan key:generate
-   [ ] php artisan migrate; php artisan db:seed
-   [ ] npm install; npm run dev (hoặc build)
-   [ ] Mở http://127.0.0.1:8000
-   [ ] Đăng nhập Admin (ADMIN_EMAIL/ADMIN_PASSWORD)
-   [ ] Kiểm tra: Ngân hàng Ý tưởng, Cuộc thi, Bản tin NCKH, Tìm kiếm

---

Ghi chú:

-   Tất cả dữ liệu nhạy cảm cần được cấu hình qua biến môi trường.
-   Khi seed trên môi trường production, luôn dùng cờ --force và cân nhắc dữ liệu.
