@extends('layouts.app')

@section('title', 'Verify Email - Admin Access')

@section('content')
<div class="container py-5" style="max-width: 720px;">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">
            <h1 class="h4 fw-bold mb-3">Verify your email address</h1>
            <p class="text-muted">
                Use the verification link sent to <strong>{{ auth()->user()->email }}</strong> to activate admin access.
                Check your spam folder if it is not in your inbox.
            </p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('status') && session('status') !== 'verification-link-sent')
                <div class="alert alert-info">{{ session('status') }}</div>
            @endif
            @if(session('status') === 'verification-link-sent')
                <div class="alert alert-success">A new verification link has been sent.</div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-danger rounded-pill px-4">
                    Resend verification email
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
