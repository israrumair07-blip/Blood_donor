@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>

    .adm {
        --red: #b3122b; --red-dark: #8c0d21; --rose: #fdebee;
        --ink: #1c2130; --muted: #6b7280; --line: #e8e9ee; --bg: #f5f6fa;
        font-family: 'Manrope', system-ui, sans-serif; color: var(--ink);
    }
    .adm .page-title { font-weight: 800; letter-spacing: -.4px; margin: 0; }
    .adm .page-sub { color: var(--muted); font-size: .9rem; margin: 0; }

    .adm .btn-brand { background: var(--red); color: #fff; border: 0; border-radius: 10px; font-weight: 700; padding: .55rem 1.1rem; }
    .adm .btn-brand:hover { background: var(--red-dark); color: #fff; }
    .adm .btn-soft { background: #fff; color: var(--ink); border: 1.5px solid var(--line); border-radius: 10px; font-weight: 700; }
    .adm .btn-soft:hover { border-color: var(--red); color: var(--red); }

    .adm .panel { background: #fff; border: 1px solid var(--line); border-radius: 16px; box-shadow: 0 4px 16px rgba(28, 33, 48, .05); overflow: hidden; }
    .adm .panel-head { padding: 1rem 1.25rem; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
    .adm .panel-head h6 { margin: 0; font-weight: 800; }

    /* Stat cards */
    .adm .stat { background: #fff; border: 1px solid var(--line); border-radius: 16px; padding: 1.1rem 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 16px rgba(28, 33, 48, .05); }
    .adm .stat .ico { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .adm .stat .num { font-size: 1.8rem; font-weight: 800; line-height: 1; }
    .adm .stat .lbl { color: var(--muted); font-size: .82rem; font-weight: 600; margin-top: .25rem; }
    .adm .ico-red { background: var(--rose); color: var(--red); }
    .adm .ico-green { background: #e6f6ee; color: #1a9d5b; }
    .adm .ico-amber { background: #fff4d6; color: #b27a00; }

    /* Tables */
    .adm table { margin: 0; }
    .adm table thead th { font-size: .72rem; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); background: #fafbfd; border-bottom: 1px solid var(--line); font-weight: 700; padding: .8rem 1rem; white-space: nowrap; }
    .adm table tbody td { padding: .85rem 1rem; border-color: var(--line); vertical-align: middle; }
    .adm table tbody tr:hover { background: #fcfcfe; }

    .adm .group-pill { display: inline-block; min-width: 42px; text-align: center; background: var(--red); color: #fff; font-weight: 800; font-size: .82rem; padding: .25rem .6rem; border-radius: 999px; }
    .adm .tag { display: inline-block; font-size: .76rem; font-weight: 700; padding: .28rem .7rem; border-radius: 999px; }
    .adm .tag-ok { background: #e6f6ee; color: #146c43; }
    .adm .tag-off { background: #eceef3; color: #5a6072; }
    .adm .tag-wait { background: #fff4d6; color: #8a6100; }
    .adm .tag-cool { background: #e7f0ff; color: #1d4ed8; }

    .adm .icon-btn { width: 34px; height: 34px; border-radius: 10px; border: 1.5px solid var(--line); background: #fff; color: var(--muted); display: inline-flex; align-items: center; justify-content: center; transition: .15s; }
    .adm .icon-btn:hover { border-color: var(--red); color: var(--red); background: var(--rose); }
    .adm .icon-btn.edit:hover { border-color: #2563eb; color: #2563eb; background: #eef4ff; }
    .adm .btn-donated { background: var(--rose); color: var(--red-dark); border: 1.5px solid #f3bcc6; border-radius: 10px; font-weight: 700; font-size: .8rem; padding: .35rem .7rem; }
    .adm .btn-donated:hover { background: var(--red); color: #fff; border-color: var(--red); }
    .adm .btn-confirm { background: #1a9d5b; color: #fff; border: 0; border-radius: 10px; font-weight: 700; font-size: .8rem; padding: .4rem .8rem; }
    .adm .btn-confirm:hover { background: #148049; color: #fff; }
    .adm .btn-reject { background: #fff; color: var(--muted); border: 1.5px solid var(--line); border-radius: 10px; font-weight: 700; font-size: .8rem; padding: .4rem .8rem; }
    .adm .btn-reject:hover { border-color: var(--red); color: var(--red); }

    /* Forms */
    .adm .form-label { font-size: .82rem; font-weight: 700; }
    .adm .form-control, .adm .form-select { height: 44px; border-radius: 10px; border: 1.5px solid var(--line); font-weight: 500; }
    .adm .form-control:focus, .adm .form-select:focus { border-color: var(--red); box-shadow: 0 0 0 .2rem rgba(179, 18, 43, .15); }
    .adm .form-check-input:checked { background-color: var(--red); border-color: var(--red); }

    /* Alerts */
    .adm .alert { border: 0; border-radius: 12px; font-weight: 600; }
    .adm .alert-success { background: #e6f6ee; color: #146c43; }
    .adm .alert-info { background: #e7f0ff; color: #1d4ed8; }
    .adm .alert-danger { background: var(--rose); color: var(--red-dark); }
    .adm .alert-warning { background: #fff4d6; color: #8a6100; }

    /* Modals */
    .adm .modal-content { border: 0; border-radius: 18px; overflow: hidden; }
    .adm .modal-header.brand { background: var(--red); color: #fff; border: 0; }
    .adm .modal-header.edit { background: #1d4ed8; color: #fff; border: 0; }
    .adm .modal-footer { border-top: 1px solid var(--line); }

    /* Pagination */
    .adm .pager nav { display: flex; flex-direction: column; align-items: center; gap: .6rem; }
    .adm .pager nav > div:first-child { display: none; }
    .adm .pager nav > div:last-child { display: flex; flex-direction: column; align-items: center; gap: .6rem; }
    .adm .pager p.small { margin: 0; color: var(--muted); font-weight: 600; }
    .adm .pagination { gap: .35rem; margin: 0; flex-wrap: wrap; justify-content: center; }
    .adm .pagination .page-link { min-width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; border: 1.5px solid var(--line); border-radius: 10px !important; color: var(--ink); font-weight: 700; background: #fff; }
    .adm .pagination .page-link:hover { background: var(--rose); border-color: var(--red); color: var(--red); }
    .adm .pagination .page-item.active .page-link { background: var(--red); border-color: var(--red); color: #fff; }
    .adm .pagination .page-item.disabled .page-link { background: var(--bg); color: #b5b8c2; }

    .adm .empty-row { text-align: center; color: var(--muted); padding: 2.5rem 1rem !important; }
</style>


@php $pendingDonors = $pendingDonors ?? collect(); @endphp

<div class="adm">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="h4 page-title">System Overview</h2>
            <p class="page-sub">Donor statistics and donation requests waiting for your review.</p>
        </div>
        <a href="{{ route('donors.index') }}" class="btn btn-brand btn-sm">
            <i class="fa-solid fa-list me-1"></i> Manage Donors
        </a>
    </div>

    @foreach (['success' => 'success', 'info' => 'info', 'error' => 'danger'] as $key => $type)
        @if(session($key))
            <div class="alert alert-{{ $type }}">{{ session($key) }}</div>
        @endif
    @endforeach

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat">
                <div class="ico ico-red"><i class="fa-solid fa-users"></i></div>
                <div>
                    <div class="num">{{ $totalDonors }}</div>
                    <div class="lbl">Total registered donors</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat">
                <div class="ico ico-green"><i class="fa-solid fa-heart-pulse"></i></div>
                <div>
                    <div class="num">{{ $availableDonors }}</div>
                    <div class="lbl">Available to donate</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat">
                <div class="ico ico-amber"><i class="fa-solid fa-clock"></i></div>
                <div>
                    <div class="num">{{ $pendingDonors->count() }}</div>
                    <div class="lbl">{{ $pendingDonors->count() === 1 ? 'Donation awaiting verification' : 'Donations awaiting verification' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending donation requests -->
    <div class="panel mb-4">
        <div class="panel-head">
            <h6><i class="fa-solid fa-bell text-danger me-2"></i>Pending Donation Requests</h6>
            @if($pendingDonors->count())
                <span class="tag tag-wait">{{ $pendingDonors->count() }} pending</span>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Donor</th>
                        <th>Blood Group</th>
                        <th>City</th>
                        <th>Phone</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingDonors as $donor)
                        <tr>
                            <td class="fw-bold">{{ $donor->name }}</td>
                            <td><span class="group-pill">{{ $donor->blood_group }}</span></td>
                            <td>{{ $donor->city }}</td>
                            <td>{{ $donor->phone }}</td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <form action="{{ route('donors.confirmDonation', $donor->id) }}" method="POST"
                                          onsubmit="return confirm('Confirm this donation? The donor will move to a 90-day cooldown.')">
                                        @csrf
                                        <button class="btn btn-confirm"><i class="fa-solid fa-check me-1"></i>Confirm</button>
                                    </form>
                                    <form action="{{ route('donors.rejectDonation', $donor->id) }}" method="POST"
                                          onsubmit="return confirm('Reject this donation request?')">
                                        @csrf
                                        <button class="btn btn-reject"><i class="fa-solid fa-xmark me-1"></i>Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-row"><i class="fa-solid fa-circle-check text-success me-1"></i>No pending requests right now.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent donors -->
    <div class="panel">
        <div class="panel-head">
            <h6>Recently Added Donors</h6>
            <a href="{{ route('donors.index') }}" class="btn btn-soft btn-sm">View all</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Blood Group</th>
                        <th>City</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentDonors as $donor)
                        <tr>
                            <td class="fw-bold">{{ $donor->name }}</td>
                            <td><span class="group-pill">{{ $donor->blood_group }}</span></td>
                            <td>{{ $donor->city }}</td>
                            <td>
                                @if($donor->donation_pending)
                                    <span class="tag tag-wait">Pending</span>
                                @elseif(!$donor->is_eligible)
                                    <span class="tag tag-cool">Cooldown</span>
                                @elseif($donor->is_available)
                                    <span class="tag tag-ok">Available</span>
                                @else
                                    <span class="tag tag-off">Unavailable</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty-row">No donor records available yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection