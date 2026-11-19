<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urgent Blood Requests - Live Emergency Feed</title>
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
        /* ---------- Page: urgent feed ---------- */
        .bd-cta.is-emergency { animation: ringPulse 2s infinite; }
        @keyframes ringPulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 255, 255, .6); }
            70% { box-shadow: 0 0 0 12px rgba(255, 255, 255, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
        }
        .emergency-pill {
            display: inline-flex; align-items: center; gap: .5rem; margin-bottom: 1.2rem;
            border: 1px solid rgba(255, 255, 255, .55); border-radius: 999px; padding: .4rem 1rem;
            font-size: .6rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase;
        }
        .emergency-pill i { animation: blink 1.4s ease-in-out infinite; }
        @keyframes blink { 50% { opacity: .35; } }
        .bd-hero-body { padding-bottom: 6rem; }

        /* Filter */
        .filter-panel { margin-top: -3rem; position: relative; z-index: 4; padding: 1.3rem; }

        /* Request cards */
        .request-card {
            position: relative; height: 100%; display: flex; flex-direction: column; overflow: hidden;
            background: #fff; border: 1px solid var(--line); border-radius: 18px;
            box-shadow: 0 1px 2px rgba(60, 0, 0, .04), 0 18px 36px -24px rgba(110, 0, 0, .3);
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .request-card::before { content: ""; position: absolute; left: 0; right: 0; top: 0; height: 3px; }
        .request-card.is-open::before { background: linear-gradient(90deg, var(--red-3), var(--red), #ff3b3b); }
        .request-card.is-fulfilled::before { background: var(--green); }
        .request-card.is-fulfilled { opacity: .88; background: #fdfcfc; }
        .request-card:hover { transform: translateY(-4px); box-shadow: 0 2px 4px rgba(60, 0, 0, .05), 0 30px 50px -26px rgba(110, 0, 0, .45); }
        .request-card h3 { font-weight: 800; text-transform: uppercase; letter-spacing: .02em; font-size: 1rem !important; }
        .request-card .details strong { font-size: .6rem; letter-spacing: .14em; text-transform: uppercase; color: var(--muted) !important; font-weight: 700; margin-right: .25rem; }
        .request-card .details > div { font-size: .82rem; color: var(--ink) !important; }
        .request-card .note { background: var(--rose) !important; color: var(--red-2) !important; border-left: 2px solid var(--red); border-radius: 0 10px 10px 0 !important; }

        .urgency-badge {
            display: inline-flex; align-items: center; font-size: .6rem; font-weight: 700; letter-spacing: .1em;
            padding: .3rem .75rem; border-radius: 999px; text-transform: uppercase;
        }
        .urgency-immediate { background: var(--red); color: #fff; }
        .urgency-24h { background: #fff4d6; color: #8a6100; border: 1px solid #fde68a; }
        .urgency-routine { background: #eef4ff; color: #1d4ed8; border: 1px solid #dbe6ff; }
        .units-badge { font-size: .6rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; border-radius: 999px; padding: .3rem .75rem; background: #fff; border: 1px solid var(--line); color: var(--ink); }

        .fulfilled-banner {
            margin-top: auto; padding: .7rem; text-align: center; border-radius: 999px;
            background: #e9f7ef; color: #11693f; font-weight: 700; font-size: .64rem; letter-spacing: .12em; text-transform: uppercase;
        }
        .manage-panel { border-top: 1px dashed var(--rose-line); padding-top: .9rem; margin-top: .9rem; }
        .manage-panel .btn { font-size: .62rem; letter-spacing: .12em; text-transform: uppercase; min-height: 40px; }

        .empty-feed { padding: 3.5rem 1.5rem; text-align: center; }
        .empty-feed .ring {
            width: 72px; height: 72px; border-radius: 50%; margin-bottom: 1rem;
            display: inline-flex; align-items: center; justify-content: center;
            border: 1px solid var(--green); color: var(--green); font-size: 1.7rem;
        }
        .empty-feed h3 { font-weight: 800; text-transform: uppercase; letter-spacing: .03em; }

        @media (max-width: 767.98px) { .request-card:hover { transform: none; } }
    </style>
</head>
<body>

<!-- Hero + navbar -->
<header class="bd-hero">
    <div class="bd-watermark" aria-hidden="true">URGENT</div>
    <div class="rbc-field" aria-hidden="true">
        <span class="rbc" style="--x:62%;--y:12%;--s:clamp(70px,8vw,120px);--rx:60deg;--ry:-18deg;--rz:20deg;--blur:1.5px;--dur:11s;"><i></i></span>
        <span class="rbc" style="--x:78%;--y:46%;--s:clamp(90px,11vw,170px);--rx:64deg;--ry:10deg;--rz:-12deg;--dur:12s;--delay:-4s;--fx:-12px;--fy:-16px;"><i style="--spin:38s"></i></span>
        <span class="rbc" style="--x:88%;--y:6%;--s:clamp(40px,4vw,60px);--rx:48deg;--ry:22deg;--rz:-30deg;--blur:3px;--dur:9s;"><i></i></span>
        <span class="rbc" style="--x:68%;--y:72%;--s:clamp(50px,5vw,80px);--rx:52deg;--ry:-25deg;--rz:40deg;--blur:1px;--dur:13s;--delay:-2s;"><i></i></span>
        <span class="rbc" style="--x:6%;--y:70%;--s:clamp(36px,3.5vw,54px);--rx:55deg;--ry:-20deg;--rz:30deg;--blur:4px;--dur:10s;--delay:-6s;"><i></i></span>
    </div>

    <div class="container">
        <nav class="bd-nav">
            <a href="{{ route('donors.public.search') }}" class="bd-brand" aria-label="Blood Donor home">
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
                <li><a href="{{ route('donors.public.search') }}">Find a donor</a></li>
                <li><a href="{{ route('blood-exchanges.index') }}"><i class="fa-solid fa-right-left me-1"></i> Exchange blood</a></li>
                <li><a href="{{ route('donor-contacts.my-requests') }}"><i class="fa-solid fa-clock-rotate-left me-1"></i> My requests</a></li>
                <li><a href="{{ route('blood-requests.index') }}" class="is-active">Urgent feed</a></li>
            </ul>
            <div class="bd-actions">
                <a href="{{ route('blood-requests.create') }}" class="bd-cta is-emergency">
                    <i class="fa-solid fa-bell"></i> <span class="lbl">Post urgent blood need</span>
                </a>
            </div>
        </nav>

        <div class="bd-hero-body">
            <div class="emergency-pill">
                <i class="fa-solid fa-circle-exclamation"></i> Emergency blood dispatch feed
            </div>
            <h1 class="bd-title">Urgent blood<br>requirements</h1>
            <p class="bd-lead">Real-time emergency blood postings. When you submit a request, it goes LIVE immediately and alerts registered donors via email.</p>
        </div>
    </div>
</header>

<!-- Main Feed -->
<main class="container pb-5">

    <!-- Flash message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3 mb-0 shadow-sm position-relative" style="z-index:5;" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Bar -->
    <div class="filter-panel bd-panel mb-5">
        <form method="GET" action="{{ route('blood-requests.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="blood_group" class="form-select">
                    <option value="">All Blood Groups</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>Blood Group: {{ $bg }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="city" class="form-control" placeholder="Filter by City (e.g. Lahore, Karachi)" value="{{ request('city') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Only Open</option>
                    <option value="fulfilled" {{ request('status') == 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-bd w-100">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Requests Grid -->
    @if($requests->count() > 0)
        <div class="row g-4">
            @foreach($requests as $req)
                <div class="col-md-6 col-lg-4">
                    <div class="request-card p-4 {{ $req->is_open ? 'is-open' : 'is-fulfilled' }}" data-req-id="{{ $req->id }}">

                        <!-- Card Header -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h3 class="h5 mb-1 text-dark">{{ $req->patient_name }}</h3>
                                <div class="text-muted small fw-semibold">
                                    <i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $req->city }}
                                </div>
                            </div>
                            <div class="bd-drop {{ $req->is_open ? '' : 'is-green' }}">
                                <span>{{ $req->blood_group }}</span>
                            </div>
                        </div>

                        <!-- Urgency & Units -->
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                            @if($req->is_open)
                                @if(Str::contains(strtolower($req->urgency_level), ['immediate', 'emergency', 'critical']))
                                    <span class="urgency-badge urgency-immediate">
                                        <i class="fa-solid fa-bolt me-1"></i> {{ $req->urgency_level }}
                                    </span>
                                @elseif(Str::contains(strtolower($req->urgency_level), '24'))
                                    <span class="urgency-badge urgency-24h">
                                        <i class="fa-solid fa-clock me-1"></i> {{ $req->urgency_level }}
                                    </span>
                                @else
                                    <span class="urgency-badge urgency-routine">
                                        {{ $req->urgency_level }}
                                    </span>
                                @endif
                                <span class="units-badge">
                                    {{ $req->units_needed }} {{ Str::plural('Unit', $req->units_needed) }} Needed
                                </span>
                            @else
                                <span class="urgency-badge bg-success-subtle text-success border border-success-subtle">
                                    <i class="fa-solid fa-check-circle me-1"></i> Blood Fulfilled
                                </span>
                            @endif
                        </div>

                        <!-- Details -->
                        <div class="details small mb-3">
                            <div class="mb-1 text-secondary">
                                <strong class="text-dark">Hospital:</strong> {{ $req->hospital }}
                            </div>
                            <div class="mb-1 text-secondary">
                                <strong class="text-dark">Attendant:</strong> {{ $req->contact_name }}
                            </div>
                            @if($req->notes)
                                <div class="note p-2 bg-light rounded-3 text-muted small fst-italic mt-2">
                                    "{{ $req->notes }}"
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        @if($req->is_open)
                            <div class="row g-2 mt-auto">
                                <div class="col-6">
                                    <a href="tel:{{ $req->contact_phone }}" class="btn-call d-flex w-100">
                                        <i class="fa-solid fa-phone"></i> Call
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="https://wa.me/{{ $req->whatsapp_number }}?text={{ urlencode('Assalam-o-Alaikum, I saw your urgent blood request for ' . $req->blood_group . ' at ' . $req->hospital . ', ' . $req->city . '. I want to help.') }}" target="_blank" class="btn-wa d-flex w-100">
                                        <i class="fa-brands fa-whatsapp"></i> WhatsApp
                                    </a>
                                </div>
                            </div>

                            <!-- Requester Mark as Fulfilled Button -->
                            <div class="manage-panel requester-fulfill-box d-none">
                                <form method="POST" action="{{ route('blood-requests.fulfill', $req->id) }}" onsubmit="return confirm('Has the patient received the required blood? This will close the urgent request.')">
                                    @csrf
                                    <input type="hidden" name="manage_token" class="input-manage-token">
                                    <button type="submit" class="btn btn-outline-success btn-sm w-100 rounded-pill fw-bold">
                                        <i class="fa-solid fa-circle-check me-1"></i> I Got the Blood (Mark Fulfilled)
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="fulfilled-banner">
                                <i class="fa-solid fa-heart me-1 text-danger"></i> Blood Arranged on {{ $req->fulfilled_at ? $req->fulfilled_at->format('M d, Y') : 'Recently' }}
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $requests->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="empty-feed bd-panel">
            <div class="ring">
                <i class="fa-solid fa-heart-circle-check"></i>
            </div>
            <h3 class="h5">No Open Blood Requests Right Now</h3>
            <p class="text-muted small">There are currently no active urgent blood requests matching your filter.</p>
            <a href="{{ route('blood-requests.create') }}" class="btn btn-bd mt-2">
                <i class="fa-solid fa-plus"></i> Post an Emergency Request
            </a>
        </div>
    @endif

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Check local token if this browser posted any request
document.addEventListener('DOMContentLoaded', () => {
    @if(session('new_request_id') && session('new_manage_token'))
        localStorage.setItem('my_req_token_{{ session('new_request_id') }}', '{{ session('new_manage_token') }}');
    @endif

    document.querySelectorAll('.request-card').forEach(card => {
        const reqId = card.dataset.reqId;
        const storedToken = localStorage.getItem('my_req_token_' + reqId);
        if (storedToken) {
            const fulfillBox = card.querySelector('.requester-fulfill-box');
            if (fulfillBox) {
                fulfillBox.classList.remove('d-none');
                fulfillBox.querySelector('.input-manage-token').value = storedToken;
            }
        }
    });
});
</script>

</body>
</html>
xa