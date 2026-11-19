<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Become a Blood Donor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* =========================================================
           BE.DONOR — PUBLIC THEME
           ========================================================= */
        :root {
            --red: #b30000;
            --red-2: #990000;
            --red-3: #6e0000;
            --red-hot: #d40000;
            --ink: #1d1012;
            --muted: #76666a;
            --line: #eee3e4;
            --bg: #faf6f6;
            --rose: #fff1f1;
            --rose-line: #f5d0d0;
            --green: #17935a;
            --font: 'Montserrat', system-ui, -apple-system, 'Segoe UI', sans-serif;
        }

        html { scroll-behavior: smooth; }
        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--ink);
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }
        a:focus-visible, button:focus-visible, .btn:focus-visible { outline: 2px solid var(--red); outline-offset: 3px; }
        .bd-hero a:focus-visible, .bd-hero button:focus-visible { outline-color: #fff; }

        /* ---------- Red hero stage ---------- */
        .bd-hero {
            position: relative; overflow: hidden; isolation: isolate; color: #fff;
            background:
                radial-gradient(ellipse 55% 65% at 50% 40%, #d40000 0%, rgba(212, 0, 0, 0) 70%),
                radial-gradient(ellipse 120% 90% at 50% 50%, var(--red) 0%, var(--red-2) 60%, var(--red-3) 100%);
        }
        .bd-hero::after {
            content: ""; position: absolute; inset: 0; z-index: -1; pointer-events: none;
            background: radial-gradient(ellipse at center, transparent 55%, rgba(40, 0, 0, .45) 100%);
        }
        .bd-watermark {
            position: absolute; inset: 0; z-index: -1; display: flex; align-items: center; justify-content: center;
            pointer-events: none; user-select: none; white-space: nowrap;
            font-weight: 900; font-size: clamp(7rem, 24vw, 22rem); line-height: .8; letter-spacing: -.02em;
            color: rgba(255, 255, 255, .05);
        }

        /* ---------- Navbar ---------- */
        .bd-nav { position: relative; z-index: 10; display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1.6rem 0 0; flex-wrap: wrap; }
        .bd-brand { display: inline-flex; align-items: center; gap: .55rem; color: #fff; text-decoration: none; }
        .bd-brand:hover { color: #fff; }
        .bd-brand svg { width: 30px; height: 36px; flex: 0 0 auto; }
        .bd-brand span { font-size: .72rem; font-weight: 800; letter-spacing: .16em; }
        .bd-links { display: flex; align-items: center; gap: clamp(1rem, 2.5vw, 2.25rem); list-style: none; margin: 0; padding: 0; }
        .bd-links a {
            position: relative; color: rgba(255, 255, 255, .75); text-decoration: none;
            font-size: .62rem; font-weight: 400; letter-spacing: .2em; text-transform: uppercase; padding: .4rem 0;
            transition: color .2s ease;
        }
        .bd-links a::after {
            content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 1px; background: #fff;
            transform: scaleX(0); transform-origin: left; transition: transform .3s ease;
        }
        .bd-links a:hover, .bd-links a.is-active { color: #fff; font-weight: 600; }
        .bd-links a:hover::after, .bd-links a.is-active::after { transform: scaleX(1); }
        .bd-actions { display: flex; align-items: center; gap: .6rem; }
        .bd-cta {
            display: inline-flex; align-items: center; gap: .5rem; white-space: nowrap;
            background: #fff; color: var(--red); border: 1px solid #fff; border-radius: 999px;
            font-size: .62rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
            padding: .62rem 1.1rem; text-decoration: none; transition: background .2s, color .2s, transform .2s;
        }
        .bd-cta:hover { background: transparent; color: #fff; transform: translateY(-1px); }
        .bd-cta.ghost { background: transparent; color: #fff; border-color: rgba(255, 255, 255, .65); }
        .bd-cta.ghost:hover { background: #fff; color: var(--red); border-color: #fff; }

        /* ---------- Hero copy ---------- */
        .bd-hero-body { position: relative; z-index: 3; padding: 3.5rem 0 4.5rem; }
        .bd-eyebrow {
            display: inline-flex; align-items: center; gap: .8rem; margin-bottom: 1.1rem;
            font-size: .6rem; letter-spacing: .24em; text-transform: uppercase; color: rgba(255, 255, 255, .78);
        }
        .bd-eyebrow b { font-size: 1.15rem; font-weight: 800; color: #fff; letter-spacing: 0; }
        .bd-eyebrow b small { font-size: .55rem; font-weight: 400; color: rgba(255, 255, 255, .7); margin-left: .1rem; }
        .bd-eyebrow::after { content: ""; width: 60px; height: 1px; background: rgba(255, 255, 255, .45); }
        .bd-title {
            font-weight: 800; text-transform: uppercase; letter-spacing: .01em; line-height: 1;
            font-size: clamp(2rem, 6vw, 4.6rem); margin: 0 0 1.1rem;
            text-shadow: 0 12px 40px rgba(70, 0, 0, .35);
        }
        .bd-lead { font-size: .8rem; line-height: 2; max-width: 520px; color: rgba(255, 255, 255, .9); margin: 0; }
        .bd-line-stat { display: inline-flex; align-items: center; gap: 1rem; font-size: .62rem; font-weight: 300; letter-spacing: .04em; color: rgba(255, 255, 255, .8); }
        .bd-line-stat::before {
            content: ""; width: 9px; height: 9px; border-radius: 50%; background: #fff; flex: 0 0 auto;
            box-shadow: 0 0 0 0 rgba(255, 255, 255, .6); animation: bdPulse 2.4s ease-out infinite;
        }
        .bd-line-stat::after { content: ""; width: 80px; height: 1px; background: rgba(255, 255, 255, .45); order: -1; }
        @keyframes bdPulse { to { box-shadow: 0 0 0 12px rgba(255, 255, 255, 0); } }

        /* ---------- Content surfaces ---------- */
        .bd-panel {
            background: #fff; border: 1px solid var(--line); border-radius: 18px;
            box-shadow: 0 1px 2px rgba(60, 0, 0, .04), 0 22px 44px -26px rgba(110, 0, 0, .28);
        }
        .bd-section-label {
            display: inline-flex; align-items: center; gap: .8rem; color: var(--muted);
            font-size: .62rem; font-weight: 600; letter-spacing: .22em; text-transform: uppercase;
        }
        .bd-section-label b { color: var(--red); font-size: 1.05rem; font-weight: 800; letter-spacing: 0; }
        .bd-section-label::after { content: ""; width: 48px; height: 1px; background: var(--rose-line); }

        /* Forms */
        .form-label {
            font-size: .6rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase;
            color: var(--muted); margin-bottom: .45rem;
        }
        .form-control, .form-select {
            height: 50px; border-radius: 12px; border: 1px solid var(--line); background-color: #fcfafa;
            font-family: var(--font); font-weight: 500; font-size: .88rem; color: var(--ink);
            transition: border-color .15s, box-shadow .15s, background-color .15s;
        }
        textarea.form-control { height: auto; padding-top: .8rem; }
        .form-control::placeholder { color: #b3a6a8; font-weight: 400; }
        .form-control:hover, .form-select:hover { border-color: #e2d0d2; }
        .form-control:focus, .form-select:focus { border-color: var(--red); background-color: #fff; box-shadow: 0 0 0 .22rem rgba(179, 0, 0, .12); }
        .form-check-input:checked { background-color: var(--red); border-color: var(--red); }
        .form-check-input:focus { border-color: var(--red); box-shadow: 0 0 0 .2rem rgba(179, 0, 0, .18); }
        .form-text, .hint { color: var(--muted); font-size: .7rem; line-height: 1.6; }

        /* Buttons */
        .btn-bd {
            display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
            min-height: 50px; padding: 0 1.6rem; border: 0; border-radius: 999px;
            background: var(--red); color: #fff;
            font-weight: 700; font-size: .68rem; letter-spacing: .16em; text-transform: uppercase;
            box-shadow: 0 14px 26px -12px rgba(179, 0, 0, .75);
            transition: background .2s, transform .2s, box-shadow .2s;
        }
        .btn-bd:hover { background: var(--red-2); color: #fff; transform: translateY(-1px); box-shadow: 0 18px 30px -12px rgba(179, 0, 0, .8); }
        .btn-bd:disabled { opacity: .7; transform: none; }
        .btn-round {
            width: 50px; height: 50px; flex: 0 0 50px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            border: 1px solid var(--red); color: var(--red); background: #fff; text-decoration: none;
            transition: background .2s, color .2s, transform .2s;
        }
        .btn-round:hover { background: var(--red); color: #fff; transform: scale(1.05); }

        /* Alerts */
        .alert { border: 0; border-radius: 14px; font-weight: 600; font-size: .84rem; line-height: 1.6; }
        .alert-success { background: #e9f7ef; color: #11693f; border-left: 3px solid var(--green); }
        .alert-danger { background: var(--rose); color: var(--red-2); border-left: 3px solid var(--red); }

        /* Pagination: round outline buttons (like the hero arrows) */
        .pagination { gap: .45rem; margin: 0; flex-wrap: wrap; justify-content: center; }
        .pagination .page-link {
            min-width: 44px; height: 44px; padding: 0 .6rem; display: inline-flex; align-items: center; justify-content: center;
            border: 1px solid var(--rose-line); border-radius: 999px !important; background: #fff;
            color: var(--ink); font-weight: 700; font-size: .8rem; transition: all .15s ease;
        }
        .pagination .page-link:hover { background: var(--rose); border-color: var(--red); color: var(--red); }
        .pagination .page-link:focus { box-shadow: 0 0 0 .2rem rgba(179, 0, 0, .18); }
        .pagination .page-item.active .page-link { background: var(--red); border-color: var(--red); color: #fff; box-shadow: 0 10px 18px -8px rgba(179, 0, 0, .7); }
        .pagination .page-item.disabled .page-link { background: var(--bg); color: #c3b6b8; border-color: var(--line); }

        /* Blood-drop badge */
        .bd-drop {
            width: 54px; height: 54px; min-width: 54px; border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; margin: 4px 4px 0 0;
            background: radial-gradient(circle at 35% 30%, #ff3b3b, var(--red) 55%, var(--red-3));
            box-shadow: 0 12px 20px -6px rgba(179, 0, 0, .55), inset 0 2px 3px rgba(255, 255, 255, .3), inset 0 -3px 6px rgba(0, 0, 0, .2);
        }
        .bd-drop span { transform: rotate(45deg); color: #fff; font-weight: 800; font-size: .92rem; }
        .bd-drop.is-green { background: radial-gradient(circle at 35% 30%, #34c27f, var(--green) 55%, #0f5c38); box-shadow: 0 12px 20px -6px rgba(23, 147, 90, .5); }

        /* Contact buttons */
        .btn-call, .btn-wa {
            display: inline-flex; align-items: center; justify-content: center; gap: .35rem;
            min-height: 44px; border-radius: 999px; text-decoration: none; text-align: center;
            font-weight: 700; font-size: .66rem; letter-spacing: .14em; text-transform: uppercase;
            transition: background .15s, color .15s, transform .15s, border-color .15s;
        }
        .btn-call { background: #fff; color: var(--ink); border: 1px solid var(--line); }
        .btn-call:hover { border-color: var(--red); color: var(--red); transform: translateY(-1px); }
        .btn-wa { background: var(--green); color: #fff; border: 1px solid var(--green); }
        .btn-wa:hover { background: #12784a; border-color: #12784a; color: #fff; transform: translateY(-1px); }

        /* ---------- Responsive ---------- */
        @media (max-width: 991.98px) {
            .bd-links { order: 3; width: 100%; justify-content: center; padding-top: .8rem; border-top: 1px solid rgba(255, 255, 255, .15); flex-wrap: wrap; row-gap: .4rem; }
        }
        @media (max-width: 575.98px) {
            .bd-nav { padding-top: 1.1rem; }
            .bd-actions .bd-cta .lbl { display: none; }
            .bd-actions .bd-cta { padding: .6rem .75rem; }
            .bd-hero-body { padding: 2.4rem 0 3.5rem; }
            .bd-links { gap: 1rem; }
            .bd-links a { font-size: .56rem; letter-spacing: .14em; }
            .form-control, .form-select { font-size: 16px; } /* stops iOS zoom */
            .container { padding-left: 1rem; padding-right: 1rem; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
        /* ---------- 3D red blood cells (pure CSS) ---------- */
        .rbc-field { position: absolute; inset: 0; perspective: 900px; pointer-events: none; z-index: -1; }
        .rbc {
            position: absolute; left: var(--x); top: var(--y); width: var(--s); aspect-ratio: 1;
            filter: blur(var(--blur, 0px));
            animation: rbcFloat var(--dur, 10s) ease-in-out var(--delay, 0s) infinite alternate;
        }
        .rbc i {
            position: absolute; inset: 0; border-radius: 50%;
            transform: rotateX(var(--rx, 55deg)) rotateY(var(--ry, 0deg)) rotateZ(var(--rz, 0deg));
            background: radial-gradient(circle at 50% 50%, #5c0000 0%, #8f0006 16%, #c1000c 32%, #ff2b2b 52%, #ff4a43 58%, #d3000f 70%, #9a0007 86%, #6a0000 100%);
            box-shadow: inset 0 0 0 2px rgba(255, 90, 90, .25), inset -14px -18px 30px rgba(50, 0, 0, .55), inset 12px 14px 22px rgba(255, 140, 130, .35), 0 40px 50px -10px rgba(50, 0, 0, .45);
            animation: rbcSpin var(--spin, 28s) linear infinite;
        }
        .rbc i::after {
            content: ""; position: absolute; inset: 6%; border-radius: 50%;
            background: radial-gradient(ellipse 45% 30% at 32% 22%, rgba(255, 210, 200, .55), transparent 70%);
            mix-blend-mode: screen;
        }
        @keyframes rbcFloat { to { transform: translate3d(var(--fx, 10px), var(--fy, -20px), 0); } }
        @keyframes rbcSpin {
            from { transform: rotateX(var(--rx, 55deg)) rotateY(var(--ry, 0deg)) rotateZ(var(--rz, 0deg)); }
            to   { transform: rotateX(var(--rx, 55deg)) rotateY(var(--ry, 0deg)) rotateZ(calc(var(--rz, 0deg) + 360deg)); }
        }
        /* ---------- Page: become a donor ---------- */
        * { -webkit-tap-highlight-color: transparent; }
        .bd-hero-body { padding: 2.6rem 0 6.5rem; text-align: center; }
        .bd-hero-body .bd-lead { margin: 0 auto; }
        .bd-hero-body .bd-eyebrow { justify-content: center; }

        .form-card { margin-top: -4.5rem; position: relative; z-index: 4; padding: 1.8rem 1.8rem 1.6rem; overflow: hidden; }
        .form-card .card-icon {
            width: 54px; height: 54px; border-radius: 50%; margin: -.2rem auto 1.2rem;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid var(--red); color: var(--red); font-size: 1.15rem;
        }
        .form-card .mb-3 { margin-bottom: .9rem !important; }

        /* Guardian / privacy box */
        .guardian-box { background: var(--rose); border: 1px solid var(--rose-line); border-radius: 14px; padding: 1rem; }
        .guardian-box .title { color: var(--red-2); font-weight: 800; font-size: .7rem; letter-spacing: .14em; text-transform: uppercase; }
        .guardian-box .form-control, .guardian-box .form-select { background-color: #fff; }

        /* Checkbox rows */
        .form-check {
            display: flex; align-items: center; gap: .7rem; padding: 0 1rem; margin-bottom: 0;
            background: #fcfafa; border: 1px solid var(--line); border-radius: 12px; min-height: 48px;
        }
        .form-check .form-check-input { float: none; margin: 0; width: 1.1rem; height: 1.1rem; flex: 0 0 auto; }
        .form-check-label { cursor: pointer; font-size: .8rem; }

        .btn-register { width: 100%; }

        @media (max-width: 575.98px) {
            .form-card { margin-top: -4rem; padding: 1.3rem 1.05rem 1.15rem; border-radius: 16px; }
        }
        @supports (padding: max(0px)) {
            .bd-hero .container, .container.pb-5 {
                padding-left: max(1rem, env(safe-area-inset-left));
                padding-right: max(1rem, env(safe-area-inset-right));
            }
        }
    </style>
</head>
<body>

<header class="bd-hero">
    <div class="bd-watermark" aria-hidden="true">DONOR</div>
    <div class="rbc-field" aria-hidden="true">
        <span class="rbc" style="--x:62%;--y:12%;--s:clamp(70px,8vw,120px);--rx:60deg;--ry:-18deg;--rz:20deg;--blur:1.5px;--dur:11s;"><i></i></span>
        <span class="rbc" style="--x:78%;--y:46%;--s:clamp(90px,11vw,170px);--rx:64deg;--ry:10deg;--rz:-12deg;--dur:12s;--delay:-4s;--fx:-12px;--fy:-16px;"><i style="--spin:38s"></i></span>
        <span class="rbc" style="--x:88%;--y:6%;--s:clamp(40px,4vw,60px);--rx:48deg;--ry:22deg;--rz:-30deg;--blur:3px;--dur:9s;"><i></i></span>
        <span class="rbc" style="--x:68%;--y:72%;--s:clamp(50px,5vw,80px);--rx:52deg;--ry:-25deg;--rz:40deg;--blur:1px;--dur:13s;--delay:-2s;"><i></i></span>
        <span class="rbc" style="--x:6%;--y:70%;--s:clamp(36px,3.5vw,54px);--rx:55deg;--ry:-20deg;--rz:30deg;--blur:4px;--dur:10s;--delay:-6s;"><i></i></span>
    </div>

    <div class="container">
        <nav class="bd-nav">
            <a class="bd-brand" href="{{ url('/') }}" aria-label="Blood Donor Network home">
                <svg viewBox="0 0 34 40" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M17 2C17 2 5 15.5 5 24.5C5 31.4 10.4 37 17 37C23.6 37 29 31.4 29 24.5C29 15.5 17 2 17 2Z"/>
                <path d="M10 26.5C10 29.8 12.6 32.5 16 32.8"/>
                <rect x="17.5" y="21" width="12" height="12" rx="1" fill="var(--logo-fill, #b30000)"/>
                <path d="M23.5 23.5V30.5M20 27H27"/>
            </svg>
                <span>BE.DONOR</span>
            </a>
            <ul class="bd-links" role="list">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ route('blood-requests.index') }}"><i class="fa-solid fa-bell me-1"></i> Urgent requests</a></li>
                <li><a href="{{ route('donors.public.create') }}" class="is-active">Become a donor</a></li>
            </ul>
            <div class="bd-actions">
                <a href="{{ route('donors.public.search') }}" class="bd-cta">
                    <i class="fa-solid fa-magnifying-glass"></i> <span class="lbl">Find a donor</span>
                </a>
            </div>
        </nav>

        <div class="bd-hero-body">
            <div class="bd-eyebrow"><b>02<small>/03</small></b> Registration</div>
            <h1 class="bd-title">Become a<br>blood donor</h1>
            <p class="bd-lead">Register once and help people find you when they urgently need blood.</p>
        </div>
    </div>
</header>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
            <div class="form-card bd-panel">
                <div class="card-icon" aria-hidden="true"><i class="fa-solid fa-hand-holding-heart"></i></div>

                <div id="successBox" class="alert alert-success d-none"></div>
                <div id="errorBox" class="alert alert-danger d-none"></div>

                <form id="publicDonorForm">
                    <div class="mb-3">
                        <label class="form-label">Full name</label>
                        <input type="text" name="name" class="form-control" placeholder="Your full name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Personal phone number</label>
                        <input type="text" name="phone" class="form-control" placeholder="03001234567" required>
                        <small class="hint"><i class="fa-solid fa-lock me-1"></i>This number will not be shown publicly for female donors.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                        <small class="hint"><i class="fa-solid fa-bell me-1"></i>Used to alert you when someone in your city urgently needs your blood group.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" id="genderSelect" class="form-select" required>
                            <option value="" disabled selected>Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Female privacy section -->
                    <div id="guardianSection" class="guardian-box mb-3 d-none">
                        <div class="title mb-1"><i class="fa-solid fa-shield-heart me-2"></i>Privacy protection (guardian contact)</div>
                        <p class="text-muted small mb-3">This contact will be shown on public search instead of your personal number.</p>

                        <div class="row g-2">
                            <div class="col-12 col-sm-6">
                                <label class="form-label small">Guardian / Mehram name</label>
                                <input type="text" name="guardian_name" id="guardianName" class="form-control" placeholder="e.g. Ahmad Rao">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label small">Relation</label>
                                <select name="guardian_relation" id="guardianRelation" class="form-select">
                                    <option value="Father">Father</option>
                                    <option value="Brother">Brother</option>
                                    <option value="Husband">Husband</option>
                                    <option value="Guardian">Other guardian</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small">Guardian phone / WhatsApp</label>
                                <input type="text" name="guardian_phone" id="guardianPhone" class="form-control" placeholder="03009876543">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Blood group</label>
                            <select name="blood_group" class="form-select" required>
                                <option value="">Select</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" placeholder="e.g. Sargodha" required>
                        </div>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_available" value="1" id="availableCheck" checked>
                        <label class="form-check-label fw-semibold" for="availableCheck">I am available to donate</label>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="notify_urgent_requests" value="1" id="alertCheck" checked>
                        <label class="form-check-label fw-semibold text-danger" for="alertCheck">
                            <i class="fa-solid fa-envelope-circle-check me-1"></i> Email me when someone urgently needs my blood
                        </label>
                    </div>

                    <button type="submit" id="submitBtn" class="btn btn-bd btn-register w-100">
                        <i class="fa-solid fa-hand-holding-heart me-1"></i> Register now
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const genderSelect = document.getElementById('genderSelect');
const guardianSection = document.getElementById('guardianSection');
const guardianFields = guardianSection.querySelectorAll('input, select');
const submitLabel = '<i class="fa-solid fa-hand-holding-heart me-1"></i> Register now';

function toggleGuardian() {
    const isFemale = genderSelect.value === 'Female';
    guardianSection.classList.toggle('d-none', !isFemale);
    guardianFields.forEach(f => {
        f.disabled = !isFemale;              // disabled fields are not submitted
        f.required = isFemale && f.id !== 'guardianRelation';
    });
}

genderSelect.addEventListener('change', toggleGuardian);
toggleGuardian();

document.getElementById('publicDonorForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const btn = document.getElementById('submitBtn');
    const successBox = document.getElementById('successBox');
    const errorBox = document.getElementById('errorBox');

    btn.disabled = true;
    btn.innerText = 'Registering...';
    successBox.classList.add('d-none');
    errorBox.classList.add('d-none');

    const formData = new FormData(this);

    try {
        const response = await fetch("{{ route('donors.public.store') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrf,
                "Accept": "application/json"
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            // Save ownership in localStorage
            localStorage.setItem('my_donor_token', data.donor_token);
            localStorage.setItem('my_donor_id', data.donor_id);

            this.reset();
            toggleGuardian();
            successBox.innerHTML = `${data.message} <br><a href="{{ route('donors.public.search') }}" class="alert-link">View your card on Public Search</a>`;
            successBox.classList.remove('d-none');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            let errorText = data.message || 'Validation error!';
            if (data.errors) {
                errorText = Object.values(data.errors).flat().join('<br>');
            }
            errorBox.innerHTML = errorText;
            errorBox.classList.remove('d-none');
        }
    } catch (err) {
        errorBox.innerText = 'Network error! Please try again.';
        errorBox.classList.remove('d-none');
    } finally {
        btn.disabled = false;
        btn.innerHTML = submitLabel;
    }
});
</script>
</body>
</html>
