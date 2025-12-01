# Tailwind CSS – Hướng dẫn sử dụng trong dự án VLUTE Innovation Hub

Tài liệu này tóm tắt chuẩn sử dụng Tailwind trong dự án, các token (màu sắc, radius, shadow), mẫu (pattern) thành phần hay dùng và cách build.

## 1) Cấu hình Tailwind trong dự án

File: `tailwind.config.js`

- Content (đường quét)
  - `./resources/views/**/*.blade.php`
  - `./storage/framework/views/*.php`
  - View từ Laravel Pagination của framework
- Theme
  - container: center, padding 1rem, 2xl = 1400px
  - Colors mở rộng:
    - brand-navy: #0a0f5a
    - brand-green: #0aa84f
    - brand-gray-50: #f5f7fb
    - brand-gray-100: #eef2f7
  - Shadow: `shadow-card` = `0 8px 24px rgba(2,16,43,.08)`
  - Radius mặc định: 16px
  - Font: Inter (kế thừa system-ui)
  - Keyframes/Animation cho toast: slide-in/out-right, fade-in/out
- Plugins: `@tailwindcss/forms`

## 2) Build/Run

- Dev: `npm run dev`
- Prod: `npm run build`
- Laravel vite đã được cấu hình gọi `resources/css/app.css` và `resources/js/app.js`.

## 3) Quy ước sử dụng

- Ưu tiên utility class của Tailwind. Hạn chế viết CSS thuần.
- Background hình ảnh: dùng arbitrary value `bg-[url('/images/...')]` + lớp phủ overlay (gradient/rgba) bằng một `div` tuyệt đối `absolute inset-0`.
- Spacing/typography: dùng spacing scale mặc định (`p-4`, `py-6`, `gap-4`), font Extrabold cho tiêu đề chính.
- Border radius nhất quán: `rounded-2xl` hoặc `rounded-xl` cho card/modal.
- Shadow card: `shadow-card` (đã extend trong config).
- Container: dùng `.container` để canh giữa và giới hạn bề rộng.

## 4) Token (quick reference)

- Màu:
  - Nền chủ đạo: `bg-brand-navy`, `text-brand-navy`
  - Điểm nhấn: `text-brand-green`, `bg-brand-gray-50`, `bg-brand-gray-100`
- Border/shadow:
  - Border nhạt: `border-slate-200`, input: `border-slate-300`
  - Card: `shadow-card`
- Radius: `rounded-xl`, `rounded-2xl`, button pill: `rounded-full`

## 5) Pattern thành phần thường dùng

### Header (đã refactor)

```html
<div class="bg-brand-navy text-white">
  <div class="container flex items-center justify-between gap-4 py-3">
    <!-- Logo + title -->
    <!-- Actions + user menu -->
  </div>
</div>
<header class="sticky top-0 z-40 bg-white border-b border-slate-200">
  <div class="container flex items-center gap-6 py-3">
    <nav class="hidden sm:flex items-center gap-2">
      <a class="font-bold px-3 py-2 rounded-lg hover:bg-brand-gray-50" href="#">Trang chủ</a>
      <!-- ... -->
    </nav>
    <form class="ml-auto flex items-center gap-2">
      <input class="w-64 rounded-full border border-slate-300 px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500" />
      <button class="rounded-full border border-slate-300 bg-white font-bold px-4 py-2 text-sm hover:bg-slate-50">Tìm</button>
    </form>
  </div>
</header>
```

### Hero với overlay

```html
<section class="relative text-white">
  <div class="absolute inset-0 bg-[url('/images/panel-truong.jpg')] bg-cover bg-center"></div>
  <div class="absolute inset-0 bg-gradient-to-tr from-brand-navy/90 to-brand-green/75"></div>
  <div class="relative">
    <div class="container py-20 min-h-[500px] grid lg:grid-cols-[1.4fr,0.9fr] gap-8 items-center">
      <!-- content -->
    </div>
  </div>
</section>
```

### Card

```html
<article class="bg-white border border-slate-200 rounded-2xl shadow-card p-5">
  <h4 class="text-lg font-bold mb-2">Tiêu đề</h4>
  <p class="text-slate-600">Nội dung…</p>
</article>
```

### Nút

```html
<a class="inline-flex items-center gap-2 rounded-full bg-white text-brand-navy px-4 py-2 font-bold border border-transparent hover:brightness-95">Primary</a>
<a class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-2 font-semibold hover:bg-slate-50">Ghost</a>
```

### Form input

```html
<label class="block font-bold text-sm mb-2">Nhãn</label>
<input class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
```

### Modal (overlay)

```html
<div id="modal-id" class="fixed inset-0 bg-black/60 hidden items-center justify-center p-5">
  <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-lg relative">
    <button class="absolute top-2 right-3 text-slate-500 hover:text-slate-700">&times;</button>
    <!-- content -->
  </div>
</div>
```

### Badge/Chip

```html
<span class="inline-block bg-brand-gray-100 text-slate-700 px-2.5 py-1 rounded-full text-xs">Chip</span>
<span class="inline-block bg-emerald-50 text-emerald-700 px-3 py-1 rounded-lg text-sm border border-emerald-200">OK</span>
<span class="inline-block bg-rose-50 text-rose-700 px-3 py-1 rounded-lg text-sm border border-rose-200">WARN</span>
```

### Toast

- Dùng animation đã khai báo trong config: `animate-slide-in-right`, `animate-slide-out-right`.

## 6) Lưu ý khi dùng Tailwind

- Arbitrary values (ví dụ `bg-[url('/images/...')]`) chỉ hoạt động trong template (Blade) được Tailwind quét. Tránh đặt trong string runtime.
- Nếu cần line-clamp, có thể dùng `max-h` + `overflow-hidden` hoặc cài plugin line-clamp.
- Với trang có bảng nhiều cột (admin), dùng `overflow-auto`, `min-w-[800px]`… để cuộn ngang.

## 7) Lộ trình chuyển đổi (đã/đang làm)

- Đã chuyển: header/footer, trang chính (welcome), trang công khai: ideas/index, events/index, competitions/index/show, auth (login/register, verify, forgot, reset).
- Đang/Chưa chuyển: trang quản trị (admin/*), my-ideas/*, manage/*, một số trang search/ideas-show đã được chuyển một phần. Tiếp tục refactor để bỏ hoàn toàn CSS thuần.

## 8) FAQ

- Làm sao chỉnh container rộng tối đa? Sửa `container.screens['2xl']` trong `tailwind.config.js`.
- Thêm màu thương hiệu mới? Extend `theme.extend.colors` trong config.
- Không generate lớp? Kiểm tra `content[]` đã bao phủ file Blade chứa lớp đó.

---

Cần hỗ trợ thêm, liên hệ maintainer dự án hoặc mở issue. 

