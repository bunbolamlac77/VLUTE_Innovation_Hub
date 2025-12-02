{{-- resources/views/auth/auth.blade.php (Tailwind utilities) --}}
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đăng nhập & Đăng ký - VLUTE Innovation Hub</title>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body data-active-tab="{{ $activeTab ?? 'login' }}" data-unapproved="{{ session('unapproved') ? '1' : '' }}"
  data-unapproved-email="{{ session('unapproved_email') ?? '' }}"
  data-unverified="{{ session('unverified') ? '1' : '' }}"
  data-unverified-email="{{ session('unverified_email') ?? '' }}" class="font-sans text-slate-900 auth-page">
  <div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center"
      style="background-image: url('{{ asset('images/panel-truong.jpg') }}')"></div>
    <div class="absolute inset-0 bg-black/45"></div>

    <div class="relative w-full max-w-xl bg-white rounded-2xl shadow-xl p-8">
      <div class="text-center mb-6">
        <img src="{{ asset('images/logotruong.jpg') }}" alt="VLUTE"
          class="w-16 h-16 rounded-md object-cover mx-auto mb-3" />
        <h1 class="m-0 text-base font-extrabold text-slate-900 tracking-wide">HỆ THỐNG XÁC THỰC TẬP TRUNG (SSO)</h1>
      </div>

      {{-- Tabs --}}
      <div class="flex bg-indigo-100 rounded-full p-1 mb-6">
        <button id="tab-login" class="flex-1 px-3 py-2 rounded-full font-extrabold text-slate-500">Đăng nhập</button>
        <button id="tab-register" class="flex-1 px-3 py-2 rounded-full font-extrabold text-slate-500">Đăng ký</button>
      </div>

      {{-- LOGIN --}}
      <div id="panel-login" class="{{ ($activeTab ?? 'login') === 'login' ? '' : 'hidden' }}">
        <form action="{{ route('login') }}" method="POST" class="space-y-4">
          @csrf
          @if ($errors->any() && old('_token') && !old('name'))
            <div class="text-red-700 font-semibold">{{ $errors->first() }}</div>
          @endif
          @if (session('status') && ($activeTab ?? 'login') === 'login')
            <div class="text-emerald-700 font-semibold">{{ session('status') }}</div>
          @endif

          <div>
            <label for="login-email" class="block font-bold text-sm mb-2">E-mail</label>
            <input id="login-email" name="email" type="email" value="{{ old('email') }}" placeholder="Nhập email"
              required autofocus
              class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
          </div>

          <div>
            <label for="login-password" class="block font-bold text-sm mb-2">Mật khẩu</label>
            <div class="relative">
              <input id="login-password" name="password" type="password" placeholder="Nhập mật khẩu" required
                class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
              <button
                class="eye-toggle absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 grid place-items-center text-slate-600"
                type="button" aria-label="Hiện/ẩn mật khẩu" data-toggle="#login-password">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                  <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
              </button>
            </div>
          </div>

          <div class="flex items-center justify-between text-sm mb-1">
            <label class="inline-flex items-center gap-2 cursor-pointer"><input type="checkbox" name="remember"
                class="rounded" /> Ghi nhớ</label>
            <a href="{{ route('password.request') }}" class="text-indigo-600 hover:underline">Quên mật khẩu?</a>
          </div>

          <button class="w-full rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-4 py-3"
            type="submit">Đăng nhập</button>
        </form>

        <div class="flex items-center my-5 text-slate-500 text-sm">
          <span class="flex-1 border-t"></span><span class="px-3">Hoặc đăng nhập với</span><span
            class="flex-1 border-t"></span>
        </div>
        <button class="w-full rounded-xl border border-slate-300 bg-white font-bold px-4 py-3 hover:bg-slate-50"
          type="button" data-google-login>Google</button>
      </div>

      {{-- REGISTER --}}
      <div id="panel-register"
        class="{{ ($activeTab ?? 'login') === 'register' || ($errors->any() && old('name')) ? '' : 'hidden' }}">
        <form id="reg-form" action="{{ route('register') }}" method="POST" class="space-y-4">
          @csrf
          @if ($errors->any() && old('name'))
            <div class="text-red-700 font-semibold">
              <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div>
            <label for="reg-name" class="block font-bold text-sm mb-2">Họ và tên</label>
            <input id="reg-name" name="name" type="text" value="{{ old('name') }}" placeholder="Nhập họ và tên" required
              class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
          </div>

          <div>
            <label for="reg-email" class="block font-bold text-sm mb-2">E-mail</label>
            <input id="reg-email" name="email" type="email" value="{{ old('email') }}" placeholder="Nhập email của bạn"
              required
              class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
          </div>

          {{-- Enterprise fields (ẩn/hiện bởi JS) --}}
          <div id="enterprise-fields" class="hidden">
            <div class="mb-2 rounded-lg bg-sky-50 border-l-4 border-indigo-600 p-3">
              <p class="m-0 text-xs text-indigo-900 font-semibold">Vui lòng điền thông tin doanh nghiệp để được phê
                duyệt tài khoản.</p>
            </div>
            <div>
              <label for="reg-company" class="block font-bold text-sm mb-2">Tên công ty/doanh nghiệp <span
                  class="text-red-500">*</span></label>
              <input id="reg-company" name="company" type="text" value="{{ old('company') }}"
                placeholder="Nhập tên công ty/doanh nghiệp"
                class="enterprise-field w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>
            <div>
              <label for="reg-position" class="block font-bold text-sm mb-2">Vị trí công tác <span
                  class="text-red-500">*</span></label>
              <input id="reg-position" name="position" type="text" value="{{ old('position') }}"
                placeholder="VD: Trưởng phòng nhân sự"
                class="enterprise-field w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>
            <div>
              <label for="reg-interest" class="block font-bold text-sm mb-2">Lĩnh vực quan tâm <span
                  class="text-red-500">*</span></label>
              <select id="reg-interest" name="interest"
                class="enterprise-field w-full rounded-xl border border-slate-300 px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">-- Chọn lĩnh vực --</option>
                <option value="it" {{ old('interest') === 'it' ? 'selected' : '' }}>Công nghệ thông tin</option>
                <option value="agritech" {{ old('interest') === 'agritech' ? 'selected' : '' }}>Nông nghiệp công nghệ cao
                </option>
                <option value="mechanics" {{ old('interest') === 'mechanics' ? 'selected' : '' }}>Cơ khí / Tự động hóa
                </option>
                <option value="other" {{ old('interest') === 'other' ? 'selected' : '' }}>Khác</option>
              </select>
            </div>
          </div>

          <div>
            <label for="reg-password" class="block font-bold text-sm mb-2">Mật khẩu</label>
            <div class="relative">
              <input id="reg-password" name="password" type="password" placeholder="Tạo mật khẩu" required
                class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
              <button
                class="eye-toggle absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 grid place-items-center text-slate-600"
                type="button" aria-label="Hiện/ẩn mật khẩu" data-toggle="#reg-password">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                  <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
              </button>
            </div>
            {{-- Strength bar --}}
            <div class="h-1.5 rounded-full bg-slate-200 mt-2 overflow-hidden"><span id="pw-bar-register"
                class="block h-full w-0 bg-gradient-to-r from-rose-500 via-amber-500 to-emerald-500 transition-[width] duration-200"></span>
            </div>
            <ul class="text-xs mt-2 space-y-1">
              <li id="pw-r1" class="text-rose-700">Tối thiểu 8 ký tự</li>
              <li id="pw-r2" class="text-rose-700">Có chữ hoa và chữ thường</li>
              <li id="pw-r3" class="text-rose-700">Có số</li>
              <li id="pw-r4" class="text-rose-700">Có ký tự đặc biệt (!@#$%^&amp;*...)</li>
              <li id="pw-r5" class="text-rose-700">Không chứa phần tên email</li>
            </ul>
          </div>

          <div>
            <label for="reg-password-confirm" class="block font-bold text-sm mb-2">Xác nhận mật khẩu</label>
            <div class="relative">
              <input id="reg-password-confirm" name="password_confirmation" type="password"
                placeholder="Nhập lại mật khẩu" required
                class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
              <button
                class="eye-toggle absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 grid place-items-center text-slate-600"
                type="button" aria-label="Hiện/ẩn mật khẩu" data-toggle="#reg-password-confirm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                  <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
              </button>
            </div>
            <div id="match-note" class="text-xs mt-1"></div>
          </div>

          <button id="btn-register"
            class="w-full rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-4 py-3"
            type="submit">Đăng ký</button>
          <p class="text-center text-sm mt-3">Bằng việc đăng ký, bạn đồng ý với <a href="javascript:void(0)"
              class="underline">Điều khoản Dịch vụ</a>.</p>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal cho unapproved/unverified users --}}
  <div id="modal-login-block" class="fixed inset-0 bg-black/60 hidden items-center justify-center p-5 z-50">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-lg relative">
      <button class="absolute top-2 right-3 text-slate-500 hover:text-slate-700"
        data-close="modal-login-block">&times;</button>
      <h3 class="m-0 mb-2 text-xl font-extrabold">Không thể truy cập</h3>
      <div id="modal-login-block-body" class="text-slate-600"></div>
      <div class="mt-4 flex justify-end gap-2">
        <button type="button" class="rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-4 py-2"
          data-close="modal-login-block">Đã hiểu</button>
      </div>
    </div>
  </div>
</body>

</html>