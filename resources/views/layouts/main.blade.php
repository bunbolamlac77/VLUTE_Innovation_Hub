<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VLUTE Innovation Hub')</title>
    <meta name="description"
        content="Cổng Đổi mới Sáng tạo VLUTE – Nơi không có ranh giới giữa Nhà trường và Thực tế." />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Flash Toast Notification Styles */
        .flash-toast {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            min-width: 320px;
            max-width: 500px;
            animation: slideInRight 0.3s ease-out, fadeIn 0.3s ease-out;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .flash-toast-content {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #ffffff;
            border: 1px solid #d1fae5;
            border-left: 4px solid #10b981;
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .flash-toast-icon {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #10b981;
            background: #d1fae5;
            border-radius: 50%;
            padding: 4px;
        }

        .flash-toast-message {
            flex: 1;
            color: #065f46;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.5;
        }

        .flash-toast-close {
            flex-shrink: 0;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .flash-toast-close:hover {
            background: #f3f4f6;
            color: #374151;
        }

        .flash-toast.hiding {
            animation: slideOutRight 0.3s ease-out, fadeOut 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        /* Responsive */
        @media (max-width: 640px) {
            .flash-toast {
                right: 10px;
                left: 10px;
                min-width: auto;
                max-width: none;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    {{-- Masthead (Top bar với logo trường) --}}
    <div class="masthead" role="banner">
        <div class="container masthead-inner">
            <div class="school">
                <img src="{{ asset('images/logotruong.jpg') }}" alt="Logo Trường ĐHSPKT Vĩnh Long"
                    class="school-logo" />
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
                    {{-- Khi đã đăng nhập: hiển thị avatar + menu --}}
                    <div class="userbox" id="userBox" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ Auth::user()->avatar_url ? asset(Auth::user()->avatar_url) : asset('images/avatar-default.jpg') }}" alt="Ảnh đại diện" class="avatar"
                            onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 100 100%27%3E%3Ccircle cx=%2750%27 cy=%2750%27 r=%2740%27 fill=%27%230a0f5a%27/%3E%3Ctext x=%2750%27 y=%2755%27 font-size=%2740%27 fill=%27white%27 text-anchor=%27middle%27%3E{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}%3C/text%3E%3C/svg%3E'" />
                        <button class="chev" id="btnUserMenu" aria-label="Mở menu người dùng">
                            ▾
                        </button>
                        <div class="user-menu" id="userMenu" role="menu" aria-label="Menu người dùng">
                            <a href="{{ route('dashboard') }}">Bảng điều khiển</a>
                            <a href="{{ route('profile.edit') }}">Hồ sơ cá nhân</a>
                            <a href="{{ route('my-ideas.index') }}">Ý tưởng của tôi</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">Đăng xuất</button>
                            </form>
                        </div>
                    </div>
                @else
                    {{-- Khi chưa đăng nhập: hiển thị nút đăng nhập --}}
                    <a class="btn btn-primary login" id="btnLogin" href="{{ route('login') }}">Đăng nhập</a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Menu Bar --}}
    <header class="menubar" role="navigation" aria-label="Thanh menu">
        <div class="container menu-inner">
            <nav class="menu" id="menuMain" aria-label="Menu chính">
                <a href="/" data-key="home">Trang chủ</a>
                <a href="/about" data-key="about">Giới thiệu</a>
                <a href="{{ route('ideas.index') }}" data-key="ideas">Ý tưởng</a>
                <a href="/events" data-key="events">Cuộc thi &amp; Sự kiện</a>
                <a href="/news" data-key="news">Bản tin Nghiên cứu</a>
            </nav>
            <div class="menu-right">
                <form method="GET" action="{{ route('search.index') }}" style="display:flex; gap:8px; align-items:center;">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Tìm ý tưởng, cuộc thi, mentor…" style="
                      padding: 10px 12px;
                      border: 1px solid var(--border);
                      border-radius: 999px;
                      width: 260px;
                    " aria-label="Ô tìm kiếm" />
                    <button type="submit" class="btn" style="padding: 10px 14px; border: 1px solid var(--border); border-radius: 999px; background: #fff; font-weight: 700;">
                        Tìm
                    </button>
                </form>
            </div>
            <div id="dropdown" class="dropdown hidden" role="menu" aria-label="Danh mục con"></div>
        </div>
    </header>

    {{-- Flash Messages Toast --}}
    @if (session('status'))
        <div id="flash-toast" class="flash-toast" role="alert" aria-live="polite">
            <div class="flash-toast-content">
                <div class="flash-toast-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="flash-toast-message">{{ session('status') }}</div>
                <button class="flash-toast-close" onclick="closeFlashToast()" aria-label="Đóng thông báo">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Profile Incomplete Toast (nhẹ, không gây phiền) --}}
    @auth
        @if (!Auth::user()->isProfileComplete() && request()->routeIs('profile.edit') === false)
            <div id="profile-toast" class="flash-toast" role="alert" aria-live="polite"
                 style="top: 130px;">
                <div class="flash-toast-content" style="border-left-color:#f59e0b;border-color:#fef3c7;background:#fffbeb;">
                    <div class="flash-toast-icon" style="background:#fef3c7;color:#f59e0b;">
                        ⚠️
                    </div>
                    <div class="flash-toast-message" style="color:#92400e;">
                        Hồ sơ của bạn chưa đầy đủ. <a href="{{ route('profile.edit') }}" class="underline font-medium">Cập nhật ngay</a>.
                    </div>
                    <button class="flash-toast-close" onclick="closeProfileToast()" aria-label="Đóng thông báo">
                        ✕
                    </button>
                </div>
            </div>
        @endif
    @endauth

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer role="contentinfo">
        <div class="container cols">
            <div>
                <h5>TRƯỜNG ĐẠI HỌC SPKT VĨNH LONG</h5>
                <div>Địa chỉ: Số 73, Nguyễn Huệ, P. Long Châu, TP. Vĩnh Long</div>
                <div>Email: spktvl@vlute.edu.vn · Fax: 02703 821 003</div>
            </div>
            <div>
                <h5>DỊCH VỤ TIỆN ÍCH</h5>
                <div><a href="#">My VLUTE</a></div>
                <div><a href="#">Thông tin tuyển sinh</a></div>
                <div><a href="#">Công tác sinh viên</a></div>
                <div><a href="#">Phòng Đào tạo</a></div>
            </div>
            <div>
                <h5>TRUY CẬP NHANH</h5>
                <div><a href="#">Đăng ký học phần</a></div>
                <div><a href="#">EMS</a></div>
                <div><a href="#">Học trực tuyến (E-Learning)</a></div>
                <div><a href="#">Cổng thanh toán Trực tuyến</a></div>
            </div>
        </div>
        <div class="container legal">
            Bản quyền thuộc về Trường Đại học Sư phạm Kỹ thuật Vĩnh Long (VLUTE)
        </div>
    </footer>

    {{-- Social Links --}}
    <div class="social-links">
        <a href="https://www.facebook.com/vlute.edu.vn" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
            <img src="{{ asset('images/facebook.jpg') }}" alt="Facebook" />
        </a>
        <a href="https://zalo.me/0788977419" target="_blank" rel="noopener noreferrer" aria-label="Zalo">
            <img src="{{ asset('images/zalo.jpg') }}" alt="Zalo" />
        </a>
        <a href="https://www.youtube.com/@tivivlute5460" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
            <img src="{{ asset('images/youtube.jpg') }}" alt="YouTube" />
        </a>
        <a href="https://www.tiktok.com/@_vlute" target="_blank" rel="noopener noreferrer" aria-label="TikTok">
            <img src="{{ asset('images/tiktok.jpg') }}" alt="TikTok" />
        </a>
    </div>

    {{-- Scripts --}}
    <script>
        // Language switcher
        function changeLanguage(lang) {
            alert(
                "Chức năng chuyển sang ngôn ngữ " +
                lang.toUpperCase() +
                " đang được phát triển."
            );
        }

        // User menu toggle
        document.addEventListener('DOMContentLoaded', function () {
            const btnUserMenu = document.getElementById('btnUserMenu');
            const userMenu = document.getElementById('userMenu');
            const userBox = document.getElementById('userBox');

            if (btnUserMenu && userMenu && userBox) {
                btnUserMenu.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isOpen = userMenu.classList.contains('show');
                    if (isOpen) {
                        userMenu.classList.remove('show');
                        userBox.setAttribute('aria-expanded', 'false');
                    } else {
                        userMenu.classList.add('show');
                        userBox.setAttribute('aria-expanded', 'true');
                    }
                });

                // Close menu when clicking outside
                document.addEventListener('click', (e) => {
                    if (!userBox.contains(e.target)) {
                        userMenu.classList.remove('show');
                        userBox.setAttribute('aria-expanded', 'false');
                    }
                });

                // Prevent menu from closing when clicking inside it
                userMenu.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
            }

            // Auto-hide flash toast after 5 seconds
            const flashToast = document.getElementById('flash-toast');
            if (flashToast) {
                setTimeout(() => {
                    closeFlashToast();
                }, 5000);
            }

            // Auto-hide profile toast after 8 seconds
            const profileToast = document.getElementById('profile-toast');
            if (profileToast) {
                setTimeout(() => {
                    closeProfileToast();
                }, 8000);
            }
        });

        // Function to close flash toast
        function closeFlashToast() {
            const toast = document.getElementById('flash-toast');
            if (toast) {
                toast.classList.add('hiding');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }

        // Function to close profile toast
        function closeProfileToast() {
            const toast = document.getElementById('profile-toast');
            if (toast) {
                toast.classList.add('hiding');
                setTimeout(() => { toast.remove(); }, 300);
            }
        }
    </script>
    @stack('scripts')
</body>

</html>