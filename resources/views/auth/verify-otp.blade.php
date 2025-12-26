<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VibeMart - Verify OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: radial-gradient(circle at top left, #4f46e5 0, #111827 55%, #020617 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0f172a;
        }
        .auth-wrapper { max-width: 900px; width: 100%; }
        .brand-panel {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #e5e7eb;
            border-radius: 1rem 0 0 1rem;
            padding: 2.5rem 2.5rem;
        }
        .brand-logo {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .form-panel {
            background: #f9fafb;
            border-radius: 0 1rem 1rem 0;
            padding: 2.5rem 2.75rem;
        }
        .input-icon {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: .9rem;
        }
        .input-with-icon input {
            padding-left: 2.2rem;
        }
        .form-label {
            font-weight: 600;
            font-size: .85rem;
            color: #4b5563;
        }
        .form-control {
            border-radius: .6rem !important;
            border-color: #e5e7eb;
        }
        .form-control:focus {
            box-shadow: 0 0 0 .15rem rgba(79,70,229,.25);
            border-color: #4f46e5;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border: none;
            border-radius: .8rem;
            font-weight: 600;
        }
        .btn-primary:hover { filter: brightness(1.05); }
        .otp-box {
            letter-spacing: .4em;
            font-size: 1.6rem;
            text-align: center;
        }
        @media (max-width: 991.98px) {
            .brand-panel { display: none; }
            .form-panel { border-radius: 1rem; }
        }
    </style>
</head>
<body>
<div class="auth-wrapper px-3">
    <div class="row g-0 shadow-lg rounded-4 overflow-hidden">
        <!-- Left info panel -->
        <div class="col-lg-5 brand-panel d-flex flex-column justify-content-between">
            <div>
                <div class="brand-logo mb-4">
                    Vibe<span class="fw-light">Mart</span>
                </div>
                <h2 class="fw-semibold mb-2">Verify your email</h2>
                <p class="mb-4 small text-light">
                    Enter the 6‑digit OTP sent to your registered email address to activate your account.
                </p>
                <ul class="small ps-3">
                    <li>OTP is valid for 5 minutes.</li>
                    <li>Do not share the code with anyone.</li>
                    <li>You can request a new OTP if it expires.</li>
                </ul>
            </div>
            <div class="small text-secondary">
                <span class="opacity-75">Need a new code?</span>
                <a href="{{ route('resend.view') }}" class="link-light fw-semibold text-decoration-none ms-1">
                    Resend OTP →
                </a>
            </div>
        </div>

        <!-- Right form panel -->
        <div class="col-lg-7 form-panel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">Enter verification code</h4>
                    <div class="text-muted small">
                        We have sent a 6‑digit code to your email address.
                    </div>
                </div>
                <span class="badge rounded-pill bg-light text-secondary border">
                    Step 2 of 2 · <span class="text-primary fw-semibold">Verification</span>
                </span>
            </div>

            <!-- Alerts -->
            <div id="verify-alert"></div>

            <form id="verifyForm" novalidate>
                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Registered email</label>
                    <div class="position-relative input-with-icon">
                        <span class="input-icon">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input name="email" type="email" class="form-control" placeholder="you@example.com" required>
                    </div>
                </div>

                <!-- OTP -->
                <div class="mb-3">
                    <label class="form-label d-flex justify-content-between align-items-center">
                        <span>One‑time password (OTP)</span>
                        <span class="small text-muted">6‑digit code</span>
                    </label>
                    <div class="position-relative input-with-icon">
                        <span class="input-icon">
                            <i class="bi bi-shield-lock"></i>
                        </span>
                        <input name="otp" maxlength="6" class="form-control otp-box" placeholder="● ● ● ● ● ●" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <small class="text-muted">
                        Didn’t get the code?
                        <a href="{{ route('resend.view') }}" class="text-primary text-decoration-none">
                            Resend OTP
                        </a>
                    </small>
                    <small class="text-muted">
                        <i class="bi bi-clock-history me-1"></i>Expires in 5 minutes
                    </small>
                </div>

                <button class="btn btn-primary w-100 py-2" type="submit" id="submitBtn">
                    <span class="spinner-border spinner-border-sm me-2 d-none" id="loadingSpinner"></span>
                    Verify & activate account
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// prefill email from query string if present
const params = new URLSearchParams(window.location.search);
const emailFromQuery = params.get('email');
if (emailFromQuery) {
    const emailInput = document.querySelector('input[name="email"]');
    if (emailInput) emailInput.value = emailFromQuery;
}

const form      = document.getElementById('verifyForm');
const alertBox  = document.getElementById('verify-alert');
const submitBtn = document.getElementById('submitBtn');
const spinner   = document.getElementById('loadingSpinner');

function clearValidation() {
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
}
function showAlert(type, message) {
    alertBox.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

form.addEventListener('submit', async function (e) {
    e.preventDefault();
    clearValidation();
    alertBox.innerHTML = '';

    const data = Object.fromEntries(new FormData(form));

    submitBtn.disabled = true;
    spinner.classList.remove('d-none');

    try {
        const res  = await fetch('/api/auth/verify', {
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'Accept':'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        const json = await res.json();

        if (json.success) {
            // success: show message then go to login
            showAlert('success', json.message || 'Email verified successfully. Redirecting to login...');
            setTimeout(() => {
                window.location.href = '/login';
            }, 900);
        } else {
            // error: show message, stay on page
            showAlert('danger', json.message || 'Verification failed.');
            if (json.errors) {
                Object.entries(json.errors).forEach(([field, message]) => {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        input.insertAdjacentHTML(
                            'afterend',
                            `<div class="invalid-feedback d-block">${message}</div>`
                        );
                    }
                });
            }
        }
    } catch (err) {
        console.error(err);
        showAlert('danger', 'Network error. Please try again.');
    } finally {
        submitBtn.disabled = false;
        spinner.classList.add('d-none');
    }
});
</script>
</body>
</html>
