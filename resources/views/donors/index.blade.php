@extends('layouts.app')

@section('title', 'Donors List - Blood Donor')

@section('content')
<style>
    /* Donors page only. Shared styles (buttons, panels, tables, tags, forms, modals, pagination) live in layouts/app.blade.php */

    /* Filter bar */
    .adm .filter-panel { margin-top: -2.6rem; position: relative; z-index: 2; margin-left: 1.25rem; margin-right: 1.25rem; }
    .adm .search-wrap { position: relative; }
    .adm .search-wrap > i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--red); pointer-events: none; font-size: .85rem; }
    .adm .search-wrap .form-control { padding-left: 2.6rem; }
    .adm .page-hero.has-overlap { padding-bottom: 4.4rem; }

    /* Donor cell */
    .adm .donor-cell { display: flex; align-items: center; gap: .85rem; }
    .adm .avatar {
        width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center;
        background: var(--rose); color: var(--red); border: 1px solid var(--rose-line); font-weight: 800; font-size: .85rem;
    }
    .adm .donor-name { font-weight: 700; line-height: 1.2; }
    .adm .donor-phone { font-weight: 500; font-variant-numeric: tabular-nums; }
    .adm .donor-city { color: #4a3d40; font-weight: 500; }
    .adm .donors-table { min-width: 900px; }

    /* Guardian line under the phone number */
    .adm .sub-line {
        display: inline-flex; align-items: center; flex-wrap: wrap; gap: .1rem; margin-top: .4rem;
        font-size: .68rem; color: var(--red-dark); background: var(--rose); border-radius: 999px; padding: .22rem .65rem; font-weight: 600;
    }
    .adm .modal-body .sub-line { background: transparent; color: var(--muted); padding: 0; margin-top: .4rem; font-weight: 500; border-radius: 0; }

    /* Actions */
    .adm .actions { display: inline-flex; flex-wrap: nowrap; gap: .4rem; align-items: center; }
    .adm .icon-btn:hover.danger, .adm .icon-btn.danger:hover { border-color: var(--red); color: #fff; background: var(--red); }

    /* Empty state */
    .adm .empty-row .ring {
        width: 58px; height: 58px; border-radius: 50%; margin-bottom: .8rem; font-size: 1.2rem;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid var(--red); color: var(--red);
    }
    .adm .empty-row strong { display: block; color: var(--ink); font-weight: 800; font-size: .78rem; letter-spacing: .12em; text-transform: uppercase; margin-bottom: .2rem; }

    /* Guardian box inside modals */
    .adm .guardian-box { background: var(--rose); border: 1px solid var(--rose-line); border-radius: 16px; padding: 1.1rem; }
    .adm .guardian-box .title { color: var(--red-dark); font-weight: 800; font-size: .66rem; letter-spacing: .14em; text-transform: uppercase; }
    .adm .guardian-box .form-control, .adm .guardian-box .form-select { background: #fff; }

    /* Modal polish */
    .adm .modal-header { padding: 1.25rem 1.5rem; }
    .adm .modal-header::after {
        content: ""; position: absolute; right: -30px; top: -40px; width: 140px; height: 140px; border-radius: 50%; z-index: -1;
        background: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, .14), transparent 70%);
    }
    .adm .modal-header .modal-title { display: flex; align-items: center; gap: .7rem; }
    .adm .modal-header .m-ico {
        width: 34px; height: 34px; border-radius: 50%; font-size: .8rem;
        display: inline-flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, .65);
    }
    .adm .modal-body { padding: 1.5rem; }
    .adm .modal-footer { padding: 1rem 1.5rem; background: #fcf9f9; }
    .adm .form-check-input { width: 1.15rem; height: 1.15rem; margin-top: .2rem; }

    /* Floating notification (JS alert) */
    .adm #jsAlert { box-shadow: 0 12px 28px -14px rgba(60, 0, 0, .35); }

    @media (max-width: 767.98px) {
        .adm .filter-panel { margin-left: .5rem; margin-right: .5rem; }
        .adm table thead th, .adm table tbody td { padding-left: 1rem; padding-right: 1rem; }
        .adm .donors-table td, .adm .donors-table th { white-space: nowrap; }
    }
</style>


<div class="adm">

    <div class="page-hero has-overlap d-flex flex-wrap justify-content-between align-items-end gap-3" data-mark="DONOR">
        <div class="rbc-field" aria-hidden="true">
        <span class="rbc" style="--x:62%;--y:12%;--s:clamp(70px,8vw,120px);--rx:60deg;--ry:-18deg;--rz:20deg;--blur:1.5px;--dur:11s;"><i></i></span>
        <span class="rbc" style="--x:78%;--y:46%;--s:clamp(90px,11vw,170px);--rx:64deg;--ry:10deg;--rz:-12deg;--dur:12s;--delay:-4s;--fx:-12px;--fy:-16px;"><i style="--spin:38s"></i></span>
        <span class="rbc" style="--x:88%;--y:6%;--s:clamp(40px,4vw,60px);--rx:48deg;--ry:22deg;--rz:-30deg;--blur:3px;--dur:9s;"><i></i></span>
        <span class="rbc" style="--x:68%;--y:72%;--s:clamp(50px,5vw,80px);--rx:52deg;--ry:-25deg;--rz:40deg;--blur:1px;--dur:13s;--delay:-2s;"><i></i></span>
        <span class="rbc" style="--x:6%;--y:70%;--s:clamp(36px,3.5vw,54px);--rx:55deg;--ry:-20deg;--rz:30deg;--blur:4px;--dur:10s;--delay:-6s;"><i></i></span>
    </div>
        <div>
            <div class="page-eyebrow"><b>02</b> Donors</div>
            <h2 class="h4 page-title">Donors Management</h2>
            <p class="page-sub">{{ $donors->total() }} {{ Str::plural('donor', $donors->total()) }} in the system</p>
        </div>
        <button class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#addDonorModal">
            <i class="fa-solid fa-plus me-1"></i> Add Donor
        </button>
    </div>

    <!-- Filters -->
    <div class="panel filter-panel mb-4">
        <div class="p-3">
            <form method="GET" action="{{ route('donors.index') }}" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="search-wrap">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="city" class="form-control" placeholder="Search by city..." aria-label="Search by city" value="{{ request('city') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="blood_group" class="form-select" aria-label="Filter by blood group">
                        <option value="">All blood groups</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                            <option value="{{ $group }}" {{ request('blood_group') == $group ? 'selected' : '' }}>{{ $group }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-brand flex-grow-1"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('donors.index') }}" class="btn btn-soft d-inline-flex align-items-center">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Alerts -->
    <div id="jsAlert" class="alert d-none" role="alert"></div>
    @foreach (['success' => 'success', 'info' => 'info', 'error' => 'danger'] as $key => $type)
        @if(session($key))
            <div class="alert alert-{{ $type }}" role="alert">{{ session($key) }}</div>
        @endif
    @endforeach

    <!-- Donors table -->
    <div class="panel">
        <div class="table-responsive">
            <table class="table align-middle donors-table">
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
                        <td>
                            <div class="donor-cell">
                                <span class="avatar" aria-hidden="true">{{ strtoupper(mb_substr($donor->name, 0, 1)) }}</span>
                                <span class="donor-name">{{ $donor->name }}</span>
                            </div>
                        </td>
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
                                <span class="tag tag-wait">Pending</span>
                            @elseif(!$donor->is_eligible)
                                @php $daysLeft = max(0, 90 - (int) $donor->last_donated_at->diffInDays(now())); @endphp
                                <span class="tag tag-cool" title="Donated on {{ $donor->last_donated_at->format('d M Y') }}">
                                    Cooldown ({{ $daysLeft }}d left)
                                </span>
                            @elseif($donor->is_available)
                                <span class="tag tag-ok donor-status">Available</span>
                            @else
                                <span class="tag tag-off donor-status">Unavailable</span>
                            @endif
                        </td>

                        <td class="text-end">
                            <div class="actions">
                                @if($donor->is_eligible)
                                    <form action="{{ route('donors.markDonated', $donor->id) }}" method="POST" class="d-inline"
                                          data-ajax-form="true"
                                          data-confirm="Confirm that this donor has donated blood? Status will move to a 90-day cooldown."
                                          data-success-message="Donation confirmed successfully."
                                          data-remove-row="true">
                                        @csrf
                                        <button type="submit" class="btn btn-donated" title="Mark as donated today">
                                            <i class="fa-solid fa-droplet me-1"></i>Donated
                                        </button>
                                    </form>
                                @endif

                                <button type="button" class="icon-btn" title="Copy donor link" aria-label="Copy donor link" onclick="copyDonorLink({{ $donor->id }})">
                                    <i class="fa-solid fa-link"></i>
                                </button>

                                <button type="button" class="icon-btn edit" title="Edit" aria-label="Edit donor"
                                        data-id="{{ $donor->id }}"
                                        data-name="{{ $donor->name }}"
                                        data-phone="{{ $donor->phone }}"
                                        data-email="{{ $donor->email }}"
                                        data-group="{{ $donor->blood_group }}"
                                        data-city="{{ $donor->city }}"
                                        data-available="{{ $donor->is_available ? 1 : 0 }}"
                                        data-alert="{{ $donor->notify_urgent_requests ? 1 : 0 }}"
                                        data-gender="{{ $donor->gender }}"
                                        data-guardian-name="{{ $donor->guardian_name }}"
                                        data-guardian-relation="{{ $donor->guardian_relation }}"
                                        data-guardian-phone="{{ $donor->guardian_phone }}"
                                        onclick="openEditModal(this)">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <button type="button" class="icon-btn danger" title="Delete" aria-label="Delete donor" onclick="deleteDonor({{ $donor->id }})">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr id="noDataRow">
                        <td colspan="6" class="empty-row">
                            <span class="ring"><i class="fa-solid fa-user-plus"></i></span>
                            <strong>No donors registered yet</strong>
                            Use the Add Donor button to register the first one.
                        </td>
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
                    <h5 class="modal-title fw-bold"><span class="m-ico"><i class="fa-solid fa-user-plus"></i></span>Add New Blood Donor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modalErrors" class="alert alert-danger d-none"></div>

                    <div class="mb-3">
                        <label class="form-label">Full name</label>
                        <input type="text" name="name" class="form-control" placeholder="Donor's full name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Personal phone number</label>
                        <input type="text" name="phone" class="form-control" placeholder="03001234567" inputmode="tel" required>
                        <div class="sub-line"><i class="fa-solid fa-lock me-1"></i>This number will not be shown publicly for female donors.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email address <span class="text-muted small">(for urgent blood alerts)</span></label>
                        <input type="email" name="email" class="form-control" placeholder="donor@example.com">
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
                                <input type="text" name="guardian_phone" class="form-control" placeholder="03009876543" inputmode="tel">
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
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_available" value="1" id="isAvailable" checked>
                        <label class="form-check-label fw-semibold" for="isAvailable">Available to donate</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="notify_urgent_requests" value="1" id="notifyAlerts" checked>
                        <label class="form-check-label fw-semibold text-danger" for="notifyAlerts">
                            <i class="fa-solid fa-envelope me-1"></i> Send emergency blood request alerts
                        </label>
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
                    <h5 class="modal-title fw-bold"><span class="m-ico"><i class="fa-solid fa-pen"></i></span>Edit Blood Donor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <input type="text" name="phone" id="edit_phone" class="form-control" inputmode="tel" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email address <span class="text-muted small">(for urgent blood alerts)</span></label>
                        <input type="email" name="email" id="edit_email" class="form-control" placeholder="donor@example.com">
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
                                <input type="text" name="guardian_phone" id="edit_guardian_phone" class="form-control" inputmode="tel">
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
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="available" value="1" id="edit_isAvailable">
                        <label class="form-check-label fw-semibold" for="edit_isAvailable">Available for donation</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="notify_urgent_requests" value="1" id="edit_notify_urgent_requests">
                        <label class="form-check-label fw-semibold text-danger" for="edit_notify_urgent_requests">
                            <i class="fa-solid fa-envelope me-1"></i> Send emergency blood request alerts
                        </label>
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
    document.getElementById('edit_email').value = d.email || '';
    document.getElementById('edit_blood_group').value = d.group;
    document.getElementById('edit_city').value = d.city;
    document.getElementById('edit_isAvailable').checked = d.available === '1';
    document.getElementById('edit_notify_urgent_requests').checked = d.alert !== '0';
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
                <td>
                    <div class="donor-cell">
                        <span class="avatar" aria-hidden="true">${esc((d.name || '').charAt(0).toUpperCase())}</span>
                        <span class="donor-name">${esc(d.name)}</span>
                    </div>
                </td>
                <td><span class="group-pill donor-blood-group">${esc(d.blood_group)}</span></td>
                <td>${phoneCellHtml(d)}</td>
                <td class="donor-city">${esc(d.city)}</td>
                <td>${statusTag(d.available)}</td>
                <td class="text-end">
                    <div class="actions">
                        <button type="button" class="icon-btn" title="Copy donor link" aria-label="Copy donor link" onclick="copyDonorLink(${d.id})">
                            <i class="fa-solid fa-link"></i>
                        </button>
                        <button type="button" class="icon-btn edit" title="Edit" aria-label="Edit donor"
                            data-id="${d.id}" data-name="${esc(d.name)}" data-phone="${esc(d.phone)}"
                            data-email="${esc(d.email || '')}" data-alert="${d.notify_urgent_requests ? 1 : 0}"
                            data-group="${esc(d.blood_group)}" data-city="${esc(d.city)}"
                            data-available="${d.available ? 1 : 0}" data-gender="${esc(d.gender)}"
                            data-guardian-name="${esc(d.guardian_name)}" data-guardian-relation="${esc(d.guardian_relation)}"
                            data-guardian-phone="${esc(d.guardian_phone)}" onclick="openEditModal(this)">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button type="button" class="icon-btn danger" title="Delete" aria-label="Delete donor" onclick="deleteDonor(${d.id})">
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
                // keep the avatar initial in sync with the name (UI only)
                const av = row.querySelector('.avatar');
                if (av) av.innerText = (d.name || '').charAt(0).toUpperCase();
                row.querySelector('.donor-blood-group').innerText = d.blood_group;
                row.children[2].innerHTML = phoneCellHtml(d);
                row.querySelector('.donor-city').innerText = d.city;

                // Only plain Available/Unavailable tags carry .donor-status (cooldown/pending tags stay as they are)
                const tag = row.querySelector('.donor-status');
                if (tag) tag.outerHTML = statusTag(d.available);

                const editBtn = row.querySelector('.icon-btn.edit');
                Object.assign(editBtn.dataset, {
                    name: d.name, phone: d.phone, email: d.email || '',
                    group: d.blood_group, city: d.city, available: d.available ? 1 : 0,
                    alert: d.notify_urgent_requests ? 1 : 0,
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