{{-- resources/views/auth/verify-email.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Xác thực email - VLUTE Innovation Hub</title>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="verify-email-page">
  <div class="wrap">
    <div class="card">
      <div class="brand">
        <img src="{{ asset('images/logotruong.jpg') }}" alt="VLUTE"/>
      </div>
      <h1 class="title">Xác nhận địa chỉ email</h1>
      <p class="muted">Bạn cần xác thực email trước khi truy cập các tính năng nội bộ.</p>

      @if (session('status') == 'verification-link-sent')
        <div class="status">Đã gửi lại liên kết xác nhận. Hãy kiểm tra hộp thư.</div>
      @endif

      <div class="row">
        {{-- Resend link (yêu cầu đang đăng nhập, và bạn đang ở trang notice nên OK) --}}
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

  {{-- Modal "Không thể đăng nhập" --}}
  <div id="modal-login-block" class="modal">
    <div class="modal__card">
      <button class="modal__close" data-close="modal-login-block">&times;</button>
      <h3 class="modal__title">Không thể truy cập</h3>
      <p class="modal__desc">
        Tài khoản của bạn <strong>chưa xác thực email</strong>. Vui lòng mở hộp thư đã đăng ký và nhấn liên kết xác nhận.
        Nếu chưa nhận được, hãy nhấn <em>Gửi lại email xác nhận</em>.
      </p>
      <div class="modal__actions">
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
