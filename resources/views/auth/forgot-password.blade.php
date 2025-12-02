{{-- resources/views/auth/forgot-password.blade.php (Tailwind utilities) --}}
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quên mật khẩu - VLUTE Innovation Hub</title>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-900">
  <div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('/images/panel-truong.jpg')] bg-cover bg-center"></div>
    <div class="absolute inset-0 bg-black/45"></div>

    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl p-8">
      <div class="flex flex-col items-center gap-2 mb-2">
        <img src="{{ asset('images/logotruong.jpg') }}" alt="VLUTE" class="w-16 h-16 rounded-md object-cover" />
      </div>
      <h1 class="text-center text-3xl font-extrabold my-2">Quên mật khẩu</h1>

      @if (session('status'))
        <div class="text-emerald-700 font-extrabold text-center my-2">{{ session('status') }}</div>
      @endif

      @if ($errors->any())
        <div class="text-rose-700 font-semibold my-2 text-center">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('password.email') }}" class="mt-4 space-y-4">
        @csrf
        <div>
          <label for="email" class="block font-bold text-sm mb-2">E-mail</label>
          <input id="email" name="email" type="email" placeholder="Nhập email đăng ký" required autofocus
                 class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
        </div>

        <button class="w-full rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-4 py-3" type="submit">Gửi đường dẫn đặt lại mật khẩu</button>
      </form>

      <div class="text-center mt-4 text-lg">
        <a class="text-indigo-600 hover:underline" href="{{ route('login') }}">Quay về đăng nhập</a>
      </div>
    </div>
  </div>
</body>
</html>
