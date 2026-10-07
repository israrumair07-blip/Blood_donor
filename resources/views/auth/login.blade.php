@extends('layouts.app')

@section('title', 'Sign In - Blood Donor System')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .auth {
        --red: #b3122b; --red-dark: #8c0d21; --rose: #fdebee;
        --ink: #1c2130; --muted: #6b7280; --line: #e8e9ee;
        font-family: 'Manrope', system-ui, sans-serif; color: var(--ink);
        min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 2rem 0;
    }
    .auth .auth-card { width: 100%; max-width: 440px; background: #fff; border: 1px solid var(--line); border-radius: 20px; box-shadow: 0 16px 40px rgba(28, 33, 48, .1); overflow: hidden; }
    .auth .auth-head { background: linear-gradient(180deg, var(--red) 0%, var(--red-dark) 100%); color: #fff; text-align: center; padding: 2rem 1.5rem 1.6rem; }
    .auth .drop { width: 56px; height: 56px; margin: 0 auto 1rem; background: #fff; color: var(--red); border-radius: 50% 50% 50% 0; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 18px rgba(0, 0, 0, .2); }
    .auth .drop i { transform: rotate(45deg); font-size: 1.3rem; }
    .auth .auth-head h1 { font-size: 1.5rem; font-weight: 800; letter-spacing: -.4px; margin: 0 0 .25rem; }
    .auth .auth-head p { margin: 0; font-size: .9rem; color: rgba(255, 255, 255, .8); }
    .auth .auth-body { padding: 1.75rem; }

    .auth .form-label { font-size: .82rem; font-weight: 700; margin-bottom: .3rem; }
    .auth .form-control { height: 48px; border-radius: 12px; border: 1.5px solid var(--line); font-weight: 500; }
    .auth .form-control:focus { border-color: var(--red); box-shadow: 0 0 0 .2rem rgba(179, 18, 43, .15); }
    .auth .form-control.is-invalid { border-color: var(--red); background-image: none; }
    .auth .invalid-feedback { color: var(--red-dark); font-weight: 600; font-size: .8rem; }
    .auth .form-check-input:checked { background-color: var(--red); border-color: var(--red); }
    .auth .form-check-input:focus { box-shadow: 0 0 0 .2rem rgba(179, 18, 43, .2); border-color: var(--red); }

    .auth .btn-brand { height: 50px; background: var(--red); color: #fff; font-weight: 700; border: 0; border-radius: 12px; }
    .auth .btn-brand:hover { background: var(--red-dark); color: #fff; }
    .auth .switch { text-align: center; color: var(--muted); font-size: .88rem; margin: 1.4rem 0 0; }
    .auth .switch a, .auth .back a { color: var(--red); font-weight: 700; text-decoration: none; }
    .auth .switch a:hover, .auth .back a:hover { color: var(--red-dark); text-decoration: underline; }
    .auth .back { text-align: center; font-size: .82rem; margin: .8rem 0 0; }
    a:focus-visible, button:focus-visible { outline: 3px solid rgba(179, 18, 43, .4); outline-offset: 2px; }
</style>

<div class="auth">
    <div class="auth-card">
        <div class="auth-head">
            <div class="drop"><i class="fa-solid fa-droplet"></i></div>
            <h1>Welcome back</h1>
            <p>Sign in to access your dashboard</p>
        </div>

        <div class="auth-body">
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label text-muted small fw-semibold" for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn btn-brand w-100">
                    <i class="fa-solid fa-right-to-bracket me-1"></i> Log in
                </button>
            </form>

            <p class="switch">Need an admin account? <a href="{{ route('register') }}">Register here</a></p>
            <p class="back"><a href="{{ route('donors.public.search') }}"><i class="fa-solid fa-arrow-left me-1"></i>Back to donor search</a></p>
        </div>
    </div>
</div>
@endsection