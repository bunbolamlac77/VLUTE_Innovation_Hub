# Hướng dẫn chạy dự án VLUTE Innovation Hub trên Windows

Tài liệu này hướng dẫn cài đặt và chạy dự án trên Windows 10/11 theo 2 cách:
- Cách A (đề xuất khi phát triển): SQLite, không cần MySQL
- Cách B: MySQL bằng Docker Desktop (hoặc XAMPP nếu bạn quen)

Ngoài ra, tài liệu kèm hướng dẫn cấu hình Google Gemini API Key để dùng 5 tính năng AI.

---

## 1) Yêu cầu hệ thống

- Windows 10/11 (64-bit)
- Git
- PHP 8.2+ (kèm các extension: pdo_mysql, fileinfo, openssl, mbstring, tokenizer, xml)
- Composer
- Node.js 18+ và npm
- (Tùy chọn) Docker Desktop for Windows (WSL2 backend) – dùng cho MySQL

Gợi ý cài đặt nhanh bằng winget (PowerShell chạy với quyền người dùng thường):

```powershell
winget install --id Git.Git -e
winget install --id OpenJS.NodeJS.LTS -e
winget install --id Composer.Composer -e
# PHP: bạn có thể dùng Scoop hoặc tải ZIP từ https://windows.php.net/downloads/ (PHP 8.2+)
```

Nếu dùng Scoop:
```powershell
Set-ExecutionPolicy RemoteSigned -Scope CurrentUser
irm get.scoop.sh | iex
scoop install php
```

Kiểm tra phiên bản:
```powershell
php -v
composer -V
node -v
npm -v
```

---

## 2) Lấy mã nguồn dự án

```powershell
# Chọn thư mục lưu mã nguồn rồi chạy:
git clone <REPO_URL> vlute-innovation-hub
cd vlute-innovation-hub
```

---

## 3) Cấu hình môi trường (.env)

Tạo file .env và sinh APP_KEY:

```powershell
Copy-Item .env.example .env   # PowerShell
php artisan key:generate
```

Thiết lập biến môi trường AI (khuyên dùng):
```env
GEMINI_API_KEY=your_gemini_api_key_here
```

Lấy API key tại: https://aistudio.google.com/app/apikeys

### Cách A (đề xuất): Dùng SQLite

Ưu điểm: đơn giản, không cần cài MySQL/Docker.

```powershell
New-Item -Path "database" -Name "database.sqlite" -ItemType File -Force | Out-Null
```

Trong .env:
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### Cách B: Dùng MySQL (Docker Desktop)

Yêu cầu: Cài Docker Desktop và bật WSL2 backend.

1) Khởi động MySQL và phpMyAdmin bằng docker compose:
```powershell
docker compose up -d
```

2) Cập nhật .env cho MySQL (khớp docker-compose.yml):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=vlute_innovation_hub
DB_USERNAME=sail
DB_PASSWORD=password
```

Nếu bạn dùng XAMPP, sửa theo cổng và user/pass của XAMPP.

---

## 4) Cài dependencies

```powershell
composer install
npm install
```

---

## 5) Migrate & Seed dữ liệu

```powershell
php artisan migrate --seed
```

Seeder tạo sẵn:
- Roles mặc định
- Tài khoản Admin (email: ADMIN_EMAIL trong .env hoặc mặc định admin@vlute.edu.vn, mật khẩu: Admin@123)
- Dữ liệu mẫu (ý tưởng nổi bật, cuộc thi, bản tin NCKH)

Nếu gặp lỗi quyền thư mục, chạy:
```powershell
php artisan storage:link
```

---

## 6) Build frontend và chạy chế độ phát triển

Chạy 2 cửa sổ (terminal) song song:

Cửa sổ 1 – Laravel:
```powershell
php artisan serve   # http://127.0.0.1:8000
```

Cửa sổ 2 – Vite Dev Server:
```powershell
npm run dev
```

Hoặc build production:
```powershell
npm run build
```

---

## 7) Đăng nhập thử

- Truy cập: http://127.0.0.1:8000
- Đăng nhập admin:
  - Email: admin@vlute.edu.vn (hoặc theo .env ADMIN_EMAIL)
  - Mật khẩu: Admin@123 (hoặc theo .env ADMIN_PASSWORD)

---

## 8) Kiểm tra các tính năng AI

Đảm bảo đã cấu hình GEMINI_API_KEY trong .env, sau đó thử:

```powershell
# Kiểm tra cấu hình API
curl http://127.0.0.1:8000/api/test/gemini/config

# Phân tích ý tưởng (Review Insight)
curl -X POST http://127.0.0.1:8000/ai/review-insight -H "Content-Type: application/json" -H "X-CSRF-TOKEN: $(php -r "require 'vendor/autoload.php'; echo csrf_token();")" -d '{"content":"Ý tưởng của tôi là..."}'
```

Lưu ý: Nếu dùng PowerShell, tạo CSRF token qua form thực tế là đơn giản nhất. Với Postman/Insomnia, bạn có thể tạm thời bỏ CSRF cho test API hoặc gửi kèm cookie session khi đăng nhập.

Các route AI (đã bảo vệ bởi auth + verified + approved):
- POST /ai/review-insight — Phân tích ý tưởng
- POST /ai/vision — Phân tích hình ảnh (multipart/form-data, field: image)
- POST /ai/check-duplicate — Kiểm tra trùng lặp
- POST /ai/suggest-tech — Đề xuất công nghệ
- POST /ai/scout-solutions — Tìm giải pháp cho doanh nghiệp

Test công khai (không cần auth):
- POST /api/test/gemini/text
- POST /api/test/gemini/image
- GET  /api/test/gemini/config

---

## 9) Quy trình kiểm thử nhanh trên Windows

1) Đăng ký tài khoản sinh viên (email @st.vlute.edu.vn) → xác thực email → đăng nhập
2) Vào "Ý tưởng của tôi" → Tạo ý tưởng (Draft) → Nộp (Submit)
3) Tài khoản reviewer/center đăng nhập → Hàng chờ phản biện → Duyệt (approved_center/approved_final)
4) Ý tưởng hiển thị ở Ngân hàng Ý tưởng (Public)
5) Doanh nghiệp (email ngoài VLUTE) đăng ký → chờ Admin duyệt → vào menu Enterprise
6) Tạo Challenge (tiêu đề, mô tả, deadline) → Sinh viên nộp bài → Doanh nghiệp chấm điểm

---

## 10) Sự cố thường gặp trên Windows (Troubleshooting)

- Lỗi PHP không tìm thấy extension (pdo_mysql, fileinfo, openssl):
  - Mở php.ini, bật extension bằng cách bỏ dấu ";" trước các dòng tương ứng và trỏ đúng thư mục ext.
  - Kiểm tra lại bằng `php -m`.

- Lỗi Composer timeout/SSL: kiểm tra mạng, proxy, hoặc thử `composer config -g repos.packagist composer https://repo.packagist.org`

- Docker Desktop không chạy được:
  - Bật WSL2 backend trong Settings • Restart Docker Desktop
  - Đảm bảo đã cài WSL2 và một distro (Ubuntu)

- Cổng 3307 bị chiếm dụng:
  - Sửa docker-compose.yml sang cổng khác (ví dụ 3310:3306) và sửa .env

- Quyền truy cập thư mục storage/bootstrap/cache:
  - Chạy PowerShell với quyền admin không bắt buộc, chỉ cần user có quyền ghi trong project

- Node build lỗi trên Windows (path quá dài):
  - Bật Long Paths: Local Group Policy Editor → Enable Win32 long paths, hoặc dùng Git Bash.

---

## 11) Lệnh nhanh (PowerShell)

```powershell
# 1-liner (SQLite + Dev)
composer install; npm install; Copy-Item .env.example .env; php artisan key:generate; New-Item -Path "database" -Name "database.sqlite" -ItemType File -Force | Out-Null; (Get-Content .env) -replace "DB_CONNECTION=mysql","DB_CONNECTION=sqlite" -replace "DB_HOST=.*","DB_HOST=127.0.0.1" | Set-Content .env; Add-Content .env "`nDB_DATABASE=database/database.sqlite"; php artisan migrate --seed; php artisan serve
```

Mở cửa sổ khác chạy:
```powershell
npm run dev
```

---

## 12) Tài khoản & Phân quyền

- Domain @st.vlute.edu.vn → role mặc định: student (auto approved)
- Domain @vlute.edu.vn → role mặc định: staff (chờ duyệt)
- Domain khác → role: enterprise (chờ duyệt)
- Admin duyệt tài khoản tại: /admin/approvals

---

## 13) Tham khảo nhanh

- Trang chủ: http://127.0.0.1:8000
- Ngân hàng ý tưởng: /ideas
- Bản tin NCKH: /scientific-news
- Enterprise (thợ săn giải pháp): /enterprise/scout
- Admin: /admin

Chúc bạn cài đặt thành công trên Windows! 🎯

