{{-- resources/views/auth/forgot-password.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quên mật khẩu - VLUTE Innovation Hub</title>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <style>
    body{margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#f3f4f6;color:#111827}
    .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;
      background:linear-gradient(rgba(0,0,0,.5),rgba(0,0,0,.5)), url('{{ asset('images/panel-truong.jpg') }}') center/cover no-repeat; padding:24px;}
    .card{width:100%;max-width:460px;background:#fff;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.1);padding:24px}
    .logo{width:64px;height:64px;border-radius:8px;object-fit:cover;display:block;margin:0 auto 12px}
    .title{text-align:center;font-weight:800;margin:0 0 8px}
    .group{margin-bottom:16px}
    .group label{display:block;font-weight:700;margin-bottom:8px}
    .group input{width:100%;padding:12px 14px;border:1px solid #e5e7eb;border-radius:8px}
    .btn{width:100%;padding:12px 16px;border:none;border-radius:8px;background:#2563eb;color:#fff;font-weight:800;cursor:pointer}
    .status{color:#059669;font-weight:700;text-align:center;margin-top:8px}
    a{color:#2563eb;text-decoration:none} a:hover{text-decoration:underline}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <img src="{{ asset('images/logotruong.jpg') }}" class="logo" alt="VLUTE"/>
      <h1 class="title">Quên mật khẩu</h1>

      @if (session('status'))
        <div class="status">{{ session('status') }}</div>
      @endif
      @if ($errors->any())
        <div style="margin:10px 0;color:#b91c1c;font-weight:600;">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="group">
          <label for="email">E-mail</label>
          <input id="email" name="email" type="email" required autofocus />
        </div>
        <button class="btn" type="submit">Gửi đường dẫn đặt lại mật khẩu</button>
      </form>

      <div style="text-align:center;margin-top:12px">
        <a href="{{ route('login') }}">Quay về đăng nhập</a>
      </div>
    </div>
  </div>
</body>
</html>