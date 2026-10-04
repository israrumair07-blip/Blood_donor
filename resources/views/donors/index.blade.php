@extends('layouts.app')

@section('title', 'Donors List - Blood Donor')

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

    .adm .guardian-box { background: var(--rose); border: 1.5px solid #f6c9d1; border-radius: 14px; padding: 1rem; }
    .adm .guardian-box .title { color: var(--red-dark); font-weight: 800; font-size: .9rem; }
    .adm .guardian-box .form-control, .adm .guardian-box .form-select { background: #fff; }
    .adm .sub-line { font-size: .75rem; color: var(--muted); margin-top: .15rem; }
</style>


<div class="adm">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="h4 page-title">Donors Management</h2>
            <p class="page-sub">{{ $donors->total() }} {{ Str::plural('donor', $donors->total()) }} in the system</p>
        </div>
        <button class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#addDonorModal">
            <i class="fa-solid fa-plus me-1"></i> Add Donor
        </button>
    </div>

    <!-- Filters -->
    <div class="panel mb-4">
        <div class="p-3">
            <form method="GET" action="{{ route('donors.index') }}" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="city" class="form-control" placeholder="Search by city..." value="{{ request('city') }}">
                </div>
                <div class="col-md-4">
                    <select name="blood_group" class="form-select">
                        <option value="">All blood groups</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                            <option value="{{ $group }}" {{ request('blood_group') == $group ? 'selected' : '' }}>{{ $group }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-brand flex-grow-1"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('donors.index') }}" class="btn btn-soft">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Alerts -->
    <div id="jsAlert" class="alert d-none" role="alert"></div>
    @foreach (['success' => 'success', 'info' => 'info', 'error' => 'danger'] as $key => $type)
        @if(session($key))
            <div class="alert alert-{{ $type }}">{{ session($key) }}</div>
        @endif
    @endforeach

    <!-- Donors table -->
    <div class="panel">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Blood Group</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody id="donorTableBody">
                @forelse ($donors as $donor)
                    <tr id="donor-row-{{ $donor->id }}">
                        <td class="fw-bold donor-name">{{ $donor->name }}</td>
                        <td><span class="group-pill donor-blood-group">{{ $donor->blood_group }}</span></td>
                        <td>
                            <span class="donor-phone">{{ $donor->phone }}</span>
                            @if($donor->gender === 'Female' && $donor->guardian_name)
                                <div class="sub-line"><i class="fa-solid fa-shield-heart me-1"></i>{{ $donor->guardian_name }} ({{ $donor->guardian_relation }}) &middot; {{ $donor->guardian_phone }}</div>
                            @endif
                        </td>
                        <td class="donor-city">{{ $donor->city }}</td>

                        <td>
                            @if($donor->donation_pending)
                                <span class="tag tag-wait"><i class="fa-solid fa-clock me-1"></i>Pending</span>
                            @elseif(!$donor->is_eligible)
                                @php $daysLeft = max(0, 90 - (int) $donor->last_donated_at->diffInDays(now())); @endphp
                                <span class="tag tag-cool" title="Donated on {{ $donor->last_donated_at->format('d M Y') }}">
                                    <i class="fa-solid fa-hourglass-half me-1"></i>Cooldown ({{ $daysLeft }}d left)
                                </span>
                            @elseif($donor->is_available)
                                <span class="tag tag-ok donor-status">Available</span>
                            @else
                                <span class="tag tag-off donor-status">Unavailable</span>
                            @endif
                        </td>

                        <td class="text-end">
                            <div class="d-inline-flex gap-1 align-items-center">
                                @if($donor->is_eligible)
                                    <form action="{{ route('donors.markDonated', $donor->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Confirm that this donor has donated blood? Status will move to a 90-day cooldown.')">
                                        @csrf
                                        <button type="submit" class="btn btn-donated" title="Mark as donated today">
                                            <i class="fa-solid fa-droplet me-1"></i>Donated
                                        </button>
                                    </form>
                                @endif

                                <button type="button" class="icon-btn" title="Copy donor link" onclick="copyDonorLink({{ $donor->id }})">
                                    <i class="fa-solid fa-link"></i>
                                </button>

                                <button type="button" class="icon-btn edit" title="Edit"
                                        data-id="{{ $donor->id }}"
                                        data-name="{{ $donor->name }}"
                                        data-phone="{{ $donor->phone }}"
                                        data-group="{{ $donor->blood_group }}"
                                        data-city="{{ $donor->city }}"
                                        data-available="{{ $donor->is_available ? 1 : 0 }}"
                                        data-gender="{{ $donor->gender }}"
                                        data-guardian-name="{{ $donor->guardian_name }}"
                                        data-guardian-relation="{{ $donor->guardian_relation }}"
                                        data-guardian-phone="{{ $donor->guardian_phone }}"
                                        onclick="openEditModal(this)">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <button type="button" class="icon-btn" title="Delete" onclick="deleteDonor({{ $donor->id }})">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr id="noDataRow">
                        <td colspan="6" class="empty-row">No donors registered yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($donors->hasPages())
        <div class="pager mt-4">
            {{ $donors->links('pagination::bootstrap-5') }}
        </div>
    @endif

    <!-- Add modal -->
    <div class="modal fade" id="addDonorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form id="addDonorForm" class="modal-content shadow">
                <div class="modal-header brand">
                    <h5 class="modal-title fw-bold">Add New Blood Donor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="modalErrors" class="alert alert-danger d-none"></div>

                    <div class="mb-3">
                        <label class="form-label">Full name</label>
                        <input type="text" name="name" class="form-control" placeholder="Donor's full name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Personal phone number</label>
                        <input type="text" name="phone" class="form-control" placeholder="03001234567" required>
                        <div class="sub-line"><i class="fa-solid fa-lock me-1"></i>This number will not be shown publicly for female donors.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" id="addGender" class="form-select" required>
                            <option value="" disabled selected>Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Female privacy section -->
                    <div id="addGuardianSection" class="guardian-box mb-3 d-none">
                        <div class="title mb-1"><i class="fa-solid fa-shield-heart me-2"></i>Privacy protection (guardian contact)</div>
                        <p class="text-muted small mb-3">This contact will be shown on public search instead of the personal number.</p>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Guardian / Mehram name</label>
                                <input type="text" name="guardian_name" class="form-control" placeholder="e.g. Ahmad Rao">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Relation</label>
                                <select name="guardian_relation" class="form-select">
                                    <option value="Father">Father</option>
                                    <option value="Brother">Brother</option>
                                    <option value="Husband">Husband</option>
                                    <option value="Guardian">Other guardian</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Guardian phone / WhatsApp</label>
                                <input type="text" name="guardian_phone" class="form-control" placeholder="03009876543">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Blood group</label>
                            <select name="blood_group" class="form-select" required>
                                <option value="">Select</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $g)
                                    <option value="{{ $g }}">{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" placeholder="e.g. Sargodha" required>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_available" value="1" id="isAvailable" checked>
                        <label class="form-check-label fw-semibold" for="isAvailable">Available to donate</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-soft" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="saveBtn" class="btn btn-brand">Save Donor</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit modal -->
    <div class="modal fade" id="editDonorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form id="editDonorForm" class="modal-content shadow">
                <div class="modal-header edit">
                    <h5 class="modal-title fw-bold">Edit Blood Donor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="editModalErrors" class="alert alert-danger d-none"></div>

                    <input type="hidden" id="edit_donor_id" name="id">

                    <div class="mb-3">
                        <label class="form-label">Full name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone number</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" id="edit_gender" class="form-select" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div id="editGuardianSection" class="guardian-box mb-3 d-none">
                        <div class="title mb-1"><i class="fa-solid fa-shield-heart me-2"></i>Privacy protection (guardian contact)</div>
                        <p class="text-muted small mb-3">This contact is shown on public search instead of the personal number.</p>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Guardian / Mehram name</label>
                                <input type="text" name="guardian_name" id="edit_guardian_name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Relation</label>
                                <select name="guardian_relation" id="edit_guardian_relation" class="form-select">
                                    <option value="Father">Father</option>
                                    <option value="Brother">Brother</option>
                                    <option value="Husband">Husband</option>
                                    <option value="Guardian">Other guardian</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Guardian phone / WhatsApp</label>
                                <input type="text" name="guardian_phone" id="edit_guardian_phone" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Blood group</label>
                            <select name="blood_group" id="edit_blood_group" class="form-select" required>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $g)
                                    <option value="{{ $g }}">{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" name="city" id="edit_city" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="available" value="1" id="edit_isAvailable">
                        <label class="form-check-label fw-semibold" for="edit_isAvailable">Available for donation</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-soft" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="editSaveBtn" class="btn btn-brand">Update Donor</button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Escape text before putting it into innerHTML
const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));


// Guardian section (female donors only), same logic as the public register form
const addGender = document.getElementById('addGender');
const addGuardian = document.getElementById('addGuardianSection');
const addGuardianFields = addGuardian.querySelectorAll('input, select');

function toggleGuardian() {
    const isFemale = addGender.value === 'Female';
    addGuardian.classList.toggle('d-none', !isFemale);
    addGuardianFields.forEach(f => {
        f.disabled = !isFemale;                       // disabled fields are not submitted
        f.required = isFemale && f.name !== 'guardian_relation';
    });
}
addGender.addEventListener('change', toggleGuardian);
toggleGuardian();


// Same guardian logic for the edit modal
const editGender = document.getElementById('edit_gender');
const editGuardian = document.getElementById('editGuardianSection');
const editGuardianFields = editGuardian.querySelectorAll('input, select');

function toggleEditGuardian() {
    const isFemale = editGender.value === 'Female';
    editGuardian.classList.toggle('d-none', !isFemale);
    editGuardianFields.forEach(f => {
        f.disabled = !isFemale;
        f.required = isFemale && f.name !== 'guardian_relation';
    });
}
editGender.addEventListener('change', toggleEditGuardian);

// Phone cell (phone + guardian line for female donors), used by add and edit
function phoneCellHtml(d) {
    const guardian = (d.gender === 'Female' && d.guardian_name)
        ? `<div class="sub-line"><i class="fa-solid fa-shield-heart me-1"></i>${esc(d.guardian_name)} (${esc(d.guardian_relation)}) &middot; ${esc(d.guardian_phone)}</div>`
        : '';
    return `<span class="donor-phone">${esc(d.phone)}</span>${guardian}`;
}

function showNotify(msg, type = 'success') {
    const box = document.getElementById('jsAlert');
    box.className = `alert alert-${type}`;
    box.innerText = msg;
    box.classList.remove('d-none');
    setTimeout(() => box.classList.add('d-none'), 3000);
}

function statusTag(available) {
    return available
        ? '<span class="tag tag-ok donor-status">Available</span>'
        : '<span class="tag tag-off donor-status">Unavailable</span>';
}

// 1. OPEN EDIT MODAL (reads values from the button's data attributes)
function openEditModal(btn) {
    const d = btn.dataset;
    document.getElementById('edit_donor_id').value = d.id;
    document.getElementById('edit_name').value = d.name;
    document.getElementById('edit_phone').value = d.phone;
    document.getElementById('edit_blood_group').value = d.group;
    document.getElementById('edit_city').value = d.city;
    document.getElementById('edit_isAvailable').checked = d.available === '1';
    editGender.value = d.gender || 'Male';
    document.getElementById('edit_guardian_name').value = d.guardianName || '';
    document.getElementById('edit_guardian_relation').value = d.guardianRelation || 'Father';
    document.getElementById('edit_guardian_phone').value = d.guardianPhone || '';
    toggleEditGuardian();

    document.getElementById('editModalErrors').classList.add('d-none');
    bootstrap.Modal.getOrCreateInstance(document.getElementById('editDonorModal')).show();
}

// 2. ADD DONOR
document.getElementById('addDonorForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const saveBtn = document.getElementById('saveBtn');
    const errBox = document.getElementById('modalErrors');
    saveBtn.disabled = true;
    errBox.classList.add('d-none');

    try {
        const res = await fetch("{{ route('donors.store') }}", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": csrf, "Accept": "application/json" },
            body: new FormData(this)
        });
        const data = await res.json();

        if (res.ok) {
            const d = data.donor;
            bootstrap.Modal.getInstance(document.getElementById('addDonorModal')).hide();
            this.reset();
            toggleGuardian();
            document.getElementById('noDataRow')?.remove();

            const tr = document.createElement('tr');
            tr.id = `donor-row-${d.id}`;
            tr.innerHTML = `
                <td class="fw-bold donor-name">${esc(d.name)}</td>
                <td><span class="group-pill donor-blood-group">${esc(d.blood_group)}</span></td>
                <td>${phoneCellHtml(d)}</td>
                <td class="donor-city">${esc(d.city)}</td>
                <td>${statusTag(d.available)}</td>
                <td class="text-end">
                    <div class="d-inline-flex gap-1 align-items-center">
                        <button type="button" class="icon-btn" title="Copy donor link" onclick="copyDonorLink(${d.id})">
                            <i class="fa-solid fa-link"></i>
                        </button>
                        <button type="button" class="icon-btn edit" title="Edit"
                            data-id="${d.id}" data-name="${esc(d.name)}" data-phone="${esc(d.phone)}"
                            data-group="${esc(d.blood_group)}" data-city="${esc(d.city)}"
                            data-available="${d.available ? 1 : 0}" data-gender="${esc(d.gender)}"
                            data-guardian-name="${esc(d.guardian_name)}" data-guardian-relation="${esc(d.guardian_relation)}"
                            data-guardian-phone="${esc(d.guardian_phone)}" onclick="openEditModal(this)">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button type="button" class="icon-btn" title="Delete" onclick="deleteDonor(${d.id})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>`;
            document.getElementById('donorTableBody').prepend(tr);
            showNotify(data.message);
        } else {
            let msg = data.message || 'Validation error';
            if (data.errors) msg = Object.values(data.errors).flat().map(esc).join('<br>');
            errBox.innerHTML = msg;
            errBox.classList.remove('d-none');
        }
    } catch (err) {
        console.error(err);
        errBox.innerText = 'Error: Unable to add donor.';
        errBox.classList.remove('d-none');
    } finally {
        saveBtn.disabled = false;
    }
});

// 3. EDIT / UPDATE DONOR
document.getElementById('editDonorForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const saveBtn = document.getElementById('editSaveBtn');
    const errBox = document.getElementById('editModalErrors');
    saveBtn.disabled = true;
    errBox.classList.add('d-none');

    const donorId = document.getElementById('edit_donor_id').value;
    const formData = new FormData(this);
    formData.append('_method', 'PUT');

    try {
        const res = await fetch(`/donors/${donorId}`, {
            method: "POST",
            headers: { "X-CSRF-TOKEN": csrf, "Accept": "application/json" },
            body: formData
        });
        const data = await res.json();

        if (res.ok) {
            const d = data.donor;
            bootstrap.Modal.getInstance(document.getElementById('editDonorModal')).hide();

            const row = document.getElementById(`donor-row-${d.id}`);
            if (row) {
                row.querySelector('.donor-name').innerText = d.name;
                row.querySelector('.donor-blood-group').innerText = d.blood_group;
                row.children[2].innerHTML = phoneCellHtml(d);
                row.querySelector('.donor-city').innerText = d.city;

                // Only plain Available/Unavailable tags carry .donor-status (cooldown/pending tags stay as they are)
                const tag = row.querySelector('.donor-status');
                if (tag) tag.outerHTML = statusTag(d.available);

                const editBtn = row.querySelector('.icon-btn.edit');
                Object.assign(editBtn.dataset, {
                    name: d.name, phone: d.phone, group: d.blood_group,
                    city: d.city, available: d.available ? 1 : 0,
                    gender: d.gender || '', guardianName: d.guardian_name || '',
                    guardianRelation: d.guardian_relation || '', guardianPhone: d.guardian_phone || ''
                });
            }
            showNotify(data.message || 'Donor updated successfully!');
        } else {
            let msg = data.message || 'Validation error';
            if (data.errors) msg = Object.values(data.errors).flat().map(esc).join('<br>');
            errBox.innerHTML = msg;
            errBox.classList.remove('d-none');
        }
    } catch (err) {
        console.error(err);
        errBox.innerText = 'Error: Unable to update donor.';
        errBox.classList.remove('d-none');
    } finally {
        saveBtn.disabled = false;
    }
});

// 5. COPY THE DONOR'S PERSONAL LINK (opening it on the donor's phone unlocks their "I donated today" button)
async function copyDonorLink(id) {
    try {
        const res = await fetch(`/donors/${id}/claim-link`, {
            method: "POST",
            headers: { "X-CSRF-TOKEN": csrf, "Accept": "application/json" }
        });
        const data = await res.json();
        if (!res.ok) throw new Error('failed');

        try {
            await navigator.clipboard.writeText(data.url);
            showNotify('Donor link copied. Send it to the donor on WhatsApp.');
        } catch (e) {
            prompt('Copy this link and send it to the donor:', data.url);
        }
    } catch (err) {
        alert('Could not create the link.');
    }
}

// 4. DELETE DONOR
async function deleteDonor(id) {
    if (!confirm('Are you sure you want to delete this donor?')) return;

    try {
        const res = await fetch(`/donors/${id}`, {
            method: "DELETE",
            headers: { "X-CSRF-TOKEN": csrf, "Accept": "application/json" }
        });
        const data = await res.json();

        if (res.ok) {
            document.getElementById(`donor-row-${id}`)?.remove();
            showNotify(data.message, 'warning');
        } else {
            alert('Delete failed');
        }
    } catch (err) {
        alert('Delete request failed');
    }
}
</script>
@endpush
@endsection