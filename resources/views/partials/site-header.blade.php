{{-- Header (Tailwind utilities only) --}}
<div class="bg-brand-navy text-white" role="banner">
  <div class="container flex items-center justify-between gap-6 py-5">
    <div class="flex items-center gap-3 min-w-0">
      <img src="{{ asset('images/logotruong.jpg') }}" alt="Logo Trường ĐHSPKT Vĩnh Long"
        class="w-10 h-10 rounded-md object-cover bg-white shrink-0" />
      <div class="leading-tight min-w-0">
        <div class="font-extrabold text-base truncate">TRƯỜNG ĐẠI HỌC SƯ PHẠM KỸ THUẬT VĨNH LONG</div>
        <div class="font-semibold text-xs opacity-95 truncate mt-0.5">Nơi không có ranh giới giữa Nhà trường và Thực tế
        </div>
      </div>
    </div>

    <div class="flex items-center gap-3">
      <div class="font-extrabold text-sm whitespace-nowrap hidden sm:block">BỘ GIÁO DỤC VÀ ĐÀO TẠO</div>
      <button onclick="changeLanguage('en')" class="text-xl p-1.5 rounded hover:bg-white/10" title="Switch to English"
        aria-label="Switch language">🇬🇧</button>

      @auth
      {{-- Notifications bell --}}
      <div id="notifBox" class="relative">
        @php
          $__notifCount = 0;
          $__notifications = collect();
          try {
            $__notifCount = Auth::user()->unreadNotifications()->count();
            $__notifications = Auth::user()->notifications()->latest()->limit(12)->get();
          } catch (\Throwable $e) {
            $__notifCount = 0;
            $__notifications = collect();
          }
        @endphp
        <button id="btnNotifMenu" class="relative px-2 py-1 rounded hover:bg-white/15" aria-label="Mở thông báo"
          aria-expanded="false">
          <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
          </svg>
          @if($__notifCount > 0)
            <span
              class="absolute -top-1 -right-1 min-w-[18px] h-[18px] grid place-items-center rounded-full bg-red-500 text-white text-[11px] font-bold leading-none px-[4px]">
              {{ $__notifCount > 99 ? '99+' : $__notifCount }}
            </span>
          @endif
        </button>
        <div id="notifMenu"
          class="hidden absolute right-0 top-full mt-2 w-[420px] bg-white text-slate-900 rounded-xl shadow-xl border border-slate-200 overflow-hidden z-50"
          role="menu" aria-label="Thông báo">
          <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <div class="text-sm font-extrabold">Thông báo</div>
            @if($__notifCount > 0)
              <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs text-brand-navy font-semibold hover:underline">Đọc tất cả</button>
              </form>
            @endif
          </div>
          <div class="max-h-96 overflow-y-auto divide-y divide-slate-200">
            @forelse($__notifications as $n)
              <a href="{{ route('notifications.read', $n?->id) }}"
                class="flex items-start gap-3 px-4 py-4 hover:bg-slate-50 {{ $n?->read_at ? '' : 'bg-blue-50/40' }}">
                <div
                  class="flex-shrink-0 w-9 h-9 rounded-full bg-slate-100 text-slate-600 grid place-items-center mt-0.5">🔔
                </div>
                <div class="flex-1 min-w-0">
                  <div class="text-[15px] font-bold text-slate-900 leading-5 truncate">
                    {{ $n?->data['title'] ?? 'Thông báo' }}</div>
                  <div class="text-[14px] text-slate-700 leading-6 line-clamp-2">{{ $n?->data['message'] ?? '' }}</div>
                  <div class="text-[12px] text-slate-500 mt-1">{{ $n?->created_at->diffForHumans() }}</div>
                </div>
                @if(!$n?->read_at)
                  <span class="mt-1 h-2 w-2 bg-blue-600 rounded-full"></span>
                @endif
              </a>
            @empty
              <div class="p-5 text-center text-sm text-slate-500">Không có thông báo nào.</div>
            @endforelse
          </div>
        </div>
      </div>
      {{-- User menu --}}
      <div id="userBox" class="relative flex items-center gap-2" aria-haspopup="true"
        aria-expanded="false">
      <img src="{{ Auth::user()?->avatar_url ? asset(Auth::user()?->avatar_url) : asset('images/avatar-default.svg') }}"
        alt="Ảnh đại diện" class="w-10 h-10 rounded-full object-cover bg-white border-2 border-white/30 cursor-pointer"
        id="userAvatar"
        onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 100 100%27%3E%3Ccircle cx=%2750%27 cy=%2750%27 r=%2740%27 fill=%27%230a0f5a%27/%3E%3Ctext x=%2750%27 y=%2755%27 font-size=%2740%27 fill=%27white%27 text-anchor=%27middle%27%3E{{ strtoupper(substr(auth()->user()?->name, 0, 1)) }}%3C/text%3E%3C/svg%3E'" />
      <button id="btnUserMenu" type="button" aria-controls="userMenu" class="px-3 py-2 rounded hover:bg-white/15 text-lg font-bold" aria-label="Mở menu người dùng">▾</button>
      <div id="userMenu"
        class="hidden absolute right-0 top-full mt-2 w-56 bg-white text-slate-900 rounded-xl shadow-xl border border-slate-200 p-2 z-50"
        role="menu" aria-label="Menu người dùng">
        <a class="block px-3 py-2 rounded-lg font-semibold hover:bg-slate-100" href="{{ route('dashboard') }}">Bảng
          điều khiển</a>
        <a class="block px-3 py-2 rounded-lg font-semibold hover:bg-slate-100" href="{{ route('profile.edit') }}">Hồ
          sơ cá nhân</a>
        @php($u = auth()->user())
              @if ($u !== null && ($u->hasRole('student') || (!$u->hasRole('staff') && !$u->hasRole('center') && !$u->hasRole('board') && !$u->hasRole('admin'))))
                <a class="block px-3 py-2 rounded-lg font-semibold hover:bg-slate-100" href="{{ route('my-ideas.index') }}">Ý
                  tưởng của tôi</a>
                <a class="block px-3 py-2 rounded-lg font-semibold hover:bg-slate-100"
                  href="{{ route('my-competitions.index') }}">Cuộc thi của tôi</a>
              @elseif ($u && ($u->hasRole('staff') || $u->hasRole('center') || $u->hasRole('board') || $u->hasRole('reviewer')))
                @if ($u->hasRole('staff'))
                  <a class="block px-3 py-2 rounded-lg font-semibold hover:bg-slate-100" href="{{ route('mentor.ideas') }}">Dự án
                    hướng dẫn</a>
                @endif
                @if ($u->hasRole('center') || $u->hasRole('board') || $u->hasRole('reviewer'))
                  <a class="block px-3 py-2 rounded-lg font-semibold hover:bg-slate-100"
                    href="{{ route('manage.review-queue.index') }}">Hàng chờ phản biện</a>
                @endif
              @elseif ($u && $u->hasRole('admin'))
                <a class="block px-3 py-2 rounded-lg font-semibold hover:bg-slate-100" href="{{ route('admin.home') }}">Bảng
                  quản trị</a>
              @endif
              <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-lg font-semibold hover:bg-slate-100">Đăng
                  xuất</button>
              </form>
            </div>
          </div>
        @else
    <a id="btnLogin" href="{{ route('login') }}"
      class="inline-flex items-center font-bold rounded-full border border-white/30 bg-white/0 hover:bg-white/15 transition px-4 py-2">Đăng
      nhập</a>
    @endauth
  </div>
</div>
</div>

{{-- Menubar --}}
<header class="sticky top-0 z-40 bg-white border-b border-slate-200" role="navigation" aria-label="Thanh menu">
  <div class="container flex items-center gap-6 py-3">
    <nav id="menuMain" aria-label="Menu chính" class="hidden sm:flex items-center gap-2">
      <a class="font-bold px-3 py-2 rounded-lg hover:bg-brand-gray-50" href="/" data-key="home">Trang chủ</a>
      <a class="font-bold px-3 py-2 rounded-lg hover:bg-brand-gray-50" href="/about" data-key="about">Giới thiệu</a>
      <a class="font-bold px-3 py-2 rounded-lg hover:bg-brand-gray-50" href="{{ route('ideas.index') }}"
        data-key="ideas">Ý tưởng</a>
      <a class="font-bold px-3 py-2 rounded-lg hover:bg-brand-gray-50" href="/events" data-key="events">Cuộc thi &amp;
        Sự kiện</a>
      <a class="font-bold px-3 py-2 rounded-lg hover:bg-brand-gray-50" href="{{ route('challenges.index') }}"
        data-key="challenges">Challenges</a>
      <a class="font-bold px-3 py-2 rounded-lg hover:bg-brand-gray-50" href="{{ route('scientific-news.index') }}"
        data-key="news">Bản tin Nghiên cứu</a>
    </nav>

    {{-- Mobile Menu Button --}}
    <div class="flex sm:hidden items-center mr-2">
      <button id="btnMobileMenu" class="p-2 text-slate-300 hover:text-white transition">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
    </div>

    <div class="ml-auto">
      <form method="GET" action="{{ route('search.index') }}" class="flex items-center gap-2">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Tìm ý tưởng, cuộc thi, mentor…"
          class="w-64 rounded-full border border-slate-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
          aria-label="Ô tìm kiếm" />
        <button type="submit"
          class="rounded-full border border-slate-300 bg-white font-bold px-4 py-2 text-sm hover:bg-slate-50">Tìm</button>
      </form>
    </div>
  </div>
</header>

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Notifications dropdown (bell)
      const notifBox = document.getElementById('notifBox');
      const btn = document.getElementById('btnNotifMenu');
      const menu = document.getElementById('notifMenu');
      if (notifBox && btn && menu) {
        btn.addEventListener('click', function (e) {
          e.stopPropagation();
          const isHidden = menu.classList.contains('hidden');
          if (isHidden) {
            menu.classList.remove('hidden');
            btn.setAttribute('aria-expanded', 'true');
          } else {
            menu.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
          }
        });
        document.addEventListener('click', function (e) {
          if (!notifBox.contains(e.target)) {
            menu.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
          }
        });
      }

      // Mobile menu toggle
      const btnMobileMenu = document.getElementById('btnMobileMenu');
      const menuMain = document.getElementById('menuMain');
      if (btnMobileMenu && menuMain) {
        btnMobileMenu.addEventListener('click', function () {
          menuMain.classList.toggle('hidden');
          menuMain.classList.toggle('absolute');
          menuMain.classList.toggle('top-full');
          menuMain.classList.toggle('left-0');
          menuMain.classList.toggle('w-full');
          menuMain.classList.toggle('bg-brand-navy');
          menuMain.classList.toggle('flex-col');
          menuMain.classList.toggle('p-4');
        });
      }


    });
  </script>
@endpush

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const userBox = document.getElementById('userBox');
      const userBtn = document.getElementById('btnUserMenu');
      const userAvatar = document.getElementById('userAvatar');
      const userMenu = document.getElementById('userMenu');
      
      if (userBox && userBtn && userMenu) {
        // Toggle menu function
        const toggleMenu = function (e) {
          e.stopPropagation();
          const isHidden = userMenu.classList.contains('hidden');
          if (isHidden) {
            userMenu.classList.remove('hidden');
            userBtn.setAttribute('aria-expanded', 'true');
          } else {
            userMenu.classList.add('hidden');
            userBtn.setAttribute('aria-expanded', 'false');
          }
        };
        
        // Button click handler
        userBtn.addEventListener('click', toggleMenu);
        
        // Avatar click handler
        if (userAvatar) {
          userAvatar.addEventListener('click', toggleMenu);
        }
        
        // Close menu when clicking outside
        document.addEventListener('click', function (e) {
          if (!userBox.contains(e.target)) {
            userMenu.classList.add('hidden');
            userBtn.setAttribute('aria-expanded', 'false');
          }
        });
        
        // Close menu on Escape key
        document.addEventListener('keydown', function (e) {
          if (e.key === 'Escape') {
            userMenu.classList.add('hidden');
            userBtn.setAttribute('aria-expanded', 'false');
          }
        });
      }
    });
  </script>
@endpush

