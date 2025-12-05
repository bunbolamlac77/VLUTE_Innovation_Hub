<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VLUTE Innovation Hub')</title>
    <meta name="description" content="Cổng Đổi mới Sáng tạo VLUTE – Nơi không có ranh giới giữa Nhà trường và Thực tế." />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans text-slate-900">
    {{-- Header & Menu --}}
    @include('partials.site-header')

    {{-- Flash Messages Toast (Tailwind utilities) --}}
    @if (session('status'))
        <div id="flash-toast" role="alert" aria-live="polite"
             class="fixed top-20 right-5 z-[9999] min-w-[320px] max-w-[500px] animate-slide-in-right">
            <div class="flex items-center gap-3 bg-white border border-emerald-200 border-l-4 border-l-emerald-500 rounded-xl px-5 py-4 shadow">
                <div class="w-6 h-6 grid place-items-center text-emerald-600 bg-emerald-100 rounded-full">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <div class="flex-1 text-emerald-800 text-sm font-medium leading-relaxed">{{ session('status') }}</div>
                <button class="p-1.5 rounded hover:bg-slate-100 text-slate-500" onclick="closeFlashToast()" aria-label="Đóng thông báo">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Profile Incomplete Toast --}}
    @auth
        @if (!Auth::user()->isProfileComplete() && request()->routeIs('profile.edit') === false)
            <div id="profile-toast" role="alert" aria-live="polite"
                 class="fixed top-32 right-5 z-[9999] min-w-[320px] max-w-[500px] animate-slide-in-right">
                <div class="flex items-center gap-3 rounded-xl px-5 py-4 shadow border border-amber-200 bg-amber-50">
                    <div class="w-6 h-6 grid place-items-center text-amber-600 bg-amber-100 rounded-full">⚠️</div>
                    <div class="flex-1 text-amber-800 text-sm leading-relaxed">
                        Hồ sơ của bạn chưa đầy đủ. <a href="{{ route('profile.edit') }}" class="underline font-medium">Cập nhật ngay</a>.
                    </div>
                    <button class="p-1.5 rounded hover:bg-white text-slate-500" onclick="closeProfileToast()" aria-label="Đóng thông báo">✕</button>
                </div>
            </div>
        @endif
    @endauth

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer & Social links --}}
    @include('partials.site-footer')

    {{-- Scripts --}}
    <script>
        function changeLanguage(lang) {
            alert("Chức năng chuyển sang ngôn ngữ " + lang.toUpperCase() + " đang được phát triển.");
        }

        document.addEventListener('DOMContentLoaded', function () {
            const flashToast = document.getElementById('flash-toast');
            if (flashToast) setTimeout(() => { closeFlashToast(); }, 5000);
            const profileToast = document.getElementById('profile-toast');
            if (profileToast) setTimeout(() => { closeProfileToast(); }, 8000);
        });

        function closeFlashToast() {
            const toast = document.getElementById('flash-toast');
            if (toast) {
                toast.classList.remove('animate-slide-in-right');
                toast.classList.add('animate-slide-out-right');
                setTimeout(() => { toast.remove(); }, 300);
            }
        }
        function closeProfileToast() {
            const toast = document.getElementById('profile-toast');
            if (toast) {
                toast.classList.remove('animate-slide-in-right');
                toast.classList.add('animate-slide-out-right');
                setTimeout(() => { toast.remove(); }, 300);
            }
        }
    </script>
    @stack('scripts')
</body>

</html>
