{{-- resources/views/auth/reset-password.blade.php (Tailwind utilities) --}}
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Đặt lại mật khẩu - VLUTE Innovation Hub</title>
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

      <h1 class="text-center text-2xl font-extrabold my-2">Đặt lại mật khẩu</h1>

      @if ($errors->any())
        <div class="text-rose-700 font-semibold my-2 text-center">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('password.store') }}" class="mt-4 space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
          <label for="email" class="block font-bold text-sm mb-2">E-mail</label>
          <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus
                 class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
        </div>

        <div>
          <label for="password" class="block font-bold text-sm mb-2">Mật khẩu mới</label>
          <div class="relative">
            <input id="password" name="password" type="password" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            <button class="eye-toggle absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 grid place-items-center text-slate-600" type="button" aria-label="Hiện/ẩn mật khẩu" data-toggle="#password">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <div>
          <label for="password_confirmation" class="block font-bold text-sm mb-2">Xác nhận mật khẩu</label>
          <div class="relative">
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            <button class="eye-toggle absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 grid place-items-center text-slate-600" type="button" aria-label="Hiện/ẩn mật khẩu" data-toggle="#password_confirmation">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <button class="w-full rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-4 py-3" type="submit">Cập nhật mật khẩu</button>
        <div class="text-center mt-3"><a class="text-indigo-600 hover:underline" href="{{ route('login') }}">Quay về đăng nhập</a></div>
      </form>
    </div>
  </div>
</body>
</html>
