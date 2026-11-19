@extends('layouts.app')

@section('title', 'Sign In - Blood Donor System')

@section('content')
<style>
    /* Full-screen red stage, same look as the landing page */
    .auth-viewport {
        position: relative;
        isolation: isolate;
        min-height: 100vh;
        min-height: 100dvh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 5.5rem 1rem 2rem;
        overflow: hidden;
        color: #fff;
        background:
            radial-gradient(ellipse 55% 60% at 50% 45%, #d40000 0%, rgba(212, 0, 0, 0) 70%),
            radial-gradient(ellipse 120% 90% at 50% 50%, #b30000 0%, #990000 60%, #6e0000 100%);
    }
    .auth-viewport::after {
        content: ""; position: absolute; inset: 0; z-index: -1; pointer-events: none;
        background: radial-gradient(ellipse at center, transparent 55%, rgba(40, 0, 0, .45) 100%);
    }
    .auth-mark {
        position: absolute; inset: 0; z-index: -1; display: flex; align-items: center; justify-content: center;
        pointer-events: none; user-select: none; white-space: nowrap;
        font-weight: 900; font-size: clamp(8rem, 28vw, 26rem); line-height: .8; letter-spacing: -.02em;
        color: rgba(255, 255, 255, .05);
    }
    .auth-brand {
        position: absolute; top: 1.8rem; left: clamp(1.25rem, 5vw, 4.5rem);
        display: inline-flex; align-items: center; gap: .55rem; color: #fff; text-decoration: none;
    }
    .auth-brand svg { width: 30px; height: 36px; }
    .auth-brand span { font-size: .72rem; font-weight: 800; letter-spacing: .16em; }

    .auth-card {
        position: relative; z-index: 1;
        width: 100%;
        max-width: 420px;
        color: var(--ink);
        background: #fff;
        border-radius: 22px;
        box-shadow: 0 40px 80px -30px rgba(40, 0, 0, .8), 0 10px 24px -12px rgba(40, 0, 0, .4);
        overflow: hidden;
        animation: authIn .6s cubic-bezier(.2, .7, .2, 1) both;
    }
    @keyframes authIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }

    /* Header */
    .auth-head { text-align: center; padding: 2rem 1.75rem 1.2rem; }
    .auth-drop {
        width: 58px; height: 58px; margin: 0 auto 1rem; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--red); color: var(--red);
    }
    .auth-drop i { font-size: 1.2rem; }
    .auth-eyebrow { display: block; font-size: .56rem; font-weight: 600; letter-spacing: .26em; text-transform: uppercase; color: var(--muted); margin-bottom: .45rem; }
    .auth-head h1 { font-weight: 800 !important; text-transform: uppercase; letter-spacing: .02em; line-height: 1.1; }
    .auth-head p { color: var(--muted) !important; font-size: .74rem; }

    /* Body */
    .auth-body { padding: .4rem 1.9rem 1.6rem; }

    .form-floating > .form-control {
        border-radius: 12px;
        border: 1px solid var(--line);
        background-color: #fcfafa;
        font-weight: 500;
        height: 54px;
        transition: border-color .15s, box-shadow .15s, background-color .15s;
    }
    .form-floating > .form-control:hover { border-color: #e2d0d2; }
    .form-floating > .form-control:focus { border-color: var(--red); background-color: #fff; box-shadow: 0 0 0 .22rem rgba(179, 0, 0, .12); }
    .form-floating > .form-control.is-invalid { border-color: var(--red); background-image: none; }
    .form-floating > label { color: var(--muted); font-size: .64rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; padding-top: 1.1rem; }
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label { color: var(--red); }
    .invalid-feedback { color: var(--red); font-size: .74rem; }

    .form-check-input { width: 1.1rem; height: 1.1rem; margin-top: .15rem; }
    .form-check-input:checked { background-color: var(--red); border-color: var(--red); }
    .form-check-input:focus { box-shadow: 0 0 0 .2rem rgba(179, 0, 0, .18); border-color: var(--red); }

    .btn-auth {
        height: 52px;
        color: #fff;
        border: 0;
        border-radius: 999px;
        background: var(--red);
        font-weight: 700; font-size: .68rem; letter-spacing: .18em; text-transform: uppercase;
        box-shadow: 0 16px 28px -12px rgba(179, 0, 0, .8) !important;
        transition: background .2s ease, transform .2s ease;
    }
    .btn-auth:hover { color: #fff; background: var(--red-dark); transform: translateY(-1px); }
    .btn-auth:active { transform: translateY(0); }

    .auth-body .border-top { border-color: var(--line) !important; }
    .auth-body a.text-danger { color: var(--red) !important; }
    .auth-body a.text-danger:hover { color: var(--red-dark) !important; text-decoration: underline !important; }

    .auth-foot { position: relative; z-index: 1; margin-top: 1.4rem; display: inline-flex; align-items: center; gap: .9rem; font-size: .6rem; font-weight: 300; letter-spacing: .06em; color: rgba(255, 255, 255, .8); }
    .auth-foot::before { content: ""; width: 60px; height: 1px; background: rgba(255, 255, 255, .45); }
    .auth-foot i { width: 8px; height: 8px; border-radius: 50%; background: #fff; display: inline-block; }

    a:focus-visible, button:focus-visible { outline: 2px solid var(--red); outline-offset: 2px; }
    .form-control:focus-visible { outline: none; }

    /* ---------- Responsive ---------- */
    @media (max-width: 575.98px) {
        .auth-viewport { padding: 5rem .85rem 1.5rem; }
        .auth-card { border-radius: 20px; }
        .auth-head { padding: 1.6rem 1.25rem 1rem; }
        .auth-body { padding: .3rem 1.25rem 1.3rem; }
        .form-floating > .form-control { font-size: 16px; } /* stops iOS zoom */
    }
    @media (max-height: 680px) {
        .auth-viewport { padding-top: 4.5rem; }
        .auth-head { padding-top: 1.1rem; padding-bottom: .7rem; }
        .auth-drop { width: 44px; height: 44px; margin-bottom: .5rem; }
        .auth-head p { display: none; }
        .form-floating > .form-control { height: 48px; }
        .btn-auth { height: 46px; }
        .auth-foot { display: none; }
    }
    @media (prefers-reduced-motion: reduce) {
        .auth-card { animation: none; }
        * { transition: none !important; }
    }
</style>


<div class="auth-viewport">
    <div class="auth-mark" aria-hidden="true">BLOOD</div>
    <div class="rbc-field" aria-hidden="true">
        <span class="rbc" style="--x:62%;--y:12%;--s:clamp(70px,8vw,120px);--rx:60deg;--ry:-18deg;--rz:20deg;--blur:1.5px;--dur:11s;"><i></i></span>
        <span class="rbc" style="--x:78%;--y:46%;--s:clamp(90px,11vw,170px);--rx:64deg;--ry:10deg;--rz:-12deg;--dur:12s;--delay:-4s;--fx:-12px;--fy:-16px;"><i style="--spin:38s"></i></span>
        <span class="rbc" style="--x:88%;--y:6%;--s:clamp(40px,4vw,60px);--rx:48deg;--ry:22deg;--rz:-30deg;--blur:3px;--dur:9s;"><i></i></span>
        <span class="rbc" style="--x:68%;--y:72%;--s:clamp(50px,5vw,80px);--rx:52deg;--ry:-25deg;--rz:40deg;--blur:1px;--dur:13s;--delay:-2s;"><i></i></span>
        <span class="rbc" style="--x:6%;--y:70%;--s:clamp(36px,3.5vw,54px);--rx:55deg;--ry:-20deg;--rz:30deg;--blur:4px;--dur:10s;--delay:-6s;"><i></i></span>
    </div>
    <a href="{{ url('/') }}" class="auth-brand" aria-label="BE.DONOR home">
        <svg viewBox="0 0 34 40" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M17 2C17 2 5 15.5 5 24.5C5 31.4 10.4 37 17 37C23.6 37 29 31.4 29 24.5C29 15.5 17 2 17 2Z"/>
                <path d="M10 26.5C10 29.8 12.6 32.5 16 32.8"/>
                <rect x="17.5" y="21" width="12" height="12" rx="1" fill="var(--logo-fill, #b30000)"/>
                <path d="M23.5 23.5V30.5M20 27H27"/>
            </svg>
        <span>BE.DONOR</span>
    </a>

    <div class="auth-card">
        <div class="auth-head">
            <div class="auth-drop"><i class="fa-solid fa-droplet"></i></div>
            <span class="auth-eyebrow">Admin access</span>
            <h1 class="h4 fw-bold mb-1">Welcome back</h1>
            <p class="small text-white-50 mb-0">Sign in to access your administrative dashboard</p>
        </div>

        <div class="auth-body">
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="form-floating mb-3">
                    <input type="email" name="email" id="emailInput" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                    <label for="emailInput">Email address</label>
                    @error('email') <div class="invalid-feedback fw-semibold">{{ $message }}</div> @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="password" name="password" id="passwordInput" 
                           class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Password" required>
                    <label for="passwordInput">Password</label>
                    @error('password') <div class="invalid-feedback fw-semibold">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted small fw-semibold" for="remember">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-auth w-100 shadow-sm">
                    <i class="fa-solid fa-right-to-bracket me-1"></i> Log In
                </button>
            </form>

            <div class="text-center mt-3 pt-3 border-top border-light-subtle">
                <!-- <p class="small text-muted mb-1">
                    Approved admin email? <a href="{{ route('register') }}" class="fw-bold text-danger text-decoration-none">Create account</a>
                </p> -->
                <!-- <a href="{{ route('donors.public.search') }}" class="small text-muted text-decoration-none d-inline-block mt-1">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to public search
                </a> -->
            </div>
        </div>
    </div>

    <div class="auth-foot" aria-hidden="true"><i></i> 1 up to save 3 lives</div>
</div>
@endsection
