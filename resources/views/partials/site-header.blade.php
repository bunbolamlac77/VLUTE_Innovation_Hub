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
      {{-- User menu --}}
      <div id="userBox" class="relative flex items-center gap-2" aria-haspopup="true" aria-expanded="false">
        <img src="{{ Auth::user()->avatar_url ? asset(Auth::user()->avatar_url) : asset('images/avatar-default.svg') }}"
          alt="Ảnh đại diện" class="w-10 h-10 rounded-full object-cover bg-white border-2 border-white/30"
          onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 100 100%27%3E%3Ccircle cx=%2750%27 cy=%2750%27 r=%2740%27 fill=%27%230a0f5a%27/%3E%3Ctext x=%2750%27 y=%2755%27 font-size=%2740%27 fill=%27white%27 text-anchor=%27middle%27%3E{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}%3C/text%3E%3C/svg%3E'" />
        <button id="btnUserMenu" class="px-2 py-1 rounded hover:bg-white/15" aria-label="Mở menu người dùng">▾</button>
        <div id="userMenu"
          class="hidden absolute right-0 top-full mt-2 w-56 bg-white text-slate-900 rounded-xl shadow-xl border border-slate-200 p-2 z-50"
          role="menu" aria-label="Menu người dùng">
          <a class="block px-3 py-2 rounded-lg font-semibold hover:bg-slate-100" href="{{ route('dashboard') }}">Bảng
            điều khiển</a>
          <a class="block px-3 py-2 rounded-lg font-semibold hover:bg-slate-100" href="{{ route('profile.edit') }}">Hồ
            sơ cá nhân</a>
          @php($u = auth()->user())
                @if ($u && ($u->hasRole('student') || (!$u->hasRole('staff') && !$u->hasRole('center') && !$u->hasRole('board') && !$u->hasRole('admin'))))
                  <a class="block px-3 py-2 rounded-lg font-semibold hover:bg-slate-100" href="{{ route('my-ideas.index') }}">Ý
                    tưởng của tôi</a>
                  <a class="block px-3 py-2 rounded-lg font-semibold hover:bg-slate-100"
                    href="{{ route('my-competitions.index') }}">Cuộc thi của tôi</a>
                @elseif ($u && ($u->hasRole('staff') || $u->hasRole('center') || $u->hasRole('board') || $u->hasRole('reviewer')))
                  @if ($u->hasRole('staff'))
                    <a class="block px-3 py-2 rounded-lg font-semibold hover:bg-slate-100"
                      href="{{ route('mentor.ideas') }}">Dự án hướng dẫn</a>
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
      <a class="font-bold px-3 py-2 rounded-lg hover:bg-brand-gray-50" href="{{ route('challenges.index') }}" data-key="challenges">Challenges</a>
      <a class="font-bold px-3 py-2 rounded-lg hover:bg-brand-gray-50" href="{{ route('scientific-news.index') }}"
        data-key="news">Bản tin Nghiên cứu</a>
    </nav>

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
