<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opening your donor card</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;800;900&display=swap" rel="stylesheet">
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
        .claim-stage { min-height: 100vh; min-height: 100svh; display: flex; align-items: center; justify-content: center; }
        .claim-box { position: relative; z-index: 3; text-align: center; padding: 2rem 1.25rem; max-width: 460px; }
        .claim-drop {
            width: 74px; height: 74px; margin: 0 auto 1.4rem; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid rgba(255, 255, 255, .8); font-size: 1.6rem;
            animation: claimPulse 1.8s ease-out infinite;
        }
        @keyframes claimPulse { 0% { box-shadow: 0 0 0 0 rgba(255, 255, 255, .5); } 100% { box-shadow: 0 0 0 22px rgba(255, 255, 255, 0); } }
        .claim-box h5 { font-weight: 800; text-transform: uppercase; letter-spacing: .03em; font-size: clamp(1.4rem, 5vw, 2rem); }
        .claim-box p { font-size: .78rem; letter-spacing: .04em; color: rgba(255, 255, 255, .85) !important; }
        .claim-box .btn-open {
            display: inline-flex; align-items: center; gap: .5rem; border-radius: 999px; padding: .85rem 1.6rem;
            background: #fff; color: var(--red); font-weight: 700; font-size: .66rem; letter-spacing: .16em; text-transform: uppercase;
            border: 1px solid #fff; transition: background .2s, color .2s;
        }
        .claim-box .btn-open:hover { background: transparent; color: #fff; }
    </style>
</head>
<body class="bd-hero claim-stage">
    <div class="bd-watermark" aria-hidden="true">DONOR</div>
    <div class="rbc-field" aria-hidden="true">
        <span class="rbc" style="--x:62%;--y:12%;--s:clamp(70px,8vw,120px);--rx:60deg;--ry:-18deg;--rz:20deg;--blur:1.5px;--dur:11s;"><i></i></span>
        <span class="rbc" style="--x:78%;--y:46%;--s:clamp(90px,11vw,170px);--rx:64deg;--ry:10deg;--rz:-12deg;--dur:12s;--delay:-4s;--fx:-12px;--fy:-16px;"><i style="--spin:38s"></i></span>
        <span class="rbc" style="--x:88%;--y:6%;--s:clamp(40px,4vw,60px);--rx:48deg;--ry:22deg;--rz:-30deg;--blur:3px;--dur:9s;"><i></i></span>
        <span class="rbc" style="--x:68%;--y:72%;--s:clamp(50px,5vw,80px);--rx:52deg;--ry:-25deg;--rz:40deg;--blur:1px;--dur:13s;--delay:-2s;"><i></i></span>
        <span class="rbc" style="--x:6%;--y:70%;--s:clamp(36px,3.5vw,54px);--rx:55deg;--ry:-20deg;--rz:30deg;--blur:4px;--dur:10s;--delay:-6s;"><i></i></span>
    </div>

    <div class="claim-box">
        <div class="claim-drop"><i class="fa-solid fa-droplet"></i></div>
        <h5 class="mb-2">Hello {{ $donor->name }}</h5>
        <p class="text-muted mb-4">Linking your donor card on this device...</p>
        <a id="go" href="{{ route('donors.public.search', ['blood_group' => $donor->blood_group, 'city' => $donor->city]) }}" class="btn btn-open">Open my card <i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <script>
        try {
            localStorage.setItem('my_donor_token', @json($token));
            localStorage.setItem('my_donor_id', @json((string) $donor->id));
        } catch (e) {}
        window.location.replace(document.getElementById('go').href);
    </script>
</body>
</html>
