<div class="masthead" role="banner">
    <div class="container masthead-inner">
        <div class="school">
            <img src="{{ asset('images/logotruong.jpg') }}" alt="Logo Trường ĐHSPKT Vĩnh Long" class="school-logo" />
            <div class="school-title">
                <div class="name">TRƯỜNG ĐẠI HỌC SƯ PHẠM KỸ THUẬT VĨNH LONG</div>
                <div class="slogan">
                    Nơi không có ranh giới giữa Nhà trường và Thực tế
                </div>
            </div>
        </div>
        <div class="mast-right">
            <div class="link">BỘ GIÁO DỤC VÀ ĐÀO TẠO</div>
            <a href="javascript:void(0)" onclick="changeLanguage('en')" class="lang-switcher"
                title="Switch to English">🇬🇧</a>
            @auth
                <!-- Khi đã đăng nhập: hiện avatar + nút sổ xuống -->
                <div class="userbox" id="userBox" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('assets/avatar-default.jpg') }}" alt="Ảnh đại diện" class="avatar" />
                    <button class="chev" id="btnUserMenu" aria-label="Mở menu người dùng">
                        ▾
                    </button>
                    <div class="user-menu" id="userMenu" role="menu" aria-label="Menu người dùng">
                        <a href="{{ route('dashboard') }}">Bảng điều khiển</a>
                        <a href="/profile">Hồ sơ cá nhân</a>
                        <a href="/ideas/my">Ý tưởng của tôi</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                style="width: 100%; text-align: left; padding: 10px 12px; border: none; background: transparent; cursor: pointer; font-weight: 600; color: #0f172a;">Đăng
                                xuất</button>
                        </form>
                    </div>
                </div>
            @else
                <a class="btn btn-primary login" id="btnLogin" href="{{ route('login') }}">Đăng nhập</a>
            @endauth
        </div>
    </div>
</div>

<header class="menubar" role="navigation" aria-label="Thanh menu">
    <div class="container menu-inner">
        <nav class="menu" id="menuMain" aria-label="Menu chính">
            <a href="/" data-key="home">Trang chủ</a>
            <a href="/about" data-key="about">Giới thiệu</a>
            <a href="/ideas" data-key="ideas">Ý tưởng</a>
            <a href="/events" data-key="events">Cuộc thi &amp; Sự kiện</a>
            <a href="/news" data-key="news">Bản tin Nghiên cứu</a>
        </nav>
        <div class="menu-right">
            <input type="search" placeholder="Tìm ý tưởng, cuộc thi, mentor…" style="
          padding: 10px 12px;
          border: 1px solid var(--border);
          border-radius: 999px;
          width: 260px;
        " aria-label="Ô tìm kiếm" />
        </div>
        <div id="dropdown" class="dropdown hidden" role="menu" aria-label="Danh mục con"></div>
    </div>
</header>