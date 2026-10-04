<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Blood Donors</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --red: #b3122b;
            --red-dark: #8c0d21;
            --rose: #fdebee;
            --ink: #1c2130;
            --muted: #6b7280;
            --line: #e8e9ee;
            --bg: #f5f6fa;
            --green: #1a9d5b;
        }
        body { font-family: 'Manrope', system-ui, sans-serif; background: var(--bg); color: var(--ink); }

        /* Top bar */
        .topbar { background: var(--red); box-shadow: 0 2px 12px rgba(140, 13, 33, .25); }
        .topbar .brand { color: #fff; font-weight: 800; font-size: 1.15rem; text-decoration: none; letter-spacing: -.2px; }
        .topbar .btn-join { background: #fff; color: var(--red); font-weight: 700; border: 0; border-radius: 999px; padding: .45rem 1.1rem; }
        .topbar .btn-join:hover { background: var(--rose); color: var(--red-dark); }

        /* Hero */
        .hero { background: linear-gradient(180deg, var(--red) 0%, var(--red-dark) 100%); color: #fff; padding: 2.5rem 0 5.5rem; }
        .hero h1 { font-weight: 800; font-size: clamp(1.7rem, 4vw, 2.4rem); letter-spacing: -.5px; margin-bottom: .4rem; }
        .hero p { color: rgba(255, 255, 255, .8); margin: 0; }

        /* Search panel overlaps hero */
        .search-panel { margin-top: -3.5rem; background: #fff; border-radius: 18px; padding: 1.25rem; box-shadow: 0 12px 32px rgba(28, 33, 48, .12); }
        .search-panel .form-label { font-size: .8rem; font-weight: 700; color: var(--muted); margin-bottom: .3rem; }
        .search-panel .form-select, .search-panel .form-control { height: 48px; border-radius: 12px; border: 1.5px solid var(--line); font-weight: 500; }
        .search-panel .form-select:focus, .search-panel .form-control:focus { border-color: var(--red); box-shadow: 0 0 0 .2rem rgba(179, 18, 43, .15); }
        .btn-search { height: 48px; background: var(--red); color: #fff; font-weight: 700; border: 0; border-radius: 12px; }
        .btn-search:hover { background: var(--red-dark); color: #fff; }
        .btn-reset { height: 48px; width: 48px; border: 1.5px solid var(--line); border-radius: 12px; color: var(--muted); background: #fff; display: inline-flex; align-items: center; justify-content: center; }
        .btn-reset:hover { border-color: var(--red); color: var(--red); }

        .result-count { color: var(--muted); font-size: .9rem; font-weight: 600; }

        /* Donor card */
        .donor-card { background: #fff; border: 1px solid var(--line); border-radius: 18px; overflow: hidden; height: 100%; transition: box-shadow .2s, transform .2s; }
        .donor-card:hover { box-shadow: 0 10px 26px rgba(28, 33, 48, .1); transform: translateY(-2px); }
        .donor-card .card-body { padding: 1.25rem; display: flex; flex-direction: column; height: 100%; }
        .donor-name { font-weight: 700; font-size: 1.1rem; margin: 0; }
        .donor-city { color: var(--muted); font-size: .88rem; font-weight: 500; }
        .contact-chip { display: inline-block; background: var(--rose); color: var(--red-dark); font-size: .74rem; font-weight: 600; padding: .2rem .6rem; border-radius: 999px; margin-top: .4rem; }

        /* Blood drop badge */
        .drop { width: 52px; height: 52px; min-width: 52px; background: var(--red); border-radius: 50% 50% 50% 0; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 14px rgba(179, 18, 43, .3); margin: 4px 4px 0 0; }
        .drop span { transform: rotate(45deg); color: #fff; font-weight: 800; font-size: .95rem; }

        /* Contact buttons */
        .btn-call, .btn-wa { border-radius: 10px; font-weight: 700; font-size: .88rem; padding: .55rem; border: 0; }
        .btn-call { background: var(--bg); color: var(--ink); }
        .btn-call:hover { background: var(--line); color: var(--ink); }
        .btn-wa { background: var(--green); color: #fff; }
        .btn-wa:hover { background: #148049; color: #fff; }

        /* Owner action panel */
        .donor-action-panel { border-top: 1px dashed var(--line); margin-top: .9rem; padding-top: .9rem; }
        .btn-donated { background: var(--rose); color: var(--red-dark); font-weight: 700; border: 1.5px solid var(--red); border-radius: 10px; }
        .btn-donated:hover { background: var(--red); color: #fff; }
        .btn-pending { background: #fff4d6; color: #8a6100; font-weight: 700; border: 0; border-radius: 10px; }

        /* Pagination */
        .pager-wrap nav { display: flex; flex-direction: column; align-items: center; gap: .75rem; }
        .pager-wrap nav > div:first-child { display: none; } /* hide the small mobile-only block */
        .pager-wrap nav > div:last-child { display: flex; flex-direction: column; align-items: center; gap: .75rem; }
        .pager-wrap p.small { margin: 0; color: var(--muted); font-weight: 600; }
        .pagination { gap: .4rem; margin: 0; flex-wrap: wrap; justify-content: center; }
        .pagination .page-link { min-width: 42px; height: 42px; display: inline-flex; align-items: center; justify-content: center; border: 1.5px solid var(--line); border-radius: 12px !important; color: var(--ink); font-weight: 700; background: #fff; }
        .pagination .page-link:hover { background: var(--rose); border-color: var(--red); color: var(--red); }
        .pagination .page-link:focus { box-shadow: 0 0 0 .2rem rgba(179, 18, 43, .2); }
        .pagination .page-item.active .page-link { background: var(--red); border-color: var(--red); color: #fff; box-shadow: 0 6px 14px rgba(179, 18, 43, .3); }
        .pagination .page-item.disabled .page-link { background: var(--bg); color: #b5b8c2; border-color: var(--line); }

        .empty { background: #fff; border: 1px dashed var(--line); border-radius: 18px; padding: 3rem 1rem; text-align: center; color: var(--muted); }
        .empty i { font-size: 2.4rem; color: var(--red); margin-bottom: .75rem; }

        a:focus-visible, button:focus-visible { outline: 3px solid rgba(179, 18, 43, .4); outline-offset: 2px; }
    </style>
</head>
<body>

<nav class="topbar py-3">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="brand" href="{{ url('/') }}"><i class="fa-solid fa-droplet me-2"></i>Blood Donor Network</a>
        <a href="{{ route('donors.public.create') }}" class="btn btn-join btn-sm">
            <i class="fa-solid fa-hand-holding-heart me-1"></i> Become a Donor
        </a>
    </div>
</nav>

<section class="hero">
    <div class="container text-center">
        <h1>Find a blood donor near you</h1>
        <p>Search by blood group and city, then call or message the donor directly.</p>
    </div>
</section>

<div class="container pb-5">

    <!-- Search -->
    <form method="GET" action="{{ route('donors.public.search') }}" class="search-panel row g-3 align-items-end mx-0">
        <div class="col-md-5">
            <label class="form-label">Blood group</label>
            <select name="blood_group" class="form-select">
                <option value="">All blood groups</option>
                @foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $group)
                    <option value="{{ $group }}" {{ request('blood_group') == $group ? 'selected' : '' }}>{{ $group }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-5">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" placeholder="e.g. Sargodha, Lahore" value="{{ request('city') }}">
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-search flex-grow-1"><i class="fa-solid fa-magnifying-glass me-1"></i> Search</button>
            <a href="{{ route('donors.public.search') }}" class="btn-reset" title="Reset filters"><i class="fa-solid fa-rotate-left"></i></a>
        </div>
    </form>

    <div class="result-count my-4">
        {{ $donors->total() }} {{ Str::plural('donor', $donors->total()) }} found
    </div>

    <!-- Donor cards -->
    <div class="row g-4">
        @forelse ($donors as $donor)
            <div class="col-md-6 col-lg-4">
                <div class="donor-card" data-donor-id="{{ $donor->id }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="donor-name">{{ $donor->name }}</h5>
                                @if($donor->gender === 'Female')
                                    <span class="contact-chip"><i class="fa-solid fa-user-shield me-1"></i>Contact: {{ $donor->display_contact_name }}</span>
                                @endif
                            </div>
                            <div class="drop"><span>{{ $donor->blood_group }}</span></div>
                        </div>

                        <p class="donor-city mb-3"><i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $donor->city }}</p>

                        <div class="d-flex gap-2 mt-auto">
                            <a href="tel:{{ $donor->display_phone }}" class="btn btn-call flex-grow-1">
                                <i class="fa-solid fa-phone me-1"></i> Call
                            </a>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $donor->display_phone) }}?text={{ urlencode('Hello, I urgently need ' . $donor->blood_group . ' blood.') }}"
                               target="_blank" rel="noopener" class="btn btn-wa flex-grow-1">
                                <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
                            </a>
                        </div>

                        {{-- Owner-only panel: hidden until JS confirms this browser registered this donor --}}
                        <div class="donor-action-panel d-none text-center">
                            @if($donor->donation_pending)
                                <button class="btn btn-sm btn-pending w-100" disabled>
                                    <i class="fa-solid fa-clock me-1"></i> Verification pending
                                </button>
                            @elseif($donor->is_eligible)
                                <button type="button" class="btn btn-sm btn-donated w-100" onclick="triggerDonatedToday({{ $donor->id }})">
                                    <i class="fa-solid fa-heart-circle-check me-1"></i> I donated today
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty">
                    <i class="fa-solid fa-droplet-slash"></i>
                    <h5 class="fw-bold text-dark">No donors found</h5>
                    <p class="mb-0">Try a different blood group or city, or reset the filters.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($donors->hasPages())
        <div class="pager-wrap mt-5">
            {{ $donors->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<script>
const csrf    = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const myToken = localStorage.getItem('my_donor_token');
const myId    = localStorage.getItem('my_donor_id');

document.addEventListener('DOMContentLoaded', () => {
    if (!myToken || !myId) return;
    document.querySelectorAll('.donor-card').forEach(card => {
        if (card.dataset.donorId === myId) {
            card.querySelector('.donor-action-panel')?.classList.remove('d-none');
        }
    });
});

async function triggerDonatedToday(donorId) {
    if (!confirm('Have you really donated blood today? A verification notification will be sent to the admin.')) return;

    try {
        const res = await fetch(`/donors/${donorId}/notify-donation`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Donor-Token': myToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ donor_token: myToken })
        });
        const data = await res.json();
        alert(data.message || 'Done');
        if (res.ok) location.reload();
    } catch (e) {
        alert('Could not reach the server.');
    }
}
</script>
</body>
</html>