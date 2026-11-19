<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Blood Exchange Offer - BE.DONOR</title>
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
            padding-bottom: 4rem;
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
        .form-label { font-size: .58rem; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; color: var(--muted); margin-bottom: .3rem; }
        .form-control, .form-select { height: 42px; padding: .4rem .75rem; border-radius: 10px; border: 1px solid var(--line); background-color: #fcfafa; font-size: .82rem; }
        textarea.form-control { height: auto; padding: .55rem .75rem; }
        .btn-bd {
            display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
            min-height: 44px; padding: 0 1.5rem; border: 0; border-radius: 999px;
            background: var(--red); color: #fff; font-weight: 700; font-size: .66rem; letter-spacing: .14em; text-transform: uppercase;
            box-shadow: 0 14px 26px -12px rgba(179, 0, 0, .75); text-decoration: none;
        }
        .btn-bd:hover { background: var(--red-2); color: #fff; transform: translateY(-1px); }
        .btn-bd:disabled { opacity: .7; transform: none; }
        /* =========================================================
   COMPACT FORM (Facebook-style) — paste at the END of <style>
   ========================================================= */

/* Card thoda narrow aur compact */
.col-lg-8 { max-width: 680px; }
.bd-panel { border-radius: 10px; }
.bd-panel.p-md-4 { padding: 1.2rem 1.3rem !important; }

/* Rows ki spacing kam */
#bloodExchangeForm { --bs-gutter-x: .7rem; --bs-gutter-y: .65rem; }

/* Labels: chhote, clean, sentence case */
#bloodExchangeForm .form-label {
    font-size: .76rem;
    font-weight: 600;
    letter-spacing: 0;
    text-transform: none;
    margin-bottom: .25rem;
}
#bloodExchangeForm .form-label:not(.text-danger):not(.text-primary) { color: #5b4a4d; }

/* Inputs / selects: 38px height, chhota radius */
#bloodExchangeForm .form-control,
#bloodExchangeForm .form-select {
    height: 38px;
    padding: .3rem .7rem;
    border-radius: 6px;
    border: 1px solid #dddfe2;
    background-color: #fff;
    font-size: .86rem;
    font-weight: 400;
    transition: border-color .15s, box-shadow .15s;
}
#bloodExchangeForm .form-select { padding-right: 2rem; }
#bloodExchangeForm textarea.form-control {
    height: auto;
    min-height: 68px;
    padding: .5rem .7rem;
    resize: vertical;
}
#bloodExchangeForm .form-control::placeholder { font-size: .82rem; color: #a8a0a2; }
#bloodExchangeForm .form-control:hover,
#bloodExchangeForm .form-select:hover { border-color: #c4c7cc; }
#bloodExchangeForm .form-control:focus,
#bloodExchangeForm .form-select:focus {
    border-color: var(--red);
    box-shadow: 0 0 0 2px rgba(179, 0, 0, .15);
}

/* Blood group pair box compact */
#bloodExchangeForm .rounded-4 { border-radius: 8px !important; padding: .7rem .8rem !important; }
#bloodExchangeForm h6 { font-size: .62rem !important; }
#bloodExchangeForm small.text-muted { font-size: .68rem !important; }

/* Button: 40px, rounded-rect, no heavy shadow */
#bloodExchangeForm .btn-bd {
    min-height: 40px;
    padding: 0 1rem;
    border-radius: 6px;
    font-size: .85rem;
    font-weight: 700;
    letter-spacing: 0;
    text-transform: none;
    box-shadow: none;
}
#bloodExchangeForm .btn-bd:hover { box-shadow: none; transform: none; }

/* Alert compact */
#exchangeFormStatus { font-size: .8rem; padding: .6rem .85rem; border-radius: 8px; }

/* Mobile: iOS zoom se bachne ke liye 16px zaroori hai */
@media (max-width: 575.98px) {
    #bloodExchangeForm .form-control,
    #bloodExchangeForm .form-select { font-size: 16px; height: 40px; }
    #bloodExchangeForm textarea.form-control { height: auto; }
}
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
                <li><a href="{{ route('blood-exchanges.index') }}" class="is-active">Exchange blood</a></li>
                <li><a href="{{ route('donor-contacts.my-requests') }}"><i class="fa-solid fa-clock-rotate-left me-1"></i> My requests</a></li>
                <li><a href="{{ route('blood-requests.index') }}">Urgent feed</a></li>
            </ul>
        </nav>

        <div class="py-4 text-center">
            <div class="text-uppercase small fw-bold tracking-widest text-white-50 mb-2">Mutual Donor Exchange</div>
            <h1 class="display-6 fw-bolder text-white mb-2">Post a Blood Exchange Offer</h1>
            <p class="text-white-50" style="max-width: 580px; margin: 0 auto;">
                Have donors willing to donate blood of one group, but need a different blood group for your patient? Submit an exchange offer below.
            </p>
        </div>
    </div>
</header>

<div class="container pb-5" style="margin-top: -3.5rem; position: relative; z-index: 5;">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="bd-panel p-3 p-md-4">
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('blood-exchanges.store') }}" id="bloodExchangeForm" class="row g-3">
                    @csrf

                    <!-- Exchange Groups Matchup -->
                    <div class="col-12">
                        <div class="p-2 rounded-4 bg-light border">
                            <h6 class="fw-bold text-uppercase text-danger mb-2" style="font-size: 0.68rem; letter-spacing: 0.12em;">
                                <i class="fa-solid fa-right-left me-2"></i>Blood Group Pair
                            </h6>
                            <div class="row g-2 align-items-center">
                                <div class="col-md-5">
                                    <label class="form-label text-danger">Blood Group You Have (Offered) <span class="text-danger">*</span></label>
                                    <select name="offered_blood_group" class="form-select border-danger-subtle" required>
                                        <option value="">Select blood you have</option>
                                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                            <option value="{{ $bg }}" {{ old('offered_blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted" style="font-size: 0.68rem;">Group your donor can give</small>
                                </div>
                                <div class="col-md-2 text-center d-none d-md-block">
                                    <i class="fa-solid fa-arrow-right-arrow-left fs-4 text-muted"></i>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label text-primary">Blood Group You Need <span class="text-danger">*</span></label>
                                    <select name="needed_blood_group" class="form-select border-primary-subtle" required>
                                        <option value="">Select blood you need</option>
                                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                            <option value="{{ $bg }}" {{ old('needed_blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted" style="font-size: 0.68rem;">Group your patient needs</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Units Available for Exchange <span class="text-danger">*</span></label>
                        <input type="number" name="units" class="form-control" min="1" max="20" value="{{ old('units', 1) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Urgency Level <span class="text-danger">*</span></label>
                        <select name="urgency_level" class="form-select" required>
                            <option value="Urgent (Within 6 hours)" {{ old('urgency_level') === 'Urgent (Within 6 hours)' ? 'selected' : '' }}>Urgent (Within 6 hours)</option>
                            <option value="Emergency (Immediate)" {{ old('urgency_level') === 'Emergency (Immediate)' ? 'selected' : '' }}>Emergency (Immediate)</option>
                            <option value="Routine (Within 24-48 hours)" {{ old('urgency_level') === 'Routine (Within 24-48 hours)' ? 'selected' : '' }}>Routine (Within 24-48 hours)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Patient Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="patient_name" class="form-control" value="{{ old('patient_name') }}" placeholder="Patient name" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control" value="{{ old('city') }}" placeholder="e.g. Sargodha" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Hospital / Blood Bank Location <span class="text-danger">*</span></label>
                        <input type="text" name="hospital" class="form-control" value="{{ old('hospital') }}" placeholder="e.g. DHQ Hospital Blood Bank" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                        <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name') }}" placeholder="Attendant / Family member" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" name="contact_phone" class="form-control" value="{{ old('contact_phone') }}" placeholder="e.g. 03001234567" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Contact Email (Optional, for tracking link)</label>
                        <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email') }}" placeholder="e.g. attendant@gmail.com">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Additional Medical Notes / Timing Details</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Explain patient status, exchange willingness, donor availability timings...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="col-12">
                        <div id="exchangeFormStatus" class="alert d-none mb-0" role="status" aria-live="polite"></div>
                    </div>

                    <div class="col-12 text-center pt-1">
                        <button type="submit" class="btn btn-bd w-100 py-2">
                            <i class="fa-solid fa-paper-plane me-2"></i> Submit Blood Exchange Offer
                        </button>
                        <div class="text-muted small mt-2">All exchange requests are verified by our system admin before appearing on the public board.</div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const exchangeForm = document.getElementById('bloodExchangeForm');
const exchangeFormStatus = document.getElementById('exchangeFormStatus');

function showExchangeFormStatus(message, type, trackingUrl) {
    exchangeFormStatus.className = 'alert alert-' + type + ' mb-0';
    exchangeFormStatus.replaceChildren(document.createTextNode(message));

    if (trackingUrl) {
        const link = document.createElement('a');
        link.href = trackingUrl;
        link.className = 'alert-link ms-1';
        link.textContent = 'Track this request';
        exchangeFormStatus.append(' ', link);
    }
}

if (exchangeForm) {
    exchangeForm.addEventListener('submit', async function (event) {
        event.preventDefault();
        const submitButton = exchangeForm.querySelector('button[type="submit"]');
        const originalHtml = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-1"></i> Submitting...';

        try {
            const response = await fetch(exchangeForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(exchangeForm)
            });
            let data;
            try {
                data = await response.json();
            } catch (error) {
                throw new Error('The server returned an invalid response. Please try again.');
            }

            if (!response.ok || !data.success) {
                const validationErrors = Object.values(data.errors || {}).flat();
                throw new Error(validationErrors.join(' ') || data.message || 'Could not submit the exchange offer.');
            }

            showExchangeFormStatus(data.message || 'Your exchange request has been submitted.', 'success', data.tracking_url);
            exchangeForm.reset();
        } catch (error) {
            showExchangeFormStatus(error.message || 'Could not submit the exchange offer. Please try again.', 'danger');
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = originalHtml;
        }
    });
}
</script>
</body>
</html>

