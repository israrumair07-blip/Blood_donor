<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Contact Request #{{ $contactRequest->id }} - BE.DONOR</title>
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
        .status-rejected { background: var(--rose); color: var(--red); border: 1px solid var(--rose-line); }
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
                <li><a href="{{ route('donor-contacts.my-requests') }}">My requests</a></li>
                <li><a href="{{ route('blood-requests.index') }}">Urgent requests</a></li>
            </ul>
        </nav>

        <div class="text-center py-5">
            <div class="text-uppercase small fw-bold tracking-widest text-white-50 mb-2">Request Tracking Portal</div>
            <h1 class="display-6 fw-bolder text-white mb-2">Donor Contact Request #{{ $contactRequest->id }}</h1>
            <p class="text-white-50">Bookmark this page or save your link to follow admin verification progress.</p>
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

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="bd-panel p-4 p-md-5">
                <!-- Status Banner -->
                <div class="text-center pb-4 border-bottom mb-4">
                    <div class="text-muted small text-uppercase fw-bold mb-2">Current Status</div>
                    @if($contactRequest->status === 'approved')
                        <div class="status-badge status-approved">
                            <i class="fa-solid fa-circle-check"></i> Request Approved & Mediated
                        </div>
                        <div class="mt-3 p-3 rounded-3" style="background:#f0fdf4; border:1px solid #bbf7d0; text-align: left;">
                            <h6 class="fw-bold text-success mb-1"><i class="fa-solid fa-circle-info me-2"></i>Admin Approval Instructions:</h6>
                            <p class="text-dark small mb-0">{{ $contactRequest->admin_notes ?: 'Your emergency contact request has been verified by the administrator. The volunteer donor has been notified with your patient details.' }}</p>
                        </div>
                    @elseif($contactRequest->status === 'rejected')
                        <div class="status-badge status-rejected">
                            <i class="fa-solid fa-circle-xmark"></i> Request Rejected
                        </div>
                        <div class="mt-3 p-3 rounded-3" style="background:var(--rose); border:1px solid var(--rose-line); text-align: left;">
                            <h6 class="fw-bold text-danger mb-1"><i class="fa-solid fa-circle-exclamation me-2"></i>Reason from Admin:</h6>
                            <p class="text-dark small mb-0">{{ $contactRequest->admin_notes ?: 'This request could not be approved at this time.' }}</p>
                        </div>
                    @else
                        <div class="status-badge status-pending">
                            <i class="fa-solid fa-clock"></i> Pending Admin Verification
                        </div>
                        <p class="text-muted small mt-2 mb-0">An administrator is reviewing your request to verify urgency and facilitate mediated contact with the volunteer donor.</p>
                    @endif
                </div>

                <!-- Request Details -->
                <h6 class="fw-bold text-uppercase text-danger mb-3" style="font-size: 0.75rem; letter-spacing: 0.15em;">Request Summary</h6>
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="text-muted small">Target Donor Blood Group</div>
                        <div class="fw-bold fs-5 text-danger">{{ $contactRequest->donor->blood_group ?? 'Volunteer Donor' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Target Donor City</div>
                        <div class="fw-bold text-dark">{{ $contactRequest->donor->city ?? 'N/A' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Requester Name</div>
                        <div class="fw-bold text-dark">{{ $contactRequest->requester_name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Requester Phone</div>
                        <div class="fw-bold text-dark">{{ $contactRequest->requester_phone }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Patient Name</div>
                        <div class="fw-bold text-dark">{{ $contactRequest->patient_name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Urgency Level</div>
                        <div class="badge bg-danger-subtle text-danger border border-danger-subtle">{{ $contactRequest->urgency_level }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Hospital / Location</div>
                        <div class="fw-semibold text-dark"><i class="fa-solid fa-hospital text-danger me-1"></i>{{ $contactRequest->hospital }}, {{ $contactRequest->city }}</div>
                    </div>
                    @if($contactRequest->message)
                        <div class="col-12">
                            <div class="text-muted small">Message / Medical Notes</div>
                            <div class="p-3 bg-light rounded text-dark small fst-italic">"{{ $contactRequest->message }}"</div>
                        </div>
                    @endif
                </div>

                <div class="d-flex flex-wrap gap-2 pt-3 border-top">
                    <a href="{{ route('donors.public.search') }}" class="btn btn-bd">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Browse More Donors
                    </a>
                    <a href="{{ route('blood-requests.create') }}" class="btn btn-outline-danger rounded-pill px-4" style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase;">
                        <i class="fa-solid fa-bullhorn me-1"></i> Post Emergency Request
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

