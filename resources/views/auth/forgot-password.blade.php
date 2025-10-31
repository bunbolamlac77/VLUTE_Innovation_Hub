{{-- resources/views/auth/forgot-password.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quên mật khẩu - VLUTE Innovation Hub</title>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="forgot-password-page">
  <div class="wrap">
    <div class="card">
      <div class="brand">
        <img src="{{ asset('images/logotruong.jpg') }}" alt="VLUTE">
      </div>

      <h1 class="title">Quên mật khẩu</h1>

      @if (session('status'))
        <div class="status">{{ session('status') }}</div>
      @endif

      @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="group">
          <label for="email">E-mail</label>
          <div class="field">
            <input id="email" name="email" type="email" placeholder="Nhập email đăng ký" required autofocus>
          </div>
        </div>

        <button class="btn" type="submit">Gửi đường dẫn đặt lại mật khẩu</button>
      </form>

      <div class="link"><a href="{{ route('login') }}">Quay về đăng nhập</a></div>
    </div>
  </div>
</body>
</html>
