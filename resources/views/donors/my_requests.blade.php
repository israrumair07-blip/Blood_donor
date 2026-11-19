<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blood Requests - Status Tracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
            --font: 'Montserrat', system-ui, -apple-system, sans-serif;
        }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--ink);
            min-height: 100vh;
        }

        .bd-hero {
            position: relative; overflow: hidden; isolation: isolate; color: #fff;
            background:
                radial-gradient(ellipse 55% 65% at 50% 40%, #d40000 0%, rgba(212, 0, 0, 0) 70%),
                radial-gradient(ellipse 120% 90% at 50% 50%, var(--red) 0%, var(--red-2) 60%, var(--red-3) 100%);
        }
        .bd-watermark {
            position: absolute; inset: 0; z-index: -1; display: flex; align-items: center; justify-content: center;
            pointer-events: none; user-select: none; white-space: nowrap;
            font-weight: 900; font-size: clamp(7rem, 24vw, 22rem); line-height: .8; letter-spacing: -.02em;
            color: rgba(255, 255, 255, .05);
        }

        /* Navbar */
        .bd-nav { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1.6rem 0 0; flex-wrap: wrap; }
        .bd-brand { display: inline-flex; align-items: center; gap: .55rem; color: #fff; text-decoration: none; }
        .bd-brand:hover { color: #fff; }
        .bd-brand svg { width: 30px; height: 36px; }
        .bd-brand span { font-size: .72rem; font-weight: 800; letter-spacing: .16em; }
        .bd-links { display: flex; align-items: center; gap: clamp(1rem, 2.5vw, 2.25rem); list-style: none; margin: 0; padding: 0; }
        .bd-links a {
            position: relative; color: rgba(255, 255, 255, .75); text-decoration: none;
            font-size: .62rem; font-weight: 400; letter-spacing: .2em; text-transform: uppercase; padding: .4rem 0;
            transition: color .2s ease;
        }
        .bd-links a:hover, .bd-links a.is-active { color: #fff; font-weight: 600; }
        .bd-links a.is-active::after {
            content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 1px; background: #fff;
        }
        .bd-cta {
            display: inline-flex; align-items: center; gap: .5rem; white-space: nowrap;
            background: #fff; color: var(--red); border-radius: 999px;
            font-size: .62rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
            padding: .62rem 1.1rem; text-decoration: none;
        }

        /* Hero */
        .bd-hero-body { padding: 3rem 0 4.5rem; }
        .bd-eyebrow {
            display: inline-flex; align-items: center; gap: .8rem; margin-bottom: 1rem;
            font-size: .6rem; letter-spacing: .24em; text-transform: uppercase; color: rgba(255, 255, 255, .78);
        }
        .bd-eyebrow b { font-size: 1.15rem; font-weight: 800; color: #fff; }
        .bd-title {
            font-weight: 800; text-transform: uppercase; letter-spacing: .01em; line-height: 1.05;
            font-size: clamp(1.9rem, 5vw, 3.8rem); margin: 0 0 1rem;
        }
        .bd-lead { font-size: .84rem; max-width: 600px; color: rgba(255, 255, 255, .9); line-height: 1.8; margin: 0; }

        /* Panels & Cards */
        .bd-panel {
            background: #fff; border: 1px solid var(--line); border-radius: 18px;
            box-shadow: 0 1px 2px rgba(60, 0, 0, .04), 0 20px 40px -25px rgba(110, 0, 0, .25);
        }
        .search-panel { margin-top: -2.5rem; position: relative; z-index: 4; padding: 1.5rem; }
        .btn-bd {
            background: var(--red); color: #fff; border: 0; border-radius: 999px;
            font-weight: 700; font-size: .68rem; letter-spacing: .14em; text-transform: uppercase;
            padding: .75rem 1.4rem; transition: background .2s;
        }
        .btn-bd:hover { background: var(--red-2); color: #fff; }

        .request-card {
            background: #fff; border: 1px solid var(--line); border-radius: 16px;
            box-shadow: 0 2px 6px rgba(0,0,0,.03); padding: 1.4rem;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .request-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px -15px rgba(179, 0, 0, .25); }

        .status-badge {
            display: inline-flex; align-items: center; font-size: .68rem; font-weight: 800;
            letter-spacing: .08em; text-transform: uppercase; border-radius: 999px; padding: .35rem .85rem;
        }
        .status-pending { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .status-approved { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .status-rejected { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        .donor-pill {
            display: inline-flex; align-items: center; justify-content: center;
            width: 44px; height: 44px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg);
            background: radial-gradient(circle at 35% 30%, #ff3b3b, var(--red) 55%, var(--red-3));
            color: #fff; font-weight: 800; font-size: .88rem;
        }
        .donor-pill span { transform: rotate(45deg); }
    </style>
</head>
<body>

<header class="bd-hero">
    <div class="bd-watermark" aria-hidden="true">REQUESTS</div>
    <div class="container">
        <nav class="bd-nav">
            <a class="bd-brand" href="{{ url('/') }}">
                <svg viewBox="0 0 34 40" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 2C17 2 5 15.5 5 24.5C5 31.4 10.4 37 17 37C23.6 37 29 31.4 29 24.5C29 15.5 17 2 17 2Z"/>
                    <path d="M10 26.5C10 29.8 12.6 32.5 16 32.8"/>
                    <rect x="17.5" y="21" width="12" height="12" rx="1" fill="#b30000"/>
                    <path d="M23.5 23.5V30.5M20 27H27"/>
                </svg>
                <span>BE.DONOR</span>
            </a>
            <ul class="bd-links">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ route('donors.public.search') }}">Find a donor</a></li>
                <li><a href="{{ route('blood-exchanges.index') }}"><i class="fa-solid fa-right-left me-1"></i> Exchange blood</a></li>
                <li><a href="{{ route('donor-contacts.my-requests') }}" class="is-active">My requests</a></li>
                <li><a href="{{ route('blood-requests.index') }}"><i class="fa-solid fa-bolt me-1"></i> Urgent feed</a></li>
            </ul>
            <div class="bd-actions">
                <a href="{{ route('donors.public.search') }}" class="bd-cta">
                    <i class="fa-solid fa-magnifying-glass"></i> Find Donors
                </a>
            </div>
        </nav>

        <div class="bd-hero-body">
            <div class="bd-eyebrow"><b>STATUS</b> Request Tracker</div>
            <h1 class="bd-title">Track your donor<br>contact requests</h1>
            <p class="bd-lead">Check real-time status of your submitted contact requests. All requests are reviewed by system administrators to maintain safety and verify patient conditions.</p>
        </div>
    </div>
</header>

<div class="container pb-5">

    <!-- Flash message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Phone lookup filter -->
    <div class="search-panel bd-panel mb-4">
        <form method="GET" action="{{ route('donor-contacts.my-requests') }}" class="row g-3 align-items-end">
            <div class="col-12 col-md-8 col-lg-9">
                <label class="form-label text-uppercase small fw-bold text-muted" style="letter-spacing: .12em;">Look up requests by Phone Number</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-phone text-danger"></i></span>
                    <input type="text" name="phone" value="{{ $phone ?? '' }}" class="form-control border-start-0" placeholder="Enter the requester phone number used (e.g. 03001234567)">
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-3 d-flex gap-2">
                <button type="submit" class="btn btn-bd w-100">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Search
                </button>
                @if(!empty($phone))
                    <a href="{{ route('donor-contacts.my-requests') }}" class="btn btn-outline-secondary rounded-pill px-3" title="Clear">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Requests list -->
    <div class="row g-4">
        @forelse($requests as $req)
            <div class="col-12 col-lg-6">
                <div class="request-card h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="donor-pill">
                                <span>{{ $req->donor->blood_group ?? '?' }}</span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0 text-uppercase">{{ $req->donor->name ?? 'Blood Donor' }}</h5>
                                <small class="text-muted"><i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $req->donor->city ?? $req->city }}</small>
                            </div>
                        </div>

                        <div>
                            @if($req->status === 'approved')
                                <span class="status-badge status-approved">
                                    <i class="fa-solid fa-circle-check me-1"></i> Accepted
                                </span>
                            @elseif($req->status === 'rejected')
                                <span class="status-badge status-rejected">
                                    <i class="fa-solid fa-circle-xmark me-1"></i> Rejected
                                </span>
                            @else
                                <span class="status-badge status-pending">
                                    <i class="fa-solid fa-clock me-1"></i> Pending Review
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-3 small">
                        <div class="row g-2">
                            <div class="col-6">
                                <span class="text-muted d-block text-uppercase fw-bold" style="font-size: .65rem;">Patient</span>
                                <strong class="text-dark">{{ $req->patient_name }}</strong>
                            </div>
                            <div class="col-6">
                                <span class="text-muted d-block text-uppercase fw-bold" style="font-size: .65rem;">Urgency</span>
                                <span class="badge bg-danger-subtle text-danger">{{ $req->urgency_level }}</span>
                            </div>
                            <div class="col-12 pt-1">
                                <span class="text-muted d-block text-uppercase fw-bold" style="font-size: .65rem;">Hospital</span>
                                <span>{{ $req->hospital }}, {{ $req->city }}</span>
                            </div>
                            @if($req->message)
                                <div class="col-12 pt-1 border-top mt-1">
                                    <span class="text-muted d-block text-uppercase fw-bold" style="font-size: .65rem;">Condition / Note</span>
                                    <span class="fst-italic text-secondary">"{{ $req->message }}"</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Admin Feedback / Actions -->
                    @if($req->status === 'approved')
                        <div class="alert alert-success border-0 py-2 px-3 small rounded-3 mb-0 mt-auto">
                            <strong><i class="fa-solid fa-shield-halved me-1"></i> Admin Accepted:</strong>
                            <div class="mt-1">{{ $req->admin_notes ?: 'Your request has been verified and mediated communication has been arranged.' }}</div>
                        </div>
                    @elseif($req->status === 'rejected')
                        <div class="alert alert-danger border-0 py-2 px-3 small rounded-3 mb-0 mt-auto">
                            <strong><i class="fa-solid fa-circle-exclamation me-1"></i> Request Declined:</strong>
                            <div class="mt-1">{{ $req->admin_notes ?: 'The request could not be approved at this time.' }}</div>
                        </div>
                    @else
                        <div class="alert alert-warning border-0 py-2 px-3 small rounded-3 mb-0 mt-auto">
                            <i class="fa-solid fa-info-circle me-1"></i> Your request is in queue and waiting for administrator verification. Please check back shortly.
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center text-muted small mt-3 pt-2 border-top">
                        <span style="font-size: .72rem;"><i class="fa-solid fa-calendar me-1"></i> Submitted: {{ $req->created_at->diffForHumans() }}</span>
                        <span class="badge bg-light text-secondary border" style="font-size: .68rem;">Request #{{ $req->id }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="bd-panel p-5 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 70px; height: 70px; background: var(--rose); color: var(--red);">
                        <i class="fa-solid fa-folder-open fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-dark text-uppercase">No Submitted Requests Found</h4>
                    <p class="text-muted small max-w-md mx-auto mb-4">
                        If you recently submitted a request to contact a blood donor, enter the phone number you used in the search box above to retrieve its status.
                    </p>
                    <a href="{{ route('donors.public.search') }}" class="btn btn-bd">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Find a Donor
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if(method_exists($requests, 'hasPages') && $requests->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $requests->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<footer class="py-4 text-center text-white small" style="background: var(--red-3);">
    <div>&copy; {{ date('Y') }} BE.DONOR — Blood Donor Management System</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
