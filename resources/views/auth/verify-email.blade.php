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
    :root{--blue:#2563eb;--border:#e5e7eb;--text:#0f172a;--muted:#64748b;--card:#fff;--r:22px;--shadow:0 20px 40px rgba(0,0,0,.12)}
    *{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#f3f4f6;color:var(--text)}
    a{color:var(--blue);text-decoration:none} a:hover{text-decoration:underline}
    .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;
      background:linear-gradient(rgba(0,0,0,.45),rgba(0,0,0,.45)), url('{{ asset('images/panel-truong.jpg') }}') center/cover no-repeat;}
    .card{width:100%;max-width:640px;background:var(--card);border-radius:var(--r);box-shadow:var(--shadow);padding:28px 28px 22px}
    .brand{display:flex;flex-direction:column;align-items:center;gap:8px;margin-bottom:4px}
    .brand img{width:64px;height:64px;border-radius:12px;object-fit:cover}
    .title{text-align:center;font-weight:800;margin:6px 0 10px;font-size:26px}
    .muted{text-align:center;color:var(--muted);margin:0 0 12px}
    .row{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin-top:8px}
    .btn{border:none;border-radius:12px;padding:12px 16px;cursor:pointer;font-weight:800}
    .btn-primary{background:#2563eb;color:#fff}
    .btn-outline{background:#fff;color:#0f172a;border:1px solid #e5e7eb}
    .status{color:#059669;font-weight:800;text-align:center;margin:8px 0}

    /* Modal chặn đăng nhập */
    .modal{position:fixed;inset:0;background:rgba(0,0,0,.6);display:none;align-items:center;justify-content:center;z-index:1000;padding:20px}
    .modal.show{display:flex}
    .modal__card{background:#fff;border-radius:16px;box-shadow:var(--shadow);padding:22px;width:95%;max-width:600px;position:relative;max-height:90vh;overflow:auto}
    .modal__close{position:absolute;top:10px;right:12px;background:none;border:none;font-size:24px;color:#6b7280;cursor:pointer}
    .modal__title{margin:0 0 6px;font-weight:800;font-size:20px}
    .modal__desc{margin:0 0 12px;color:#475569}
    .modal__actions{display:flex;justify-content:flex-end;gap:10px;margin-top:10px}
  </style>
</head>
<body>
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

  <script>
    // mở modal ngay khi tới trang
    (function(){ document.getElementById('modal-login-block')?.classList.add('show'); })();

    // đóng modal khi click nút X hoặc nền
    document.addEventListener('click', function(e){
      if (e.target.matches('[data-close]')) {
        const id = e.target.getAttribute('data-close');
        document.getElementById(id)?.classList.remove('show');
      }
      // click ra ngoài modal để đóng
      const opened = document.querySelector('.modal.show');
      if (opened && e.target === opened) opened.classList.remove('show');
    });
  </script>
</body>
</html>