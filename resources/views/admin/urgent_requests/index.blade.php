@extends('layouts.app')

@section('title', 'Urgent Blood Requests - Admin')

@section('content')
<div class="adm">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="h4 page-title mb-1">Urgent Blood Requests</h2>
            <p class="page-sub mb-0">Review every urgent request and delete it when it should no longer remain in the system.</p>
        </div>
        <a href="{{ route('blood-requests.index') }}" target="_blank" class="btn btn-soft btn-sm">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Public Feed
        </a>
    </div>

    <div class="panel mb-4">
        <div class="panel-body p-3">
            <form method="GET" action="{{ route('admin.urgent-requests.index') }}" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">All statuses</option>
                        @foreach(['open' => 'Open', 'pending' => 'Pending', 'approved' => 'Approved', 'fulfilled' => 'Fulfilled', 'rejected' => 'Rejected', 'cancelled' => 'Cancelled'] as $value => $label)
                            <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-brand w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h6><i class="fa-solid fa-bullhorn me-2 text-danger"></i>Requests ({{ $requests->total() }})</h6>
        </div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Request</th>
                        <th>Patient / Blood Group</th>
                        <th>Hospital & City</th>
                        <th>Contact</th>
                        <th>Urgency</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td>
                                <span class="text-muted fw-bold">#{{ $req->id }}</span>
                                <div class="small text-muted">{{ $req->created_at->format('M j, Y') }}</div>
                            </td>
                            <td>
                                <strong>{{ $req->patient_name }}</strong>
                                <div><span class="group-pill">{{ $req->blood_group }}</span>
                                    <span class="small text-muted ms-1">{{ $req->units_needed }} {{ Str::plural('unit', $req->units_needed) }}</span>
                                </div>
                            </td>
                            <td>
                                <div>{{ $req->hospital }}</div>
                                <small class="text-muted">{{ $req->city }}</small>
                            </td>
                            <td>
                                <div>{{ $req->contact_name }}</div>
                                <a href="tel:{{ $req->contact_phone }}" class="phone-link small">{{ $req->contact_phone }}</a>
                            </td>
                            <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle">{{ $req->urgency_level }}</span></td>
                            <td><span class="tag {{ $req->status === 'open' || $req->status === 'approved' ? 'tag-ok' : 'tag-off' }}">{{ ucfirst($req->status) }}</span></td>
                            <td class="text-end">
                                <form action="{{ route('blood-requests.destroy', $req) }}" method="POST"
                                      data-ajax-form="true"
                                      data-confirm="Delete this urgent blood request permanently?"
                                      data-success-message="Blood request deleted."
                                      data-remove-row="true">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-reject" title="Delete request">
                                        <i class="fa-solid fa-trash-can me-1"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-row">No urgent blood requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
            <div class="p-3 border-top">
                {{ $requests->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
