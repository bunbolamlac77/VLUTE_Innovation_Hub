{{-- resources/views/auth/verify-email.blade.php (Tailwind utilities) --}}
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
<body class="font-sans text-slate-900">
  <div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('/images/panel-truong.jpg')] bg-cover bg-center"></div>
    <div class="absolute inset-0 bg-black/45"></div>

    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl p-7">
      <div class="flex flex-col items-center gap-2 mb-2">
        <img src="{{ asset('images/logotruong.jpg') }}" alt="VLUTE" class="w-16 h-16 rounded-md object-cover"/>
      </div>
      <h1 class="text-center font-extrabold text-2xl my-2">Xác nhận địa chỉ email</h1>
      <p class="text-center text-slate-600 m-0 mb-3">Bạn cần xác thực email trước khi truy cập các tính năng nội bộ.</p>

      @if (session('status') == 'verification-link-sent')
        <div class="text-emerald-700 font-extrabold text-center my-2">Đã gửi lại liên kết xác nhận. Hãy kiểm tra hộp thư.</div>
      @endif

      <div class="flex gap-2 justify-center mt-2 flex-wrap">
        <form method="POST" action="{{ route('verification.send') }}">
          @csrf
          <button type="submit" class="rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-4 py-2">Gửi lại email xác nhận</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-900 font-bold px-4 py-2">Đăng xuất</button>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal "Không thể đăng nhập" (dùng chung JS) --}}
  <div id="modal-login-block" class="fixed inset-0 bg-black/60 hidden items-center justify-center p-5 z-50">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-lg relative">
      <button class="absolute top-2 right-3 text-slate-500 hover:text-slate-700" data-close="modal-login-block">&times;</button>
      <h3 class="m-0 mb-2 text-xl font-extrabold">Không thể truy cập</h3>
      <p class="text-slate-600 m-0">
        Tài khoản của bạn <strong>chưa xác thực email</strong>. Vui lòng mở hộp thư đã đăng ký và nhấn liên kết xác nhận.
        Nếu chưa nhận được, hãy nhấn <em>Gửi lại email xác nhận</em>.
      </p>
      <div class="mt-4 flex gap-2 justify-end">
        <form method="POST" action="{{ route('verification.send') }}">
          @csrf
          <button type="submit" class="rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-4 py-2">Gửi lại email xác nhận</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-900 font-bold px-4 py-2">Đăng xuất</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
