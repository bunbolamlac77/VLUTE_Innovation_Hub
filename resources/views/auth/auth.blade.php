{{-- resources/views/auth/auth.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Đăng nhập & Đăng ký - VLUTE Innovation Hub</title>

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    :root{--blue:#2563eb;--border:#e5e7eb;--text:#111827;--muted:#6b7280;--card:#fff;--r:16px;--shadow:0 20px 40px rgba(0,0,0,.12)}
    *{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#f3f4f6;color:var(--text)}
    a{color:var(--blue);text-decoration:none} a:hover{text-decoration:underline}

    .auth-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;
      background:linear-gradient(rgba(0,0,0,.45),rgba(0,0,0,.45)),url('{{ asset('images/panel-truong.jpg') }}') center/cover no-repeat}
    .card{width:100%;max-width:600px;background:var(--card);border-radius:var(--r);box-shadow:var(--shadow);padding:32px}

    .auth-header{text-align:center;margin-bottom:24px}
    .auth-header img.logo{width:64px;height:64px;border-radius:10px;object-fit:cover;display:block;margin:0 auto 10px}
    .auth-header h1{margin:0;font-size:18px;font-weight:800;color:#0f172a;letter-spacing:.02em}

    .tabs{display:flex;background:#eef2ff;border-radius:999px;padding:4px;margin-bottom:20px}
    .tabs button{flex:1;padding:10px 12px;border:none;border-radius:999px;background:transparent;font-weight:800;color:#64748b;cursor:pointer}
    .tabs button.active{background:#fff;box-shadow:0 2px 6px rgba(0,0,0,.06);color:var(--blue)}

    .group{margin-bottom:16px}
    .group label{display:block;font-weight:700;font-size:14px;margin-bottom:8px;color:#0f172a}

    .field{position:relative}
    .field input,.field select{width:100%;padding:12px 44px 12px 14px;border:1px solid var(--border);border-radius:12px;font-size:16px;background:#fff}
    .field input:focus,.field select:focus{outline:none;border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,235,.18)}

    /* chỉ phục vụ nút con mắt */
    .has-eye{position:relative}
    .has-eye input{padding-right:44px}
    .eye-toggle{
      position:absolute;right:10px;top:50%;transform:translateY(-50%);
      width:30px;height:30px;border:none;background:transparent;color:#374151;cursor:pointer;
      display:flex;align-items:center;justify-content:center
    }
    .eye-toggle svg{width:22px;height:22px}

    .options{display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:18px;font-size:14px}
    .btn-primary{width:100%;padding:14px;border:none;border-radius:12px;background:var(--blue);color:#fff;font-size:16px;font-weight:800;cursor:pointer}
    .btn-primary[disabled]{opacity:.6;cursor:not-allowed}
    .btn-primary:hover{background:#1d4ed8}
    .divider{display:flex;align-items:center;color:#64748b;font-size:14px;margin:22px 0}
    .divider:before,.divider:after{content:"";flex:1;border-bottom:1px solid var(--border)}
    .divider:before{margin-right:.25em}.divider:after{margin-left:.25em}
    .btn-social-dndk{display:flex;align-items:center;justify-content:center;gap:12px;width:100%;padding:12px;border:1px solid var(--border);border-radius:12px;background:#fff;font-weight:700;cursor:pointer}
    .btn-social-dndk:hover{background:#f8fafc;box-shadow:0 0 0 4px rgba(37,99,235,.18)}
    .hidden{display:none!important}

    /* thanh độ mạnh (giữ nguyên tinh thần cũ) */
    .pw-hint{margin-top:8px;font-size:13px;color:#64748b}
    .pw-bar{height:6px;border-radius:999px;background:#e5e7eb;margin-top:8px;overflow:hidden}
    .pw-bar>span{display:block;height:100%;width:0;background:linear-gradient(90deg,#f43f5e,#f59e0b,#22c55e);transition:width .25s}
    .pw-ok{color:#059669}.pw-bad{color:#b91c1c}

    /* báo khớp mật khẩu */
    .match-note{font-size:13px;margin-top:6px}
  </style>
</head>
<body>
  <div class="auth-wrap">
    <div class="card">
      <div class="auth-header">
        <img src="{{ asset('images/logotruong.jpg') }}" alt="VLUTE" class="logo" />
        <h1>HỆ THỐNG XÁC THỰC TẬP TRUNG (SSO)</h1>
      </div>

      <div class="tabs">
        <button id="tab-login" class="{{ ($activeTab ?? 'login') === 'login' ? 'active' : '' }}">Đăng nhập</button>
        <button id="tab-register" class="{{ ($activeTab ?? 'login') === 'register' ? 'active' : '' }}">Đăng ký</button>
      </div>

      {{-- LOGIN --}}
      <div id="panel-login" class="{{ ($activeTab ?? 'login') === 'login' ? '' : 'hidden' }}">
        <form action="{{ route('login') }}" method="POST">
          @csrf
          @if ($errors->any() && ($activeTab ?? 'login') === 'login')
            <div style="margin:10px 0;color:#b91c1c;font-weight:600;">{{ $errors->first() }}</div>
          @endif
          @if (session('status'))
            <div style="margin:10px 0;color:#059669;font-weight:700;">{{ session('status') }}</div>
          @endif

          <div class="group">
            <label for="login-email">E-mail</label>
            <div class="field"><input id="login-email" name="email" type="email" placeholder="Nhập email" required autofocus></div>
          </div>

          <div class="group">
            <label for="login-password">Mật khẩu</label>
            <div class="field has-eye">
              <input id="login-password" name="password" type="password" placeholder="Nhập mật khẩu" required>
              <button class="eye-toggle" type="button" aria-label="Hiện/ẩn mật khẩu" data-toggle="#login-password">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>

          <div class="options">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer"><input type="checkbox" name="remember"> Ghi nhớ</label>
            <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
          </div>

          <button class="btn-primary" type="submit">Đăng nhập</button>
        </form>

        <div class="divider">Hoặc đăng nhập với</div>
        <button class="btn-social-dndk" type="button" onclick="alert('Đăng nhập Google đang được phát triển');">Google</button>
      </div>

      {{-- REGISTER --}}
      <div id="panel-register" class="{{ ($activeTab ?? 'login') === 'register' ? '' : 'hidden' }}">
        <form id="reg-form" action="{{ route('register') }}" method="POST">
          @csrf
          @if ($errors->any() && ($activeTab ?? 'login') === 'register')
            <div style="margin:10px 0;color:#b91c1c;font-weight:600;">{{ $errors->first() }}</div>
          @endif

          <div class="group">
            <label for="reg-name">Họ và tên</label>
            <div class="field"><input id="reg-name" name="name" type="text" placeholder="Nhập họ và tên" required></div>
          </div>

          <div class="group">
            <label for="reg-email">E-mail</label>
            <div class="field"><input id="reg-email" name="email" type="email" placeholder="Nhập email của bạn" required></div>
          </div>

          {{-- DN fields của bạn giữ nguyên nếu có --}}

          <div class="group">
            <label for="reg-password">Mật khẩu</label>
            <div class="field has-eye">
              <input id="reg-password" name="password" type="password" placeholder="Tạo mật khẩu" required>
              <button class="eye-toggle" type="button" aria-label="Hiện/ẩn mật khẩu" data-toggle="#reg-password">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>

            {{-- thanh độ mạnh giữ nguyên như cũ của bạn --}}
            <div class="pw-hint" id="pw-hint-register">
              <div class="pw-bar"><span id="pw-bar-register"></span></div>
              <div id="pw-r1" class="pw-bad">Tối thiểu 8 ký tự</div>
              <div id="pw-r2" class="pw-bad">Có chữ hoa và chữ thường</div>
              <div id="pw-r3" class="pw-bad">Có số</div>
              <div id="pw-r4" class="pw-bad">Có ký tự đặc biệt (!@#$%^&amp;*...)</div>
              <div id="pw-r5" class="pw-bad">Không chứa phần tên email</div>
            </div>
          </div>

          {{-- XÁC NHẬN MẬT KHẨU (mới) --}}
          <div class="group">
            <label for="reg-password-confirm">Xác nhận mật khẩu</label>
            <div class="field has-eye">
              <input id="reg-password-confirm" name="password_confirmation" type="password" placeholder="Nhập lại mật khẩu" required>
              <button class="eye-toggle" type="button" aria-label="Hiện/ẩn mật khẩu" data-toggle="#reg-password-confirm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <div id="match-note" class="match-note"></div>
          </div>

          <button id="btn-register" class="btn-primary" type="submit">Đăng ký</button>

          <p style="text-align:center;margin-top:16px;font-size:14px">
            Bằng việc đăng ký, bạn đồng ý với <a href="javascript:void(0)">Điều khoản Dịch vụ</a>.
          </p>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Tabs giữ nguyên
    (function(){
      const active="{{ $activeTab ?? 'login' }}",tabL=document.getElementById('tab-login'),tabR=document.getElementById('tab-register'),
            pL=document.getElementById('panel-login'),pR=document.getElementById('panel-register');
      function show(t){ if(t==='login'){tabL.classList.add('active');tabR.classList.remove('active');pL.classList.remove('hidden');pR.classList.add('hidden');}
        else{tabR.classList.add('active');tabL.classList.remove('active');pR.classList.remove('hidden');pL.classList.add('hidden');} }
      show(active); tabL.onclick=()=>show('login'); tabR.onclick=()=>show('register');
    })();

    // Con mắt cho tất cả input có data-toggle
    document.addEventListener('click', (e)=>{
      const btn = e.target.closest('.eye-toggle'); if(!btn) return;
      const sel = btn.getAttribute('data-toggle'), input = document.querySelector(sel);
      if(!input) return; input.type = input.type === 'password' ? 'text' : 'password';
    });

    // Ẩn/hiện DN fields theo domain email của bạn (nếu có), giữ logic cũ
    (function(){
      const email = document.getElementById('reg-email'), block = document.getElementById('enterprise-fields');
      function toggle(){
        if(!email || !block) return;
        const v=(email.value||'').toLowerCase(), isVlute = v.endsWith('@st.vlute.edu.vn') || v.endsWith('@vlute.edu.vn');
        block.classList.toggle('hidden', isVlute || !v.includes('@'));
      }
      email && email.addEventListener('input', toggle); toggle();
    })();

    // Thanh độ mạnh (giữ nguyên tinh thần cũ, chỉ tính điểm cơ bản)
    (function(){
      const pw=document.getElementById('reg-password'),
            em=document.getElementById('reg-email'),
            bar=document.getElementById('pw-bar-register');
      const ids={r1:'pw-r1',r2:'pw-r2',r3:'pw-r3',r4:'pw-r4',r5:'pw-r5'};
      function setOK(id,ok){const el=document.getElementById(id); if(!el) return; el.classList.toggle('pw-ok',ok); el.classList.toggle('pw-bad',!ok);}
      function calc(v, email){
        const r1=v.length>=8, r2=/[A-Z]/.test(v)&&/[a-z]/.test(v), r3=/\d/.test(v), r4=/[^\w\s]/.test(v);
        let r5=true; if(email){const local=(email.split('@')[0]||'').toLowerCase(); if(local.length>=4) r5=!v.toLowerCase().includes(local);}
        const score=[r1,r2,r3,r4,r5].filter(Boolean).length; return {r1,r2,r3,r4,r5,score};
      }
      function paint(){
        const c=calc(pw.value||'', em?em.value:'');
        setOK(ids.r1,c.r1); setOK(ids.r2,c.r2); setOK(ids.r3,c.r3); setOK(ids.r4,c.r4); setOK(ids.r5,c.r5);
        if(bar) bar.style.width = (c.score/5*100)+'%';
      }
      if(pw){ pw.addEventListener('input',paint); em && em.addEventListener('input',paint); paint(); }
    })();

    // Kiểm tra KHỚP mật khẩu để bật/tắt nút Đăng ký
    (function(){
      const pw=document.getElementById('reg-password'),
            pc=document.getElementById('reg-password-confirm'),
            note=document.getElementById('match-note'),
            btn=document.getElementById('btn-register');
      function sync(){
        if(!pw||!pc||!btn) return;
        const ok = pw.value.length>0 && pw.value===pc.value;
        btn.disabled = !ok;
        if(note){
          note.textContent = ok ? 'Mật khẩu khớp.' : 'Mật khẩu nhập lại chưa khớp.';
          note.style.color = ok ? '#059669' : '#b91c1c';
        }
      }
      pw && pw.addEventListener('input',sync);
      pc && pc.addEventListener('input',sync);
      sync();
    })();
    (function(){
    const unapproved = "{{ session('unapproved') ? '1' : '' }}";
    if (unapproved) {
      const el = document.getElementById('modal-login-block-body');
      const email = @json(session('unapproved_email'));
      if (el) {
        el.innerHTML = `
          <p>Tài khoản <strong>${email ?? ''}</strong> đang chờ phê duyệt.</p>
          <p>Vui lòng đợi quản trị viên xác nhận. Bạn có thể quay lại sau.</p>
        `;
      }
      document.getElementById('modal-login-block')?.classList.add('show');
    }
  })();
  </script>
</body>
</html>