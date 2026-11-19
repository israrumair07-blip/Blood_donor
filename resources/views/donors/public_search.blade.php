<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Blood Donors</title>
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
        #requestContactModal .modal-header { padding: .75rem 1rem; }
        #requestContactModal .modal-body { padding: 1rem !important; }
        #requestContactModal .modal-footer { padding: .65rem 1rem; }
        #requestContactModal .form-label { margin-bottom: .25rem; font-size: .56rem; }
        #requestContactModal .form-control,
        #requestContactModal .form-select { height: 42px; padding: .35rem .7rem; border-radius: 9px; font-size: .82rem; }
        #requestContactModal textarea.form-control { height: auto; padding: .5rem .7rem; }
        #requestContactModal .btn-bd { min-height: 42px; padding: .35rem 1rem !important; font-size: .61rem; }
        #requestContactModal .modal-footer .btn-secondary { padding: .35rem .8rem !important; font-size: .76rem; }
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
            #requestContactModal .form-control,
            #requestContactModal .form-select { height: 44px; font-size: 16px; }
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
        /* ---------- Page: public search ---------- */
        .bd-hero-body { padding-bottom: 7.5rem; }
        .bd-urgent {
            display: inline-flex; align-items: center; gap: .6rem; margin-top: 1.8rem;
            color: #fff; text-decoration: none; font-size: .64rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase;
            border: 1px solid rgba(255, 255, 255, .7); border-radius: 999px; padding: .75rem 1.3rem;
            transition: background .2s, color .2s;
        }
        .bd-urgent:hover { background: #fff; color: var(--red); }

        /* Search panel overlaps the hero */
        .search-panel { margin-top: -4.5rem; position: relative; z-index: 4; padding: 1.5rem; }
        .btn-reset { width: 50px; height: 50px; flex: 0 0 50px; }

        .result-count { margin: 2.6rem 0 1.6rem; }

        /* Donor card */
        .donor-card {
            position: relative; height: 100%; overflow: hidden;
            background: #fff; border: 1px solid var(--line); border-radius: 18px;
            box-shadow: 0 1px 2px rgba(60, 0, 0, .04), 0 18px 36px -24px rgba(110, 0, 0, .3);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }
        .donor-card::before {
            content: attr(data-group); position: absolute; right: -.5rem; bottom: -1.4rem;
            font-weight: 900; font-size: 6.5rem; line-height: 1; letter-spacing: -.04em;
            color: rgba(179, 0, 0, .045); pointer-events: none;
        }
        .donor-card:hover { transform: translateY(-4px); border-color: var(--rose-line); box-shadow: 0 2px 4px rgba(60, 0, 0, .05), 0 30px 50px -26px rgba(110, 0, 0, .45); }
        .donor-card .card-body { position: relative; z-index: 1; display: flex; flex-direction: column; height: 100%; padding: 1.5rem 1.4rem 1.35rem; }
        .donor-name { font-weight: 800; font-size: 1.02rem; text-transform: uppercase; letter-spacing: .02em; margin: 0; word-break: break-word; overflow-wrap: anywhere; }
        .donor-city { color: var(--muted); font-size: .72rem; font-weight: 500; letter-spacing: .06em; text-transform: uppercase; }
        .contact-chip {
            display: inline-flex; align-items: center; margin-top: .55rem;
            background: var(--rose); color: var(--red-2); border: 1px solid var(--rose-line);
            font-size: .62rem; font-weight: 700; letter-spacing: .04em; padding: .25rem .7rem; border-radius: 999px;
        }
        .donor-card .d-flex > div:first-child { min-width: 0; padding-right: .75rem; }

        /* ACTIVE badge & status chips */
        .donor-active-badge {
            display: inline-flex; align-items: center; gap: .35rem;
            background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;
            border-radius: 999px; font-size: .6rem; font-weight: 800; letter-spacing: .08em;
            text-transform: uppercase; padding: .2rem .6rem;
        }
        .donor-active-badge .active-dot {
            width: 6px; height: 6px; border-radius: 50%; background: #10b981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, .7); animation: activePulse 2s infinite;
        }
        @keyframes activePulse {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, .7); }
            70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        .donor-status-chip {
            display: inline-flex; align-items: center; font-size: .62rem; font-weight: 700;
            letter-spacing: .04em; color: var(--muted);
        }
        .donor-meta-bar {
            background: #fff8f8; border: 1px solid var(--rose-line); font-size: .72rem;
        }

        /* Owner action panel */
        .donor-action-panel { border-top: 1px dashed var(--rose-line); margin-top: 1rem; padding-top: 1rem; }
        .btn-donated, .btn-pending {
            min-height: 42px; border-radius: 999px; font-weight: 700; font-size: .64rem; letter-spacing: .14em; text-transform: uppercase;
        }
        .btn-donated { background: var(--rose); color: var(--red); border: 1px solid var(--red); transition: all .15s ease; }
        .btn-donated:hover { background: var(--red); color: #fff; }
        .btn-pending { background: #fff4d6; color: #8a6100; border: 0; }
        .alert-toggle-btn { letter-spacing: .04em; }
        .alert-toggle-btn.btn-danger { background: var(--red); border-color: var(--red); }

        /* Pagination wrapper */
        .pager-wrap nav { display: flex; flex-direction: column; align-items: center; gap: .75rem; }
        .pager-wrap nav > div:first-child { display: none; }
        .pager-wrap nav > div:last-child { display: flex; flex-direction: column; align-items: center; gap: .75rem; }
        .pager-wrap p.small { margin: 0; color: var(--muted); font-weight: 600; font-size: .72rem !important; letter-spacing: .06em; }

        /* Empty state */
        .empty { padding: 3.5rem 1rem; text-align: center; color: var(--muted); border-style: dashed; border-color: var(--rose-line); }
        .empty i {
            width: 68px; height: 68px; border-radius: 50%; margin-bottom: 1rem;
            display: inline-flex; align-items: center; justify-content: center;
            border: 1px solid var(--red); color: var(--red); font-size: 1.5rem;
        }
        .empty h5 { font-weight: 800; text-transform: uppercase; letter-spacing: .04em; color: var(--ink); }

        /* Footer */
        .bd-foot { background: var(--red-3); color: rgba(255, 255, 255, .7); }
        .admin-link { color: rgba(255, 255, 255, .55); font-size: .8rem; text-decoration: none; padding: .5rem; display: inline-flex; width: 38px; height: 38px; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, .3); border-radius: 50%; transition: all .2s; }
        .admin-link:hover { color: var(--red); background: #fff; border-color: #fff; }

        @media (max-width: 767.98px) {
            .search-panel { margin-top: -4rem; padding: 1.1rem; }
            .donor-card:hover { transform: none; }
        }
    </style>
</head>
<body>

<header class="bd-hero">
    <div class="bd-watermark" aria-hidden="true">BLOOD</div>
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
                <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'is-active' : '' }}">Home</a></li>
                <li><a href="{{ route('donors.public.search') }}" class="{{ request()->is('find-donor') ? 'is-active' : '' }}">Find a donor</a></li>
                <li><a href="{{ route('blood-exchanges.index') }}"><i class="fa-solid fa-right-left me-1"></i> Exchange blood</a></li>
                <li><a href="{{ route('donor-contacts.my-requests') }}"><i class="fa-solid fa-clock-rotate-left me-1"></i> My requests</a></li>
                <li><a href="{{ route('blood-requests.index') }}"><i class="fa-solid fa-bolt me-1"></i> Urgent blood feed</a></li>
            </ul>
            <div class="bd-actions">
                <a href="{{ route('donors.public.create') }}" class="bd-cta">
                    <i class="fa-solid fa-hand-holding-heart"></i> <span class="lbl">Become a donor</span>
                </a>
            </div>
        </nav>

        <div class="bd-hero-body">
            <div class="bd-eyebrow"><b>01<small>/03</small></b> Donor search</div>
            <h1 class="bd-title">Find a blood<br>donor near you</h1>
            <p class="bd-lead">Search by blood group and city. To protect donor privacy, submit a verified request to contact them through admin mediation.</p>
            <a href="{{ route('blood-requests.create') }}" class="bd-urgent">
                <i class="fa-solid fa-bullhorn"></i> Need blood urgently? Post an emergency request
            </a>
        </div>
    </div>
</header>

<div class="container pb-5">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 mt-3" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 mt-3" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search -->
    <form method="GET" action="{{ route('donors.public.search') }}" class="search-panel bd-panel row g-3 align-items-end mx-0">
        <div class="col-12 col-md-6 col-lg-5">
            <label class="form-label">Blood group</label>
            <select name="blood_group" class="form-select">
                <option value="">All blood groups</option>
                @foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $group)
                    <option value="{{ $group }}" {{ request('blood_group') == $group ? 'selected' : '' }}>{{ $group }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-6 col-lg-5">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" placeholder="e.g. Sargodha, Lahore" value="{{ request('city') }}">
        </div>
        <div class="col-12 col-lg-2 d-flex gap-2">
            <button class="btn btn-bd flex-grow-1"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
            <a href="{{ route('donors.public.search') }}" class="btn-round btn-reset" title="Reset filters" aria-label="Reset filters"><i class="fa-solid fa-rotate-left"></i></a>
        </div>
    </form>

    <div class="result-count bd-section-label">
        <b>{{ $donors->total() }}</b> {{ Str::plural('donor', $donors->total()) }} found
    </div>

    <!-- Donor cards -->
    <div class="row g-4">
        @forelse ($donors as $donor)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="donor-card" data-donor-id="{{ $donor->id }}" data-group="{{ $donor->blood_group }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                    <span class="donor-active-badge">
                                        <span class="active-dot"></span> ACTIVE
                                    </span>
                                </div>
                                <h5 class="donor-name">{{ $donor->name }}</h5>
                                @if($donor->gender === 'Female')
                                    <span class="contact-chip"><i class="fa-solid fa-user-shield me-1"></i>Contact: {{ $donor->display_contact_name }}</span>
                                @endif
                            </div>
                            <div class="bd-drop"><span>{{ $donor->blood_group }}</span></div>
                        </div>

                        <div class="donor-meta-bar d-flex justify-content-between align-items-center my-2 py-2 px-3 rounded-3">
                            <span class="text-muted"><i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $donor->city }}</span>
                            <span class="text-danger fw-semibold"><i class="fa-solid fa-shield-heart me-1"></i>Verified Donor</span>
                        </div>

                        <div class="mt-2">
                            <button type="button"
                                    class="btn btn-bd w-100"
                                    style="min-height: 44px; font-size: 0.68rem;"
                                    onclick="openContactModal({{ $donor->id }}, '{{ addslashes($donor->name) }}', '{{ $donor->blood_group }}', '{{ addslashes($donor->city) }}')">
                                <i class="fa-solid fa-paper-plane me-1"></i> Request to Contact Donor
                            </button>
                        </div>

                        {{-- Owner-only panel: hidden until JS confirms this browser registered this donor --}}
                        <div class="donor-action-panel d-none text-center">
                            @if($donor->donation_pending)
                                <button class="btn btn-sm btn-pending w-100" disabled>
                                    <i class="fa-solid fa-clock me-1"></i> Verification pending
                                </button>
                            @elseif($donor->is_eligible)
                                <button type="button"
                                        class="btn btn-sm btn-donated w-100"
                                        id="donated-btn-{{ $donor->id }}"
                                        onclick="triggerDonatedToday({{ $donor->id }}, this)">
                                    <i class="fa-solid fa-heart-circle-check me-1"></i> I donated today
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty bd-panel">
                    <i class="fa-solid fa-droplet-slash"></i>
                    <h5>No donors found</h5>
                    <p class="mb-0 small">Try a different blood group or city, or reset the filters.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($donors->hasPages())
        <div class="pager-wrap mt-5">
            {{ $donors->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Request to Contact Donor Modal -->
<div class="modal fade" id="requestContactModal" tabindex="-1" aria-labelledby="contactModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form action="{{ route('donor-contacts.store') }}" method="POST" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden"
              id="contactRequestForm"
              data-ajax-form="true"
              data-close-modal="true"
              data-success-message="Your request to contact this donor has been submitted."
              data-reset-form="true">
            @csrf
            <input type="hidden" name="donor_id" id="modalDonorId">
            <div class="modal-header bg-danger text-white py-3 px-4">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="contactModalTitle">
                        <i class="fa-solid fa-hand-holding-heart me-2"></i>Request Donor Contact
                    </h5>
                    <small class="text-white-50">Admin-mediated, privacy-safe contact process</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="p-2 mb-2 rounded-3" style="background: var(--rose); border: 1px solid var(--rose-line);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.1em;">Target Donor</small>
                            <strong class="fs-6 text-dark" id="modalDonorName">—</strong>
                            <div class="text-muted small"><i class="fa-solid fa-location-dot text-danger me-1"></i><span id="modalDonorCity">—</span></div>
                        </div>
                        <span class="badge bg-danger fs-5 px-3 py-2 rounded-pill shadow-sm" id="modalDonorGroup">—</span>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label">Your Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="requester_name" class="form-control" required placeholder="e.g. Ali Khan">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Your Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" name="requester_phone" class="form-control" required placeholder="e.g. 03001234567">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Your Email (Optional, for tracking & reply)</label>
                        <input type="email" name="requester_email" class="form-control" placeholder="e.g. yourname@gmail.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Patient Name <span class="text-danger">*</span></label>
                        <input type="text" name="patient_name" class="form-control" required placeholder="Patient full name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Urgency Level <span class="text-danger">*</span></label>
                        <select name="urgency_level" class="form-select" required>
                            <option value="Urgent (Within 6 hours)">Urgent (Within 6 hours)</option>
                            <option value="Emergency (Immediate)">Emergency (Immediate)</option>
                            <option value="Routine (Within 24-48 hours)">Routine (Within 24-48 hours)</option>
                        </select>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label">Hospital / Clinic <span class="text-danger">*</span></label>
                        <input type="text" name="hospital" class="form-control" required placeholder="e.g. DHQ Hospital, Sargodha">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" id="modalPrefilledCity" class="form-control" required placeholder="e.g. Sargodha">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Reason / Patient Condition Note</label>
                        <textarea name="message" class="form-control" rows="2" placeholder="Briefly describe the emergency need..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-bd px-4">
                    <i class="fa-solid fa-paper-plane me-1"></i> Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<footer class="bd-foot py-4 text-center">
    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="admin-link" title="Admin" aria-label="Admin login">
        <i class="fa-solid fa-lock"></i>
    </a>
</footer>

<div id="ajaxToast" class="toast-popup toast-success" role="alert" aria-live="polite" style="display:none; position:fixed; top:24px; right:24px; z-index:99999; min-width:300px; padding:1rem 1.2rem; border-radius:14px; box-shadow:0 18px 26px -12px rgba(0,0,0,.25); color:#fff; align-items:center; gap:.8rem; font-weight:700;">
    <i class="fa-solid fa-circle-check fs-5" id="toastIcon"></i>
    <div id="toastText" class="flex-grow-1">Action completed successfully.</div>
    <button type="button" class="btn-close btn-close-white ms-2" aria-label="Close" onclick="hideToast()"></button>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showToast(message, type = 'success') {
    const toast = document.getElementById('ajaxToast');
    const text = document.getElementById('toastText');
    const icon = document.getElementById('toastIcon');
    if (!toast || !text || !icon) return;
    text.textContent = message || 'Action completed successfully.';
    toast.style.background = type === 'error' ? '#b91c1c' : (type === 'info' ? '#1d4ed8' : '#15803d');
    toast.style.borderLeft = type === 'error' ? '4px solid #f87171' : (type === 'info' ? '4px solid #60a5fa' : '4px solid #4ade80');
    icon.className = 'fa-solid fs-5 ' + (type === 'error' ? 'fa-circle-xmark' : (type === 'info' ? 'fa-circle-info' : 'fa-circle-check'));
    toast.style.display = 'flex';
    clearTimeout(window.toastTimer);
    window.toastTimer = setTimeout(hideToast, 3200);
}
function hideToast() {
    const toast = document.getElementById('ajaxToast');
    if (toast) toast.style.display = 'none';
}
function openContactModal(donorId, donorName, donorGroup, donorCity) {
    document.getElementById('modalDonorId').value = donorId;
    document.getElementById('modalDonorName').textContent = donorName;
    document.getElementById('modalDonorGroup').textContent = donorGroup;
    document.getElementById('modalDonorCity').textContent = donorCity;
    document.getElementById('modalPrefilledCity').value = donorCity;
    const modal = new bootstrap.Modal(document.getElementById('requestContactModal'));
    modal.show();
}
const csrf    = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const myToken = localStorage.getItem('my_donor_token');
const myId    = localStorage.getItem('my_donor_id');

document.addEventListener('DOMContentLoaded', () => {
    if (!myToken || !myId) return;
    document.querySelectorAll('.donor-card').forEach(card => {
        if (card.dataset.donorId === myId) {
            card.querySelector('.donor-action-panel')?.classList.remove('d-none');
        }
    });
});

const contactRequestForm = document.getElementById('contactRequestForm');
if (contactRequestForm) {
    contactRequestForm.addEventListener('submit', async function (event) {
        event.preventDefault();
        const form = event.currentTarget;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn ? submitBtn.innerHTML : '';

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-1"></i> Sending...';
        }

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(form)
            });

            const data = await response.json().catch(() => ({}));
            if (!response.ok) throw new Error(data.message || 'Request failed.');

            showToast(data.message || 'Your request to contact this donor has been submitted.', 'success');
            const modalEl = document.getElementById('requestContactModal');
            if (modalEl && bootstrap && bootstrap.Modal) {
                const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modal.hide();
            }
            form.reset();
        } catch (error) {
            showToast(error.message || 'Could not submit your request right now.', 'error');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }
    });
}


async function triggerDonatedToday(donorId, button) {
    if (!confirm('Have you really donated blood today? A verification notification will be sent to the admin.')) {
        return;
    }

    // Prevent multiple clicks
    button.disabled = true;

    // Show pending immediately
    button.classList.remove('btn-donated');
    button.classList.add('btn-pending');
    button.innerHTML =
        '<i class="fa-solid fa-clock me-1"></i> Verification pending';

    // Show alert immediately
    alert('Your donation verification request is being submitted.');

    try {
        const res = await fetch(`/donors/${donorId}/notify-donation`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Donor-Token': myToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                donor_token: myToken
            })
        });

        const data = await res.json();

        if (!res.ok) {
            throw new Error(data.message || 'Request failed');
        }

        // Optional: show server confirmation after completion
        console.log(data.message || 'Verification request submitted');

    } catch (error) {
        // Restore button if request fails
        button.disabled = false;
        button.classList.remove('btn-pending');
        button.classList.add('btn-donated');
        button.innerHTML =
            '<i class="fa-solid fa-heart-circle-check me-1"></i> I donated today';

        alert(error.message || 'Could not reach the server. Please try again.');
    }
}

async function toggleDonorAlerts(donorId, button) {
    if (!myToken) {
        alert('You must open your donor card link to manage your settings.');
        return;
    }
    button.disabled = true;
    try {
        const res = await fetch(`/donors/${donorId}/toggle-alerts`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Donor-Token': myToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ donor_token: myToken })
        });
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Failed to toggle alerts');

        if (data.notify_urgent_requests) {
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-danger');
            button.innerHTML = '🔔 Active';
        } else {
            button.classList.remove('btn-danger');
            button.classList.add('btn-outline-secondary');
            button.innerHTML = '🔕 Off';
        }
        alert(data.message);
    } catch (err) {
        alert(err.message || 'Error updating alert settings.');
    } finally {
        button.disabled = false;
    }
}


</script>
</body>
</html>
