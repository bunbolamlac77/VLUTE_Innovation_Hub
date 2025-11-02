# VLUTE Innovation Hub

## 📖 Giới thiệu

**VLUTE Innovation Hub** là một nền tảng quản lý ý tưởng đổi mới sáng tạo được phát triển cho Trường Đại học Sư phạm Kỹ thuật Vĩnh Long (VLUTE). Hệ thống kết nối sinh viên – giảng viên – mentor – doanh nghiệp – đối tác để cùng giải quyết các bài toán thực tế, tổ chức đợt gọi ý tưởng, cohort ươm tạo và hỗ trợ truyền thông nghiên cứu.

### Mục tiêu

-   **Kết nối hệ sinh thái**: Kết nối các bên liên quan để cùng giải quyết bài toán thực tế
-   **Ươm tạo & đồng hành**: Tổ chức gọi ý tưởng, cohort ươm tạo, workshop và cố vấn chuyên sâu
-   **Lan toả nghiên cứu**: Hỗ trợ truyền thông, công bố bản tin nghiên cứu, giới thiệu giải pháp tiêu biểu

## 🚀 Tính năng chính

### Đối với người dùng công khai

-   Xem danh sách ý tưởng công khai (Ngân hàng Ý tưởng)
-   Xem chi tiết ý tưởng theo slug
-   Thích (like) ý tưởng (cần đăng nhập)

### Đối với người dùng đã đăng nhập

-   Quản lý hồ sơ cá nhân
-   Dashboard quản lý ý tưởng
-   Tương tác với ý tưởng

### Đối với Admin

-   Quản lý người dùng và phân quyền
-   Duyệt/ từ chối tài khoản người dùng mới
-   Quản lý phân loại: Khoa (Faculties), Danh mục (Categories), Thẻ (Tags)
-   Quản lý ý tưởng: Cập nhật trạng thái, gán reviewer

## 🛠️ Công nghệ sử dụng

### Backend

-   **PHP**: ^8.2
-   **Laravel**: ^12.0
-   **Database**: SQLite (mặc định)

### Frontend

-   **TailwindCSS**: ^3.1.0
-   **AlpineJS**: ^3.4.2
-   **Vite**: ^7.0.7
-   **Laravel Breeze**: ^2.3 (Authentication)

### Development Tools

-   **PHPUnit**: ^11.5.3 (Testing)
-   **Laravel Pint**: ^1.24 (Code formatting)
-   **Laravel Sail**: ^1.41 (Docker development)

## 📋 Yêu cầu hệ thống

-   PHP >= 8.2
-   Composer
-   Node.js >= 18.x và npm
-   SQLite (hoặc có thể cấu hình MySQL/PostgreSQL)

## 🔧 Cài đặt

### Bước 1: Clone repository

```bash
git clone <repository-url>
cd vlute-innovation-hub
```

### Bước 2: Cài đặt dependencies PHP

```bash
composer install
```

### Bước 3: Cấu hình môi trường

Tạo file `.env` từ `.env.example` (nếu có) hoặc tạo mới:

```bash
cp .env.example .env
# Hoặc tạo file .env mới
```

Cấu hình database trong file `.env`:

```env
DB_CONNECTION=sqlite
# Hoặc sử dụng MySQL/PostgreSQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=vlute_innovation_hub
# DB_USERNAME=root
# DB_PASSWORD=
```

Nếu dùng SQLite, đảm bảo file database tồn tại:

```bash
touch database/database.sqlite
```

### Bước 4: Tạo application key

```bash
php artisan key:generate
```

### Bước 5: Chạy migrations và seeders

```bash
php artisan migrate
php artisan db:seed
```

Seeder sẽ tạo:

-   Các roles cơ bản
-   Tài khoản Admin mặc định

### Bước 6: Cài đặt dependencies Node.js

```bash
npm install
```

### Bước 7: Build assets (hoặc chạy dev server)

**Để phát triển (development):**

```bash
npm run dev
```

**Để production:**

```bash
npm run build
```

## ▶️ Chạy dự án

### Chạy development server

Sử dụng script composer để chạy đồng thời nhiều services:

```bash
composer run dev
```

Script này sẽ chạy:

-   Laravel development server
-   Queue worker
-   Log viewer (Pail)
-   Vite dev server

**Hoặc chạy riêng lẻ:**

Terminal 1 - Laravel server:

```bash
php artisan serve
```

Terminal 2 - Vite dev server:

```bash
npm run dev
```

Truy cập ứng dụng tại: `http://localhost:8000`

### Chạy tests

```bash
php artisan test
```

Hoặc:

```bash
composer run test
```

## 📁 Cấu trúc dự án

```
vlute-innovation-hub/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Controllers xử lý logic
│   │   ├── Middleware/           # Middleware (auth, approval, admin)
│   │   └── Requests/             # Form requests validation
│   ├── Models/                   # Eloquent models
│   └── Providers/                # Service providers
├── database/
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Database seeders
├── resources/
│   ├── views/                    # Blade templates
│   ├── css/                      # CSS files
│   └── js/                       # JavaScript files
├── routes/
│   ├── web.php                   # Web routes
│   └── auth.php                  # Authentication routes
├── public/                       # Public assets
└── tests/                        # Test files
```

## 👤 Tài khoản mặc định

Sau khi chạy seeder, hệ thống sẽ tạo tài khoản Admin mặc định. Thông tin đăng nhập có thể được kiểm tra trong file seeder:

-   `database/seeders/AdminUserSeeder.php`

## 🔐 Xác thực và Phân quyền

### Middleware

-   `auth`: Yêu cầu đăng nhập
-   `verified.to.login`: Yêu cầu email đã được xác thực
-   `approved.to.login`: Yêu cầu tài khoản đã được admin duyệt
-   `is.admin`: Chỉ admin mới truy cập được

### Roles

Hệ thống hỗ trợ phân quyền theo roles. Các roles mặc định được tạo trong `RolesSeeder`.

## 🎨 Giao diện

-   **Framework CSS**: TailwindCSS
-   **JavaScript**: AlpineJS (lightweight framework)
-   **Build tool**: Vite

## 📝 Migrations

Các bảng chính:

-   `users`: Người dùng
-   `roles`: Vai trò
-   `ideas`: Ý tưởng
-   `idea_members`: Thành viên ý tưởng
-   `idea_invitations`: Lời mời tham gia ý tưởng
-   `reviews`: Đánh giá
-   `review_assignments`: Phân công đánh giá
-   `categories`: Danh mục
-   `faculties`: Khoa
-   `tags`: Thẻ
-   `attachments`: Tệp đính kèm
-   `change_requests`: Yêu cầu thay đổi

## 🧪 Testing

```bash
# Chạy tất cả tests
php artisan test

# Chạy test cụ thể
php artisan test --filter TestName
```

## 🔄 Scripts hữu ích

```bash
# Setup toàn bộ dự án (install + migrate + seed + build)
composer run setup

# Chạy development với tất cả services
composer run dev

# Format code
./vendor/bin/pint

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 📧 Cấu hình Email

Để sử dụng tính năng xác thực email, cần cấu hình mail server trong file `.env`:

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

## 🤝 Đóng góp

1. Fork repository
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Mở Pull Request

## 📄 License

Dự án này được phát triển cho mục đích học tập và nghiên cứu tại Trường Đại học Sư phạm Kỹ thuật Vĩnh Long.

## 📞 Liên hệ

-   **Email**: spktvl@vlute.edu.vn
-   **Website**: vlute.edu.vn
-   **Địa chỉ**: Số 73 Nguyễn Huệ, Phường Long Châu, tỉnh Vĩnh Long

---

**Phát triển bởi**: Sinh viên Khoa Khoa học Máy tính - VLUTE
**Phiên bản**: 1.0.0
