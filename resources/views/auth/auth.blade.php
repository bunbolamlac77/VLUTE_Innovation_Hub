{{-- resources/views/auth/auth.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đăng nhập & Đăng ký - VLUTE Innovation Hub</title>

  {{-- Fonts + CSS gốc của bạn --}}
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    /* DÁN NGUYÊN phần <style> trong dangnhap-dangky.html của bạn vào đây.
       Chỉ chỉnh đúng 2 chỗ ảnh nền và các btn nếu cần. */
    :root{--navy:#0a0f5a;--blue:#2563eb;--border:#e5e7eb;--text:#111827;--muted:#6b7280;--card:#fff;--r:12px;--shadow:0 10px 25px rgba(0,0,0,0.1);}
    *{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#f3f4f6;color:var(--text)}
    a{color:var(--blue);text-decoration:none} a:hover{text-decoration:underline}
    .auth-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;
      background:linear-gradient(rgba(0,0,0,.5),rgba(0,0,0,.5)), url('{{ asset('images/panel-truong.jpg') }}') center/cover no-repeat;}
    .card{width:100%;max-width:460px;background:var(--card);border-radius:var(--r);box-shadow:var(--shadow);padding:32px;position:relative}
    .auth-header{text-align:center;margin-bottom:24px}
    .auth-header img.logo{width:72px;height:72px;margin:0 auto 14px;border-radius:8px;object-fit:cover}
    .auth-header h1{margin:0;font-size:18px;font-weight:800;color:var(--text)}
    .tabs{display:flex;background:#f3f4f6;border-radius:999px;padding:4px;margin-bottom:24px}
    .tabs button{flex:1;padding:10px;border:none;border-radius:999px;background:transparent;font-weight:800;color:var(--muted);cursor:pointer}
    .tabs button.active{background:#fff;box-shadow:0 2px 4px rgba(0,0,0,.06);color:var(--blue)}
    .group{margin-bottom:16px}
    .group label{display:block;font-weight:700;font-size:14px;margin-bottom:8px;color:var(--text)}
    .group input,.group select{width:100%;padding:12px 14px;border:1px solid var(--border);border-radius:8px;font-size:16px}
    .group input:focus,.group select:focus{outline:none;border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,235,.2)}
    .options{display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:20px;font-size:14px}
    .btn-primary{width:100%;padding:14px;border:none;border-radius:8px;background:var(--blue);color:#fff;font-size:16px;font-weight:800;cursor:pointer}
    .btn-primary:hover{background:#1d4ed8}
    .divider{display:flex;align-items:center;color:var(--muted);font-size:14px;margin:24px 0}
    .divider:before,.divider:after{content:"";flex:1;border-bottom:1px solid var(--border)}
    .divider:before{margin-right:.25em} .divider:after{margin-left:.25em}
    .btn-social-dndk{display:flex;align-items:center;justify-content:center;gap:12px;width:100%;padding:12px;border:1px solid var(--border);
      border-radius:8px;background:#fff;font-weight:700;cursor:pointer}
    .btn-social-dndk:hover{background:#f9fafb;box-shadow:0 0 0 4px rgba(37,99,235,.2)}
    .hidden{display:none!important}
    /* Modal dùng chung (giữ nguyên như file gốc của bạn) */
    .modal{position:fixed;inset:0;background:rgba(0,0,0,.6);display:none;align-items:center;justify-content:center;z-index:1000;padding:20px}
    .modal.show{display:flex}
    .modal__card{background:#fff;border-radius:12px;box-shadow:var(--shadow);padding:24px;width:95%;max-width:600px;position:relative;max-height:90vh;overflow:auto}
    .modal__close{position:absolute;top:10px;right:15px;background:none;border:none;font-size:24px;color:var(--muted);cursor:pointer}
    .modal__actions{display:flex;justify-content:flex-end;gap:10px;margin-top:16px}
    .btn-outline{border:1px solid var(--border);background:#fff;color:var(--text);padding:10px 14px;border-radius:8px;font-weight:700;cursor:pointer}
    /* Thanh strength password (giữ như gốc) */
    .pw-hint{margin-top:8px;font-size:13px;color:var(--muted)} .pw-hint .req{display:flex;align-items:center;gap:8px;margin:4px 0}
    .pw-hint .ok{color:#059669} .pw-hint .bad{color:#b91c1c}
    .pw-bar{height:6px;border-radius:999px;background:#e5e7eb;margin-top:8px;overflow:hidden}
    .pw-bar>span{display:block;height:100%;width:0;background:linear-gradient(90deg,#f43f5e,#f59e0b,#22c55e);transition:width .25s}
  </style>
</head>
<body>
  <div class="auth-wrap">
    <div class="card">
      <div class="auth-header">
        <img src="{{ asset('images/logotruong.jpg') }}" alt="Logo VLUTE" class="logo" />
        <h1>HỆ THỐNG XÁC THỰC TẬP TRUNG (SSO)</h1>
      </div>

      {{-- Tabs --}}
      <div class="tabs">
        <button id="tab-login" class="{{ ($activeTab ?? 'login') === 'login' ? 'active' : '' }}">Đăng nhập</button>
        <button id="tab-register" class="{{ ($activeTab ?? 'login') === 'register' ? 'active' : '' }}">Đăng ký</button>
      </div>

      {{-- Panel Đăng nhập --}}
      <div id="panel-login" class="{{ ($activeTab ?? 'login') === 'login' ? '' : 'hidden' }}">
        <form id="form-login" action="{{ route('login') }}" method="POST">
          @csrf
          {{-- Thông báo/lỗi --}}
          @if ($errors->any())
            <div style="margin:10px 0;color:#b91c1c;font-weight:600;">{{ $errors->first() }}</div>
          @endif
          @if (session('status'))
            <div style="margin:10px 0;color:#059669;font-weight:600;">{{ session('status') }}</div>
          @endif

          <div class="group">
            <label for="login-email">E-mail</label>
            <input id="login-email" name="email" type="email" placeholder="Nhập email của bạn" required />
          </div>
          <div class="group">
            <label for="login-password">Mật khẩu</label>
            <input id="login-password" name="password" type="password" placeholder="Nhập mật khẩu" required />
          </div>
          <div class="options">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
              <input type="checkbox" name="remember" /> Ghi nhớ
            </label>
            <div style="display:flex;gap:14px;align-items:center">
              <a id="link-forgot" href="{{ route('password.request') }}">Quên mật khẩu?</a>
            </div>
          </div>
          <button class="btn-primary" type="submit">Đăng nhập</button>
        </form>

        <div class="divider">Hoặc đăng nhập với</div>
        <button class="btn-social-dndk" type="button" onclick="alert('Đăng nhập Google đang được phát triển');">
          {{-- icon Google giữ như file gốc của bạn --}}
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/><path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.223,0-9.657-3.657-11.303-8H6.306C9.656,39.663,16.318,44,24,44z"/><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.574l6.19,5.238C41.38,36.143,44,30.638,44,24C44,22.659,43.862,21.35,43.611,20.083z"/></svg>
          Google
        </button>
      </div>

      {{-- Panel Đăng ký --}}
      <div id="panel-register" class="{{ ($activeTab ?? 'login') === 'register' ? '' : 'hidden' }}">
        <form id="form-register" action="{{ route('register') }}" method="POST">
          @csrf
          @if ($errors->any() && ($activeTab ?? 'login') === 'register')
            <div style="margin:10px 0;color:#b91c1c;font-weight:600;">{{ $errors->first() }}</div>
          @endif

          <div class="group">
            <label for="reg-name">Họ và tên</label>
            <input id="reg-name" name="name" type="text" placeholder="Nhập họ và tên" required />
          </div>
          <div class="group">
            <label for="reg-email">E-mail</label>
            <input id="reg-email" name="email" type="email" placeholder="Nhập email của bạn" required />
          </div>

          {{-- Khối DN hiện khi email KHÔNG phải miền VLUTE (giữ logic JS của bạn) --}}
          <div id="enterprise-fields" class="hidden">
            <div class="group">
              <label for="reg-company">Tên công ty</label>
              <input id="reg-company" type="text" placeholder="Tên công ty/doanh nghiệp" />
            </div>
            <div class="group">
              <label for="reg-position">Vị trí công tác</label>
              <input id="reg-position" type="text" placeholder="VD: Trưởng phòng nhân sự" />
            </div>
            <div class="group">
              <label for="reg-interest">Lĩnh vực quan tâm</label>
              <select id="reg-interest">
                <option value="">-- Chọn lĩnh vực --</option>
                <option value="it">Công nghệ thông tin</option>
                <option value="agritech">Nông nghiệp công nghệ cao</option>
                <option value="mechanics">Cơ khí / Tự động hóa</option>
                <option value="other">Khác</option>
              </select>
            </div>
          </div>

          <div class="group">
            <label for="reg-password">Mật khẩu</label>
            <input id="reg-password" name="password" type="password" placeholder="Tạo mật khẩu" required />
            <input type="hidden" name="password_confirmation" id="pw-confirm-hidden" />
            {{-- Thanh gợi ý độ mạnh mật khẩu giữ nguyên như file gốc --}}
            <div class="pw-hint" id="pw-hint-register">
              <div class="pw-bar"><span id="pw-bar-register"></span></div>
              <div class="req" id="pw-r1">Tối thiểu 8 ký tự</div>
              <div class="req" id="pw-r2">Có chữ hoa và chữ thường</div>
              <div class="req" id="pw-r3">Có số</div>
              <div class="req" id="pw-r4">Có ký tự đặc biệt (!@#$%^&amp;*...)</div>
              <div class="req" id="pw-r5">Không chứa phần tên email</div>
            </div>
          </div>
          <button class="btn-primary" type="submit">Đăng ký</button>
        </form>

        <p style="text-align:center;margin-top:16px;font-size:14px">
          Bằng việc đăng ký, bạn đồng ý với
          <a href="javascript:void(0)">Điều khoản Dịch vụ</a>.
        </p>
      </div>
    </div>
  </div>

  {{-- Modals xác nhận email / chặn đăng nhập (giữ nguyên nội dung của bạn) --}}
  {{-- Ví dụ: #modal-student, #modal-others, #modal-login-block ... --}}

  <script>
    // Kích hoạt tab theo biến server
    (function(){
      const active = "{{ $activeTab ?? 'login' }}";
      const tabLogin = document.getElementById('tab-login');
      const tabRegister = document.getElementById('tab-register');
      const panelLogin = document.getElementById('panel-login');
      const panelRegister = document.getElementById('panel-register');

      function show(tab){
        if(!tabLogin || !tabRegister) return;
        if(tab === 'login'){
          tabLogin.classList.add('active'); tabRegister.classList.remove('active');
          panelLogin.classList.remove('hidden'); panelRegister.classList.add('hidden');
        }else{
          tabRegister.classList.add('active'); tabLogin.classList.remove('active');
          panelRegister.classList.remove('hidden'); panelLogin.classList.add('hidden');
        }
      }
      show(active);

      tabLogin && tabLogin.addEventListener('click', ()=>show('login'));
      tabRegister && tabRegister.addEventListener('click', ()=>show('register'));

      // Ẩn/hiện khối DN theo miền email (giữ logic đơn giản client-side)
      const emailInput = document.getElementById('reg-email');
      const enterprise = document.getElementById('enterprise-fields');
      function toggleEnterprise(){
        if(!emailInput || !enterprise) return;
        const v = (emailInput.value || '').toLowerCase();
        const isVLUTE = v.endsWith('@st.vlute.edu.vn') || v.endsWith('@vlute.edu.vn');
        enterprise.classList.toggle('hidden', isVLUTE || !v.includes('@'));
      }
      emailInput && emailInput.addEventListener('input', toggleEnterprise);
      toggleEnterprise();

      // Đồng bộ password_confirmation ẩn (để qua rule mặc định)
      const pw = document.getElementById('reg-password');
      const pwc = document.getElementById('pw-confirm-hidden');
      pw && pw.addEventListener('input', ()=>{ if(pwc) pwc.value = pw.value; });
    })();
  </script>
</body>
</html>