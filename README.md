# VLUTE Innovation Hub

Nền tảng phục vụ Đổi mới Sáng tạo tại Trường Đại học Sư phạm Kỹ thuật Vĩnh Long (VLUTE). Kết nối sinh viên – giảng viên (mentor) – doanh nghiệp/đối tác – trung tâm để hình thành, ươm tạo và triển khai ý tưởng.

## 1) Kiến trúc & Công nghệ

-   PHP 8.2+ · Laravel 12
-   View: Blade · Build: Vite
-   CSS: Tailwind CSS (100% utilities – xem thêm TAILWIND.md)
-   JS nhẹ: Alpine.js
-   DB mặc định: SQLite (có tuỳ chọn MySQL Docker)

## 2) Tính năng chính

Công khai

-   Trang chủ với số liệu tổng quan động (ý tưởng công khai đã duyệt, mentor, đối tác, cuộc thi đang mở)
-   Ngân hàng ý tưởng (danh sách/chi tiết theo slug, like cần đăng nhập)
-   Cuộc thi & sự kiện (danh sách/chi tiết, đăng ký)
-   Bản tin Nghiên cứu Khoa học (route: `scientific-news.index`)

Đã đăng nhập

-   Hồ sơ cá nhân (avatar, thông tin cơ bản; kiểm tra hoàn thiện hồ sơ)
-   Ý tưởng của tôi (tạo/cập nhật/xoá, mời thành viên, nhận xét nội bộ, nộp duyệt)
-   Đăng ký cuộc thi, nộp bài

Quản trị

-   Phê duyệt tài khoản; Khoá/Mở khoá
-   Phân quyền/đổi vai (student/staff/center/board/reviewer/admin)
-   Quản trị Phân loại: Khoa, Danh mục, Thẻ
-   Gán người phản biện (reviewer), đổi trạng thái ý tưởng

## 3) Cài đặt nhanh

### Yêu cầu

-   PHP ≥ 8.2, Composer
-   Node ≥ 18, npm
-   SQLite (mặc định) hoặc MySQL Docker (tùy chọn)

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

-   Mặc định (khuyên dùng khi dev): SQLite

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

```bash
touch database/database.sqlite
```

-   Tuỳ chọn MySQL Docker (docker-compose.yml có sẵn)

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

### Bước 4. Migrate & seed

```bash
php artisan migrate --seed
```

Seeder sẽ tạo:

-   Roles mặc định
-   Tài khoản Admin (email: `env(ADMIN_EMAIL,'admin@vlute.edu.vn')`, mật khẩu: `env(ADMIN_PASSWORD,'Admin@123')`)

### Bước 5. Chạy ứng dụng

-   Dev 2 cửa sổ:

```bash
php artisan serve         # http://127.0.0.1:8000
npm run dev               # Vite dev server
```

-   Hoặc build production:

```bash
npm run build
```

## 4) Tailwind CSS

-   Dự án đã chuyển hoàn toàn sang Tailwind utilities.
-   `resources/css/app.css` chỉ giữ @layer base/components cho các tinh chỉnh nhịp layout và lớp tương thích tạm thời cho các trang admin cũ.
-   Token (màu/đổ bóng/radius/container) đã cấu hình trong `tailwind.config.js`.
-   Tài liệu nội bộ: xem `TAILWIND.md`.

## 5) Số liệu động trên trang chủ

Controller: `App\Http\Controllers\WelcomeController@index`

-   Ý tưởng đã nộp (`$ideaCount`): `Idea::publicApproved()->count()`
-   Mentor (`$mentorCount`): người dùng có `role = 'staff'` hoặc trong pivot `roles.slug in ['staff','reviewer']`
-   Đối tác (`$partnerCount`): `Organization::count()` nếu có dữ liệu; nếu chưa thì mặc định 13 (bằng số logo đang hiển thị)
-   Cuộc thi đang mở (`$openCompetitionsCount`): `Competition::where('status','open')->where(end_date>now or null)`

> Lưu ý: Các số hiển thị khác (ví dụ khối Counters) cũng đã bind bằng các biến này.

## 6) Luồng xác thực & phê duyệt

Middleware:

-   `auth`: yêu cầu đăng nhập
-   `verified.to.login`: yêu cầu xác thực email
-   `approved.to.login`: yêu cầu admin phê duyệt (đối với một số vai)
-   `is.admin`: chỉ cho phép admin

Trang đăng nhập/đăng ký/đặt lại mật khẩu/verify đã được làm lại bằng Tailwind utilities, có modal thông báo cho trường hợp chưa verified/approved.

## 7) Lược đồ CSDL chính

-   `users`, `roles`, `role_user` (pivot)
-   `ideas`, `idea_members`, `idea_invitations`, `idea_likes`, `attachments`
-   `reviews`, `review_assignments`, `change_requests`
-   `faculties`, `categories`, `tags`, `idea_tag`
-   `competitions`, `competition_registrations`, `competition_submissions`
-   (Tuỳ chọn) `organizations` cho đối tác

## 8) Tuyến (routes) tiêu biểu

Công khai

-   `/` Trang chủ (welcome)
-   `/ideas`, `/ideas/{slug}` Ngân hàng ý tưởng
-   `/events` & `/competitions` (danh sách/chi tiết)
-   `/scientific-news` Bản tin Nghiên cứu

Authenticated

-   `/dashboard`, `/profile`
-   `/my-ideas/*` (CRUD ý tưởng, mời, nộp duyệt)
-   `/my-competitions/*` (đăng ký & nộp bài)

Admin (đã login + verified + approved + is.admin)

-   `/admin` – một trang nhiều tab (approvals, users, ideas, taxonomies, logs)

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

## 10) Scripts tiện ích

```bash
# Thiết lập nhanh (gợi ý):
composer install && npm install && php artisan key:generate && php artisan migrate --seed && npm run dev

# Dọn cache
php artisan cache:clear && php artisan config:clear && php artisan view:clear

# Format code (Pint)
./vendor/bin/pint
```

## 11) Ghi chú phát triển

-   Nếu triển khai dưới subpath, dùng `asset('...')` cho ảnh nền trong Blade (đã áp dụng ở hero) thay vì bg-[url(...)] để tránh lỗi đường dẫn.
-   Một số trang quản trị còn dùng lớp tương thích `.card/.btn/.tbl…` trong `app.css`. Khi refactor hoàn tất admin sang utilities thuần, có thể gỡ bỏ các lớp tương thích này.

---

**Liên hệ**: spktvl@vlute.edu.vn · Website: https://vlute.edu.vn · Địa chỉ: Số 73 Nguyễn Huệ, P. Long Châu, TP. Vĩnh Long

Phát triển bởi sinh viên Khoa Khoa học Máy tính – VLUTE.
