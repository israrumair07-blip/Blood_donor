<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Blood Donor System')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* =========================================================
           BE.DONOR — GLOBAL TOKENS
           ========================================================= */
        :root {
            --red: #b30000;
            --red-dark: #990000;
            --red-deep: #6e0000;
            --rose: #fff1f1;
            --rose-line: #f5d0d0;
            --ink: #1d1012;
            --muted: #76666a;
            --line: #eee3e4;
            --bg: #faf6f6;
            --green: #17935a;
            --font: 'Montserrat', system-ui, -apple-system, 'Segoe UI', sans-serif;
        }

        html { scroll-behavior: smooth; }
        body {
            background: var(--bg);
            color: var(--ink);
            font-family: var(--font);
            -webkit-font-smoothing: antialiased;
        }

        /* Visible keyboard focus everywhere */
        a:focus-visible, button:focus-visible, .btn:focus-visible {
            outline: 2px solid var(--red);
            outline-offset: 3px;
        }

        .skip-link {
            position: absolute; left: -999px; top: .5rem; z-index: 2000;
            background: #fff; color: var(--red); padding: .5rem 1rem; border-radius: 999px; font-weight: 700;
        }
        .skip-link:focus { left: .5rem; }

        .toast-popup {
            position: fixed; top: 1rem; right: 1rem; z-index: 2000;
            display: none; align-items: center; gap: .75rem;
            width: min(420px, calc(100vw - 2rem)); padding: .95rem 1.15rem;
            border-radius: 14px; box-shadow: 0 16px 36px -10px rgba(0, 0, 0, .35);
            color: #fff; font-size: .84rem; font-weight: 600;
        }
        .toast-success { background: #15803d; border-left: 4px solid #4ade80; }
        .toast-info { background: #1d4ed8; border-left: 4px solid #60a5fa; }
        .toast-error { background: #b91c1c; border-left: 4px solid #f87171; }

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

        /* =========================================================
           APP SHELL
           ========================================================= */
        .app-shell { display: flex; min-height: 100vh; }
        .app-main { flex: 1; min-width: 0; padding: 1.25rem 1rem 2.5rem; }
        .app-main-inner { max-width: 1180px; margin: 0 auto; }

        /* Sidebar: deep red stage with watermark */
        .sidebar {
            position: relative;
            overflow: hidden;
            isolation: isolate;
            color: #fff;
            flex-shrink: 0;
            width: 100%;
            background:
                radial-gradient(ellipse 120% 60% at 30% 20%, #c80000 0%, rgba(200, 0, 0, 0) 70%),
                linear-gradient(180deg, var(--red) 0%, var(--red-dark) 55%, var(--red-deep) 100%);
        }
        .sidebar::before {
            content: "BLOOD";
            position: absolute; z-index: -1; pointer-events: none;
            left: 50%; bottom: 12%;
            transform: translateX(-50%) rotate(-90deg);
            transform-origin: center;
            font-weight: 900; font-size: 9rem; line-height: 1; letter-spacing: -.02em;
            color: rgba(255, 255, 255, .05);
            white-space: nowrap;
        }
        .sidebar-top {
            display: flex; align-items: center; justify-content: space-between;
            padding: .85rem 1rem;
        }
        .brand { display: flex; align-items: center; gap: .65rem; color: #fff; text-decoration: none; }
        .brand:hover { color: #fff; }
        .brand-mark { display: inline-flex; width: 30px; height: 36px; color: #fff; }
        .brand-mark svg { width: 100%; height: 100%; }
        .brand-name { font-weight: 800; letter-spacing: .16em; font-size: .78rem; line-height: 1.3; text-transform: uppercase; }
        .brand-sub { display: block; font-size: .56rem; font-weight: 400; color: rgba(255, 255, 255, .7); letter-spacing: .22em; }

        .nav-toggle {
            background: transparent; border: 1px solid rgba(255, 255, 255, .55); color: #fff;
            width: 40px; height: 40px; border-radius: 50%;
        }

        .side-nav { padding: .25rem .9rem 1rem; }
        .side-nav .nav-label {
            display: flex; align-items: center; gap: .6rem;
            font-size: .56rem; font-weight: 600; letter-spacing: .24em; text-transform: uppercase;
            color: rgba(255, 255, 255, .55); padding: 1.1rem .75rem .5rem;
        }
        .side-nav .nav-label::after { content: ""; flex: 1; height: 1px; background: rgba(255, 255, 255, .15); }
        .side-nav a.nav-item {
            position: relative;
            display: flex; align-items: center; gap: .8rem;
            color: rgba(255, 255, 255, .78); text-decoration: none;
            padding: .75rem .95rem; border-radius: 999px; margin-bottom: .2rem;
            font-weight: 500; font-size: .64rem; letter-spacing: .16em; text-transform: uppercase;
            transition: background .2s, color .2s;
        }
        .side-nav a.nav-item i { width: 18px; text-align: center; font-size: .85rem; }
        .side-nav a.nav-item:hover { color: #fff; background: rgba(255, 255, 255, .1); }
        .side-nav a.nav-item.active { color: var(--red); background: #fff; font-weight: 700; box-shadow: 0 12px 24px -14px rgba(40, 0, 0, .7); }
        .nav-badge {
            margin-left: auto; min-width: 24px; height: 22px; padding: 0 .5rem;
            display: inline-flex; align-items: center; justify-content: center;
            background: #fff; color: var(--red); border-radius: 999px;
            font-size: .68rem; font-weight: 800; letter-spacing: 0;
        }
        .side-nav a.nav-item.active .nav-badge { background: var(--red); color: #fff; }

        .side-foot { padding: 1rem .9rem 1.4rem; border-top: 1px solid rgba(255, 255, 255, .15); margin-top: .5rem; }
        .btn-logout {
            width: 100%; display: flex; align-items: center; justify-content: center; gap: .5rem;
            background: transparent; color: #fff; border: 1px solid rgba(255, 255, 255, .6);
            border-radius: 999px; font-weight: 700; font-size: .62rem; letter-spacing: .18em; text-transform: uppercase; padding: .75rem;
            transition: background .2s, color .2s;
        }
        .btn-logout:hover { background: #fff; color: var(--red); }

        /* Desktop: fixed-width sticky sidebar */
        @media (min-width: 768px) {
            .sidebar {
                width: 260px; height: 100vh; position: sticky; top: 0;
                display: flex; flex-direction: column; overflow-y: auto;
            }
            .sidebar-top { padding: 1.8rem 1.4rem 1.2rem; }
            .nav-toggle { display: none; }
            .side-nav { flex: 1; }
            .app-main { padding: 2rem 2.25rem 3rem; }
        }
        @media (max-width: 767.98px) {
            .sidebar { position: sticky; top: 0; z-index: 1030; }
            .sidebar::before { display: none; }
            .side-nav { max-height: 75vh; overflow-y: auto; }
        }

        /* Guest (login / register own the full screen) */
        .guest-main { padding: 0; }

        /* =========================================================
           SHARED DESIGN SYSTEM (used by every page inside .adm)
           ========================================================= */
        .adm { color: var(--ink); }
        .adm .page-title { font-weight: 800; letter-spacing: .01em; text-transform: uppercase; margin: 0; font-size: 1.65rem; }
        .adm .page-sub { color: var(--muted); font-size: .78rem; margin: .35rem 0 0; }

        /* Red page banner (matches the landing page) */
        .adm .page-hero {
            position: relative; overflow: hidden; isolation: isolate;
            color: #fff; border-radius: 22px; padding: 2rem 2rem 1.8rem;
            background:
                radial-gradient(ellipse 60% 90% at 70% 30%, #d40000 0%, rgba(212, 0, 0, 0) 70%),
                linear-gradient(120deg, var(--red-deep) 0%, var(--red-dark) 40%, var(--red) 100%);
            box-shadow: 0 26px 50px -30px rgba(110, 0, 0, .8);
        }
        .adm .page-hero::before {
            content: attr(data-mark);
            position: absolute; z-index: -1; right: -1rem; top: 50%; transform: translateY(-50%);
            font-weight: 900; font-size: clamp(5rem, 13vw, 10rem); line-height: .8; letter-spacing: -.02em;
            color: rgba(255, 255, 255, .06); white-space: nowrap; pointer-events: none;
        }
        .adm .page-hero .page-title { font-size: clamp(1.5rem, 3.4vw, 2.4rem); text-shadow: 0 10px 30px rgba(60, 0, 0, .35); }
        .adm .page-hero .page-sub { color: rgba(255, 255, 255, .85); }
        .adm .page-eyebrow {
            display: inline-flex; align-items: center; gap: .75rem; margin-bottom: .7rem;
            font-size: .58rem; letter-spacing: .24em; text-transform: uppercase; color: rgba(255, 255, 255, .78);
        }
        .adm .page-eyebrow b { font-size: 1.05rem; font-weight: 800; letter-spacing: 0; color: #fff; }
        .adm .page-eyebrow::after { content: ""; width: 50px; height: 1px; background: rgba(255, 255, 255, .45); }
        .adm .page-hero .rbc-field { z-index: -1; }
        .adm .page-hero .btn-brand { background: #fff; color: var(--red); box-shadow: 0 14px 26px -14px rgba(40, 0, 0, .8); }
        .adm .page-hero .btn-brand:hover { background: var(--rose); color: var(--red-dark); }

        /* Buttons */
        .adm .btn { transition: background .15s, color .15s, border-color .15s, box-shadow .15s, transform .1s; }
        .adm .btn:active { transform: translateY(1px); }
        .adm .btn-brand {
            background: var(--red); color: #fff; border: 0; border-radius: 999px;
            font-weight: 700; font-size: .64rem; letter-spacing: .16em; text-transform: uppercase;
            padding: .75rem 1.3rem; box-shadow: 0 12px 22px -12px rgba(179, 0, 0, .75);
        }
        .adm .btn-brand.btn-sm { padding: .6rem 1.05rem; font-size: .6rem; }
        .adm .btn-brand:hover { background: var(--red-dark); color: #fff; }
        .adm .btn-soft {
            background: #fff; color: var(--ink); border: 1px solid var(--line); border-radius: 999px;
            font-weight: 700; font-size: .64rem; letter-spacing: .14em; text-transform: uppercase; padding: .72rem 1.2rem;
        }
        .adm .btn-soft.btn-sm { padding: .55rem .95rem; font-size: .6rem; }
        .adm .btn-soft:hover { border-color: var(--red); color: var(--red); background: #fff; }

        /* Panels */
        .adm .panel {
            background: #fff; border: 1px solid var(--line); border-radius: 20px; overflow: hidden;
            box-shadow: 0 1px 2px rgba(60, 0, 0, .04), 0 22px 44px -30px rgba(110, 0, 0, .3);
        }
        .adm .panel-head { padding: 1.15rem 1.4rem; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; }
        .adm .panel-head h6 { margin: 0; font-weight: 800; font-size: .74rem; letter-spacing: .16em; text-transform: uppercase; }

        /* Tables */
        .adm table { margin: 0; }
        .adm table thead th {
            font-size: .58rem; color: var(--muted); background: #fcf9f9; border-bottom: 1px solid var(--line);
            font-weight: 700; letter-spacing: .18em; text-transform: uppercase; padding: .95rem 1.25rem; white-space: nowrap;
        }
        .adm table tbody td { padding: .95rem 1.25rem; border-color: var(--line); vertical-align: middle; font-size: .84rem; }
        .adm table tbody tr { transition: background .12s; }
        .adm table tbody tr:hover { background: #fffafa; }
        .adm table tbody tr:last-child td { border-bottom: 0; }
        /* Pills & tags */
        .adm .group-pill {
            display: inline-flex; align-items: center; justify-content: center; min-width: 46px;
            background: var(--red); color: #fff; font-weight: 800; font-size: .76rem;
            padding: .3rem .7rem; border-radius: 999px; box-shadow: 0 8px 14px -8px rgba(179, 0, 0, .7);
        }
        .adm .tag { display: inline-flex; align-items: center; gap: .45rem; font-size: .62rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; padding: .35rem .8rem; border-radius: 999px; white-space: nowrap; }
        .adm .tag::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
        .adm .tag-ok { background: #e9f7ef; color: #146c43; }
        .adm .tag-off { background: #f1eded; color: #6a5e60; }
        .adm .tag-wait { background: #fff4d6; color: #8a6100; }
        .adm .tag-cool { background: #eef4ff; color: #1d4ed8; }

        /* Donor cell & Avatar (Shared across all admin tables) */
        .adm .donor-cell { display: flex; align-items: center; gap: .75rem; min-width: 0; }
        .adm .avatar {
            width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--rose); color: var(--red); border: 1px solid var(--rose-line); font-weight: 800; font-size: .8rem;
        }
        .adm .donor-name { font-weight: 700; line-height: 1.2; font-size: .86rem; color: var(--ink); white-space: nowrap; }
        .adm .phone-link { color: var(--ink); text-decoration: none; font-weight: 500; font-variant-numeric: tabular-nums; }
        .adm .phone-link:hover { color: var(--red); text-decoration: underline; }
        .adm .urgency-badge { font-size: .62rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; border-radius: 999px !important; padding: .35rem .7rem; white-space: nowrap; }

        /* Row action buttons (round, like the landing arrows) */
        .adm .icon-btn {
            width: 36px; height: 36px; border-radius: 50%; border: 1px solid var(--line); background: #fff; color: var(--muted);
            display: inline-flex; align-items: center; justify-content: center; transition: .15s; font-size: .8rem;
        }
        .adm .icon-btn:hover { border-color: var(--red); color: var(--red); background: var(--rose); }
        .adm .icon-btn.edit:hover { border-color: #2563eb; color: #2563eb; background: #eef4ff; }
        .adm .btn-donated, .adm .btn-confirm, .adm .btn-reject {
            display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
            border-radius: 999px; font-weight: 700; font-size: .6rem; letter-spacing: .12em; text-transform: uppercase; padding: .5rem .9rem;
        }
        .adm .btn-donated { background: var(--rose); color: var(--red); border: 1px solid var(--rose-line); }
        .adm .btn-donated:hover { background: var(--red); color: #fff; border-color: var(--red); }
        .adm .btn-confirm { background: var(--green); color: #fff; border: 1px solid var(--green); }
        .adm .btn-confirm:hover { background: #12784a; border-color: #12784a; color: #fff; }
        .adm .btn-reject { background: #fff; color: var(--muted); border: 1px solid var(--line); }
        .adm .btn-reject:hover { border-color: var(--red); color: var(--red); background: var(--rose); }

        /* Forms */
        .adm .form-label { font-size: .58rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: var(--muted); margin-bottom: .45rem; }
        .adm .form-control, .adm .form-select {
            height: 48px; border-radius: 12px; border: 1px solid var(--line); background-color: #fcfafa;
            font-weight: 500; font-size: .86rem; transition: border-color .15s, box-shadow .15s, background-color .15s;
        }
        .adm textarea.form-control { height: auto; }
        .adm .form-control:focus, .adm .form-select:focus { border-color: var(--red); background-color: #fff; box-shadow: 0 0 0 .22rem rgba(179, 0, 0, .12); }
        .adm .form-check-input:checked { background-color: var(--red); border-color: var(--red); }
        .adm .form-check-input:focus { border-color: var(--red); box-shadow: 0 0 0 .22rem rgba(179, 0, 0, .12); }
        .adm .form-check-label { font-size: .84rem; }

        /* Alerts */
        .adm .alert { border: 0; border-radius: 14px; font-weight: 600; font-size: .84rem; padding: .95rem 1.15rem; }
        .adm .alert-success { background: #e9f7ef; color: #146c43; border-left: 3px solid var(--green); }
        .adm .alert-info { background: #eef4ff; color: #1d4ed8; border-left: 3px solid #1d4ed8; }
        .adm .alert-danger { background: var(--rose); color: var(--red-dark); border-left: 3px solid var(--red); }
        .adm .alert-warning { background: #fff4d6; color: #8a6100; border-left: 3px solid #d4a017; }

        /* Modals */
        .adm .modal-content { border: 0; border-radius: 22px; overflow: hidden; box-shadow: 0 30px 70px rgba(60, 0, 0, .35); }
        .adm .modal-header { position: relative; overflow: hidden; isolation: isolate; }
        .adm .modal-header.brand { background: linear-gradient(120deg, var(--red-deep), var(--red)); color: #fff; border: 0; }
        .adm .modal-header.edit { background: linear-gradient(120deg, #1e3a8a, #1d4ed8); color: #fff; border: 0; }
        .adm .modal-header .modal-title { font-weight: 800 !important; font-size: .82rem !important; letter-spacing: .14em; text-transform: uppercase; }
        .adm .modal-footer { border-top: 1px solid var(--line); }

        /* Pagination: round outline buttons */
        .adm .pager nav { display: flex; flex-direction: column; align-items: center; gap: .6rem; }
        .adm .pager nav > div:first-child { display: none; }
        .adm .pager nav > div:last-child { display: flex; flex-direction: column; align-items: center; gap: .6rem; }
        .adm .pager p.small { margin: 0; color: var(--muted); font-weight: 600; font-size: .72rem !important; letter-spacing: .05em; }
        .adm .pagination { gap: .4rem; margin: 0; flex-wrap: wrap; justify-content: center; }
        .adm .pagination .page-link {
            min-width: 40px; height: 40px; padding: 0 .55rem; display: inline-flex; align-items: center; justify-content: center;
            border: 1px solid var(--rose-line); border-radius: 999px !important; color: var(--ink); font-weight: 700; font-size: .78rem; background: #fff;
        }
        .adm .pagination .page-link:hover { background: var(--rose); border-color: var(--red); color: var(--red); }
        .adm .pagination .page-item.active .page-link { background: var(--red); border-color: var(--red); color: #fff; }
        .adm .pagination .page-item.disabled .page-link { background: var(--bg); color: #c3b6b8; }

        /* Empty states */
        .adm .empty-row { text-align: center; color: var(--muted); padding: 2.75rem 1rem !important; font-weight: 600; font-size: .82rem !important; }

        @media (max-width: 767.98px) {
            .adm .page-hero { padding: 1.5rem 1.25rem; border-radius: 18px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { transition: none !important; animation: none !important; scroll-behavior: auto !important; }
        }
    </style>
</head>
<body>

<a href="#main-content" class="skip-link">Skip to content</a>

@auth
@php
    $pendingCount         = \App\Models\Donor::where('donation_pending', true)->count();
    $pendingContactCount  = class_exists(\App\Models\DonorContactRequest::class) ? \App\Models\DonorContactRequest::where('status', 'pending')->count() : 0;
    $pendingExchangeCount = class_exists(\App\Models\BloodExchange::class) ? \App\Models\BloodExchange::where('status', 'pending')->count() : 0;
@endphp
<div id="ajaxToast" class="toast-popup toast-success" role="alert" aria-live="polite">
    <i class="fa-solid fa-circle-check fs-5" id="toastIcon"></i>
    <div id="toastText" class="flex-grow-1">Action completed successfully.</div>
    <button type="button" class="btn-close btn-close-white ms-2" onclick="window.hideToast && window.hideToast()"></button>
</div>
<div class="app-shell flex-column flex-md-row">

    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-top">
            <a href="{{ route('dashboard') }}" class="brand">
                <span class="brand-mark">
                    <svg viewBox="0 0 34 40" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M17 2C17 2 5 15.5 5 24.5C5 31.4 10.4 37 17 37C23.6 37 29 31.4 29 24.5C29 15.5 17 2 17 2Z"/>
                <path d="M10 26.5C10 29.8 12.6 32.5 16 32.8"/>
                <rect x="17.5" y="21" width="12" height="12" rx="1" fill="var(--logo-fill, #b30000)"/>
                <path d="M23.5 23.5V30.5M20 27H27"/>
            </svg>
                </span>
                <span class="brand-name">BE.DONOR<small class="brand-sub">Admin panel</small></span>
            </a>
            <button class="nav-toggle d-md-none" type="button"
                    data-bs-toggle="collapse" data-bs-target="#sideMenu"
                    aria-controls="sideMenu" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <div class="collapse d-md-flex flex-md-column flex-md-grow-1" id="sideMenu">
            <nav class="side-nav" aria-label="Main navigation">
                <div class="nav-label">Management</div>
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge"></i> <span>Dashboard</span>
                    @if($pendingCount > 0)
                        <span class="nav-badge" title="Pending donation requests">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('donors.index') }}" class="nav-item {{ request()->routeIs('donors.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-hand-holding-heart"></i> <span>Donors List</span>
                </a>
                <a href="{{ route('admin.urgent-requests.index') }}" class="nav-item {{ request()->routeIs('admin.urgent-requests.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-bullhorn"></i> <span>Urgent Requests</span>
                </a>
                <a href="{{ route('admin.contact-requests.index') }}" class="nav-item {{ request()->routeIs('admin.contact-requests.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-envelope-open-text"></i> <span>Contact Requests</span>
                    @if($pendingContactCount > 0)
                        <span class="nav-badge" title="Pending contact requests">{{ $pendingContactCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.blood-exchanges.index') }}" class="nav-item {{ request()->routeIs('admin.blood-exchanges.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-right-left"></i> <span>Blood Exchange</span>
                    @if($pendingExchangeCount > 0)
                        <span class="nav-badge" title="Pending blood exchange requests">{{ $pendingExchangeCount }}</span>
                    @endif
                </a>

                <div class="nav-label">Public Portals</div>
                <a href="{{ route('donors.public.search') }}" target="_blank" class="nav-item">
                    <i class="fa-solid fa-magnifying-glass"></i> <span>Find Donors</span>
                </a>
                <a href="{{ route('blood-exchanges.index') }}" target="_blank" class="nav-item">
                    <i class="fa-solid fa-right-left"></i> <span>Exchange Board</span>
                </a>
                <a href="{{ route('donor-contacts.my-requests') }}" target="_blank" class="nav-item">
                    <i class="fa-solid fa-clock-rotate-left"></i> <span>My Requests</span>
                </a>
                <a href="{{ route('blood-requests.index') }}" target="_blank" class="nav-item">
                    <i class="fa-solid fa-bullhorn"></i> <span>Urgent Requests</span>
                </a>
            </nav>

            <div class="side-foot">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area (pages show their own flash messages) -->
    <main class="app-main" id="main-content">
        <div class="app-main-inner">
            @yield('content')
        </div>
    </main>
</div>
@else
    <!-- Guest Layout Container -->
    <main class="guest-main" id="main-content">
        @yield('content')
    </main>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.showToast = function(message, type = 'success') {
        const toast = document.getElementById('ajaxToast');
        const text = document.getElementById('toastText');
        const icon = document.getElementById('toastIcon');
        if (!toast || !text || !icon) return;

        text.textContent = message || 'Action completed successfully.';
        toast.className = 'toast-popup toast-' + (type || 'success');
        icon.className = 'fa-solid fs-5 ' + (
            type === 'error' ? 'fa-circle-xmark' :
            type === 'info' ? 'fa-circle-info' :
            'fa-circle-check'
        );
        toast.style.display = 'flex';
        clearTimeout(window.toastTimer);
        window.toastTimer = setTimeout(window.hideToast, 3200);
    };

    window.hideToast = function() {
        const toast = document.getElementById('ajaxToast');
        if (toast) toast.style.display = 'none';
    };

    window.submitAjaxForm = async function(form, customOptions = {}) {
        if (!form) return false;

        const confirmText = form.getAttribute('data-confirm');
        if (confirmText && !window.confirm(confirmText)) {
            return false;
        }

        const btn = form.querySelector('button[type="submit"], button:not([type="button"])');
        const originalText = btn ? btn.innerHTML : '';
        const successMessage = form.getAttribute('data-success-message') || 'Action completed successfully.';

        if (btn) {
            btn.disabled = true;
            btn.dataset.pendingText = originalText;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-1"></i>';
        }

        try {
            const res = await fetch(form.action, {
                method: form.method || 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(form)
            });

            let data = {};
            try {
                data = await res.json();
            } catch (error) {
                throw new Error('The server returned an invalid response. Please try again.');
            }

            if (!res.ok || data.success === false) {
                throw new Error(data.message || 'Action failed.');
            }

            if (form.getAttribute('data-remove-row') === 'true') {
                const row = form.closest('tr');
                if (row) row.remove();
            }

            if (form.getAttribute('data-close-modal') === 'true') {
                const modal = form.closest('.modal');
                if (modal && window.bootstrap) {
                    const bsModal = window.bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                    bsModal.hide();
                }
            }

            if (form.getAttribute('data-reset-form') === 'true') {
                form.reset();
            }

            const toastType = data.type || 'success';
            window.showToast(data.message || successMessage, toastType);

            if (typeof customOptions.onSuccess === 'function') {
                customOptions.onSuccess(data, form, res);
            }

            return true;
        } catch (error) {
            window.showToast(error.message || 'Something went wrong.', 'error');
            return false;
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = btn.dataset.pendingText || originalText;
            }
        }
    };

    document.addEventListener('submit', function(event) {
        const form = event.target;
        if (form instanceof HTMLFormElement && form.getAttribute('data-ajax-form') === 'true') {
            event.preventDefault();
            window.submitAjaxForm(form);
        }
    }, true);
</script>
@stack('scripts')
</body>
</html>
