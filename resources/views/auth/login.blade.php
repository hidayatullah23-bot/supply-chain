<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#07111f">
    <title>Login | SupplyChain Risk Intelligence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root{--navy:#07111f;--panel:#0d192b;--panel-2:#111f35;--line:rgba(148,163,184,.16);--muted:#8fa0b8;--white:#f6f9ff;--violet:#7c6cff;--cyan:#22d3ee;--yellow:#ffbe21}
        *{box-sizing:border-box}html,body{margin:0;min-height:100%;font-family:Manrope,system-ui,sans-serif;color:var(--white);background:var(--navy)}body{min-height:100vh;overflow-x:hidden}.auth-shell{min-height:100vh;display:grid;grid-template-columns:minmax(0,1.15fr) minmax(480px,.85fr);position:relative;background:radial-gradient(circle at 22% 54%,rgba(39,80,151,.24),transparent 34%),#07111f}.visual-panel{min-height:100vh;position:relative;overflow:hidden;padding:42px 54px;display:flex;flex-direction:column;border-right:1px solid var(--line)}.visual-panel:before{content:"";position:absolute;inset:0;background-image:linear-gradient(rgba(148,163,184,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(148,163,184,.035) 1px,transparent 1px);background-size:46px 46px;mask-image:linear-gradient(to bottom,#000,transparent 90%)}.brand{display:flex;align-items:center;gap:12px;text-decoration:none;color:#fff;position:relative;z-index:4;width:max-content}.brand-mark{width:43px;height:43px;border-radius:13px;display:grid;place-items:center;color:#111827;background:linear-gradient(135deg,#ffd44d,#ff9e00);box-shadow:0 12px 30px rgba(255,174,0,.18)}.brand-name{font-size:1rem;font-weight:800;line-height:1.05}.brand-name small{display:block;margin-top:6px;color:#fbbf24;font-size:.61rem;letter-spacing:.16em;font-weight:700}.visual-copy{position:relative;z-index:4;margin-top:auto;margin-bottom:3vh;max-width:650px}.eyebrow{display:inline-flex;align-items:center;gap:9px;padding:7px 11px;border:1px solid rgba(103,232,249,.2);border-radius:99px;color:#a5f3fc;background:rgba(8,145,178,.08);font-size:.66rem;letter-spacing:.13em;font-weight:800}.eyebrow i{font-size:.58rem;color:#34d399}.visual-copy h1{font-size:clamp(2.2rem,4.2vw,4.5rem);line-height:1.05;letter-spacing:-.055em;margin:22px 0 18px}.visual-copy h1 span{background:linear-gradient(90deg,#fff 5%,#a5b4fc 55%,#67e8f9);-webkit-background-clip:text;background-clip:text;color:transparent}.visual-copy p{max-width:570px;color:#94a3b8;font-size:.95rem;line-height:1.8;margin:0}.signal-row{display:flex;flex-wrap:wrap;gap:12px;margin-top:30px}.signal{padding:10px 13px;border:1px solid var(--line);border-radius:11px;background:rgba(13,25,43,.72);backdrop-filter:blur(10px);font-size:.7rem;color:#aab7c9}.signal i{color:#67e8f9;margin-right:7px}.globe{position:absolute;width:min(53vw,720px);aspect-ratio:1;right:-16%;top:4%;border-radius:50%;border:1px solid rgba(103,232,249,.18);background:radial-gradient(circle at 35% 30%,rgba(124,108,255,.2),transparent 30%),radial-gradient(circle at 50% 50%,rgba(17,37,68,.25),rgba(5,13,25,.58) 67%);box-shadow:inset -30px -25px 80px rgba(0,0,0,.46),0 0 90px rgba(34,211,238,.07)}.globe:before,.globe:after{content:"";position:absolute;inset:12%;border:1px solid rgba(103,232,249,.12);border-radius:50%}.globe:before{transform:scaleX(.42)}.globe:after{transform:scaleY(.42)}.globe-lines{position:absolute;inset:0;border-radius:50%;overflow:hidden;background:repeating-radial-gradient(ellipse at center,transparent 0 55px,rgba(103,232,249,.06) 56px 57px)}.orbit{position:absolute;inset:-8%;border:1px solid rgba(124,108,255,.18);border-radius:50%;transform:rotate(-18deg) scaleY(.36)}.orbit:after{content:"";position:absolute;width:8px;height:8px;border-radius:50%;background:#67e8f9;left:15%;top:18%;box-shadow:0 0 18px #22d3ee}.continent{position:absolute;background:linear-gradient(135deg,rgba(65,105,170,.5),rgba(28,63,110,.25));border:1px solid rgba(125,211,252,.17);filter:drop-shadow(0 0 12px rgba(34,211,238,.1))}.c1{width:29%;height:22%;left:19%;top:23%;border-radius:70% 30% 55% 45%/45% 43% 57% 55%;transform:rotate(-18deg)}.c2{width:17%;height:30%;left:39%;top:47%;border-radius:40% 60% 70% 30%/32% 30% 70% 68%;transform:rotate(12deg)}.c3{width:35%;height:18%;left:51%;top:27%;border-radius:60% 40% 52% 48%/42% 62% 38% 58%;transform:rotate(9deg)}.c4{width:13%;height:10%;left:72%;top:62%;border-radius:68% 32% 57% 43%;transform:rotate(20deg)}.node{position:absolute;width:7px;height:7px;border-radius:50%;background:#fbbf24;box-shadow:0 0 0 5px rgba(251,191,36,.1),0 0 18px #fbbf24;z-index:2}.n1{top:33%;left:34%}.n2{top:38%;left:67%}.n3{top:58%;left:48%}.n4{top:67%;left:76%}.route{position:absolute;height:1px;transform-origin:left;background:linear-gradient(90deg,#fbbf24,rgba(34,211,238,.3));opacity:.55}.r1{width:34%;left:34%;top:33%;transform:rotate(10deg)}.r2{width:26%;left:48%;top:58%;transform:rotate(-45deg)}
        .form-panel{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:48px;position:relative;background:radial-gradient(circle at 100% 0,rgba(124,108,255,.1),transparent 30%),rgba(7,17,31,.82)}.login-wrap{width:min(100%,440px)}.secure-label{display:flex;align-items:center;justify-content:space-between;margin-bottom:38px;color:#718096;font-size:.67rem;letter-spacing:.09em;text-transform:uppercase}.secure-label span:first-child{display:flex;align-items:center;gap:8px}.secure-label i{color:#34d399}.secure-label .status{width:7px;height:7px;border-radius:50%;background:#34d399;box-shadow:0 0 0 4px rgba(52,211,153,.1)}.login-heading h2{font-size:2rem;letter-spacing:-.04em;margin:0 0 9px}.login-heading p{font-size:.84rem;color:var(--muted);margin:0 0 32px;line-height:1.65}.field{margin-bottom:18px}.field-head{display:flex;justify-content:space-between;margin-bottom:9px}.field label{font-size:.72rem;color:#cbd5e1;font-weight:700}.input-shell{height:54px;display:flex;align-items:center;border:1px solid #26364e;border-radius:13px;background:rgba(8,18,33,.75);transition:.2s}.input-shell:focus-within{border-color:#7c6cff;box-shadow:0 0 0 4px rgba(124,108,255,.11);background:#0b1728}.input-shell>i{width:48px;text-align:center;color:#64748b}.input-shell input{flex:1;min-width:0;height:100%;border:0;outline:0;background:transparent;color:#fff;font:500 .83rem Manrope;padding:0 14px 0 0}.input-shell input::placeholder{color:#536279}.toggle-password{width:48px;height:100%;border:0;background:transparent;color:#64748b;cursor:pointer}.toggle-password:hover{color:#c4bfff}.form-options{display:flex;align-items:center;justify-content:space-between;margin:4px 0 23px;color:#98a7bb;font-size:.72rem}.remember{display:flex;align-items:center;gap:8px;cursor:pointer}.remember input{appearance:none;width:16px;height:16px;border:1px solid #40516a;border-radius:5px;background:#0a1525;display:grid;place-content:center}.remember input:checked{background:#7c6cff;border-color:#7c6cff}.remember input:checked:after{content:"✓";font-size:.68rem;color:#fff}.submit{width:100%;height:54px;border:0;border-radius:13px;background:linear-gradient(100deg,#7565ff,#5648de);color:#fff;font:800 .79rem Manrope;letter-spacing:.02em;cursor:pointer;box-shadow:0 14px 32px rgba(85,72,222,.24);transition:.2s}.submit:hover{transform:translateY(-2px);box-shadow:0 18px 40px rgba(85,72,222,.34)}.submit i{margin-left:9px}.error-box{display:flex;gap:10px;margin:-4px 0 18px;padding:11px 13px;border:1px solid rgba(248,113,113,.25);border-radius:10px;background:rgba(127,29,29,.16);color:#fca5a5;font-size:.7rem}.register-link{text-align:center;color:#7f8da3;font-size:.73rem;margin-top:24px}.register-link a{color:#b8b1ff;text-decoration:none;font-weight:700}.register-link a:hover{color:#fff}.trust{display:flex;justify-content:center;gap:18px;margin-top:35px;padding-top:24px;border-top:1px solid var(--line);color:#596980;font-size:.62rem}.trust span{display:flex;align-items:center;gap:6px}.trust i{color:#64748b}
        @media(max-width:1000px){.auth-shell{grid-template-columns:1fr}.visual-panel{min-height:390px;padding:32px;border-right:0;border-bottom:1px solid var(--line)}.visual-copy{margin-top:85px}.visual-copy h1{font-size:2.65rem}.globe{width:480px;right:-150px;top:-30px}.form-panel{min-height:auto;padding:60px 26px}.secure-label{margin-bottom:28px}}
        @media(max-width:560px){.visual-panel{min-height:315px;padding:24px}.brand-mark{width:38px;height:38px}.visual-copy{margin-top:auto;margin-bottom:0}.visual-copy h1{font-size:2rem;margin:16px 0 10px}.visual-copy p{font-size:.78rem}.signal-row{display:none}.globe{width:360px;right:-190px;top:-40px;opacity:.7}.form-panel{padding:42px 20px}.secure-label{margin-bottom:24px}.login-heading h2{font-size:1.72rem}.trust{gap:10px;flex-wrap:wrap}}
    </style>
</head>
<body>
<main class="auth-shell">
    <section class="visual-panel" aria-label="Global Supply Chain Intelligence">
        <a class="brand" href="{{ route('countries.index') }}">
            <span class="brand-mark"><i class="fa-solid fa-boxes-stacked"></i></span>
            <span class="brand-name">SupplyChain<small>RISK INTELLIGENCE</small></span>
        </a>

        <div class="globe" aria-hidden="true">
            <div class="globe-lines"></div><div class="orbit"></div>
            <i class="continent c1"></i><i class="continent c2"></i><i class="continent c3"></i><i class="continent c4"></i>
            <i class="node n1"></i><i class="node n2"></i><i class="node n3"></i><i class="node n4"></i>
            <i class="route r1"></i><i class="route r2"></i>
        </div>

        <div class="visual-copy">
            <span class="eyebrow"><i class="fa-solid fa-circle"></i> GLOBAL COMMAND CENTER</span>
            <h1>See risk.<br><span>Move with confidence.</span></h1>
            <p>Platform intelijen terpadu untuk memantau risiko ekonomi, cuaca, pelabuhan, dan pergerakan rantai pasok di seluruh dunia.</p>
            <div class="signal-row">
                <span class="signal"><i class="fa-solid fa-earth-asia"></i>192 negara terpantau</span>
                <span class="signal"><i class="fa-solid fa-anchor"></i>3.824 pelabuhan</span>
                <span class="signal"><i class="fa-solid fa-satellite-dish"></i>Live intelligence</span>
            </div>
        </div>
    </section>

    <section class="form-panel">
        <div class="login-wrap">
            <div class="secure-label"><span><span class="status"></span> Secure access portal</span><span>INTEL / 01</span></div>
            <div class="login-heading">
                <h2>Selamat datang kembali</h2>
                <p>Masuk untuk mengakses dashboard risiko global dan insight rantai pasok Anda.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="field">
                    <div class="field-head"><label for="email">Alamat email</label></div>
                    <div class="input-shell">
                        <i class="fa-regular fa-envelope"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@perusahaan.com" autocomplete="email" required autofocus>
                    </div>
                </div>
                <div class="field">
                    <div class="field-head"><label for="password">Password</label></div>
                    <div class="input-shell">
                        <i class="fa-solid fa-lock"></i>
                        <input id="password" type="password" name="password" placeholder="Masukkan password" autocomplete="current-password" required>
                        <button class="toggle-password" type="button" aria-label="Tampilkan password" id="togglePassword"><i class="fa-regular fa-eye"></i></button>
                    </div>
                </div>

                @error('email')<div class="error-box"><i class="fa-solid fa-circle-exclamation"></i><span>{{ $message }}</span></div>@enderror

                <div class="form-options">
                    <label class="remember" for="remember"><input type="checkbox" name="remember" id="remember"> Ingat saya di perangkat ini</label>
                </div>
                <button class="submit" type="submit">Masuk ke Command Center <i class="fa-solid fa-arrow-right"></i></button>
            </form>

            <p class="register-link">Belum memiliki akses? <a href="{{ route('register') }}">Buat akun baru</a></p>
            <div class="trust"><span><i class="fa-solid fa-lock"></i>Encrypted session</span><span><i class="fa-solid fa-shield-halved"></i>Protected access</span><span><i class="fa-solid fa-circle-check"></i>System operational</span></div>
        </div>
    </section>
</main>
<script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    toggle.addEventListener('click', () => {
        const hidden = password.type === 'password';
        password.type = hidden ? 'text' : 'password';
        toggle.innerHTML = `<i class="fa-regular fa-eye${hidden ? '-slash' : ''}"></i>`;
        toggle.setAttribute('aria-label', hidden ? 'Sembunyikan password' : 'Tampilkan password');
    });
</script>
</body>
</html>
