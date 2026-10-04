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
    <!-- Guest Layout Container -->
    <main class="py-5">
        @yield('content')
    </main>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>