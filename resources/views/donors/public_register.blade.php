<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become a Blood Donor</title>
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
        }
        body { font-family: 'Manrope', system-ui, sans-serif; background: var(--bg); color: var(--ink); }

        .topbar { background: var(--red); box-shadow: 0 2px 12px rgba(140, 13, 33, .25); }
        .topbar .brand { color: #fff; font-weight: 800; font-size: 1.15rem; text-decoration: none; letter-spacing: -.2px; }
        .topbar .btn-join { background: #fff; color: var(--red); font-weight: 700; border: 0; border-radius: 999px; padding: .45rem 1.1rem; }
        .topbar .btn-join:hover { background: var(--rose); color: var(--red-dark); }

        .hero { background: linear-gradient(180deg, var(--red) 0%, var(--red-dark) 100%); color: #fff; padding: 2.5rem 0 6rem; }
        .hero h1 { font-weight: 800; font-size: clamp(1.7rem, 4vw, 2.4rem); letter-spacing: -.5px; margin-bottom: .4rem; }
        .hero p { color: rgba(255, 255, 255, .8); margin: 0; }

        .form-card { margin-top: -4rem; background: #fff; border-radius: 18px; padding: 2rem; box-shadow: 0 12px 32px rgba(28, 33, 48, .12); }
        .form-label { font-size: .85rem; font-weight: 700; color: var(--ink); margin-bottom: .3rem; }
        .form-control, .form-select { height: 48px; border-radius: 12px; border: 1.5px solid var(--line); font-weight: 500; }
        .form-control:focus, .form-select:focus { border-color: var(--red); box-shadow: 0 0 0 .2rem rgba(179, 18, 43, .15); }
        .hint { color: var(--muted); font-size: .8rem; margin-top: .35rem; display: block; }

        .guardian-box { background: var(--rose); border: 1.5px solid #f6c9d1; border-radius: 14px; padding: 1.1rem; }
        .guardian-box .title { color: var(--red-dark); font-weight: 800; font-size: .92rem; }
        .guardian-box .form-control, .guardian-box .form-select { background: #fff; }

        .form-check-input:checked { background-color: var(--red); border-color: var(--red); }
        .form-check-input:focus { box-shadow: 0 0 0 .2rem rgba(179, 18, 43, .2); border-color: var(--red); }

        .btn-register { height: 50px; background: var(--red); color: #fff; font-weight: 700; border: 0; border-radius: 12px; }
        .btn-register:hover { background: var(--red-dark); color: #fff; }
        .btn-register:disabled { opacity: .7; }

        .alert { border-radius: 12px; border: 0; font-weight: 500; }
        .alert-success { background: #e6f6ee; color: #146c43; }
        .alert-danger { background: var(--rose); color: var(--red-dark); }

        a:focus-visible, button:focus-visible { outline: 3px solid rgba(179, 18, 43, .4); outline-offset: 2px; }
    </style>
</head>
<body>

<nav class="topbar py-3">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="brand" href="{{ url('/') }}"><i class="fa-solid fa-droplet me-2"></i>Blood Donor Network</a>
        <a href="{{ route('donors.public.search') }}" class="btn btn-join btn-sm">
            <i class="fa-solid fa-magnifying-glass me-1"></i> Find a Donor
        </a>
    </div>
</nav>

<section class="hero">
    <div class="container text-center">
        <h1>Become a blood donor</h1>
        <p>Register once and help people find you when they urgently need blood.</p>
    </div>
</section>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="form-card">

                <div id="successBox" class="alert alert-success d-none"></div>
                <div id="errorBox" class="alert alert-danger d-none"></div>

                <form id="publicDonorForm">
                    <div class="mb-3">
                        <label class="form-label">Full name</label>
                        <input type="text" name="name" class="form-control" placeholder="Your full name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Personal phone number</label>
                        <input type="text" name="phone" class="form-control" placeholder="03001234567" required>
                        <small class="hint"><i class="fa-solid fa-lock me-1"></i>This number will not be shown publicly for female donors.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" id="genderSelect" class="form-select" required>
                            <option value="" disabled selected>Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Female privacy section -->
                    <div id="guardianSection" class="guardian-box mb-3 d-none">
                        <div class="title mb-1"><i class="fa-solid fa-shield-heart me-2"></i>Privacy protection (guardian contact)</div>
                        <p class="text-muted small mb-3">This contact will be shown on public search instead of your personal number.</p>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small">Guardian / Mehram name</label>
                                <input type="text" name="guardian_name" id="guardianName" class="form-control" placeholder="e.g. Ahmad Rao">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Relation</label>
                                <select name="guardian_relation" id="guardianRelation" class="form-select">
                                    <option value="Father">Father</option>
                                    <option value="Brother">Brother</option>
                                    <option value="Husband">Husband</option>
                                    <option value="Guardian">Other guardian</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small">Guardian phone / WhatsApp</label>
                                <input type="text" name="guardian_phone" id="guardianPhone" class="form-control" placeholder="03009876543">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Blood group</label>
                            <select name="blood_group" class="form-select" required>
                                <option value="">Select</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" placeholder="e.g. Sargodha" required>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="is_available" value="1" id="availableCheck" checked>
                        <label class="form-check-label fw-semibold" for="availableCheck">I am available to donate</label>
                    </div>

                    <button type="submit" id="submitBtn" class="btn btn-register w-100">
                        <i class="fa-solid fa-hand-holding-heart me-1"></i> Register now
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const genderSelect = document.getElementById('genderSelect');
const guardianSection = document.getElementById('guardianSection');
const guardianFields = guardianSection.querySelectorAll('input, select');
const submitLabel = '<i class="fa-solid fa-hand-holding-heart me-1"></i> Register now';

function toggleGuardian() {
    const isFemale = genderSelect.value === 'Female';
    guardianSection.classList.toggle('d-none', !isFemale);
    guardianFields.forEach(f => {
        f.disabled = !isFemale;              // disabled fields are not submitted
        f.required = isFemale && f.id !== 'guardianRelation';
    });
}

genderSelect.addEventListener('change', toggleGuardian);
toggleGuardian();

document.getElementById('publicDonorForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const btn = document.getElementById('submitBtn');
    const successBox = document.getElementById('successBox');
    const errorBox = document.getElementById('errorBox');

    btn.disabled = true;
    btn.innerText = 'Registering...';
    successBox.classList.add('d-none');
    errorBox.classList.add('d-none');

    const formData = new FormData(this);

    try {
        const response = await fetch("{{ route('donors.public.store') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrf,
                "Accept": "application/json"
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            // Save ownership in localStorage
            localStorage.setItem('my_donor_token', data.donor_token);
            localStorage.setItem('my_donor_id', data.donor_id);

            this.reset();
            toggleGuardian();
            successBox.innerHTML = `${data.message} <br><a href="{{ route('donors.public.search') }}" class="alert-link">View your card on Public Search</a>`;
            successBox.classList.remove('d-none');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            let errorText = data.message || 'Validation error!';
            if (data.errors) {
                errorText = Object.values(data.errors).flat().join('<br>');
            }
            errorBox.innerHTML = errorText;
            errorBox.classList.remove('d-none');
        }
    } catch (err) {
        errorBox.innerText = 'Network error! Please try again.';
        errorBox.classList.remove('d-none');
    } finally {
        btn.disabled = false;
        btn.innerHTML = submitLabel;
    }
});
</script>
</body>
</html>