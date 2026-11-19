<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Blood Exchange #{{ $exchange->id }} - BE.DONOR</title>
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
            --ink: #1d1012;
            --muted: #76666a;
            --line: #eee3e4;
            --bg: #faf6f6;
            --rose: #fff1f1;
            --rose-line: #f5d0d0;
            --green: #17935a;
            --font: 'Montserrat', system-ui, -apple-system, 'Segoe UI', sans-serif;
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
            padding-bottom: 5rem;
        }
        .bd-nav { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1.6rem 0 0; flex-wrap: wrap; }
        .bd-brand { display: inline-flex; align-items: center; gap: .55rem; color: #fff; text-decoration: none; }
        .bd-brand svg { width: 30px; height: 36px; }
        .bd-brand span { font-size: .72rem; font-weight: 800; letter-spacing: .16em; }
        .bd-links { display: flex; align-items: center; gap: clamp(1rem, 2.5vw, 2.25rem); list-style: none; margin: 0; padding: 0; }
        .bd-links a { color: rgba(255, 255, 255, .8); text-decoration: none; font-size: .62rem; letter-spacing: .2em; text-transform: uppercase; }
        .bd-links a:hover { color: #fff; }
        .bd-panel {
            background: #fff; border: 1px solid var(--line); border-radius: 20px;
            box-shadow: 0 1px 2px rgba(60, 0, 0, .04), 0 22px 44px -26px rgba(110, 0, 0, .28);
        }
        .btn-bd {
            display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
            min-height: 46px; padding: 0 1.5rem; border: 0; border-radius: 999px;
            background: var(--red); color: #fff; font-weight: 700; font-size: .68rem; letter-spacing: .14em; text-transform: uppercase;
            text-decoration: none;
        }
        .btn-bd:hover { background: var(--red-2); color: #fff; }
        .status-badge {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .6rem 1.2rem; border-radius: 999px; font-weight: 800; font-size: .85rem; letter-spacing: .05em; text-transform: uppercase;
        }
        .status-pending { background: #fff4d6; color: #8a6100; border: 1px solid #ffe18a; }
        .status-approved { background: #e9f7ef; color: #146c43; border: 1px solid #b7ebcc; }
        .status-completed { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .status-cancelled { background: #f1eded; color: #6a5e60; border: 1px solid #e0d8d9; }
        .status-rejected { background: var(--rose); color: var(--red); border: 1px solid var(--rose-line); }
        /* =========================================================
   COMPACT TRACKER PAGE (Facebook-style) — paste at the END of <style>
   ========================================================= */

/* Card narrow aur compact */
.col-lg-8 { max-width: 680px; }
.bd-panel { border-radius: 10px; }
.bd-panel.p-4 { padding: 1.2rem 1.3rem !important; }
@media (max-width: 575.98px) {
    .bd-panel.p-4 { padding: 1rem .85rem !important; }
}

/* Hero thoda chhota */
.bd-hero { padding-bottom: 4rem; }
.bd-hero .py-5 { padding-top: 1.5rem !important; padding-bottom: 1rem !important; }
.bd-hero h1 { font-size: clamp(1.4rem, 4vw, 2rem); }
.bd-hero p { font-size: .85rem; }

/* Status badge chhota */
.status-badge {
    padding: .4rem .9rem;
    font-size: .72rem;
    letter-spacing: .04em;
}

/* Status section ki spacing kam */
.bd-panel .pb-4 { padding-bottom: 1rem !important; }
.bd-panel .mb-4 { margin-bottom: 1rem !important; }
.bd-panel .small { font-size: .78rem; }

/* Blood group matchup box compact */
.bd-panel .rounded-4 { border-radius: 8px !important; padding: .8rem !important; }
.bd-panel .badge.fs-3 { font-size: 1.15rem !important; padding: .4rem .9rem !important; }
.bd-panel .fa-arrow-right-arrow-left.fs-3 { font-size: 1.1rem !important; }
.bd-panel .gap-4 { gap: 1.2rem !important; }

/* Details grid compact */
.bd-panel .row.g-3 { --bs-gutter-y: .7rem; }
.bd-panel .fw-bold.text-dark { font-size: .88rem; }

/* Buttons: 40px, rounded-rect */
.btn-bd {
    min-height: 40px;
    padding: 0 1rem;
    border-radius: 6px;
    font-size: .82rem;
    font-weight: 700;
    letter-spacing: 0;
    text-transform: none;
}
.btn-outline-secondary.rounded-pill {
    min-height: 40px;
    border-radius: 6px !important;
    font-size: .82rem !important;
    padding: 0 1rem !important;
}

/* Nav links active */
.bd-links a:hover { font-weight: 600; }

/* Mobile: dono buttons full width */
@media (max-width: 575.98px) {
    .bd-panel .border-top form,
    .bd-panel .border-top .btn { width: 100%; }
}
    </style>
</head>
<body>

<header class="bd-hero">
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
            <ul class="bd-links" role="list">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ route('donors.public.search') }}">Find a donor</a></li>
                <li><a href="{{ route('blood-exchanges.index') }}">Exchange blood</a></li>
                <li><a href="{{ route('donor-contacts.my-requests') }}"><i class="fa-solid fa-clock-rotate-left me-1"></i> My requests</a></li>
                <li><a href="{{ route('blood-requests.index') }}">Urgent feed</a></li>
            </ul>
        </nav>

        <div class="text-center py-5">
            <div class="text-uppercase small fw-bold tracking-widest text-white-50 mb-2">Live Exchange Tracker</div>
            <h1 class="display-6 fw-bolder text-white mb-2">Blood Exchange Request #{{ $exchange->id }}</h1>
            <p class="text-white-50">Bookmark this page to track your exchange request or cancel it when fulfilled.</p>
        </div>
    </div>
</header>

<div class="container pb-5" style="margin-top: -3rem; position: relative; z-index: 5;">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-info me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="bd-panel p-4 p-md-5">
                <!-- Status Banner -->
                <div class="text-center pb-4 border-bottom mb-4">
                    <div class="text-muted small text-uppercase fw-bold mb-2">Status</div>
                    @if($exchange->status === 'approved')
                        <div class="status-badge status-approved">
                            <i class="fa-solid fa-circle-check"></i> Live on Public Exchange Board
                        </div>
                        <p class="text-success small mt-2 mb-0">Your exchange request is active! Donors and families can see your requirement and contact you.</p>
                    @elseif($exchange->status === 'completed')
                        <div class="status-badge status-completed">
                            <i class="fa-solid fa-handshake"></i> Exchange Completed
                        </div>
                        <p class="text-primary small mt-2 mb-0">This blood exchange has been successfully fulfilled.</p>
                    @elseif($exchange->status === 'cancelled')
                        <div class="status-badge status-cancelled">
                            <i class="fa-solid fa-ban"></i> Offer Cancelled
                        </div>
                    @elseif($exchange->status === 'rejected')
                        <div class="status-badge status-rejected">
                            <i class="fa-solid fa-circle-xmark"></i> Not Approved
                        </div>
                        @if($exchange->admin_notes)
                            <div class="small text-danger mt-2"><strong>Admin Note:</strong> {{ $exchange->admin_notes }}</div>
                        @endif
                    @else
                        <div class="status-badge status-pending">
                            <i class="fa-solid fa-clock"></i> Pending Admin Verification
                        </div>
                        <p class="text-muted small mt-2 mb-0">An administrator is reviewing your offer. Once approved, it will immediately appear on the public exchange board.</p>
                    @endif
                </div>

                <!-- Matchup Card -->
                <div class="p-3 mb-4 rounded-4 bg-light text-center border">
                    <div class="text-muted small text-uppercase fw-bold mb-2">Exchange Details</div>
                    <div class="d-flex align-items-center justify-content-center gap-4">
                        <div>
                            <span class="badge bg-danger fs-3 px-3 py-2 rounded-pill">{{ $exchange->offered_blood_group }}</span>
                            <div class="text-muted small fw-bold text-uppercase mt-1">Offered (Have)</div>
                        </div>
                        <i class="fa-solid fa-arrow-right-arrow-left fs-3 text-muted"></i>
                        <div>
                            <span class="badge bg-primary fs-3 px-3 py-2 rounded-pill">{{ $exchange->needed_blood_group }}</span>
                            <div class="text-muted small fw-bold text-uppercase mt-1">Needed</div>
                        </div>
                    </div>
                    <div class="mt-3 fw-bold text-dark">{{ $exchange->units }} Unit(s) required</div>
                </div>

                <!-- Details Summary -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="text-muted small">Patient Name</div>
                        <div class="fw-bold text-dark">{{ $exchange->patient_name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Hospital & City</div>
                        <div class="fw-bold text-dark">{{ $exchange->hospital }}, {{ $exchange->city }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Contact Person</div>
                        <div class="fw-bold text-dark">{{ $exchange->contact_name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Contact Phone</div>
                        <div class="fw-bold text-dark">{{ $exchange->contact_phone }}</div>
                    </div>
                    @if($exchange->notes)
                        <div class="col-12">
                            <div class="text-muted small">Additional Notes</div>
                            <div class="p-3 bg-light rounded text-dark small fst-italic">"{{ $exchange->notes }}"</div>
                        </div>
                    @endif
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3 border-top">
                    <a href="{{ route('blood-exchanges.index') }}" class="btn btn-bd">
                        <i class="fa-solid fa-arrow-left me-1"></i> Browse Exchange Board
                    </a>

                    @if(in_array($exchange->status, ['pending', 'approved']))
                        <form action="{{ route('blood-exchanges.cancel', $exchange->id) }}" method="POST" onsubmit="return confirm('Cancel this exchange offer? It will no longer appear on the board.')">
                            @csrf
                            <input type="hidden" name="manage_token" value="{{ $exchange->manage_token }}">
                            <button type="submit" class="btn btn-outline-secondary rounded-pill px-3 fw-bold" style="font-size: 0.72rem;">
                                <i class="fa-solid fa-ban me-1"></i> Cancel My Offer
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

