@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    /* Dashboard-only styles (shared styles live in layouts/app.blade.php) */

    /* Stats: numbered cards in the BE.DONOR style */
    .adm .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; }
    .adm .stat-cell {
        position: relative; overflow: hidden; display: flex; align-items: center; gap: 1rem;
        padding: 1.35rem 1.4rem; background: #fff; border: 1px solid var(--line); border-radius: 20px;
        box-shadow: 0 1px 2px rgba(60, 0, 0, .04), 0 22px 44px -30px rgba(110, 0, 0, .3);
    }
    .adm .stat-cell::after {
        content: ""; position: absolute; left: 1.4rem; right: 1.4rem; bottom: 0; height: 2px;
        background: linear-gradient(90deg, var(--red), transparent); opacity: .5;
    }
    .adm .stat .ico {
        width: 50px; height: 50px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1.05rem;
    }
    .adm .stat .num { font-size: 2.1rem; font-weight: 800; line-height: 1; letter-spacing: -.02em; font-variant-numeric: tabular-nums; }
    .adm .stat .lbl { color: var(--muted); font-size: .58rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; margin-top: .5rem; line-height: 1.5; }
    .adm .ico-red { background: var(--red); color: #fff; box-shadow: 0 12px 20px -10px rgba(179, 0, 0, .8); }
    .adm .ico-green { background: #e9f7ef; color: var(--green); border: 1px solid #cdebd9; }
    .adm .ico-amber { background: #fff4d6; color: #b27a00; border: 1px solid #f6e2a6; }
    .adm .stat-cell.has-pending { border-color: #f6e2a6; background: linear-gradient(135deg, #fffaf0, #fff 60%); }
    .adm .stat-cell.has-pending .num { color: #8a6100; }

    /* Pending panel gets visual priority when there is work to do */
    .adm .panel-alert { border-color: var(--rose-line); box-shadow: 0 1px 2px rgba(179, 0, 0, .06), 0 26px 50px -30px rgba(179, 0, 0, .45); }
    .adm .panel-alert .panel-head { background: linear-gradient(90deg, var(--rose), #fff 70%); border-bottom-color: var(--rose-line); }
    .adm .panel-head .head-ico {
        width: 34px; height: 34px; border-radius: 50%; margin-right: .7rem; font-size: .8rem;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid var(--red); color: var(--red); background: #fff;
    }

    /* Donor cell */
    .adm .donor-cell { display: flex; align-items: center; gap: .85rem; }
    .adm .avatar {
        width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center;
        background: var(--rose); color: var(--red); border: 1px solid var(--rose-line); font-weight: 800; font-size: .85rem;
    }
    .adm .donor-name { font-weight: 700; line-height: 1.2; }
    .adm .phone-link { color: var(--ink); text-decoration: none; font-weight: 500; font-variant-numeric: tabular-nums; }
    .adm .phone-link:hover { color: var(--red); }
    .adm .units-badge { font-size: .6rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; border-radius: 999px !important; padding: .4rem .7rem; }
    .adm .urgency-badge { font-size: .6rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; border-radius: 999px !important; padding: .4rem .75rem; background: var(--rose) !important; color: var(--red) !important; border-color: var(--rose-line) !important; }
    .adm .dashboard-fit-table { width: 100%; table-layout: fixed; }
    .adm .dashboard-fit-table th { white-space: normal; overflow-wrap: anywhere; }
    .adm .dashboard-fit-table td { overflow-wrap: anywhere; }
    .adm .dashboard-fit-table .donor-name { white-space: normal; }
    .adm .dashboard-fit-table .d-inline-flex { flex-wrap: wrap; }

    /* Empty state */
    .adm .empty-state { text-align: center; padding: 3rem 1rem; color: var(--muted); font-size: .82rem; }
    .adm .empty-state .ring {
        width: 58px; height: 58px; border-radius: 50%; margin-bottom: .8rem; font-size: 1.3rem;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid var(--green); color: var(--green);
    }
    .adm .empty-state strong { display: block; color: var(--ink); font-weight: 800; font-size: .78rem; letter-spacing: .12em; text-transform: uppercase; margin-bottom: .2rem; }

    @media (max-width: 1199.98px) { .adm .stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 767.98px) {
        .adm .stats { grid-template-columns: 1fr; }
        .adm .stat-cell { padding: 1.1rem 1.25rem; }
        .adm table thead th, .adm table tbody td { padding-left: 1rem; padding-right: 1rem; }
    }
</style>


@php $pendingDonors = $pendingDonors ?? collect(); @endphp

<div class="adm">

    <div class="page-hero d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4" data-mark="BLOOD">
        <div class="rbc-field" aria-hidden="true">
        <span class="rbc" style="--x:62%;--y:12%;--s:clamp(70px,8vw,120px);--rx:60deg;--ry:-18deg;--rz:20deg;--blur:1.5px;--dur:11s;"><i></i></span>
        <span class="rbc" style="--x:78%;--y:46%;--s:clamp(90px,11vw,170px);--rx:64deg;--ry:10deg;--rz:-12deg;--dur:12s;--delay:-4s;--fx:-12px;--fy:-16px;"><i style="--spin:38s"></i></span>
        <span class="rbc" style="--x:88%;--y:6%;--s:clamp(40px,4vw,60px);--rx:48deg;--ry:22deg;--rz:-30deg;--blur:3px;--dur:9s;"><i></i></span>
        <span class="rbc" style="--x:68%;--y:72%;--s:clamp(50px,5vw,80px);--rx:52deg;--ry:-25deg;--rz:40deg;--blur:1px;--dur:13s;--delay:-2s;"><i></i></span>
        <span class="rbc" style="--x:6%;--y:70%;--s:clamp(36px,3.5vw,54px);--rx:55deg;--ry:-20deg;--rz:30deg;--blur:4px;--dur:10s;--delay:-6s;"><i></i></span>
    </div>
        <div>
            <div class="page-eyebrow"><b>01</b> Dashboard</div>
            <h2 class="h4 page-title">System Overview</h2>
            <p class="page-sub">Donor statistics and donation requests waiting for your review.</p>
        </div>
        <a href="{{ route('donors.index') }}" class="btn btn-brand btn-sm">
            <i class="fa-solid fa-list me-1"></i> Manage Donors
        </a>
    </div>

    @foreach (['success' => 'success', 'info' => 'info', 'error' => 'danger'] as $key => $type)
        @if(session($key))
            <div class="alert alert-{{ $type }}" role="alert">{{ session($key) }}</div>
        @endif
    @endforeach

    <!-- Stats -->
    <section class="stats stat mb-4" aria-label="Donor statistics">
        <div class="stat-cell">
            <div class="ico ico-red"><i class="fa-solid fa-users"></i></div>
            <div>
                <div class="num">{{ $totalDonors }}</div>
                <div class="lbl">Total registered donors</div>
            </div>
        </div>
        <div class="stat-cell">
            <div class="ico ico-green"><i class="fa-solid fa-heart-pulse"></i></div>
            <div>
                <div class="num">{{ $availableDonors }}</div>
                <div class="lbl">Available to donate</div>
            </div>
        </div>
        <div class="stat-cell {{ $pendingDonors->count() ? 'has-pending' : '' }}">
            <div class="ico ico-amber"><i class="fa-solid fa-clock"></i></div>
            <div>
                <div class="num">{{ $pendingDonors->count() }}</div>
                <div class="lbl">{{ $pendingDonors->count() === 1 ? 'Donation awaiting review' : 'Donations awaiting review' }}</div>
            </div>
        </div>
        <div class="stat-cell {{ ($openRequestsCount ?? 0) > 0 ? 'has-pending' : '' }}">
            <div class="ico ico-red"><i class="fa-solid fa-bullhorn"></i></div>
            <div>
                <div class="num">{{ $openRequestsCount ?? 0 }}</div>
                <div class="lbl">Open Urgent Requests</div>
            </div>
        </div>
        <div class="stat-cell {{ ($pendingContactRequestsCount ?? 0) > 0 ? 'has-pending' : '' }}">
            <div class="ico ico-amber"><i class="fa-solid fa-envelope-open-text"></i></div>
            <div>
                <div class="num">{{ $pendingContactRequestsCount ?? 0 }}</div>
                <div class="lbl">Contact Requests Pending</div>
            </div>
        </div>
        <div class="stat-cell {{ ($pendingExchangesCount ?? 0) > 0 ? 'has-pending' : '' }}">
            <div class="ico ico-red"><i class="fa-solid fa-right-left"></i></div>
            <div>
                <div class="num">{{ $pendingExchangesCount ?? 0 }}</div>
                <div class="lbl">Blood Exchanges Pending</div>
            </div>
        </div>
    </section>

    <!-- Pending donation requests -->
    <section class="panel mb-4 {{ $pendingDonors->count() ? 'panel-alert' : '' }}" aria-labelledby="pending-title">
        <div class="panel-head">
            <h6 id="pending-title" class="d-flex align-items-center">
                <span class="head-ico"><i class="fa-solid fa-bell"></i></span>Pending Donation Requests
            </h6>
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
                            <td>
                                <div class="donor-cell">
                                    <span class="avatar" aria-hidden="true">{{ strtoupper(mb_substr($donor->name, 0, 1)) }}</span>
                                    <span class="donor-name">{{ $donor->name }}</span>
                                </div>
                            </td>
                            <td><span class="group-pill">{{ $donor->blood_group }}</span></td>
                            <td>{{ $donor->city }}</td>
                            <td><a href="tel:{{ $donor->phone }}" class="phone-link">{{ $donor->phone }}</a></td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <form action="{{ route('donors.confirmDonation', $donor->id) }}" method="POST"
                                          data-ajax-form="true"
                                          data-confirm="Confirm this donation? The donor will move to a 90-day cooldown."
                                          data-success-message="Donation confirmed successfully."
                                          data-remove-row="true">
                                        @csrf
                                        <button class="btn btn-confirm"><i class="fa-solid fa-check me-1"></i>Confirm</button>
                                    </form>
                                    <form action="{{ route('donors.rejectDonation', $donor->id) }}" method="POST"
                                          data-ajax-form="true"
                                          data-confirm="Reject this donation request?"
                                          data-success-message="Donation request rejected."
                                          data-remove-row="true">
                                        @csrf
                                        <button class="btn btn-reject"><i class="fa-solid fa-xmark me-1"></i>Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-0">
                                <div class="empty-state">
                                    <span class="ring"><i class="fa-solid fa-check"></i></span>
                                    <strong>All caught up</strong>
                                    No pending requests right now.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Urgent Blood Requests Live Feed -->
    <section class="panel mb-4" aria-labelledby="urgent-req-title">
        <div class="panel-head">
            <h6 id="urgent-req-title" class="d-flex align-items-center">
                <span class="head-ico"><i class="fa-solid fa-bullhorn"></i></span>
                Active Urgent Blood Requests
            </h6>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('blood-requests.create') }}" class="btn btn-brand btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> Post Urgent Need
                </a>
                <a href="{{ route('blood-requests.index') }}" class="btn btn-soft btn-sm" target="_blank">
                    Public Feed <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Group</th>
                        <th>Units</th>
                        <th>Hospital & City</th>
                        <th>Attendant / Phone</th>
                        <th>Urgency</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($openRequests ?? [] as $req)
                        <tr>
                            <td><strong>{{ $req->patient_name }}</strong></td>
                            <td><span class="group-pill">{{ $req->blood_group }}</span></td>
                            <td><span class="badge bg-light text-dark border units-badge">{{ $req->units_needed }} {{ Str::plural('unit', $req->units_needed) }}</span></td>
                            <td>
                                <div>{{ $req->hospital }}</div>
                                <small class="text-muted"><i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $req->city }}</small>
                            </td>
                            <td>
                                <div>{{ $req->contact_name }}</div>
                                <a href="tel:{{ $req->contact_phone }}" class="phone-link small">{{ $req->contact_phone }}</a>
                            </td>
                            <td>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle urgency-badge">
                                    {{ $req->urgency_level }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <form action="{{ route('blood-requests.fulfill', $req->id) }}" method="POST"
                                          data-ajax-form="true"
                                          data-confirm="Mark this blood request as fulfilled?"
                                          data-success-message="Blood request marked as fulfilled."
                                          data-remove-row="true">
                                        @csrf
                                        <button class="btn btn-confirm" title="Mark Blood Arranged / Fulfilled">
                                            <i class="fa-solid fa-check me-1"></i>Fulfilled
                                        </button>
                                    </form>
                                    <form action="{{ route('blood-requests.destroy', $req->id) }}" method="POST"
                                          data-ajax-form="true"
                                          data-confirm="Delete this request permanently?"
                                          data-success-message="Blood request deleted."
                                          data-remove-row="true">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-reject" title="Delete request">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-0">
                                <div class="empty-state">
                                    <span class="ring"><i class="fa-solid fa-heart"></i></span>
                                    <strong>No Active Emergency Requests</strong>
                                    All current blood requirements have been met.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Feature 1: Pending Donor Contact Requests Panel -->
    <section class="panel mb-4 {{ ($pendingContactRequestsCount ?? 0) ? 'panel-alert' : '' }}" aria-labelledby="contacts-title">
        <div class="panel-head">
            <h6 id="contacts-title" class="d-flex align-items-center">
                <span class="head-ico"><i class="fa-solid fa-envelope-open-text"></i></span>
                Pending Donor Contact Requests
            </h6>
            <div class="d-flex align-items-center gap-2">
                @if($pendingContactRequestsCount ?? 0)
                    <span class="tag tag-wait">{{ $pendingContactRequestsCount }} pending</span>
                @endif
                <a href="{{ route('admin.contact-requests.index') }}" class="btn btn-soft btn-sm">View all</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle dashboard-fit-table">
                <thead>
                    <tr>
                        <th>Requester</th>
                        <th>Patient & Location</th>
                        <th>Target Donor</th>
                        <th>Urgency</th>
                        <th>Message</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingContactRequests ?? [] as $req)
                        <tr>
                            <td>
                                <strong>{{ $req->requester_name }}</strong>
                                <div class="text-muted small"><a href="tel:{{ $req->requester_phone }}" class="phone-link">{{ $req->requester_phone }}</a></div>
                            </td>
                            <td>
                                <div><strong>{{ $req->patient_name }}</strong></div>
                                <div class="text-muted small"><i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $req->hospital }}, {{ $req->city }}</div>
                            </td>
                            <td>
                                <div class="donor-cell">
                                    <span class="avatar" aria-hidden="true">{{ strtoupper(mb_substr($req->donor->name ?? 'D', 0, 1)) }}</span>
                                    <div>
                                        <div class="donor-name">{{ $req->donor->name ?? 'N/A' }}</div>
                                        <span class="group-pill" style="padding:.15rem .5rem;font-size:.65rem;">{{ $req->donor->blood_group ?? '' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle urgency-badge">{{ $req->urgency_level }}</span>
                            </td>
                            <td style="max-width: 200px;">
                                <div class="text-truncate small text-muted" title="{{ $req->message }}">"{{ $req->message }}"</div>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <form action="{{ route('admin.contact-requests.approve', $req->id) }}" method="POST"
                                          onsubmit="return handleDashboardContactAction(event, this, 'approve')">
                                        @csrf
                                        <button class="btn btn-confirm" title="Approve Request">
                                            <i class="fa-solid fa-check me-1"></i>Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.contact-requests.reject', $req->id) }}" method="POST"
                                          onsubmit="return handleDashboardContactAction(event, this, 'reject')">
                                        @csrf
                                        <input type="hidden" name="admin_notes" value="Request was declined by administration due to verification checks.">
                                        <button class="btn btn-reject" title="Reject Request">
                                            <i class="fa-solid fa-xmark me-1"></i>Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-0">
                                <div class="empty-state">
                                    <span class="ring"><i class="fa-solid fa-envelope-circle-check"></i></span>
                                    <strong>No Pending Contact Requests</strong>
                                    All donor contact requests have been mediated and reviewed.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Pending Blood Exchange Requests Panel -->
    <section class="panel mb-4 {{ ($pendingExchangesCount ?? 0) ? 'panel-alert' : '' }}" aria-labelledby="exchanges-title">
        <div class="panel-head">
            <h6 id="exchanges-title" class="d-flex align-items-center">
                <span class="head-ico"><i class="fa-solid fa-right-left"></i></span>
                Pending Blood Exchange Offers
            </h6>
            <div class="d-flex align-items-center gap-2">
                @if($pendingExchangesCount ?? 0)
                    <span class="tag tag-wait">{{ $pendingExchangesCount }} pending</span>
                @endif
                <a href="{{ route('admin.blood-exchanges.index') }}" class="btn btn-soft btn-sm">View all</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle dashboard-fit-table">
                <thead>
                    <tr>
                        <th>Offered → Needed</th>
                        <th>Units</th>
                        <th>Patient</th>
                        <th>Hospital & City</th>
                        <th>Contact</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingExchanges ?? [] as $exc)
                        <tr>
                            <td>
                                <span class="group-pill" style="background:#15803d;">{{ $exc->offered_blood_group }}</span>
                                <i class="fa-solid fa-arrow-right mx-1 text-muted"></i>
                                <span class="group-pill">{{ $exc->needed_blood_group }}</span>
                            </td>
                            <td><span class="badge bg-light text-dark border units-badge">{{ $exc->units }} {{ Str::plural('unit', $exc->units) }}</span></td>
                            <td><strong>{{ $exc->patient_name }}</strong></td>
                            <td>
                                <div>{{ $exc->hospital }}</div>
                                <small class="text-muted"><i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $exc->city }}</small>
                            </td>
                            <td>
                                <div>{{ $exc->contact_name }}</div>
                                <a href="tel:{{ $exc->contact_phone }}" class="phone-link small">{{ $exc->contact_phone }}</a>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <form action="{{ route('admin.blood-exchanges.approve', $exc->id) }}" method="POST"
                                          data-ajax-form="true"
                                          data-confirm="Approve this exchange offer and make it live on the exchange board?"
                                          data-success-message="Exchange offer approved and made live."
                                          data-remove-row="true">
                                        @csrf
                                        <button class="btn btn-confirm">
                                            <i class="fa-solid fa-check me-1"></i>Make Live
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.blood-exchanges.reject', $exc->id) }}" method="POST"
                                          data-ajax-form="true"
                                          data-confirm="Reject this exchange offer?"
                                          data-success-message="Exchange offer rejected."
                                          data-remove-row="true">
                                        @csrf
                                        <input type="hidden" name="admin_notes" value="Declined by administrator.">
                                        <button class="btn btn-reject">
                                            <i class="fa-solid fa-xmark me-1"></i>Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-0">
                                <div class="empty-state">
                                    <span class="ring"><i class="fa-solid fa-right-left"></i></span>
                                    <strong>No Pending Exchange Offers</strong>
                                    All submitted blood exchange offers have been processed.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Recent donors -->
    <section class="panel" aria-labelledby="recent-title">
        <div class="panel-head">
            <h6 id="recent-title">Recently Added Donors</h6>
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
                            <td>
                                <div class="donor-cell">
                                    <span class="avatar" aria-hidden="true">{{ strtoupper(mb_substr($donor->name, 0, 1)) }}</span>
                                    <span class="donor-name">{{ $donor->name }}</span>
                                </div>
                            </td>
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
    </section>

</div>

@push('scripts')
<script>
    async function handleDashboardContactAction(event, form, action) {
        event.preventDefault();
        const actionMsg = action === 'approve' 
            ? 'Approve this contact request? Mediated communication will be initiated with the donor.' 
            : 'Reject this contact request?';
            
        if (!confirm(actionMsg)) return false;

        const btn = form.querySelector('button');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const row = form.closest('tr');

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(form)
            });
            const data = await res.json();
            if (res.ok && data.success) {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                setTimeout(() => {
                    row.remove();
                }, 300);

                // Update sidebar contact request badge
                document.querySelectorAll('.nav-badge').forEach(badge => {
                    if (badge.getAttribute('title') && badge.getAttribute('title').toLowerCase().includes('contact')) {
                        if (typeof data.pending_count !== 'undefined') {
                            if (data.pending_count > 0) {
                                badge.innerText = data.pending_count;
                                badge.style.display = 'inline-flex';
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                    }
                });
            } else {
                alert(data.message || 'Action failed.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        } catch (e) {
            console.error(e);
            alert('A network error occurred.');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }
</script>
@endpush
@endsection