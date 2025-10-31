{{-- resources/views/auth/reset-password.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Đặt lại mật khẩu - VLUTE Innovation Hub</title>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="reset-password-page">
  <div class="wrap">
    <div class="card">
      <div class="brand">
        <img src="{{ asset('images/logotruong.jpg') }}" alt="VLUTE">
        <div class="brand-name">VLUTE</div>
      </div>

      <h1 class="title">Đặt lại mật khẩu</h1>

      @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="group">
          <label for="email">E-mail</label>
          <div class="field">
            <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus>
          </div>
        </div>

        <div class="group">
          <label for="password">Mật khẩu mới</label>
          <div class="field has-eye">
            <input id="password" name="password" type="password" required>
            <button class="eye-toggle" type="button" aria-label="Hiện/ẩn mật khẩu" data-toggle="#password">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <div class="group">
          <label for="password_confirmation">Xác nhận mật khẩu</label>
          <div class="field has-eye">
            <input id="password_confirmation" name="password_confirmation" type="password" required>
            <button class="eye-toggle" type="button" aria-label="Hiện/ẩn mật khẩu" data-toggle="#password_confirmation">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <button class="btn" type="submit">Cập nhật mật khẩu</button>
        <div class="note"><a href="{{ route('login') }}">Quay về đăng nhập</a></div>
      </form>
    </div>
  </div>
</body>
</html>
