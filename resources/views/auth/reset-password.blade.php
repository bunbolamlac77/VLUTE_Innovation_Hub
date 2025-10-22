{{-- resources/views/auth/reset-password.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Đặt lại mật khẩu - VLUTE Innovation Hub</title>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <style>
    :root{--blue:#2563eb;--border:#e5e7eb;--text:#0f172a;--muted:#64748b;--card:#fff;--radius:22px;--shadow:0 20px 40px rgba(0,0,0,.12)}
    *{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#f3f4f6;color:var(--text)}
    a{color:var(--blue);text-decoration:none} a:hover{text-decoration:underline}

    .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;
      background:linear-gradient(rgba(0,0,0,.45),rgba(0,0,0,.45)),url('{{ asset('images/panel-truong.jpg') }}') center/cover no-repeat}
    .card{width:100%;max-width:640px;background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);padding:36px}

    .brand{display:flex;flex-direction:column;align-items:center;gap:6px;margin-bottom:6px}
    .brand img{width:64px;height:64px;border-radius:12px;object-fit:cover}
    .brand-name{font-weight:800;letter-spacing:.04em}

    .title{margin:8px 0 16px;text-align:center;font-size:32px;line-height:1.1;font-weight:800}

    .error{margin:8px 0;color:#b91c1c;font-weight:700}

    .group{margin-bottom:18px}
    .group label{display:block;font-weight:800;margin-bottom:8px}

    .field{position:relative}
    .field input{width:100%;padding:14px 46px 14px 14px;border:1px solid var(--border);border-radius:12px;font-size:16px;background:#fff}
    .field input:focus{outline:none;border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,235,.18)}
    .has-eye{position:relative}
    .eye-toggle{
      position:absolute;right:10px;top:50%;transform:translateY(-50%);
      width:30px;height:30px;border:none;background:transparent;color:#374151;cursor:pointer;display:flex;align-items:center;justify-content:center
    }
    .eye-toggle svg{width:22px;height:22px}

    .btn{width:100%;padding:14px;border:none;border-radius:12px;background:var(--blue);color:#fff;font-weight:800;font-size:16px;cursor:pointer}
    .btn:hover{background:#1d4ed8}
    .note{text-align:center;color:var(--muted);font-size:14px;margin-top:10px}
  </style>
</head>
<body>
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

  <script>
    // Toggle ẩn/hiện mật khẩu (dùng chung cho 2 ô)
    document.addEventListener('click', function(e){
      const btn = e.target.closest('.eye-toggle'); if(!btn) return;
      const sel = btn.getAttribute('data-toggle'); const input = document.querySelector(sel);
      if(!input) return; input.type = (input.type === 'password') ? 'text' : 'password';
    });
  </script>
</body>
</html>