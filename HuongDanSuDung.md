# Hướng Dẫn Sử Dụng VLUTE Innovation Hub

## 📋 Mục Lục

1. [Giới Thiệu Dự Án](#giới-thiệu-dự-án)
2. [Yêu Cầu Hệ Thống](#yêu-cầu-hệ-thống)
3. [Tải Dự Án Từ GitHub](#tải-dự-án-từ-github)
4. [Cài Đặt Trên Windows với MySQL](#cài-đặt-trên-windows-với-mysql)
5. [Cấu Hình Môi Trường](#cấu-hình-môi-trường)
6. [Thông Tin Đăng Nhập](#thông-tin-đăng-nhập)
7. [Hướng Dẫn Sử Dụng](#hướng-dẫn-sử-dụng)
8. [Tính Năng AI](#tính-năng-ai)
9. [Troubleshooting](#troubleshooting)
10. [Liên Hệ & Hỗ Trợ](#liên-hệ--hỗ-trợ)

---

## 🎯 Giới Thiệu Dự Án

**VLUTE Innovation Hub** là nền tảng phục vụ Đổi mới Sáng tạo tại Trường Đại học Sư phạm Kỹ thuật Vĩnh Long (VLUTE). Hệ thống kết nối sinh viên – giảng viên (mentor) – doanh nghiệp/đối tác – trung tâm để hình thành, ươm tạo và triển khai ý tưởng.

### Công Nghệ Sử Dụng

- **Backend**: PHP 8.2+ · Laravel 12
- **Frontend**: Blade Templates · Vite
- **CSS Framework**: Tailwind CSS (100% utilities)
- **JavaScript**: Alpine.js
- **Database**: SQLite (mặc định) hoặc MySQL (Docker)
- **AI Integration**: 
  - Groq API (Text, Vision) - Model: llama-3.1-70b-versatile
  - Google Gemini API (Embedding) - Model: text-embedding-004
  - OpenAI API (Embedding fallback) - Model: text-embedding-3-small

### Tính Năng Chính

- ✅ Ngân hàng ý tưởng công khai
- ✅ Quản lý ý tưởng cá nhân (tạo, chỉnh sửa, mời thành viên)
- ✅ Cuộc thi & sự kiện
- ✅ Thử thách từ doanh nghiệp
- ✅ Bản tin Nghiên cứu Khoa học
- ✅ Quản trị người dùng và phân quyền
- ✅ **5 Tính năng AI tích hợp Groq + Gemini/OpenAI**

---

## 💻 Yêu Cầu Hệ Thống

### Windows 10/11 (64-bit)

- **Git**: Để clone repository
- **PHP 8.2+** với các extension:
  - `pdo_mysql`
  - `pdo_sqlite`
  - `fileinfo`
  - `openssl`
  - `mbstring`
  - `tokenizer`
  - `xml`
- **Composer**: Quản lý dependencies PHP
- **Node.js 18+** và **npm**: Build frontend
- **MySQL 8.0+** hoặc **Docker Desktop** (để chạy MySQL container)
- **Docker Desktop** (khuyến nghị): Để chạy MySQL và phpMyAdmin

### Cài Đặt Nhanh Bằng Winget (PowerShell)

```powershell
# Cài đặt Git
winget install --id Git.Git -e

# Cài đặt Node.js LTS
winget install --id OpenJS.NodeJS.LTS -e

# Cài đặt Composer
winget install --id Composer.Composer -e

# Cài đặt Docker Desktop
winget install --id Docker.DockerDesktop -e
```

### Cài Đặt PHP Bằng Scoop

```powershell
# Bật execution policy
Set-ExecutionPolicy RemoteSigned -Scope CurrentUser

# Cài đặt Scoop
irm get.scoop.sh | iex

# Cài đặt PHP
scoop install php
```

### Kiểm Tra Phiên Bản

```powershell
php -v          # PHP 8.2+
composer -V     # Composer
node -v         # Node.js 18+
npm -v          # npm
docker --version # Docker Desktop
```

---

## 📥 Tải Dự Án Từ GitHub

### Bước 1: Clone Repository

Mở **Git Bash** hoặc **PowerShell** và chạy:

```bash
# Clone repository
git clone https://github.com/[username]/vlute-innovation-hub.git

# Di chuyển vào thư mục dự án
cd vlute-innovation-hub
```

**Lưu ý**: Thay `[username]` bằng username GitHub thực tế của bạn.

### Bước 2: Kiểm Tra Cấu Trúc Dự Án

Dự án sẽ có cấu trúc như sau:

```
vlute-innovation-hub/
├── app/                    # Application code
├── database/              # Migrations, seeders
├── resources/             # Views, CSS, JS
├── routes/               # Route definitions
├── public/               # Public assets
├── config/               # Configuration files
├── docker-compose.yml    # Docker configuration
└── composer.json         # PHP dependencies
```

---

## 🚀 Cài Đặt Trên Windows với MySQL

### Phương Pháp 1: Sử Dụng Docker Desktop (Khuyến Nghị)

#### Bước 1: Khởi Động MySQL và phpMyAdmin

```powershell
# Khởi động MySQL container
docker compose up -d mysql

# Khởi động phpMyAdmin (tùy chọn, để quản lý database)
docker compose up -d phpmyadmin
```

Sau khi chạy, bạn có thể truy cập:
- **phpMyAdmin**: http://localhost:8081
  - Server: `mysql`
  - Username: `sail`
  - Password: `password`

#### Bước 2: Cài Đặt Dependencies

```powershell
# Cài đặt PHP dependencies
composer install

# Cài đặt Node.js dependencies
npm install
```

#### Bước 3: Tạo File .env

```powershell
# Copy file .env.example thành .env
Copy-Item .env.example .env

# Hoặc nếu file .env.example không tồn tại, tạo file .env mới
```

#### Bước 4: Cấu Hình .env cho MySQL

Mở file `.env` và cấu hình như sau:

```env
APP_NAME="VLUTE Innovation Hub"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Ho_Chi_Minh
APP_URL=http://localhost:8000

# Database Configuration - MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=vlute_innovation_hub
DB_USERNAME=sail
DB_PASSWORD=password

# Groq API (Bắt buộc cho Text/Vision)
GROQ_API_KEY=your_groq_api_key_here
GROQ_MODEL=llama-3.1-70b-versatile

# Gemini API (Khuyến nghị cho Embedding)
GEMINI_API_KEY=your_gemini_api_key_here

# Hoặc OpenAI API (Fallback cho Embedding)
OPENAI_API_KEY=your_openai_api_key_here

# Admin Account (Tùy chọn)
ADMIN_EMAIL=admin@vlute.edu.vn
ADMIN_PASSWORD=Admin@123

# Mail Configuration (Tùy chọn - dùng Mailtrap cho development)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@vlute.edu.vn
MAIL_FROM_NAME="${APP_NAME}"
```

#### Bước 5: Tạo Application Key

```powershell
php artisan key:generate
```

#### Bước 6: Chạy Migrations và Seeders

```powershell
# Chạy migrations và seeders
php artisan migrate --seed
```

Lệnh này sẽ:
- Tạo các bảng trong database
- Tạo các roles mặc định
- Tạo tài khoản Admin và các tài khoản mẫu
- Seed dữ liệu mẫu (ý tưởng, cuộc thi, thử thách, bản tin)

#### Bước 7: Tạo Symbolic Link cho Storage

```powershell
php artisan storage:link
```

#### Bước 8: Build Frontend Assets

**Chế độ Development** (2 cửa sổ terminal):

**Terminal 1 - Laravel Server:**
```powershell
php artisan serve
```
Server sẽ chạy tại: http://127.0.0.1:8000

**Terminal 2 - Vite Dev Server:**
```powershell
npm run dev
```

**Chế độ Production:**
```powershell
npm run build
php artisan serve
```

### Phương Pháp 2: Sử Dụng SQLite (Đơn Giản Hơn)

#### Bước 1: Cấu Hình .env cho SQLite

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

#### Bước 2: Tạo File Database

```powershell
New-Item -Path "database" -Name "database.sqlite" -ItemType File -Force
```

#### Bước 3: Tiếp Tục Từ Bước 5 Của Phương Pháp 1

### Phương Pháp 3: Sử Dụng XAMPP (Nếu Không Dùng Docker)

#### Bước 1: Cài Đặt XAMPP

Tải và cài đặt XAMPP từ: https://www.apachefriends.org/

#### Bước 2: Khởi Động MySQL trong XAMPP

Mở XAMPP Control Panel và khởi động MySQL.

#### Bước 3: Tạo Database

Mở phpMyAdmin (http://localhost/phpmyadmin) và tạo database:

```sql
CREATE DATABASE vlute_innovation_hub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Bước 4: Cấu Hình .env

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vlute_innovation_hub
DB_USERNAME=root
DB_PASSWORD=
```

#### Bước 5: Tiếp Tục Từ Bước 5 Của Phương Pháp 1

---

## ⚙️ Cấu Hình Môi Trường

### Cấu Hình Groq API

Để sử dụng các tính năng AI Text/Vision, bạn cần cấu hình Groq API Key:

1. Truy cập: https://console.groq.com/keys
2. Tạo API Key mới
3. Copy API Key và thêm vào file `.env`:

```env
GROQ_API_KEY=your_groq_api_key_here
GROQ_MODEL=llama-3.1-70b-versatile
```

### Cấu Hình Gemini API (Cho Embedding)

Để sử dụng tính năng Embedding (Check Duplicate, Scout Solutions), bạn cần cấu hình Google Gemini API Key:

1. Truy cập: https://aistudio.google.com/app/apikeys
2. Tạo API Key mới
3. Copy API Key và thêm vào file `.env`:

```env
GEMINI_API_KEY=your_gemini_api_key_here
```

### Cấu Hình OpenAI API (Fallback cho Embedding)

Nếu không có Gemini API Key, bạn có thể dùng OpenAI API:

1. Truy cập: https://platform.openai.com/api-keys
2. Tạo API Key mới
3. Copy API Key và thêm vào file `.env`:

```env
OPENAI_API_KEY=your_openai_api_key_here
```

**Lưu ý**: 
- Groq API có giới hạn request miễn phí hàng ngày
- Gemini API có giới hạn request miễn phí hàng tháng
- Không commit API Keys vào git
- Nếu không cấu hình, các tính năng AI sẽ không hoạt động

### Cấu Hình Email (Tùy Chọn)

Để gửi email (xác thực, thông báo), cấu hình trong `.env`:

**Ví dụ với Mailtrap (Development):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@vlute.edu.vn
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🔐 Thông Tin Đăng Nhập

Sau khi chạy `php artisan migrate --seed`, các tài khoản sau sẽ được tạo tự động:

### 👨‍💼 Tài Khoản Admin

| Email | Mật Khẩu | Vai Trò | Ghi Chú |
|-------|----------|---------|---------|
| `admin@vlute.edu.vn` | `Admin@123` | Admin | Quản trị hệ thống (mặc định) |

**Hoặc tùy chỉnh trong .env:**
- `ADMIN_EMAIL`: Email admin tùy chỉnh
- `ADMIN_PASSWORD`: Mật khẩu admin tùy chỉnh

### 👨‍🎓 Tài Khoản Sinh Viên (Student)

Tất cả tài khoản sinh viên có **mật khẩu mặc định**: `Password@123`

| Email | Tên | Vai Trò | Trạng Thái |
|-------|-----|---------|------------|
| `student1@st.vlute.edu.vn` | Student One | Student | Đã duyệt, đã xác thực |
| `student2@st.vlute.edu.vn` | Student Two | Student | Đã duyệt, đã xác thực |
| `student3@st.vlute.edu.vn` | Student Three | Student | Đã duyệt, đã xác thực |
| `student4@st.vlute.edu.vn` | Student Four | Student | Đã duyệt, đã xác thực |
| `student5@st.vlute.edu.vn` | Student Five | Student | Đã duyệt, đã xác thực |
| `student6@st.vlute.edu.vn` | Student 06 | Student | Đã duyệt, đã xác thực |
| ... | ... | ... | ... |
| `student15@st.vlute.edu.vn` | Student 15 | Student | Đã duyệt, đã xác thực |

**Lưu ý**: Mỗi tài khoản sinh viên có **3 ý tưởng công khai, đã duyệt** được seed tự động.

### 👨‍🏫 Tài Khoản Giảng Viên (Staff)

Tất cả tài khoản giảng viên có **mật khẩu mặc định**: `Password@123`

| Email | Tên | Vai Trò | Khoa | Trạng Thái |
|-------|-----|---------|------|------------|
| `gv.cntt@vlute.edu.vn` | GV CNTT | Staff | Khoa Công nghệ thông tin | Đã duyệt, đã xác thực |
| `gv.ddt@vlute.edu.vn` | GV DDT | Staff | Khoa Điện - Điện tử | Đã duyệt, đã xác thực |
| `gv.ckd@vlute.edu.vn` | GV CKD | Staff | Khoa Cơ khí - Động lực | Đã duyệt, đã xác thực |
| `gv.kt@vlute.edu.vn` | GV KT | Staff | Khoa Kinh tế | Đã duyệt, đã xác thực |
| `gv.nn@vlute.edu.vn` | GV NN | Staff | Khoa Ngoại ngữ | Đã duyệt, đã xác thực |

### 🏢 Tài Khoản Trung Tâm ĐMST (Center)

| Email | Mật Khẩu | Vai Trò | Trạng Thái |
|-------|----------|---------|------------|
| `center@vlute.edu.vn` | `Password@123` | Center | Đã duyệt, đã xác thực |

### 🎓 Tài Khoản Ban Giám Hiệu (Board)

| Email | Mật Khẩu | Vai Trò | Trạng Thái |
|-------|----------|---------|------------|
| `board@vlute.edu.vn` | `Password@123` | Board | Đã duyệt, đã xác thực |

### 🏭 Tài Khoản Doanh Nghiệp (Enterprise)

| Email | Mật Khẩu | Vai Trò | Công Ty | Trạng Thái |
|-------|----------|---------|---------|------------|
| `hr@acme.example` | `Password@123` | Enterprise | ACME Corp | Đã duyệt, đã xác thực |

### 📋 Tóm Tắt Mật Khẩu

| Vai Trò | Mật Khẩu Mặc Định |
|---------|-------------------|
| Admin | `Admin@123` |
| Student | `Password@123` |
| Staff | `Password@123` |
| Center | `Password@123` |
| Board | `Password@123` |
| Enterprise | `Password@123` |
| Reviewer | `Password@123` (nếu được tạo) |

---

## 📖 Hướng Dẫn Sử Dụng

### Đăng Nhập

1. Truy cập: http://127.0.0.1:8000
2. Click **"Đăng nhập"** ở góc trên bên phải
3. Nhập email và mật khẩu (xem [Thông Tin Đăng Nhập](#-thông-tin-đăng-nhập))
4. Click **"Đăng nhập"**

### Quy Trình Sử Dụng Cơ Bản

#### 1. Sinh Viên - Tạo Ý Tưởng

1. Đăng nhập với tài khoản sinh viên
2. Vào **"Ý tưởng của tôi"** (menu trên)
3. Click **"Tạo ý tưởng mới"**
4. Điền thông tin:
   - Tiêu đề
   - Mô tả
   - Lĩnh vực
   - Thẻ (tags)
   - File đính kèm (nếu có)
5. Sử dụng tính năng AI:
   - **Đề xuất công nghệ**: Click nút "Đề xuất công nghệ" để AI gợi ý tech stack
   - **Tạo kế hoạch kinh doanh**: Click nút "Tạo kế hoạch kinh doanh" để AI tạo thuyết minh dự án
   - **Phân tích hình ảnh**: Upload poster/slide và click "Phân tích AI"
6. Lưu dưới dạng **Draft** hoặc **Submit** ngay
7. Nếu Submit, ý tưởng sẽ chờ phê duyệt

#### 2. Giảng Viên/Reviewer - Phản Biện Ý Tưởng

1. Đăng nhập với tài khoản giảng viên hoặc reviewer
2. Vào **"Hàng chờ phản biện"** (nếu có quyền)
3. Xem danh sách ý tưởng chờ duyệt
4. Sử dụng tính năng AI:
   - **Review Insight**: Click nút "Phân tích AI" để nhận đánh giá chuyên nghiệp về ý tưởng
5. Đọc và đánh giá ý tưởng
6. Chọn trạng thái: **Duyệt** hoặc **Từ chối**

#### 3. Admin - Quản Trị Hệ Thống

1. Đăng nhập với tài khoản admin
2. Vào **"/admin"** để truy cập trang quản trị
3. Các chức năng:
   - **Phê duyệt tài khoản**: Duyệt/từ chối tài khoản mới
   - **Quản lý người dùng**: Xem, chỉnh sửa, khóa/mở khóa tài khoản
   - **Quản lý ý tưởng**: Xem, duyệt, từ chối ý tưởng
   - **Phân loại**: Quản lý Khoa, Danh mục, Thẻ
   - **Quản lý cuộc thi**: CRUD cuộc thi, export danh sách đăng ký
   - **Quản lý thử thách**: CRUD thử thách
   - **Quản lý bản tin**: CRUD bản tin nghiên cứu khoa học
   - **Quản lý banner**: CRUD banner quảng cáo
   - **Nhật ký**: Xem log hoạt động

#### 4. Doanh Nghiệp - Tìm Giải Pháp (AI Scout)

1. Đăng nhập với tài khoản doanh nghiệp
2. Vào **"/enterprise/scout"**
3. Nhập vấn đề cần giải quyết
4. Hệ thống AI sẽ tìm Top 5 ý tưởng phù hợp nhất
5. Xem chi tiết và liên hệ với chủ ý tưởng

#### 5. Doanh Nghiệp - Quản Lý Thử Thách

1. Đăng nhập với tài khoản doanh nghiệp
2. Vào **"/enterprise/challenges"**
3. Tạo thử thách mới hoặc quản lý thử thách hiện có
4. Xem và đánh giá giải pháp của sinh viên

### Các Trang Chính

| Trang | URL | Mô Tả |
|-------|-----|-------|
| Trang chủ | `/` | Hiển thị tổng quan, số liệu thống kê |
| Ngân hàng ý tưởng | `/ideas` | Danh sách ý tưởng công khai đã duyệt |
| Chi tiết ý tưởng | `/ideas/{slug}` | Xem chi tiết một ý tưởng |
| Cuộc thi | `/competitions` | Danh sách cuộc thi |
| Sự kiện | `/events` | Danh sách sự kiện |
| Thử thách | `/challenges` | Danh sách thử thách từ doanh nghiệp |
| Bản tin NCKH | `/scientific-news` | Bản tin nghiên cứu khoa học |
| Dashboard | `/dashboard` | Trang cá nhân (sau khi đăng nhập) |
| Ý tưởng của tôi | `/my-ideas` | Quản lý ý tưởng cá nhân |
| Cuộc thi của tôi | `/my-competitions` | Đăng ký và nộp bài thi |
| Quản trị | `/admin` | Trang quản trị (chỉ Admin) |
| Enterprise Scout | `/enterprise/scout` | Tìm giải pháp bằng AI (Doanh nghiệp) |

---

## 🤖 Tính Năng AI

Dự án tích hợp **5 tính năng AI** sử dụng Groq API và Google Gemini API / OpenAI API:

### 1. Review Insight - Phân Tích Ý Tưởng

**Mô tả**: Phân tích nội dung ý tưởng và cung cấp đánh giá chuyên nghiệp.

**Tính năng**:
- Điểm mạnh (3-5 điểm)
- Điểm yếu & Rủi ro (3-5 điểm)
- Tiềm năng thị trường (TAM, SAM, SOM)
- Khả thi công nghệ
- Điểm số trên thang 50

**Sử dụng**: 
- Trong trang review ý tưởng
- Giúp sinh viên cải thiện ý tưởng
- Giúp giám khảo có nhận xét chuyên nghiệp

**API Endpoint**: `POST /ai/review-insight`

**Yêu cầu**: Groq API Key

### 2. Vision - Phân Tích Hình Ảnh

**Mô tả**: Đánh giá chất lượng Poster, Slide hoặc hình ảnh minh họa.

**Tính năng**:
- Đánh giá tính thẩm mỹ (màu sắc, bố cục, typography)
- Phân tích nội dung hiển thị
- Đánh giá hiệu quả truyền đạt
- Lời khuyên cải thiện (5-7 điểm cụ thể)
- Điểm số trên thang 30

**Sử dụng**: 
- Upload hình ảnh trong trang tạo ý tưởng
- Giúp sinh viên cải thiện chất lượng trình bày

**API Endpoint**: `POST /ai/vision`

**Yêu cầu**: Groq API Key

### 3. Check Duplicate - Kiểm Tra Trùng Lặp

**Mô tả**: Phát hiện ý tưởng trùng lặp hoặc tương tự.

**Cơ chế**:
- Tạo Vector Embedding cho ý tưởng (Gemini/OpenAI)
- So sánh với kho ý tưởng đã duyệt
- Sử dụng Cosine Similarity (ngưỡng: 85%)
- Trả về danh sách top 3 ý tưởng tương tự

**Sử dụng**: 
- Tự động kiểm tra khi sinh viên nộp ý tưởng mới
- Giúp tránh trùng lặp

**API Endpoint**: `POST /ai/check-duplicate`

**Yêu cầu**: Gemini API Key hoặc OpenAI API Key

### 4. Suggest Tech Stack - Đề Xuất Công Nghệ

**Mô tả**: Giúp sinh viên chọn công nghệ phù hợp cho dự án.

**Tính năng**:
- Frontend: công nghệ + lý do
- Backend: công nghệ + lý do
- Database: công nghệ
- Mobile: nếu cần
- Hardware: nếu là dự án IoT
- Cloud Infrastructure: AWS/Azure/GCP
- DevOps Tools: CI/CD và deployment
- Lời khuyên triển khai
- Đánh giá độ phức tạp và timeline

**Sử dụng**: 
- Trong trang tạo ý tưởng
- Giúp sinh viên lựa chọn công nghệ phù hợp

**API Endpoint**: `POST /ai/suggest-tech-stack`

**Yêu cầu**: Groq API Key

### 5. Scout Solutions - Thợ Săn Giải Pháp

**Mô tả**: Giúp doanh nghiệp tìm ý tưởng phù hợp.

**Cơ chế**:
- Doanh nghiệp nhập vấn đề cần giải quyết
- Hệ thống tìm kiếm ngữ nghĩa (Semantic Search)
- Trả về Top 5 ý tưởng phù hợp nhất
- Hiển thị % độ phù hợp (ngưỡng: 65%)

**Sử dụng**: 
- Trang riêng `/enterprise/scout` cho doanh nghiệp
- Tìm giải pháp cho vấn đề cụ thể

**API Endpoint**: `POST /ai/scout-solutions`

**Yêu cầu**: Gemini API Key hoặc OpenAI API Key

### 6. Business Plan Generator - Tạo Kế Hoạch Kinh Doanh

**Mô tả**: Tự động tạo thuyết minh kế hoạch ý tưởng hoàn chỉnh.

**Tính năng**:
- Tạo kế hoạch kinh doanh theo cấu trúc 12 phần:
  1. Tóm tắt dự án (Business Model Canvas)
  2. Bối cảnh thị trường & căn cứ
  3. Vị trí dự kiến
  4. Phân tích pháp luật
  5. Nghiên cứu & đánh giá thị trường
  6. Kế hoạch Marketing (4P & 4C)
  7. Quy trình hoạt động
  8. Tổ chức nhân lực
  9. Kế hoạch tài chính
  10. Tiến độ triển khai
  11. Quản trị rủi ro
  12. Kết luận & cam kết

**Sử dụng**: 
- Trong trang tạo ý tưởng
- Giúp sinh viên hoàn thiện kế hoạch kinh doanh

**API Endpoint**: `POST /api/ai/business-plan`

**Yêu cầu**: Groq API Key

### Test API AI

**Kiểm tra cấu hình:**
```powershell
curl http://127.0.0.1:8000/api/test/groq/config
```

**Test Text API:**
```powershell
curl -X POST http://127.0.0.1:8000/api/test/groq/text -H "Content-Type: application/json" -d "{\"prompt\":\"Hello\"}"
```

**Test Image API:**
```powershell
curl -X POST http://127.0.0.1:8000/api/test/groq/image -F "image=@path/to/image.jpg"
```

---

## 🔧 Troubleshooting

### Lỗi PHP Extension Không Tìm Thấy

**Vấn đề**: Lỗi `Class 'PDO' not found` hoặc extension không tìm thấy.

**Giải pháp**:
1. Mở file `php.ini` (tìm bằng `php --ini`)
2. Bỏ dấu `;` trước các dòng extension:
   ```ini
   extension=pdo_mysql
   extension=pdo_sqlite
   extension=fileinfo
   extension=openssl
   extension=mbstring
   extension=tokenizer
   extension=xml
   ```
3. Kiểm tra lại: `php -m`

### Lỗi Composer Timeout/SSL

**Vấn đề**: Composer không thể tải packages.

**Giải pháp**:
```powershell
# Kiểm tra mạng và proxy
composer config -g repos.packagist composer https://repo.packagist.org

# Hoặc tăng timeout
composer install --timeout=0
```

### Docker Desktop Không Chạy Được

**Vấn đề**: Docker Desktop không khởi động.

**Giải pháp**:
1. Bật WSL2 backend trong Settings
2. Restart Docker Desktop
3. Đảm bảo đã cài WSL2 và một distro (Ubuntu)
4. Kiểm tra: `docker --version`

### Cổng 3307 Bị Chiếm Dụng

**Vấn đề**: Port 3307 đã được sử dụng.

**Giải pháp**:
1. Sửa `docker-compose.yml`:
   ```yaml
   ports:
     - "3310:3306"  # Thay 3307 bằng 3310
   ```
2. Sửa `.env`:
   ```env
   DB_PORT=3310
   ```
3. Restart container: `docker compose restart mysql`

### Lỗi Quyền Truy Cập Thư Mục Storage

**Vấn đề**: Không thể ghi vào `storage/` hoặc `bootstrap/cache/`.

**Giải pháp**:
```powershell
# Tạo symbolic link
php artisan storage:link

# Đảm bảo quyền ghi (Windows thường không cần)
# Nếu vẫn lỗi, chạy PowerShell với quyền admin
```

### Node Build Lỗi (Path Quá Dài)

**Vấn đề**: Lỗi khi build frontend do đường dẫn quá dài.

**Giải pháp**:
1. Bật Long Paths trong Windows:
   - Mở Local Group Policy Editor
   - Enable "Win32 long paths"
2. Hoặc dùng Git Bash thay vì PowerShell

### Lỗi API Groq/Gemini

**Lỗi 404**: API Key không hợp lệ
- Kiểm tra `GROQ_API_KEY`, `GEMINI_API_KEY` trong `.env`
- Truy cập console tương ứng để xác nhận API Key

**Lỗi 429**: Quá nhiều yêu cầu
- Chờ một lúc rồi thử lại
- Kiểm tra giới hạn request của Groq/Gemini API

**Lỗi Embedding Vector**:
```powershell
# Chạy lại seeder để tạo embedding
php artisan db:seed --class=SampleIdeaSeeder

# Hoặc trong tinker
php artisan tinker
>>> app(\App\Http\Controllers\Api\AIController::class)->seedEmbeddings()
```

### Lỗi Database Connection

**Vấn đề**: Không kết nối được MySQL.

**Giải pháp**:
1. Kiểm tra MySQL container đang chạy:
   ```powershell
   docker ps
   ```
2. Kiểm tra cấu hình `.env`:
   ```env
   DB_HOST=127.0.0.1
   DB_PORT=3307
   DB_DATABASE=vlute_innovation_hub
   DB_USERNAME=sail
   DB_PASSWORD=password
   ```
3. Test kết nối:
   ```powershell
   php artisan tinker
   >>> DB::connection()->getPdo();
   ```

### Clear Cache

Nếu gặp lỗi cache:

```powershell
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

---

## 📞 Liên Hệ & Hỗ Trợ

### Thông Tin Liên Hệ

- **Email**: spktvl@vlute.edu.vn
- **Website**: https://vlute.edu.vn
- **Địa chỉ**: Số 73 Nguyễn Huệ, P. Long Châu, TP. Vĩnh Long

### Phát Triển Bởi

Sinh viên Khoa Khoa học Máy tính – VLUTE

### Tính Năng AI

Tích hợp Groq API (Text/Vision) và Google Gemini API / OpenAI API (Embedding)

---

## 📝 Ghi Chú Bổ Sung

### Cấu Trúc Database

Các bảng chính:
- `users`, `roles`, `role_user` (pivot)
- `ideas`, `idea_members`, `idea_invitations`, `idea_likes`, `attachments`
- `reviews`, `review_assignments`, `change_requests`
- `faculties`, `categories`, `tags`, `idea_tag`
- `competitions`, `competition_registrations`, `competition_submissions`
- `challenges`, `challenge_submissions`
- `organizations` (đối tác)
- `scientific_news` (Bản tin NCKH)

**Lưu ý**: Bảng `ideas` có cột `embedding_vector` (JSON) để lưu trữ Vector từ Gemini/OpenAI.

### Phân Quyền Tự Động

- Domain `@st.vlute.edu.vn` → role mặc định: **student** (auto approved)
- Domain `@vlute.edu.vn` → role mặc định: **staff** (chờ duyệt)
- Domain khác → role: **enterprise** (chờ duyệt)
- Admin duyệt tài khoản tại: `/admin/approvals`

### Scripts Tiện Ích

```powershell
# Thiết lập nhanh (1 lệnh)
composer install && npm install && php artisan key:generate && php artisan migrate --seed && npm run dev

# Dọn cache
php artisan cache:clear && php artisan config:clear && php artisan view:clear

# Format code (Pint)
./vendor/bin/pint

# Test API Groq
curl http://localhost:8000/api/test/groq/config
```

---

**Chúc bạn sử dụng dự án thành công! 🎉**
