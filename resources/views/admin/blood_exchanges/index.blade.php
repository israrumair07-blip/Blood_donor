@extends('layouts.app')

@section('title', 'Blood Exchange Management - Admin')

@section('content')
<style>
    .exchange-table { width: 100%; min-width: 1250px; table-layout: auto; }
    .exchange-table .d-inline-flex { flex-wrap: wrap; }
    .exchange-table .tag { white-space: normal; }

    @media (max-width: 991.98px) {
        .exchange-table th,
        .exchange-table td { padding: .65rem .5rem !important; font-size: .76rem !important; }
    }
</style>
<div class="adm">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="h4 page-title mb-1">Blood Exchange Requests</h2>
            <p class="page-sub mb-0">Review user blood exchange offers (have blood group X, need blood group Y), approve for public listing, or mark completed.</p>
        </div>
        <div>
            <a href="{{ route('blood-exchanges.index') }}" target="_blank" class="btn btn-soft btn-sm">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Public Exchange Board
            </a>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="panel mb-4">
        <div class="panel-body p-3">
            <form method="GET" action="{{ route('admin.blood-exchanges.index') }}" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved / Live</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="blood_group" class="form-select">
                        <option value="">Any Blood Group</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                            <option value="{{ $bg }}" {{ request('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="city" class="form-control" placeholder="Filter by City" value="{{ request('city') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-brand w-100"><i class="fa-solid fa-filter me-1"></i>Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Exchanges Table Panel -->
    <div class="panel">
        <div class="panel-head">
            <h6><i class="fa-solid fa-right-left me-2 text-danger"></i>Exchange Requests ({{ $exchanges->total() }})</h6>
        </div>
        <div class="table-responsive">
            <table class="table align-middle exchange-table">
                <thead>
                    <tr>
                        <th>Req #</th>
                        <th>Exchange Pair</th>
                        <th>Units</th>
                        <th>Requester / Contact</th>
                        <th>Patient & Location</th>
                        <th>Urgency & Notes</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exchanges as $ex)
                        <tr>
                            <td><span class="text-muted fw-bold">#{{ $ex->id }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="text-center">
                                        <span class="badge bg-danger text-white px-2 py-1 fs-6 fw-bold">{{ $ex->offered_blood_group }}</span>
                                        <div class="text-muted" style="font-size: 0.65rem; text-transform: uppercase;">Has</div>
                                    </div>
                                    <i class="fa-solid fa-arrow-right text-muted"></i>
                                    <div class="text-center">
                                        <span class="badge bg-primary text-white px-2 py-1 fs-6 fw-bold">{{ $ex->needed_blood_group }}</span>
                                        <div class="text-muted" style="font-size: 0.65rem; text-transform: uppercase;">Needs</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $ex->units }}</span> <span class="text-muted small">unit(s)</span>
                            </td>
                            <td>
                                <strong>{{ $ex->contact_name }}</strong>
                                <div class="small"><a href="tel:{{ $ex->contact_phone }}" class="text-danger text-decoration-none fw-semibold">{{ $ex->contact_phone }}</a></div>
                                @if($ex->contact_email)
                                    <div class="text-muted small">{{ $ex->contact_email }}</div>
                                @endif
                            </td>
                            <td>
                                <div><strong>{{ $ex->patient_name }}</strong></div>
                                <div class="small text-muted"><i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $ex->hospital }}, {{ $ex->city }}</div>
                            </td>
                            <td style="max-width: 220px;">
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle mb-1" style="font-size: 0.68rem;">
                                    {{ $ex->urgency_level }}
                                </span>
                                @if($ex->notes)
                                    <div class="small text-muted" title="{{ $ex->notes }}">"{{ Str::limit($ex->notes, 70) }}"</div>
                                @endif
                                @if($ex->admin_notes)
                                    <div class="small text-danger mt-1"><strong>Admin:</strong> {{ $ex->admin_notes }}</div>
                                @endif
                            </td>
                            <td>
                                @if($ex->status === 'approved')
                                    <span class="tag tag-ok">Live / Approved</span>
                                @elseif($ex->status === 'completed')
                                    <span class="tag tag-cool">Completed</span>
                                @elseif($ex->status === 'rejected')
                                    <span class="tag tag-off">Rejected</span>
                                @elseif($ex->status === 'cancelled')
                                    <span class="tag tag-off">Cancelled</span>
                                @else
                                    <span class="tag tag-wait">Pending</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    @if($ex->status === 'pending')
                                        <button class="btn btn-confirm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $ex->id }}">
                                            <i class="fa-solid fa-check me-1"></i>Approve
                                        </button>
                                        <button class="btn btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $ex->id }}">
                                            <i class="fa-solid fa-xmark me-1"></i>Reject
                                        </button>
                                    @elseif($ex->status === 'approved')
                                        <form action="{{ route('admin.blood-exchanges.complete', $ex->id) }}" method="POST" class="d-inline"
                                              data-ajax-form="true"
                                              data-confirm="Mark this blood exchange as successfully completed?"
                                              data-success-message="Blood exchange marked as completed."
                                              data-remove-row="true">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success rounded-pill fw-bold" title="Mark Completed">
                                                <i class="fa-solid fa-handshake me-1"></i>Completed
                                            </button>
                                        </form>
                                        <button class="btn btn-reject btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $ex->id }}">
                                            <i class="fa-solid fa-ban me-1"></i>Reject
                                        </button>
                                    @endif

                                    <form action="{{ route('admin.blood-exchanges.destroy', $ex->id) }}" method="POST" class="d-inline"
                                          data-ajax-form="true"
                                          data-confirm="Delete this exchange record permanently?"
                                          data-success-message="Exchange record deleted."
                                          data-remove-row="true">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-muted p-1" title="Delete record">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal{{ $ex->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('admin.blood-exchanges.approve', $ex->id) }}" method="POST" class="modal-content text-start"
                                              data-ajax-form="true"
                                              data-close-modal="true"
                                              data-success-message="Exchange offer approved and made live."
                                              data-remove-row="true">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold text-success"><i class="fa-solid fa-circle-check me-2"></i>Approve Exchange #{{ $ex->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-muted small mb-3">
                                                    Approving will make this blood exchange offer (<strong>{{ $ex->offered_blood_group }} for {{ $ex->needed_blood_group }}</strong>) visible on the public blood exchange board.
                                                </p>
                                                <label class="form-label">Admin Approval Instructions / Notes</label>
                                                <textarea name="admin_notes" class="form-control" rows="3">Verified and approved by admin. Exchange offer is live.</textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-soft" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success fw-bold">Approve & Make Live</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $ex->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('admin.blood-exchanges.reject', $ex->id) }}" method="POST" class="modal-content text-start"
                                              data-ajax-form="true"
                                              data-close-modal="true"
                                              data-success-message="Exchange offer rejected."
                                              data-remove-row="true">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-circle-xmark me-2"></i>Reject Exchange #{{ $ex->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                                                <textarea name="admin_notes" class="form-control" rows="3" required placeholder="e.g. Incomplete details, invalid contact, or already fulfilled."></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-soft" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger fw-bold">Reject Exchange</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-right-left fs-3 mb-2 d-block text-secondary"></i>
                                No blood exchange requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($exchanges->hasPages())
            <div class="p-3 border-top">
                {{ $exchanges->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection

