<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Blood Donor System')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { background: #212529; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 12px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active { color: #fff; background: #343a40; }
        /* Full-height sidebar only on desktop; on phones it is just a top menu */
        @media (min-width: 768px) { .sidebar { min-height: 100vh; } }

        /* ===== Guest (login/register) full-page split layout ===== */
        .guest-wrapper { min-height: 100vh; display: flex; flex-direction: column; }
        .guest-side {
            display: none;
            background: linear-gradient(160deg, #b3122b 0%, #8c0d21 100%);
            color: #fff; flex-direction: column; justify-content: center;
            align-items: center; text-align: center; padding: 2rem 1.5rem;
        }
        .guest-content {
            flex: 1; display: flex; align-items: center; justify-content: center;
            padding: 1.5rem 1rem;
        }
        .guest-side .drop-big {
            width: 70px; height: 70px; background: #fff; color: #b3122b;
            border-radius: 50% 50% 50% 0; transform: rotate(-45deg);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.2rem; box-shadow: 0 10px 25px rgba(0,0,0,.25);
        }
        .guest-side .drop-big i { transform: rotate(45deg); font-size: 1.8rem; }
        .guest-side h2 { font-weight: 800; margin-bottom: .5rem; font-size: 1.4rem; }
        .guest-side p { opacity: .85; max-width: 320px; font-size: .9rem; }

        /* Tablet & up: side-by-side split */
        @media (min-width: 768px) {
            .guest-wrapper { flex-direction: row; }
            .guest-side { display: flex; flex: 0 0 42%; padding: 3rem; }
            .guest-side .drop-big { width: 90px; height: 90px; margin-bottom: 1.5rem; }
            .guest-side .drop-big i { font-size: 2.2rem; }
            .guest-side h2 { font-size: 1.75rem; }
            .guest-side p { font-size: 1rem; }
            .guest-content { padding: 2rem; }
        }

        /* Small phones: tighter padding so card isn't cramped */
        @media (max-width: 420px) {
            .guest-content { padding: 1rem .75rem; }
        }
    </style>
</head>
<body>

@auth
@php $pendingCount = \App\Models\Donor::where('donation_pending', true)->count(); @endphp
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Navigation -->
        <aside class="col-md-2 p-0 sidebar">
            <div class="p-3 text-white border-bottom border-secondary">
                <h5 class="mb-0"><i class="fa-solid fa-droplet text-danger me-2"></i>Blood Donor</h5>
            </div>
            <nav class="mt-3 pb-3">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge me-2"></i> Dashboard
                    @if($pendingCount > 0)
                        <span class="badge bg-danger ms-1">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('donors.index') }}" class="{{ request()->routeIs('donors.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-hand-holding-heart me-2"></i> Donors List
                </a>
                <form action="{{ route('logout') }}" method="POST" class="mt-4 px-3">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 btn-sm">
                        <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content Area (pages show their own flash messages) -->
        <main class="col-md-10 p-4">
            @yield('content')
        </main>
    </div>
</div>
@else
    <!-- Guest Layout: full-page split-screen with red brand panel -->
    <div class="guest-wrapper">
        <div class="guest-side">
            <div class="drop-big"><i class="fa-solid fa-droplet"></i></div>
            <h2>Blood Donor System</h2>
            <p>Connecting donors and recipients to save lives — every drop counts.</p>
        </div>
        <div class="guest-content">
            @yield('content')
        </div>
    </div>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>