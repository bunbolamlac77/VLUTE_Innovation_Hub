{{-- Layout Admin: giữ nguyên header/menu/footer của site --}}
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin · VLUTE Innovation Hub</title>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">
  {{-- HEADER: copy nguyên markup từ index vào partial này --}}
  @include('partials.site-header')

  <main class="min-h-screen">
    {{-- Header nhỏ cho Admin --}}
    <section class="container py-6">
      <h1 class="text-2xl font-extrabold text-slate-900 m-0">Bảng điều khiển quản trị</h1>
      <p class="text-slate-500 mt-1">Quản lý người dùng, phê duyệt, ý tưởng, phân loại và nhật ký.</p>
    </section>

    {{-- Tabs + Nội dung --}}
    <section class="container pb-16">
      @yield('main')
    </section>
  </main>

  {{-- FOOTER: copy nguyên markup từ index vào partial này --}}
  @include('partials.site-footer')

  <script>
    // Language switcher
    function changeLanguage(lang) {
      alert("Chức năng chuyển sang ngôn ngữ " + lang.toUpperCase() + " đang được phát triển.");
    }

    // User menu toggle
    document.addEventListener('DOMContentLoaded', function () {
      const btnUserMenu = document.getElementById('btnUserMenu');
      const userMenu = document.getElementById('userMenu');
      const userBox = document.getElementById('userBox');

      if (btnUserMenu && userMenu && userBox) {
        btnUserMenu.addEventListener('click', (e) => {
          e.stopPropagation();
          const opened = userMenu.style.display === 'block';
          userMenu.style.display = opened ? 'none' : 'block';
          userBox.setAttribute('aria-expanded', String(!opened));
        });

        document.addEventListener('click', () => {
          userMenu.style.display = 'none';
          userBox.setAttribute('aria-expanded', 'false');
        });
      }

      // Close user menu when clicking on user menu links
      if (userMenu) {
        userMenu.addEventListener('click', (e) => {
          if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON') {
            userMenu.style.display = 'none';
            userBox?.setAttribute('aria-expanded', 'false');
          }
        });
      }
    });
  </script>
</body>

</html>