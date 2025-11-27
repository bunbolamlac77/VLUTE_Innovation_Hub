# 📋 CHI TIẾT DỰ ÁN: CỔNG ĐỔI MỚI SÁNG TẠO VLUTE

> Tài liệu tổng hợp đầy đủ về luồng nghiệp vụ, phân quyền, CSDL, logic chống trùng lặp và bảo mật.

---

## 📑 Mục lục

1. [Tổng quan Phân quyền & Vai trò](#1--tổng-quan-phân-quyền--vai-trò)
2. [Luồng nghiệp vụ chính](#2--luồng-nghiệp-vụ-chính)
3. [Cấu trúc Trang & Phân quyền chi tiết](#3--cấu-trúc-trang--phân-quyền-chi-tiết-matrix)
4. [Cơ sở dữ liệu đầy đủ](#4--cơ-sở-dữ-liệu-đầy-đủ-mysql)
5. [Logic dữ liệu & Tính năng chính](#5--logic-dữ-liệu--tính-năng-chính)
6. [Công nghệ & Chiến lược Bảo mật](#6--công-nghệ--chiến-lược-bảo-mật)

---

## 1. 🎯 TỔNG QUAN PHÂN QUYỀN & VAI TRÒ

Hệ thống có **7 vai trò người dùng**, mỗi vai trò có một mục đích riêng biệt:

### 1.1. Khách (Guest)

-   Người dùng vãng lai chưa đăng nhập
-   Chỉ xem được các trang công khai

### 1.2. Sinh viên (Student)

-   Vai trò trung tâm, người tạo và nộp ý tưởng
-   Tự động gán khi đăng ký với email `@st.vlute.edu.vn`
-   Tự động được duyệt (`is_approved = 1`)

### 1.3. Giảng viên (Lecturer)

-   Đóng vai trò Cố vấn (Mentor): tham gia nhóm, góp ý nội bộ; không duyệt/chặn luồng
-   Đăng ký với email `@vlute.edu.vn`, cần Admin duyệt

### 1.4. Trung tâm ĐMST (Innovation Center)

-   Quản lý các cuộc thi, duyệt ý tưởng cấp Trung tâm
-   Điều phối chung
-   Đăng ký với email `@vlute.edu.vn`, cần Admin gán vai trò

### 1.5. Ban giám hiệu (Rectorate/Board)

-   Duyệt cuối cùng, xem báo cáo tổng hợp
-   Đăng ký với email `@vlute.edu.vn`, cần Admin gán vai trò

### 1.6. Doanh nghiệp (Enterprise)

-   Đăng "Challenge" (thử thách)
-   Tìm kiếm ý tưởng, có thể làm mentor
-   Đăng ký với email khác, cần Admin duyệt

### 1.7. Admin

-   Quản trị viên hệ thống
-   Quản lý tài khoản, cấu hình và bảo mật
-   Toàn quyền truy cập

---

## 2. 🗺️ LUỒNG NGHIỆP VỤ CHÍNH

### 2.1. Luồng 1: Đăng ký & Phê duyệt Tài khoản

**Mục tiêu:** Tự động hóa việc cấp quyền dựa trên email.

#### Các bước:

1. **Người dùng vào trang `/register`**

    - Nhập: Tên, Email, Mật khẩu

2. **Logic Hệ thống (trong `RegisteredUserController.php`):**

    **Nếu email có đuôi `@st.vlute.edu.vn`:**

    - Tạo `User`
    - Gán tự động `role` = "Sinh viên"
    - Đặt `is_approved` = 1, `is_active` = 1
    - Gửi email xác thực
    - Người dùng có thể đăng nhập ngay sau khi xác thực

    **Nếu email có đuôi `@vlute.edu.vn`:**

    - Tạo `User`
    - Gán tự động `role` = "Giảng viên" (hoặc vai trò chờ)
    - Đặt `is_approved` = 0, `is_active` = 0
    - Gửi email xác thực
    - Sau khi xác thực, tài khoản vẫn ở trạng thái chờ Admin duyệt
    - Admin phải vào gán vai trò chính xác (GV, Trung tâm ĐMST hay BGH) và bật `is_active` = 1

    **Nếu email có đuôi khác (Gmail, Doanh nghiệp...):**

    - Tạo `User`
    - Gán tự động `role` = "Doanh nghiệp" (hoặc "Khách" đã xác thực)
    - Đặt `is_approved` = 0, `is_active` = 0
    - Tài khoản ở trạng thái chờ Admin duyệt

### 2.2. Luồng 2: Nộp & Duyệt Ý tưởng (Luồng cốt lõi)

Cập nhật 2025-11 — Luồng mới (Mentor, bỏ tầng duyệt GV):

1. SV tạo ý tưởng (status = 'draft').
2. SV mời Giảng viên làm Cố vấn (Mentor) vào nhóm; Mentor có quyền xem và góp ý nội bộ (comment team_only), không có quyền chặn/duyệt.
3. Nhóm hoàn thiện nội dung theo góp ý Mentor.
4. SV bấm Nộp: hệ thống chuyển thẳng sang 'submitted_center'.
5. Trung tâm ĐMST xử lý:
    - Nếu yêu cầu chỉnh sửa: 'needs_change_center' → SV chỉnh sửa rồi nộp lại.
    - Nếu duyệt: chuyển lên 'submitted_board'.
6. BGH xử lý:
    - Nếu yêu cầu chỉnh sửa: 'needs_change_board'.
    - Nếu duyệt công khai: 'approved_final' (xuất hiện trên ngân hàng ý tưởng).

Lưu ý: Có thể bật ràng buộc “phải có ≥1 Mentor để nộp” qua IDEAS_REQUIRE_MENTOR=true.

Đây là luồng quan trọng nhất của dự án, đi từ SV đến BGH.

#### Các bước:

1. **SV (Sinh viên)** vào trang "Ý tưởng của tôi" → "Tạo ý tưởng mới"

2. **SV nhập thông tin:**

    - Tiêu đề, Tóm tắt, Lĩnh vực...

3. **Logic Chống trùng lặp:**

    - Khi SV gõ Tiêu đề/Tóm tắt, hệ thống tự động chạy AJAX call
    - Tìm các ý tưởng tương tự và hiển thị cảnh báo
    - (Xem chi tiết ở Phần 5.1)

4. **SV lưu nháp:**

    - `ideas.status` = 'draft'

5. **SV mời thành viên:**

    - Vào tab "Thành viên", mời bạn bè qua email
    - Hệ thống tạo `IdeaInvitation` và gửi email

6. **Bạn bè chấp nhận lời mời:**

    - Được thêm vào `IdeaMember`

7. **SV nộp ý tưởng:**

    - Bấm "Nộp ý tưởng"
    - `ideas.status` = 'submitted_center'

8. **GV xử lý:**

    - **Nếu GV "Yêu cầu chỉnh sửa":**
        - `ideas.status` = 'needs_change_gv'
        - SV nhận thông báo, sửa và nộp lại (quay lại bước 7)
    - **Nếu GV "Duyệt (cấp Khoa)":**
        - `ideas.status` = 'approved_gv'
        - `ideas.status` = 'submitted_center' (tự động chuyển sang cấp Trung tâm)

9. **Trung tâm ĐMST xử lý:**

    - Thấy ý tưởng trong hàng chờ "Duyệt cấp Trung tâm"
    - **Nếu TTD MST "Yêu cầu chỉnh sửa":**
        - `ideas.status` = 'needs_change_center'
        - Quay lại bước 9 (hoặc 7)
    - **Nếu TTD MST "Duyệt (cấp Trường)":**
        - `ideas.status` = 'approved_center'
        - `ideas.status` = 'submitted_board' (tự động chuyển sang cấp BGH)

10. **BGH (Ban giám hiệu) xử lý:**
    - Thấy ý tưởng trong hàng chờ "Duyệt cuối cùng"
    - **Nếu BGH "Duyệt công khai":**
        - `ideas.status` = 'approved_final'
        - Ý tưởng xuất hiện trên Ngân hàng Ý tưởng công khai

---

## 3. 🖥️ CẤU TRÚC TRANG & PHÂN QUYỀN CHI TIẾT (MATRIX)

| Trang / Module                       | Nhiệm vụ                                                                     | Khách              | Sinh viên                     | Giảng viên         | Trung tâm ĐMST             | BGH                        | Doanh nghiệp        | Admin              |
| :----------------------------------- | :--------------------------------------------------------------------------- | :----------------- | :---------------------------- | :----------------- | :------------------------- | :------------------------- | :------------------ | :----------------- |
| **A. Chung (Public)**                |                                                                              |                    |                               |                    |                            |                            |                     |                    |
| `/` (Trang chủ)                      | Hiển thị banner, tin tức, ý tưởng/cuộc thi nổi bật                           | ✅ Xem             | ✅ Xem                        | ✅ Xem             | ✅ Xem                     | ✅ Xem                     | ✅ Xem              | ✅ Xem             |
| `/about` (Giới thiệu)                | Giới thiệu về cổng thông tin                                                 | ✅ Xem             | ✅ Xem                        | ✅ Xem             | ✅ Xem                     | ✅ Xem                     | ✅ Xem              | ✅ Xem             |
| `/login`, `/register`                | Đăng nhập/Đăng ký                                                            | ✅ Tương tác       | Ẩn                            | Ẩn                 | Ẩn                         | Ẩn                         | Ẩn                  | Ẩn                 |
| **B. Ý tưởng (Ideas)**               |                                                                              |                    |                               |                    |                            |                            |                     |                    |
| `/ideas` (Ngân hàng Ý tưởng)         | Danh sách ý tưởng đã duyệt công khai                                         | ✅ Xem, Lọc, Thích | ✅ Xem, Lọc, Thích            | ✅ Xem, Lọc, Thích | ✅ Xem, Lọc, Thích         | ✅ Xem, Lọc, Thích         | ✅ Xem, Lọc, Thích  | ✅ Xem, Lọc, Thích |
| `/ideas/show/{id}`                   | Chi tiết ý tưởng công khai                                                   | ✅ Xem             | ✅ Xem, Comment               | ✅ Xem, Comment    | ✅ Xem                     | ✅ Xem                     | ✅ Xem, Comment     | ✅ Xem             |
| `/my-ideas` (Ý tưởng của tôi)        | Danh sách ý tưởng (nháp, đang nộp, đã duyệt) mà SV sở hữu hoặc là thành viên | ❌                 | ✅ Tạo, Sửa, Xóa (nháp), Nộp  | ❌                 | ❌                         | ❌                         | ❌                  | ❌                 |
| `/my-ideas/create`                   | Form tạo ý tưởng mới                                                         | ❌                 | ✅ Tương tác                  | ❌                 | ❌                         | ❌                         | ❌                  | ❌                 |
| `/my-ideas/edit/{id}`                | Form chỉnh sửa ý tưởng (chỉ chủ sở hữu, trước khi duyệt cuối)                | ❌                 | ✅ Tương tác                  | ❌                 | ❌                         | ❌                         | ❌                  | ❌                 |
| `/my-ideas/invite/{id}`              | Gửi và quản lý lời mời thành viên                                            | ❌                 | ✅ Tương tác (Chỉ chủ sở hữu) | ❌                 | ❌                         | ❌                         | ❌                  | ❌                 |
| **C. Phản biện & Duyệt**             |                                                                              |                    |                               |                    |                            |                            |                     |                    |
| `/review-queue` (Hàng chờ phản biện) | Danh sách ý tưởng chờ phản biện (cấp Trung tâm/BGH)                          | ❌                 | ❌                            | ❌                 | ✅ Xem, Tương tác          | ✅ Xem, Tương tác          | ❌                  | ✅ Xem (Toàn bộ)   |
| `/review/form/{id}`                  | Biểu mẫu chấm điểm, nhận xét, duyệt/từ chối                                  | ❌                 | ❌                            | ❌                 | ✅ Tương tác (Duyệt cấp 2) | ✅ Tương tác (Duyệt cấp 3) | ❌                  | ✅ Tương tác       |
| **D. Cuộc thi & Challenge**          |                                                                              |                    |                               |                    |                            |                            |                     |                    |
| `/competitions`                      | Danh sách cuộc thi (cấp trường)                                              | ✅ Xem             | ✅ Xem, Đăng ký               | ✅ Xem             | ✅ Tạo, Sửa, Xóa           | ✅ Xem                     | ✅ Xem              | ✅ Quản lý         |
| `/challenges`                        | Danh sách challenge (từ DN)                                                  | ✅ Xem             | ✅ Xem, Nộp bài               | ✅ Xem             | ✅ Xem                     | ✅ Xem                     | ✅ Tạo, Sửa, Xóa    | ✅ Quản lý         |
| **E. Hồ sơ & Bảng điều khiển**       |                                                                              |                    |                               |                    |                            |                            |                     |                    |
| `/dashboard`                         | Bảng điều khiển cá nhân (ý tưởng, thông báo...)                              | ❌                 | ✅ Xem                        | ✅ Xem (Queue)     | ✅ Xem (Stats)             | ✅ Xem (Stats)             | ✅ Xem (Challenges) | ✅ Xem (Admin)     |
| `/profile`                           | Cập nhật thông tin cá nhân, đổi mật khẩu                                     | ❌                 | ✅ Tương tác                  | ✅ Tương tác       | ✅ Tương tác               | ✅ Tương tác               | ✅ Tương tác        | ✅ Tương tác       |
| **F. Quản trị (Admin)**              |                                                                              |                    |                               |                    |                            |                            |                     |                    |
| `/admin` (Admin Panel)               | Trang quản trị tổng                                                          | ❌                 | ❌                            | ❌                 | ✅ Xem (giới hạn)          | ❌                         | ❌                  | ✅ Toàn quyền      |
| `/admin/users`                       | Quản lý tài khoản, gán quyền, duyệt tài khoản                                | ❌                 | ❌                            | ❌                 | ❌                         | ❌                         | ❌                  | ✅ Toàn quyền      |
| `/admin/approvals`                   | Duyệt tài khoản chờ (GV, DN)                                                 | ❌                 | ❌                            | ❌                 | ❌                         | ❌                         | ❌                  | ✅ Toàn quyền      |
| `/admin/taxonomies`                  | Quản lý Khoa, Lĩnh vực, Thẻ (Tags)                                           | ❌                 | ❌                            | ❌                 | ✅ Tương tác               | ❌                         | ❌                  | ✅ Toàn quyền      |

---

## 4. 🗄️ CƠ SỞ DỮ LIỆU ĐẦY ĐỦ (MySQL)

### 4.1. Các bảng đã có (Dựa trên file migrations)

#### Bảng người dùng & phân quyền:

-   `users` (id, name, email, password, role, is_approved, is_active, ...)
-   `roles` (id, name, slug)
-   `role_user` (user_id, role_id, assigned_by)

#### Bảng ý tưởng:

-   `ideas` (id, title, slug, description, summary, content, status, visibility, owner_id, category_id, faculty_id, like_count, ...)
-   `idea_members` (id, idea_id, user_id, role_in_team)
-   `idea_invitations` (id, idea_id, invited_by, email, token, status, expires_at)
-   `idea_likes` (idea_id, user_id)
-   `idea_tag` (idea_id, tag_id)

#### Bảng phân loại:

-   `categories` (id, name, slug, description, sort_order)
-   `faculties` (id, name, code, description, sort_order)
-   `tags` (id, name, slug)

#### Bảng duyệt & phản biện:

-   `review_assignments` (id, idea_id, reviewer_id, assigned_by, review_level, status)
-   `reviews` (id, assignment_id, overall_comment, decision)
-   `change_requests` (id, review_id, idea_id, request_message, is_resolved)

#### Bảng khác:

-   `attachments` (id, attachable_type, attachable_id, path, filename, mime_type, uploaded_by)
-   `organizations` (id, name, address, contact_person)
-   `profiles` (id, user_id, student_code, bio, avatar_url, organization_id)

### 4.2. Các bảng cần bổ sung

#### Bảng Cuộc thi (Quản lý bởi Trung tâm ĐMST):

```sql
CREATE TABLE competitions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    start_date DATETIME,
    end_date DATETIME,
    status ENUM('draft', 'open', 'closed', 'archived') DEFAULT 'draft',
    created_by BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE competition_registrations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    competition_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    team_name VARCHAR(255) NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (competition_id) REFERENCES competitions(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE(competition_id, user_id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE competition_submissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    registration_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    abstract TEXT,
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (registration_id) REFERENCES competition_registrations(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### Bảng Challenge (Quản lý bởi Doanh nghiệp):

```sql
CREATE TABLE challenges (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    organization_id BIGINT UNSIGNED NOT NULL,
    deadline DATETIME,
    reward VARCHAR(255),
    status ENUM('draft', 'open', 'closed') DEFAULT 'draft',
    FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE challenge_submissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    challenge_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    idea_id BIGINT UNSIGNED NULL,
    title VARCHAR(255) NOT NULL,
    solution_description TEXT,
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (idea_id) REFERENCES ideas(id) ON DELETE SET NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### Bảng ghi lại lịch sử hoạt động (Bảo mật):

```sql
CREATE TABLE audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(255) NOT NULL,
    loggable_type VARCHAR(255),
    loggable_id BIGINT UNSIGNED,
    old_values TEXT,
    new_values TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
```

---

## 5. 💡 LOGIC DỮ LIỆU & TÍNH NĂNG CHÍNH

### 5.1. Logic Chống trùng lặp ý tưởng

**Mục tiêu:** Cảnh báo SV khi họ sắp tạo một ý tưởng _tương tự_ ý tưởng đã có.

#### Cách thực hiện:

**Bước 1: Chuẩn bị CSDL (Chỉ làm 1 lần)**

Tạo migration để kích hoạt `FULLTEXT` search trên MySQL:

```bash
php artisan make:migration add_fulltext_index_to_ideas_table
```

**Nội dung file migration:**

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Thêm index FULLTEXT vào cột title và summary
        DB::statement('ALTER TABLE ideas ADD FULLTEXT(title, summary)');
    }

    public function down(): void {
        Schema::table('ideas', function (Blueprint $table) {
            $table->dropFullText(['title', 'summary']);
        });
    }
};
```

**Bước 2: Tạo Route (trong `routes/web.php`):**

```php
Route::post('/ideas/check-similarity', [IdeaController::class, 'checkSimilarity'])
     ->middleware(['auth', 'verified']);
```

**Bước 3: Tạo Controller Function:**

```php
<?php
// Trong Controller của bạn
use Illuminate\Http\Request;
use App\Models\Idea;

public function checkSimilarity(Request $request)
{
    $request->validate(['query' => 'required|string|min:10']);
    $query = $request->input('query');

    // Sử dụng FULLTEXT search
    $similarIdeas = Idea::whereRaw('MATCH(title, summary) AGAINST(? IN NATURAL LANGUAGE MODE)', [$query])
        ->where('status', 'approved_final') // Chỉ so với ý tưởng đã công khai
        ->select('id', 'title', 'slug')
        ->take(5)
        ->get();

    return response()->json($similarIdeas);
}
```

**Bước 4: Frontend (Javascript tại trang `/my-ideas/create`):**

```javascript
// Giả sử bạn có <input id="idea_title"> và <div id="similarity_results">

document.getElementById("idea_title").addEventListener("blur", async (e) => {
    let query = e.target.value;
    if (query.length < 10) return;

    let resultsDiv = document.getElementById("similarity_results");
    resultsDiv.innerHTML = "Đang kiểm tra...";

    try {
        const response = await fetch("/ideas/check-similarity", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify({ query: query }),
        });

        const ideas = await response.json();

        if (ideas.length > 0) {
            let html =
                "<strong>Cảnh báo:</strong> Đã tìm thấy các ý tưởng tương tự:<br><ul>";
            ideas.forEach((idea) => {
                html += `<li><a href="/ideas/${idea.slug}" target="_blank">${idea.title}</a></li>`;
            });
            html += "</ul>";
            resultsDiv.innerHTML = html;
        } else {
            resultsDiv.innerHTML =
                '<span style="color: green;">Tốt! Có vẻ đây là ý tưởng mới.</span>';
        }
    } catch (error) {
        resultsDiv.innerHTML = "Không thể kiểm tra trùng lặp.";
    }
});
```

### 5.2. Logic Mời thành viên

**Mục tiêu:** Cho phép SV mời bạn bè tham gia vào nhóm phát triển ý tưởng.

#### Các bước:

1. **SV A (chủ ý tưởng)** vào `/my-ideas/invite/{id}`
2. **SV A nhập email của SV B** và bấm "Mời"
3. **Hệ thống tạo `IdeaInvitation`:**
    - Tạo bản ghi trong `idea_invitations`
    - Token ngẫu nhiên, an toàn (ví dụ: `Str::random(64)`)
    - `status` = 'pending'
    - `expires_at` = now() + 7 days
4. **Hệ thống gửi Email:**
    - Sử dụng Mailable của Laravel (`IdeaInvitationMail`)
    - Email chứa link với token: `/invitations/accept/{token}`
5. **SV B nhận email, bấm vào link**
6. **Hệ thống xử lý (route `/invitations/accept/{token}`):**
    - Tìm token trong bảng `idea_invitations`
    - Kiểm tra token hợp lệ và chưa hết hạn
    - Nếu hợp lệ:
        - Lấy `idea_id` và `email` từ bản ghi invitation
        - Tìm `user_id` của SV B dựa trên `email`
        - Nếu user chưa tồn tại, yêu cầu đăng ký
        - Nếu user chưa đăng nhập, yêu cầu đăng nhập
        - Kiểm tra user đã là member chưa
        - Thêm SV B vào bảng `idea_members`
        - Cập nhật `idea_invitations.status` = 'accepted'
        - Chuyển hướng SV B đến trang chi tiết ý tưởng với thông báo thành công

---

## 6. 🔒 CÔNG NGHỆ & CHIẾN LƯỢC BẢO MẬT

### 6.1. Xác thực (Authentication)

#### Laravel Breeze:

-   ✅ CSRF Protection
-   ✅ Session Security
-   ✅ Rate Limiting (chống đăng nhập vét)
-   ✅ Password Hashing (bcrypt)

#### Xác thực Email:

-   ✅ Bắt buộc 100% người dùng phải xác thực email
-   ✅ Middleware: `EnsureEmailIsVerifiedToLogin.php`
-   ✅ Đảm bảo vai trò được gán tự động là chính xác

### 6.2. Phân quyền (Authorization)

#### Middleware:

-   ✅ `EnsureAdmin.php` cho các route `/admin/*`
-   ✅ `EnsureEmailIsVerifiedToLogin.php` cho các route yêu cầu xác thực
-   ✅ `EnsureApprovedToLogin.php` cho các route yêu cầu duyệt

**Nên tạo thêm:**

-   `EnsureRole:lecturer` cho các route chuyên biệt của giảng viên
-   `EnsureRole:student` cho các route chuyên biệt của sinh viên

#### Laravel Policies (Rất quan trọng):

**Tạo Policy cho `Idea`:**

```bash
php artisan make:policy IdeaPolicy --model=Idea
```

**Nội dung `app/Policies/IdeaPolicy.php`:**

```php
<?php
namespace App\Policies;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IdeaPolicy
{
    use HandlesAuthorization;

    /**
     * Cho phép Admin xem mọi thứ
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    /**
     * Ai được xem chi tiết ý tưởng?
     */
    public function view(User $user, Idea $idea): bool
    {
        // 1. Nếu ý tưởng là public
        if ($idea->visibility == 'public' && $idea->status == 'approved_final') {
            return true;
        }

        // 2. Nếu là chủ sở hữu
        if ($user->id === $idea->owner_id) {
            return true;
        }

        // 3. Nếu là thành viên trong nhóm
        return $idea->members->contains(function ($member) use ($user) {
            return $member->user_id === $user->id;
        });
    }

    /**
     * Ai được cập nhật ý tưởng?
     */
    public function update(User $user, Idea $idea): bool
    {
        // Chỉ chủ sở hữu và khi ý tưởng còn là bản nháp hoặc cần chỉnh sửa
        return $user->id === $idea->owner_id &&
               ($idea->status == 'draft' || $idea->needsChange());
    }

    /**
     * Ai được xóa ý tưởng?
     */
    public function delete(User $user, Idea $idea): bool
    {
        return $user->id === $idea->owner_id && $idea->status == 'draft';
    }

    /**
     * Ai được duyệt ý tưởng?
     */
    public function approve(User $user, Idea $idea): bool
    {
        // Chỉ Giảng viên, Trung tâm ĐMST, hoặc BGH
        return $user->hasRole('staff') ||
               $user->hasRole('center') ||
               $user->hasRole('board');
    }
}
```

**Sử dụng Policy trong Controller:**

```php
// Trong hàm show()
public function show(Idea $idea)
{
    // Tự động kiểm tra hàm 'view' của IdeaPolicy
    // Nếu thất bại, sẽ ném ra lỗi 403 (Cấm truy cập)
    $this->authorize('view', $idea);

    // ... code còn lại ...
}

// Trong hàm update()
public function update(Request $request, Idea $idea)
{
    $this->authorize('update', $idea);
    // ... code còn lại ...
}
```

### 6.3. Bảo vệ Dữ liệu (Input/Output)

#### Chống SQL Injection:

-   ✅ **Luôn sử dụng Eloquent hoặc Query Builder**
-   ✅ Ví dụ: `Idea::findOrFail($id)` thay vì raw SQL
-   ❌ **Không bao giờ** viết SQL thô với dữ liệu từ người dùng

#### Chống XSS (Cross-Site Scripting):

-   ✅ **Luôn sử dụng `{{ $variable }}` trong Blade**
    -   Cú pháp này tự động lọc HTML
    -   Ví dụ: `{{ $idea->title }}`
-   ⚠️ **Chỉ dùng `{!! $variable !!}` khi chắc chắn 100% nội dung đó an toàn**
    -   Ví dụ: `{!! $idea->content !!}` (nếu đã được sanitize)

#### Form Request Validation:

-   ✅ **Sử dụng các file Request cho mọi form**
    -   Ví dụ: `ProfileUpdateRequest.php`, `StoreIdeaRequest.php`
-   ✅ **Đảm bảo dữ liệu đầu vào luôn sạch sẽ**
    -   Validation rules: `required`, `email`, `min:10`, `max:255`, etc.

### 6.4. Bảo vệ File (File Uploads)

#### Vị trí lưu trữ:

-   ✅ **KHÔNG lưu file trong thư mục `public`**
-   ✅ **Lưu file trong `storage/app/private_attachments`**

#### Cách truy cập file:

**Tạo route trong `routes/web.php`:**

```php
Route::middleware(['auth', 'verified.to.login', 'approved.to.login'])->group(function () {
    Route::get('/attachments/{id}/download', [AttachmentController::class, 'download'])
        ->name('attachments.download');
});
```

**Tạo Controller Function:**

```php
<?php
namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AttachmentController extends Controller
{
    public function download($id)
    {
        $attachment = Attachment::findOrFail($id);

        // Lấy đối tượng cha (ví dụ: Idea)
        $idea = $attachment->attachable; // Giả sử attachable là Idea

        // Kiểm tra quyền
        if (!Auth::user()->can('view', $idea)) {
            abort(403, 'Bạn không có quyền truy cập file này.');
        }

        // Kiểm tra file tồn tại
        if (!Storage::exists($attachment->path)) {
            abort(404, 'File không tồn tại.');
        }

        // Trả về file để download
        return Storage::download($attachment->path, $attachment->filename);
    }
}
```

---

## 7. 📝 GHI CHÚ QUAN TRỌNG

### 7.1. Cấu hình Mail

Để gửi email mời thành viên, cần cấu hình mail trong `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@vlute.edu.vn
MAIL_FROM_NAME="${APP_NAME}"
```

### 7.2. Cấu trúc Status của Ý tưởng

```
draft → submitted_center → approved_center → submitted_board → approved_final
             ↓                    ↓
     needs_change_center   needs_change_board
             ↓                    ↓
        (Quay lại draft)     (Quay lại draft)
```

### 7.3. Các trạng thái Visibility

-   `private`: Chỉ chủ sở hữu và thành viên nhóm
-   `team_only`: Thành viên nhóm và người được mời
-   `public`: Mọi người có thể xem (sau khi duyệt)

---

## 8. 📚 TÀI LIỆU THAM KHẢO

-   [Laravel Documentation](https://laravel.com/docs)
-   [Laravel Policies](https://laravel.com/docs/authorization#creating-policies)
-   [Laravel Mail](https://laravel.com/docs/mail)
-   [MySQL FULLTEXT Search](https://dev.mysql.com/doc/refman/8.0/en/fulltext-search.html)

---

**Tài liệu này được tạo ngày:** 2025-01-XX

**Phiên bản:** 1.0

**Cập nhật lần cuối:** 2025-01-XX

Password@123

gv.cntt@vlute.edu.vn — Khoa CNTT
gv.ddt@vlute.edu.vn — Khoa Điện – Điện tử
gv.ckd@vlute.edu.vn — Khoa Cơ khí – Động lực
gv.kt@vlute.edu.vn — Khoa Kinh tế
gv.nn@vlute.edu.vn — Khoa Ngoại ngữ

student2@st.vlute.edu.vn — tên: Student Two
student3@st.vlute.edu.vn — tên: Student Three
student4@st.vlute.edu.vn — tên: Student Four
student5@st.vlute.edu.vn — tên: Student Five

1. Sinh viên
   Email: student1@st.vlute.edu.vn
   Role: student
   Approved: yes, Email verified: yes
2. Giảng viên (đã gán khoa CNTT)
   Email: gv.cntt@vlute.edu.vn
   Role: staff
   Faculty: Khoa Công nghệ thông tin (CNTT) — nếu chưa có, seeder tự tạo
   Approved: yes, Email verified: yes
3. Trung tâm ĐMST
   Email: center@vlute.edu.vn
   Role: center
   Approved: yes, Email verified: yes
4. Ban giám hiệu
   Email: board@vlute.edu.vn
   Role: board
   Approved: yes, Email verified: yes
5. Doanh nghiệp
   Email: hr@acme.example
   Role: enterprise
   Approved: yes, Email verified: yes
   Company: ACME Corp
