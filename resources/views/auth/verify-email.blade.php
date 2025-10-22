{{-- resources/views/auth/verify-email.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Xác thực email - VLUTE Innovation Hub</title>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <style>
    body{margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#f3f4f6;color:#111827}
    .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;
      background:linear-gradient(rgba(0,0,0,.5),rgba(0,0,0,.5)), url('{{ asset('images/panel-truong.jpg') }}') center/cover no-repeat; padding:24px;}
    .card{width:100%;max-width:560px;background:#fff;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.1);padding:24px}
    .logo{width:64px;height:64px;border-radius:8px;object-fit:cover;display:block;margin:0 auto 12px}
    .title{text-align:center;font-weight:800;margin:0 0 8px}
    .muted{text-align:center;color:#6b7280;margin:0 0 16px}
    .row{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
    .btn{border:none;border-radius:8px;padding:12px 16px;cursor:pointer;font-weight:800}
    .btn-primary{background:#2563eb;color:#fff}
    .btn-outline{background:#fff;color:#111827;border:1px solid #e5e7eb}
    .status{color:#059669;font-weight:700;text-align:center;margin-top:8px}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <img src="{{ asset('images/logotruong.jpg') }}" class="logo" alt="VLUTE"/>
      <h1 class="title">Xác nhận địa chỉ email</h1>
      <p class="muted">Chúng tôi đã gửi email xác nhận. Vui lòng mở email và nhấn vào liên kết để kích hoạt tài khoản.</p>

      @if (session('status') == 'verification-link-sent')
        <div class="status">Đã gửi lại liên kết xác nhận. Hãy kiểm tra hộp thư.</div>
      @endif

      <div class="row" style="margin-top:10px">
        <form method="POST" action="{{ route('verification.send') }}">
          @csrf
          <button type="submit" class="btn btn-primary">Gửi lại email xác nhận</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-outline">Đăng xuất</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>