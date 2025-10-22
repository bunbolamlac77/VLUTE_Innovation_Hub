{{-- resources/views/auth/forgot-password.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quên mật khẩu - VLUTE Innovation Hub</title>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <style>
    :root{--blue:#2563eb;--border:#e5e7eb;--text:#0f172a;--muted:#64748b;--card:#fff;--radius:22px;--shadow:0 20px 40px rgba(0,0,0,.12)}
    *{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#f3f4f6;color:var(--text)}
    a{color:var(--blue);text-decoration:none} a:hover{text-decoration:underline}

    .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;
      background:linear-gradient(rgba(0,0,0,.45),rgba(0,0,0,.45)),url('{{ asset('images/panel-truong.jpg') }}') center/cover no-repeat}
    .card{width:100%;max-width:640px;background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);padding:36px}

    .brand{display:flex;flex-direction:column;align-items:center;gap:8px;margin-bottom:6px}
    .brand img{width:72px;height:72px;border-radius:12px;object-fit:cover}
    .title{margin:8px 0 16px;text-align:center;font-size:40px;line-height:1.1;font-weight:800;letter-spacing:.01em}

    .status{color:#059669;font-weight:800;text-align:center;margin-bottom:8px}

    .group{margin-bottom:18px}
    .group label{display:block;font-weight:800;margin-bottom:8px}
    .field{position:relative}
    .field input{width:100%;padding:14px;border:1px solid var(--border);border-radius:12px;font-size:16px;background:#fff}
    .field input:focus{outline:none;border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,235,.18)}

    .btn{width:100%;padding:14px;border:none;border-radius:12px;background:var(--blue);color:#fff;font-weight:800;font-size:16px;cursor:pointer}
    .btn:hover{background:#1d4ed8}

    .link{text-align:center;margin-top:14px;font-size:18px}
    .error{margin:8px 0;color:#b91c1c;font-weight:700}
  </style>
</head>
<body>
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