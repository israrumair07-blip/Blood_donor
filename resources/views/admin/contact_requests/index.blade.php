@extends('layouts.app')

@section('title', 'Donor Contact Requests - Admin')

@section('content')
<style>
    /* Status Filter Tabs (Replacing clunky select dropdowns) */
    .filter-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        align-items: center;
    }
    .filter-tab {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .5rem 1rem;
        border-radius: 999px;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--muted);
        background: #fff;
        border: 1px solid var(--line);
        text-decoration: none;
        transition: all .2s ease;
    }
    .filter-tab:hover {
        border-color: var(--red);
        color: var(--red);
        background: #fff8f8;
    }
    .filter-tab.active {
        background: var(--red);
        border-color: var(--red);
        color: #fff;
        box-shadow: 0 8px 16px -6px rgba(179, 0, 0, .5);
    }
    .filter-tab .tab-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: .15rem .45rem;
        border-radius: 999px;
        font-size: .65rem;
        font-weight: 800;
        background: rgba(0, 0, 0, .08);
        color: inherit;
    }
    .filter-tab.active .tab-badge {
        background: rgba(255, 255, 255, .25);
        color: #fff;
    }

    /* Blood group chip buttons (Replacing blood group dropdown) */
    .bg-chips {
        display: flex;
        flex-wrap: wrap;
        gap: .35rem;
        align-items: center;
    }
    .bg-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 32px;
        padding: 0 .6rem;
        border-radius: 8px;
        font-size: .72rem;
        font-weight: 800;
        color: var(--ink);
        background: #fff;
        border: 1px solid var(--line);
        text-decoration: none;
        transition: all .15s ease;
    }
    .bg-chip:hover {
        border-color: var(--red);
        color: var(--red);
        background: var(--rose);
    }
    .bg-chip.active {
        background: var(--red);
        border-color: var(--red);
        color: #fff;
    }

    /* Responsive Table Styles (Prevent awkward vertical text dropping) */
    .requests-table {
        width: 100%;
        min-width: 1300px;
        margin-bottom: 0;
        table-layout: auto;
    }
    .requests-table th {
        font-size: .6rem;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--muted);
        background: #faf6f6;
        padding: .85rem 1rem;
        border-bottom: 1px solid var(--line);
    }
    .requests-table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--line);
        font-size: .84rem;
    }
    .requests-table .nowrap { white-space: normal; }
    .requests-table .d-inline-flex { flex-wrap: wrap; }
    .requests-table tbody tr:hover {
        background: #fffafa;
    }
    .req-id-badge {
        display: inline-block;
        font-weight: 800;
        font-size: .75rem;
        color: var(--red);
        background: var(--rose);
        border: 1px solid var(--rose-line);
        padding: .2rem .5rem;
        border-radius: 6px;
        white-space: nowrap;
    }
    .donor-meta-box {
        display: flex;
        align-items: center;
        gap: .65rem;
        min-width: 170px;
    }
    .donor-avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--rose);
        color: var(--red);
        border: 1px solid var(--rose-line);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: .82rem;
        flex-shrink: 0;
    }
    .nowrap {
        white-space: nowrap;
    }

    /* Toast Notification */
    .toast-popup {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 10500;
        min-width: 320px;
        padding: 1rem 1.25rem;
        border-radius: 14px;
        box-shadow: 0 16px 36px -10px rgba(0, 0, 0, .25);
        display: none;
        align-items: center;
        gap: .75rem;
        color: #fff;
        font-weight: 600;
        font-size: .85rem;
        animation: slideInToast .3s cubic-bezier(.16, 1, .3, 1);
    }
    @keyframes slideInToast {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .toast-success { background: #15803d; border-left: 4px solid #4ade80; }
    .toast-info { background: #1d4ed8; border-left: 4px solid #60a5fa; }
    .toast-error { background: #b91c1c; border-left: 4px solid #f87171; }
</style>

<div class="adm">

    <!-- Toast Notification Element -->
    <div id="ajaxToast" class="toast-popup toast-success" role="alert">
        <i class="fa-solid fa-circle-check fs-5" id="toastIcon"></i>
        <div id="toastText" class="flex-grow-1">Action completed successfully.</div>
        <button type="button" class="btn-close btn-close-white ms-2" onclick="hideToast()"></button>
    </div>

    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="adm-eyebrow"><b>Requests</b> Donor Mediation</div>
            <h2 class="h4 page-title mb-1">Donor Contact Requests</h2>
            <p class="page-sub mb-0">Review user contact requests, verify patient urgency, and approve mediated donor communication.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('donors.public.search') }}" target="_blank" class="btn btn-soft btn-sm">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Public Directory
            </a>
        </div>
    </div>

    <!-- Modern Filter Panel (Without clunky dropdowns) -->
    <div class="panel mb-4">
        <div class="panel-body p-3 p-md-4">
            
            <!-- Quick Status Pill Tabs (No select dropdown) -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pb-3 border-bottom border-light-subtle">
                <div class="filter-tabs">
                    <span class="text-uppercase small fw-bold text-muted me-1" style="font-size: 0.62rem; letter-spacing: 0.1em;">Status:</span>
                    <a href="{{ route('admin.contact-requests.index', array_merge(request()->except('status', 'page'), [])) }}" 
                       class="filter-tab {{ !request('status') ? 'active' : '' }}">
                        All Requests <span class="tab-badge" id="tab-all-badge">{{ $allCount }}</span>
                    </a>
                    <a href="{{ route('admin.contact-requests.index', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}" 
                       class="filter-tab {{ request('status') === 'pending' ? 'active' : '' }}">
                        <i class="fa-solid fa-circle text-warning me-1" style="font-size: 0.5rem;"></i> Pending
                        <span class="tab-badge" id="tab-pending-badge">{{ $pendingCount }}</span>
                    </a>
                    <a href="{{ route('admin.contact-requests.index', array_merge(request()->except('status', 'page'), ['status' => 'approved'])) }}" 
                       class="filter-tab {{ request('status') === 'approved' ? 'active' : '' }}">
                        <i class="fa-solid fa-circle text-success me-1" style="font-size: 0.5rem;"></i> Approved
                        <span class="tab-badge" id="tab-approved-badge">{{ $approvedCount }}</span>
                    </a>
                    <a href="{{ route('admin.contact-requests.index', array_merge(request()->except('status', 'page'), ['status' => 'rejected'])) }}" 
                       class="filter-tab {{ request('status') === 'rejected' ? 'active' : '' }}">
                        <i class="fa-solid fa-circle text-secondary me-1" style="font-size: 0.5rem;"></i> Rejected
                        <span class="tab-badge" id="tab-rejected-badge">{{ $rejectedCount }}</span>
                    </a>
                </div>

                <!-- Blood Group Quick Chips (No select dropdown) -->
                <div class="bg-chips">
                    <span class="text-uppercase small fw-bold text-muted me-1" style="font-size: 0.62rem; letter-spacing: 0.1em;">Group:</span>
                    <a href="{{ route('admin.contact-requests.index', array_merge(request()->except('blood_group', 'page'), [])) }}" 
                       class="bg-chip {{ !request('blood_group') ? 'active' : '' }}" title="All Blood Groups">All</a>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <a href="{{ route('admin.contact-requests.index', array_merge(request()->except('blood_group', 'page'), ['blood_group' => $bg])) }}" 
                           class="bg-chip {{ request('blood_group') === $bg ? 'active' : '' }}">{{ $bg }}</a>
                    @endforeach
                </div>
            </div>

            <!-- Search by City / Name / Patient -->
            <form method="GET" action="{{ route('admin.contact-requests.index') }}" class="row g-2 align-items-center pt-3">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('blood_group'))
                    <input type="hidden" name="blood_group" value="{{ request('blood_group') }}">
                @endif
                
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Search by Requester, Phone, Patient name, or City..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-2 col-lg-2">
                    <button type="submit" class="btn btn-brand w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                </div>
                <div class="col-6 col-md-2 col-lg-1">
                    <a href="{{ route('admin.contact-requests.index') }}" class="btn btn-soft w-100" title="Reset all filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Requests Table Panel (Responsive, Clean, No multi-line vertical drop) -->
    <div class="panel">
        <div class="panel-head d-flex justify-content-between align-items-center">
            <h6><i class="fa-solid fa-envelope-open-text me-2 text-danger"></i>Contact Requests (<span id="requests-total-count">{{ $requests->total() }}</span>)</h6>
            <div class="small text-muted">Showing page {{ $requests->currentPage() }} of {{ $requests->lastPage() }}</div>
        </div>

        <div class="table-responsive">
            <table class="requests-table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Requester Details</th>
                        <th>Patient & Location</th>
                        <th>Target Donor</th>
                        <th>Urgency</th>
                        <th>Message / Note</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr id="request-row-{{ $req->id }}">
                            <!-- Request ID -->
                            <td>
                                <span class="req-id-badge">#{{ $req->id }}</span>
                            </td>

                            <!-- Requester -->
                            <td>
                                <div class="fw-bold text-dark nowrap">{{ $req->requester_name }}</div>
                                <div class="small nowrap">
                                    <a href="tel:{{ $req->requester_phone }}" class="phone-link">
                                        <i class="fa-solid fa-phone me-1 text-danger" style="font-size: 0.7rem;"></i>{{ $req->requester_phone }}
                                    </a>
                                </div>
                                @if($req->requester_email)
                                    <div class="text-muted small text-truncate" style="max-width: 160px;" title="{{ $req->requester_email }}">
                                        {{ $req->requester_email }}
                                    </div>
                                @endif
                            </td>

                            <!-- Patient & Location -->
                            <td>
                                <div class="fw-bold text-dark nowrap">{{ $req->patient_name }}</div>
                                <div class="small text-muted">
                                    <i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $req->hospital }}, {{ $req->city }}
                                </div>
                            </td>

                            <!-- Target Donor (Enhanced avatar and inline blood group badge) -->
                            <td>
                                <div class="donor-meta-box">
                                    <span class="donor-avatar-circle" aria-hidden="true">
                                        {{ strtoupper(mb_substr($req->donor->name ?? 'D', 0, 1)) }}
                                    </span>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-bold text-dark nowrap">{{ $req->donor->name ?? 'Unknown Donor' }}</span>
                                            @if($req->donor && $req->donor->blood_group)
                                                <span class="group-pill" style="padding: 0.15rem 0.5rem; font-size: 0.65rem;">
                                                    {{ $req->donor->blood_group }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="small text-muted nowrap">
                                            <i class="fa-solid fa-mobile-screen me-1"></i>{{ $req->donor->phone ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Urgency -->
                            <td>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle urgency-badge">
                                    {{ $req->urgency_level }}
                                </span>
                            </td>

                            <!-- Message -->
                            <td id="message-cell-{{ $req->id }}">
                                @if($req->message)
                                    <div class="small text-muted fst-italic" title="{{ $req->message }}">
                                        "{{ Str::limit($req->message, 70) }}"
                                    </div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                                @if($req->admin_notes)
                                    <div class="small text-danger mt-1 text-break">
                                        <strong>Admin:</strong> {{ $req->admin_notes }}
                                    </div>
                                @endif
                            </td>

                            <!-- Status (Targeted by AJAX) -->
                            <td id="status-cell-{{ $req->id }}">
                                @if($req->status === 'approved')
                                    <span class="tag tag-ok"><i class="fa-solid fa-circle-check me-1"></i>Approved</span>
                                @elseif($req->status === 'rejected')
                                    <span class="tag tag-off"><i class="fa-solid fa-circle-xmark me-1"></i>Rejected</span>
                                @else
                                    <span class="tag tag-wait"><i class="fa-solid fa-clock me-1"></i>Pending</span>
                                @endif
                            </td>

                            <!-- Actions (Targeted by AJAX with ZERO page reload) -->
                            <td class="text-end" id="action-cell-{{ $req->id }}">
                                @if($req->status === 'pending')
                                    <div class="d-inline-flex gap-2">
                                        <!-- Instant AJAX Quick Approve Button -->
                                        <button type="button" 
                                                class="btn btn-confirm" 
                                                id="btn-quick-approve-{{ $req->id }}"
                                                onclick="quickApprove({{ $req->id }})" 
                                                title="Approve immediately without reload">
                                            <i class="fa-solid fa-check me-1"></i>Approve
                                        </button>

                                        <!-- Reject Button (Opens Modal which also submits via AJAX) -->
                                        <button type="button" 
                                                class="btn btn-reject" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $req->id }}">
                                            <i class="fa-solid fa-xmark me-1"></i>Reject
                                        </button>
                                    </div>

                                    <!-- Reject Modal (Submits via AJAX without page reload) -->
                                    <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form action="{{ route('admin.contact-requests.reject', $req->id) }}" 
                                                  method="POST" 
                                                  class="modal-content text-start"
                                                  onsubmit="submitReject(event, this, {{ $req->id }})">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold text-danger">
                                                        <i class="fa-solid fa-circle-xmark me-2"></i>Reject Request #{{ $req->id }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-muted small mb-2">
                                                        Patient: <strong>{{ $req->patient_name }}</strong> &bull; Requester: <strong>{{ $req->requester_name }}</strong>
                                                    </p>
                                                    <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                    <textarea name="admin_notes" class="form-control" rows="3" required placeholder="State reason (e.g. Unverified requirement, duplicate request, etc.)"></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-soft" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger fw-bold">Reject Request</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small"><i class="fa-solid fa-lock me-1"></i>Reviewed</span>
                                @endif
                                <form action="{{ route('admin.contact-requests.destroy', $req->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      data-delete-request
                                      onsubmit="deleteContactRequest(event, this, {{ $req->id }}); return false;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-1" title="Delete contact request" aria-label="Delete contact request #{{ $req->id }}">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-row text-center py-5">
                                <div class="empty-state">
                                    <span class="ring"><i class="fa-solid fa-inbox"></i></span>
                                    <strong>No Contact Requests Found</strong>
                                    No requests matching the selected filters.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $requests->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function showToast(message, type = 'success') {
        const toast = document.getElementById('ajaxToast');
        const text = document.getElementById('toastText');
        const icon = document.getElementById('toastIcon');

        toast.className = 'toast-popup toast-' + type;
        text.innerText = message;
        icon.className = type === 'success' ? 'fa-solid fa-circle-check fs-5' : 'fa-solid fa-circle-exclamation fs-5';
        toast.style.display = 'flex';

        setTimeout(hideToast, 4000);
    }

    function hideToast() {
        const toast = document.getElementById('ajaxToast');
        if (toast) toast.style.display = 'none';
    }

    // Live update of pending badge counter in sidebar & tabs
    function updatePendingBadges(newCount) {
        // Tab badges
        const pendingBadge = document.getElementById('tab-pending-badge');
        if (pendingBadge) pendingBadge.innerText = newCount;

        // Sidebar badges
        document.querySelectorAll('.nav-badge').forEach(badge => {
            if (badge.getAttribute('title') && badge.getAttribute('title').toLowerCase().includes('contact')) {
                if (newCount > 0) {
                    badge.innerText = newCount;
                    badge.style.display = 'inline-flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        });
    }

    function updateRequestCountBadges(data) {
        const counts = {
            'tab-all-badge': data.all_count,
            'tab-pending-badge': data.pending_count,
            'tab-approved-badge': data.approved_count,
            'tab-rejected-badge': data.rejected_count
        };

        Object.entries(counts).forEach(([id, count]) => {
            const badge = document.getElementById(id);
            if (badge && typeof count !== 'undefined') badge.innerText = count;
        });

        if (typeof data.pending_count !== 'undefined') {
            updatePendingBadges(data.pending_count);
        }
    }

    async function deleteContactRequest(event, form, requestId) {
        event.preventDefault();
        if (!window.confirm('Delete contact request #' + requestId + '? This cannot be undone.')) return;

        const button = form.querySelector('button[type="submit"]');
        const originalHtml = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(form)
            });
            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Could not delete contact request.');
            }

            const row = document.getElementById('request-row-' + requestId);
            if (row) row.remove();

            const totalCount = document.getElementById('requests-total-count');
            if (totalCount) totalCount.innerText = Math.max(0, Number(totalCount.innerText) - 1);
            updateRequestCountBadges(data);

            showToast(data.message || 'Contact request deleted.', 'success');
        } catch (error) {
            console.error('Contact request deletion failed:', error);
            showToast(error.message || 'Could not delete contact request.', 'error');
            button.disabled = false;
            button.innerHTML = originalHtml;
        }
    }

    // Fast One-Click AJAX Approve (Zero Page Reload)
    async function quickApprove(requestId) {
        const btn = document.getElementById('btn-quick-approve-' + requestId);
        if (!btn || btn.disabled) return;

        const origHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-1"></i>';

        try {
            const url = '{{ url("/admin/contact-requests") }}/' + requestId + '/approve';
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    admin_notes: 'Approved by admin. The donor has been notified of your emergency requirement.'
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Instantly update status cell
                const statusCell = document.getElementById('status-cell-' + requestId);
                if (statusCell) {
                    statusCell.innerHTML = '<span class="tag tag-ok"><i class="fa-solid fa-circle-check me-1"></i>Approved</span>';
                }

                // Instantly update action cell
                const actionCell = document.getElementById('action-cell-' + requestId);
                if (actionCell) {
                    const deleteForm = actionCell.querySelector('[data-delete-request]');
                    actionCell.innerHTML = '<span class="text-success small fw-bold"><i class="fa-solid fa-check-double me-1"></i>Approved</span>';
                    if (deleteForm) actionCell.appendChild(deleteForm);
                }

                const messageCell = document.getElementById('message-cell-' + requestId);
                if (messageCell && data.admin_notes) {
                    const note = document.createElement('div');
                    const label = document.createElement('strong');
                    note.className = 'small text-danger mt-1 text-break';
                    label.textContent = 'Admin:';
                    note.append(label, document.createTextNode(' ' + data.admin_notes));
                    messageCell.appendChild(note);
                }

                // Update counter
                if (typeof data.pending_count !== 'undefined') {
                    updatePendingBadges(data.pending_count);
                }

                showToast(data.message || 'Request approved successfully! Donor has been alerted.', 'success');
            } else {
                alert(data.message || 'Could not approve request.');
                btn.disabled = false;
                btn.innerHTML = origHtml;
            }
        } catch (error) {
            console.error('Approval failed:', error);
            alert('A network error occurred. Please try again.');
            btn.disabled = false;
            btn.innerHTML = origHtml;
        }
    }

    // AJAX Reject (Zero Page Reload)
    async function submitReject(e, form, requestId) {
        e.preventDefault();
        const submitBtn = form.querySelector('button[type="submit"]');
        const origHtml = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-1"></i> Rejecting...';

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Close modal
                const modalEl = document.getElementById('rejectModal' + requestId);
                if (modalEl) {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                }

                // Update row status
                const statusCell = document.getElementById('status-cell-' + requestId);
                if (statusCell) {
                    statusCell.innerHTML = '<span class="tag tag-off"><i class="fa-solid fa-circle-xmark me-1"></i>Rejected</span>';
                }

                // Update action cell
                const actionCell = document.getElementById('action-cell-' + requestId);
                if (actionCell) {
                    const deleteForm = actionCell.querySelector('[data-delete-request]');
                    actionCell.innerHTML = '<span class="text-muted small fw-bold"><i class="fa-solid fa-ban me-1"></i>Rejected</span>';
                    if (deleteForm) actionCell.appendChild(deleteForm);
                }

                if (typeof data.pending_count !== 'undefined') {
                    updatePendingBadges(data.pending_count);
                }

                showToast(data.message || 'Contact request has been marked as rejected.', 'info');
            } else {
                alert(data.message || 'Could not reject request.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = origHtml;
            }
        } catch (err) {
            console.error('Rejection failed:', err);
            alert('A network error occurred. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = origHtml;
        }
    }
</script>
@endpush
@endsection
