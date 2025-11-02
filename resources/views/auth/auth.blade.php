{{-- resources/views/auth/auth.blade.php --}}
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

<body class="auth-page" data-active-tab="{{ $activeTab ?? 'login' }}"
  data-unapproved="{{ session('unapproved') ? '1' : '' }}"
  data-unapproved-email="{{ session('unapproved_email') ?? '' }}"
  data-unverified="{{ session('unverified') ? '1' : '' }}"
  data-unverified-email="{{ session('unverified_email') ?? '' }}">
  <div class="auth-wrap">
    <div class="card">
      <div class="auth-header">
        <img src="{{ asset('images/logotruong.jpg') }}" alt="VLUTE" class="logo" />
        <h1>HỆ THỐNG XÁC THỰC TẬP TRUNG (SSO)</h1>
      </div>

      <div class="tabs">
        <button id="tab-login" class="{{ ($activeTab ?? 'login') === 'login' ? 'active' : '' }}">Đăng nhập</button>
        <button id="tab-register" class="{{ ($activeTab ?? 'login') === 'register' ? 'active' : '' }}">Đăng ký</button>
      </div>

      {{-- LOGIN --}}
      <div id="panel-login" class="{{ ($activeTab ?? 'login') === 'login' ? '' : 'hidden' }}">
        <form action="{{ route('login') }}" method="POST">
          @csrf
          @if ($errors->any() && old('_token') && !old('name'))
            <div class="auth-alert auth-alert--error">{{ $errors->first() }}</div>
          @endif
          @if (session('status') && ($activeTab ?? 'login') === 'login')
            <div class="auth-alert auth-alert--success">{{ session('status') }}</div>
          @endif

          <div class="group">
            <label for="login-email">E-mail</label>
            <div class="field"><input id="login-email" name="email" type="email" value="{{ old('email') }}"
                placeholder="Nhập email" required autofocus></div>
          </div>

          <div class="group">
            <label for="login-password">Mật khẩu</label>
            <div class="field has-eye">
              <input id="login-password" name="password" type="password" placeholder="Nhập mật khẩu" required>
              <button class="eye-toggle" type="button" aria-label="Hiện/ẩn mật khẩu" data-toggle="#login-password">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
              </button>
            </div>
          </div>

          <div class="options">
            <label class="auth-remember"><input type="checkbox" name="remember"> Ghi nhớ</label>
            <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
          </div>

          <button class="btn-primary" type="submit">Đăng nhập</button>
        </form>

        <div class="divider">Hoặc đăng nhập với</div>
        <button class="btn-social-dndk" type="button" data-google-login>Google</button>
      </div>

      {{-- REGISTER --}}
      <div id="panel-register"
        class="{{ ($activeTab ?? 'login') === 'register' || ($errors->any() && old('name')) ? '' : 'hidden' }}">
        <form id="reg-form" action="{{ route('register') }}" method="POST">
          @csrf
          @if ($errors->any() && old('name'))
            <div class="auth-alert auth-alert--error">
              <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="group">
            <label for="reg-name">Họ và tên</label>
            <div class="field"><input id="reg-name" name="name" type="text" value="{{ old('name') }}"
                placeholder="Nhập họ và tên" required></div>
          </div>

          <div class="group">
            <label for="reg-email">E-mail</label>
            <div class="field"><input id="reg-email" name="email" type="email" value="{{ old('email') }}"
                placeholder="Nhập email của bạn" required></div>
          </div>

          {{-- Enterprise fields (hiển thị khi email không phải @vlute.edu.vn hoặc @st.vlute.edu.vn) --}}
          <div id="enterprise-fields" class="hidden">
            <div class="enterprise-fields-header">
              <p class="enterprise-note">Vui lòng điền thông tin doanh nghiệp để được phê duyệt tài khoản.</p>
            </div>
            <div class="group">
              <label for="reg-company">Tên công ty/doanh nghiệp <span class="required">*</span></label>
              <div class="field"><input id="reg-company" name="company" type="text" value="{{ old('company') }}"
                  placeholder="Nhập tên công ty/doanh nghiệp" class="enterprise-field"></div>
            </div>
            <div class="group">
              <label for="reg-position">Vị trí công tác <span class="required">*</span></label>
              <div class="field"><input id="reg-position" name="position" type="text" value="{{ old('position') }}"
                  placeholder="VD: Trưởng phòng nhân sự" class="enterprise-field"></div>
            </div>
            <div class="group">
              <label for="reg-interest">Lĩnh vực quan tâm <span class="required">*</span></label>
              <div class="field">
                <select id="reg-interest" name="interest" class="enterprise-field">
                  <option value="">-- Chọn lĩnh vực --</option>
                  <option value="it" {{ old('interest') === 'it' ? 'selected' : '' }}>Công nghệ thông tin</option>
                  <option value="agritech" {{ old('interest') === 'agritech' ? 'selected' : '' }}>Nông nghiệp công nghệ
                    cao</option>
                  <option value="mechanics" {{ old('interest') === 'mechanics' ? 'selected' : '' }}>Cơ khí / Tự động hóa
                  </option>
                  <option value="other" {{ old('interest') === 'other' ? 'selected' : '' }}>Khác</option>
                </select>
              </div>
            </div>
          </div>

          <div class="group">
            <label for="reg-password">Mật khẩu</label>
            <div class="field has-eye">
              <input id="reg-password" name="password" type="password" placeholder="Tạo mật khẩu" required>
              <button class="eye-toggle" type="button" aria-label="Hiện/ẩn mật khẩu" data-toggle="#reg-password">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
              </button>
            </div>

            {{-- thanh độ mạnh giữ nguyên như cũ của bạn --}}
            <div class="pw-hint" id="pw-hint-register">
              <div class="pw-bar"><span id="pw-bar-register"></span></div>
              <div id="pw-r1" class="pw-bad">Tối thiểu 8 ký tự</div>
              <div id="pw-r2" class="pw-bad">Có chữ hoa và chữ thường</div>
              <div id="pw-r3" class="pw-bad">Có số</div>
              <div id="pw-r4" class="pw-bad">Có ký tự đặc biệt (!@#$%^&amp;*...)</div>
              <div id="pw-r5" class="pw-bad">Không chứa phần tên email</div>
            </div>
          </div>

          {{-- XÁC NHẬN MẬT KHẨU (mới) --}}
          <div class="group">
            <label for="reg-password-confirm">Xác nhận mật khẩu</label>
            <div class="field has-eye">
              <input id="reg-password-confirm" name="password_confirmation" type="password"
                placeholder="Nhập lại mật khẩu" required>
              <button class="eye-toggle" type="button" aria-label="Hiện/ẩn mật khẩu"
                data-toggle="#reg-password-confirm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
              </button>
            </div>
            <div id="match-note" class="match-note"></div>
          </div>

          <button id="btn-register" class="btn-primary" type="submit">Đăng ký</button>

          <p class="auth-disclaimer">
            Bằng việc đăng ký, bạn đồng ý với <a href="javascript:void(0)">Điều khoản Dịch vụ</a>.
          </p>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal cho unapproved/unverified users --}}
  <div id="modal-login-block" class="modal">
    <div class="modal__card">
      <button class="modal__close" data-close="modal-login-block">&times;</button>
      <h3 class="modal__title">Không thể truy cập</h3>
      <div id="modal-login-block-body" class="modal__desc"></div>
      <div class="modal__actions">
        <button type="button" class="btn btn-primary" data-close="modal-login-block">Đã hiểu</button>
      </div>
    </div>
  </div>
</body>

</html>