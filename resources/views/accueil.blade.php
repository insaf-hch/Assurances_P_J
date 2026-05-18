<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>تصفية ملفات المساعدة القضائية</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
      height: 100vh;
      overflow: hidden;
    }

    /* ══════════════════════════════════
       HERO
    ══════════════════════════════════ */
    .hero {
      position: relative;
      width: 100vw;
      height: 100vh;
       background-size: cover;
      background-position: center center;
      background-repeat: no-repeat;
      transition: opacity 0.4s ease;
    }
    .hero.hidden { opacity: 0; pointer-events: none; }

    .logo {
      position: absolute;
      top: 12px; left: 18px;
      width: 85px;
      background: transparent;
    }

       .header-info {
      position: absolute;
      top: 14px;
      righT: 0px;
      transform: translateX(-50%);
      text-align: center;
      line-height: 1.8;
    }

    .header-info p {  
      font-size: 13.5px;
       color: #1a1a2e;  
      font-weight: 750;
       right: 50px;

    }

   .title-block {
  position: absolute;
  top: 25%;
  left: 35%;
  transform: translateX(-50%);
  width: 100%;
  direction: rtl;
  text-align: center;
}
    .title-block h1 {
      font-size: clamp(24px, 2.6vw, 36px);
      font-weight: 700; color: #111; line-height: 1.4;
    }

    .btn-connect {
       position: absolute;
  top: 50%;
  left: 35%;
  transform: translateX(-50%);
      padding: 13px 30px;
      background: #1e3a5f;
      color: #fff; border: none;
      font-size: 12px; font-weight: 700;
      letter-spacing: 2px; text-transform: uppercase;
      cursor: pointer;
      transition: background 0.2s, transform 0.15s;
    }
    .btn-connect:hover { 
      background: #2a5282; transform: translateY(-1px); }

    /* ══════════════════════════════════
       LOGIN PAGE — style image partagée
    ══════════════════════════════════ */
    .login-page {
      position: fixed; inset: 0; z-index: 20;
     background-size: cover;
      background-position: center center;
      display: none;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.35s ease;
    }
    .login-page.active { display: flex; opacity: 1; }

    /* carte style image référence */
    .login-card {
      position: relative;
      width: 400px;
      max-width: 95vw;
      background: rgba(240,244,248,0.96);
      border-radius: 10px;
      padding: 32px 36px 36px;
      box-shadow: 0 8px 40px rgba(0,0,0,0.18);
      direction: rtl;
      text-align: right;
      animation: cardPop 0.35s cubic-bezier(0.34,1.56,0.64,1) both;
    }
    @keyframes cardPop {
      from { transform: scale(0.88) translateY(22px); opacity:0; }
      to   { transform: scale(1) translateY(0); opacity:1; }
    }

    /* titre en haut de la carte */
    .card-top {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 10px;
      margin-bottom: 20px;
      direction: rtl;
    }
    .card-top-title {
      font-size: 16px;
      font-weight: 700;
      color: #1a1a2e;
      left: 0%;
      line-height: 1.4;
    }
    .card-top-icon {
      font-size: 22px;
    }

    /* logo centré rond */
    .card-logo-wrap {
      display: flex;
      justify-content: center;
      margin-bottom: 24px;
    }
    .card-logo-wrap img {
      width: 90px; height: 90px;
      border-radius: 50%;
      border: 2px solid #c8d4e8;
      background: #fff;
      object-fit: contain;
      padding: 6px;
    }

    /* champs */
    .form-field {
      width: 100%;
      padding: 12px 14px;
      border: 1px solid #c8d4e0;
      border-radius: 4px;
      font-size: 14px;
      font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
      color: #333;
      background: #f4f6f9;
      margin-bottom: 10px;
      outline: none;
      text-align: right;
      direction: rtl;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-field:focus {
      border-color: #2e5faa;
      box-shadow: 0 0 0 3px rgba(46,95,170,0.12);
      background: #fff;
    }
    .form-field::placeholder {
      color: #888;
    }

    /* bouton connexion */
    .btn-login {
      width: 100%;
      padding: 13px;
      background: #2e5faa;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: 15px;
      font-weight: 700;
      font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
      cursor: pointer;
      margin-top: 6px;
      direction: rtl;
      transition: background 0.2s, transform 0.15s;
    }
    .btn-login:hover { background: #1e4a8a; transform: translateY(-1px); }

    /* retour */
    .back-btn {
      display: block; text-align: center;
      margin-top: 16px; font-size: 12px;
      color: #2e5faa; cursor: pointer;
      text-decoration: underline;
      background: none; border: none;
      font-family: inherit; direction: ltr;
    }
    .back-btn:hover { color: #1e4a8a; }
  </style>
</head>
<body>


<!-- ═══ HERO ═══ -->
<div class="hero" id="heroPage">
   <div class="header-info">
    <p>المملكة المغربية</p>
    <p>وزارة العدل</p>
    <p>محكمة الاستئناف بالجديدة</p>
    <p>المحكمة الابتدائية بالجديدة</p>
  </div>
  <div class="title-block">
    <h1>تصفية ملفات المساعدة القضائية</h1>
  </div>
  <button class="btn-connect" onclick="showLogin()">SE CONNECTER</button>
</div>

<!-- ═══ LOGIN ═══ -->
<div class="login-page" id="loginPage">
  <div class="login-card">

    <!-- titre + icône -->
    <div class="card-top">
      <div class="card-top-title">نظام التأمينات</div>
      
      
    </div>

    <!-- logo rond centré -->
    <div class="card-logo-wrap">
        </div>

    <!-- champs — vidés à chaque ouverture -->
   <form method="POST" action="{{ route('login') }}">

    @csrf

    <input
        class="form-field"
        type="text"
        name="name"
        placeholder="اسم المستخدم"
        required
    >

    <input
        class="form-field"
        type="password"
        name="password"
        placeholder="الرقم السري"
        required
    >

    <button type="submit" class="btn-login">
        تسجيل الدخول
    </button>
    <button class="back-btn" onclick="hideLogin()">← Retour</button>

</form>
  </div>
</div>

<script>

function showLogin() {

    document.getElementById('heroPage')
      .classList.add('hidden');

    const lp =
      document.getElementById('loginPage');

    lp.style.display = 'flex';

    requestAnimationFrame(() =>
      requestAnimationFrame(() =>
        lp.classList.add('active')
      )
    );
}

  function hideLogin() {

    const lp = document.getElementById('loginPage');

    lp.classList.remove('active');

    document.getElementById('heroPage').classList.remove('hidden');

    setTimeout(() => lp.style.display = 'none', 380);
  }

  
  // LOGOUT
  function logout() {

    document.getElementById('dashboardPage')
      .style.display = 'none';

    document.getElementById('heroPage')
      .classList.remove('hidden');
  }

  document.addEventListener('keydown', e => {

    if (e.key === 'Escape')
      hideLogin();

  });

</script>
</body>
</html>