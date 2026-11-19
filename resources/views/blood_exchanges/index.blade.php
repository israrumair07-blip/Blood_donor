<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Group Exchange Board - BE.DONOR</title>
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
            padding-bottom: 7rem;
        }
        .bd-watermark {
            position: absolute; inset: 0; z-index: -1; display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: clamp(7rem, 22vw, 20rem); color: rgba(255, 255, 255, .05); user-select: none;
        }
        .bd-nav { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1.6rem 0 0; flex-wrap: wrap; }
        .bd-brand { display: inline-flex; align-items: center; gap: .55rem; color: #fff; text-decoration: none; }
        .bd-brand svg { width: 30px; height: 36px; }
        .bd-brand span { font-size: .72rem; font-weight: 800; letter-spacing: .16em; }
        .bd-links { display: flex; align-items: center; gap: clamp(1rem, 2.5vw, 2.25rem); list-style: none; margin: 0; padding: 0; }
        .bd-links a { color: rgba(255, 255, 255, .8); text-decoration: none; font-size: .62rem; letter-spacing: .2em; text-transform: uppercase; }
        .bd-links a:hover, .bd-links a.is-active { color: #fff; font-weight: 700; }
        .bd-panel {
            background: #fff; border: 1px solid var(--line); border-radius: 20px;
            box-shadow: 0 1px 2px rgba(60, 0, 0, .04), 0 22px 44px -26px rgba(110, 0, 0, .28);
        }
        .search-panel { margin-top: -4.5rem; position: relative; z-index: 4; padding: 1.5rem; }
        .form-label { font-size: .62rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: var(--muted); margin-bottom: .45rem; }
        .form-control, .form-select { height: 50px; border-radius: 12px; border: 1px solid var(--line); background-color: #fcfafa; font-size: .88rem; }
        .btn-bd {
            display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
            min-height: 50px; padding: 0 1.6rem; border: 0; border-radius: 999px;
            background: var(--red); color: #fff; font-weight: 700; font-size: .68rem; letter-spacing: .16em; text-transform: uppercase;
            box-shadow: 0 14px 26px -12px rgba(179, 0, 0, .75); text-decoration: none;
        }
        .btn-bd:hover { background: var(--red-2); color: #fff; transform: translateY(-1px); }
        .btn-round { width: 50px; height: 50px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--red); color: var(--red); background: #fff; text-decoration: none; }
        .btn-round:hover { background: var(--red); color: #fff; }
        .exchange-card {
            background: #fff; border: 1px solid var(--line); border-radius: 18px;
            box-shadow: 0 1px 2px rgba(60, 0, 0, .04), 0 18px 36px -24px rgba(110, 0, 0, .3);
            height: 100%; display: flex; flex-direction: column; overflow: hidden;
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .exchange-card:hover { transform: translateY(-4px); box-shadow: 0 25px 45px -22px rgba(110, 0, 0, .4); }
        .blood-pill {
            display: inline-flex; flex-direction: column; align-items: center; justify-content: center;
            width: 60px; height: 60px; border-radius: 14px; font-weight: 900; font-size: 1.15rem;
        }
        .blood-has { background: var(--rose); color: var(--red); border: 2px solid var(--rose-line); }
        .blood-needs { background: #eff6ff; color: #1d4ed8; border: 2px solid #bfdbfe; }
    </style>
</head>
<body>

<header class="bd-hero">
    <div class="bd-watermark" aria-hidden="true">EXCHANGE</div>
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
                <li><a href="{{ route('blood-exchanges.index') }}" class="is-active">Exchange blood</a></li>
                <li><a href="{{ route('donor-contacts.my-requests') }}"><i class="fa-solid fa-clock-rotate-left me-1"></i> My requests</a></li>
                <li><a href="{{ route('blood-requests.index') }}">Urgent feed</a></li>
            </ul>
            <div>
                <a href="{{ route('blood-exchanges.create') }}" class="btn-bd" style="background:#fff; color:var(--red);">
                    <i class="fa-solid fa-plus me-1"></i> Post Exchange Offer
                </a>
            </div>
        </nav>

        <div class="py-5 text-center text-md-start">
            <div class="text-uppercase small fw-bold tracking-widest text-white-50 mb-2">Blood Group Mutual Support</div>
            <h1 class="display-5 fw-bolder text-white mb-2">Blood Exchange System</h1>
            <p class="text-white-50 max-w-2xl mb-4" style="max-width: 600px;">
                Have A+ available but need O-? Connect with patients and families looking for matching mutual blood exchanges.
            </p>
            <a href="{{ route('blood-exchanges.create') }}" class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.15em;">
                <i class="fa-solid fa-right-left me-2"></i> Submit an Exchange Request
            </a>
        </div>
    </div>
</header>

<div class="container pb-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Form -->
    <form method="GET" action="{{ route('blood-exchanges.index') }}" class="search-panel bd-panel row g-3 align-items-end mx-0">
        <div class="col-12 col-md-4">
            <label class="form-label">Blood Group You Have (Offered)</label>
            <select name="offered_blood_group" class="form-select">
                <option value="">Any Offered Group</option>
                @foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $group)
                    <option value="{{ $group }}" {{ request('offered_blood_group') == $group ? 'selected' : '' }}>{{ $group }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-4">
            <label class="form-label">Blood Group You Need (Requested)</label>
            <select name="needed_blood_group" class="form-select">
                <option value="">Any Needed Group</option>
                @foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $group)
                    <option value="{{ $group }}" {{ request('needed_blood_group') == $group ? 'selected' : '' }}>{{ $group }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-3">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" placeholder="e.g. Sargodha" value="{{ request('city') }}">
        </div>
        <div class="col-12 col-md-1 d-flex gap-2">
            <button type="submit" class="btn-round" title="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
            <a href="{{ route('blood-exchanges.index') }}" class="btn-round" title="Reset"><i class="fa-solid fa-rotate-left"></i></a>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
        <h5 class="fw-bold text-uppercase mb-0 text-dark" style="font-size: 0.9rem; letter-spacing: 0.1em;">
            <i class="fa-solid fa-right-left text-danger me-2"></i>Active Exchange Board ({{ $exchanges->total() }})
        </h5>
        <a href="{{ route('blood-exchanges.create') }}" class="btn btn-sm btn-outline-danger rounded-pill fw-bold" style="font-size: 0.72rem;">
            + Post New Exchange
        </a>
    </div>

    <!-- Cards Grid -->
    <div class="row g-4">
        @forelse($exchanges as $ex)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="exchange-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge {{ $ex->status === 'completed' ? 'bg-secondary' : 'bg-success' }} rounded-pill px-3 py-1 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.1em;">
                            {{ $ex->status === 'completed' ? 'Completed' : 'Live Offer' }}
                        </span>
                        <span class="text-muted small fw-bold"><i class="fa-solid fa-vial me-1"></i>{{ $ex->units }} Unit(s)</span>
                    </div>

                    <!-- Exchange Visual Box -->
                    <div class="p-3 mb-3 rounded-4 bg-light d-flex align-items-center justify-content-around">
                        <div class="text-center">
                            <span class="blood-pill blood-has shadow-sm">{{ $ex->offered_blood_group }}</span>
                            <div class="mt-1 small fw-bold text-danger text-uppercase" style="font-size: 0.65rem;">Offers</div>
                        </div>
                        <div class="text-center px-2">
                            <i class="fa-solid fa-right-left fs-4 text-muted"></i>
                        </div>
                        <div class="text-center">
                            <span class="blood-pill blood-needs shadow-sm">{{ $ex->needed_blood_group }}</span>
                            <div class="mt-1 small fw-bold text-primary text-uppercase" style="font-size: 0.65rem;">Needs</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="fw-bold text-dark text-truncate">{{ $ex->patient_name }}</div>
                        <div class="small text-muted mb-1"><i class="fa-solid fa-hospital text-danger me-1"></i>{{ $ex->hospital }}, {{ $ex->city }}</div>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.65rem;">{{ $ex->urgency_level }}</span>
                    </div>

                    @if($ex->notes)
                        <div class="small text-muted bg-white p-2 rounded border mb-3 text-truncate" title="{{ $ex->notes }}">
                            "{{ $ex->notes }}"
                        </div>
                    @endif

                    <div class="mt-auto pt-2 border-top">
                        @if($ex->status === 'completed')
                            <button class="btn btn-secondary w-100 rounded-pill py-2 disabled" style="font-size: 0.72rem;">
                                <i class="fa-solid fa-check-double me-1"></i> Exchange Completed
                            </button>
                        @else
                            <button class="btn btn-bd w-100" style="min-height: 44px; font-size: 0.68rem;"
                                    data-bs-toggle="modal" data-bs-target="#contactExchangeModal{{ $ex->id }}">
                                <i class="fa-solid fa-handshake me-1"></i> Connect for Exchange
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Contact Modal for Exchange -->
                <div class="modal fade" id="contactExchangeModal{{ $ex->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                            <div class="modal-header bg-danger text-white py-3 px-4">
                                <h5 class="modal-title fw-bold mb-0"><i class="fa-solid fa-handshake me-2"></i>Contact Exchange Attendant</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="p-3 mb-3 rounded-3 bg-light text-center">
                                    <div class="text-muted small text-uppercase fw-bold mb-1">Exchange Requirement</div>
                                    <h4 class="fw-bold mb-0">
                                        <span class="text-danger">{{ $ex->offered_blood_group }}</span>
                                        <i class="fa-solid fa-arrow-right-arrow-left mx-2 text-muted fs-6"></i>
                                        <span class="text-primary">{{ $ex->needed_blood_group }}</span>
                                    </h4>
                                    <div class="small text-muted mt-1">{{ $ex->units }} Unit(s) required</div>
                                </div>

                                <div class="mb-3">
                                    <label class="small text-muted text-uppercase fw-bold">Attendant / Contact Person</label>
                                    <div class="fs-6 fw-bold text-dark">{{ $ex->contact_name }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="small text-muted text-uppercase fw-bold">Patient & Hospital</label>
                                    <div class="text-dark fw-semibold">{{ $ex->patient_name }} ({{ $ex->hospital }}, {{ $ex->city }})</div>
                                </div>
                                <div class="mb-3">
                                    <label class="small text-muted text-uppercase fw-bold">Phone Number</label>
                                    <div class="fs-5 fw-bold text-danger">{{ $ex->contact_phone }}</div>
                                </div>

                                <div class="d-grid gap-2 pt-2">
                                    <a href="tel:{{ $ex->contact_phone }}" class="btn btn-success fw-bold py-2 rounded-pill">
                                        <i class="fa-solid fa-phone me-1"></i> Call Attendant ({{ $ex->contact_phone }})
                                    </a>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ex->contact_phone) }}?text={{ urlencode('Hello, I saw your blood exchange offer (' . $ex->offered_blood_group . ' for ' . $ex->needed_blood_group . ') on BE.DONOR.') }}"
                                       target="_blank" rel="noopener" class="btn btn-outline-success fw-bold py-2 rounded-pill">
                                        <i class="fa-brands fa-whatsapp me-1"></i> Message on WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="bd-panel text-center py-5">
                    <i class="fa-solid fa-right-left text-danger fs-1 mb-3"></i>
                    <h5 class="fw-bold text-uppercase">No active blood exchange offers found</h5>
                    <p class="text-muted small">Be the first to post a blood exchange offer for your group.</p>
                    <a href="{{ route('blood-exchanges.create') }}" class="btn btn-bd mt-2">
                        <i class="fa-solid fa-plus me-1"></i> Post Exchange Offer
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if($exchanges->hasPages())
        <div class="mt-5 text-center">
            {{ $exchanges->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<footer class="bd-foot py-4 text-center mt-5" style="background: var(--red-3); color: rgba(255,255,255,0.7);">
    <div class="container small">
        &copy; {{ date('Y') }} BE.DONOR - Blood Donor & Mutual Exchange Network
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

