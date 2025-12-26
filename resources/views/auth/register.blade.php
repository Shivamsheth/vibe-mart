<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VibeMart - Create Account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: radial-gradient(circle at top left, #4f46e5 0, #111827 55%, #020617 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0f172a;
        }
        .auth-wrapper {
            max-width: 1100px;
            width: 100%;
        }
        .brand-panel {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #e5e7eb;
            border-radius: 1rem 0 0 1rem;
            padding: 3rem 2.75rem;
        }
        .brand-logo {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .brand-chip {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .8rem;
            border-radius: 999px;
            background: rgba(15,23,42,.35);
            font-size: .75rem;
            color: #e5e7eb;
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
        .input-with-icon input,
        .input-with-icon select,
        .input-with-icon textarea {
            padding-left: 2.2rem;
        }
        .form-label {
            font-weight: 600;
            font-size: .85rem;
            color: #4b5563;
        }
        .form-control,
        .form-select,
        textarea {
            border-radius: .6rem !important;
            border-color: #e5e7eb;
        }
        .form-control:focus,
        .form-select:focus,
        textarea:focus {
            box-shadow: 0 0 0 .15rem rgba(79,70,229,.25);
            border-color: #4f46e5;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border: none;
            border-radius: .8rem;
            font-weight: 600;
        }
        .btn-primary:hover {
            filter: brightness(1.05);
        }
        .badge-role {
            font-size: .7rem;
            padding: .25rem .55rem;
            border-radius: 999px;
        }
        .invalid-feedback {
            font-size: .75rem;
        }
        @media (max-width: 991.98px) {
            .brand-panel {
                display: none;
            }
            .form-panel {
                border-radius: 1rem;
            }
        }
    </style>
</head>
<body>
<div class="auth-wrapper px-3">
    <div class="row g-0 shadow-lg rounded-4 overflow-hidden">
        <!-- Left brand / marketing panel -->
        <div class="col-lg-5 brand-panel d-flex flex-column justify-content-between">
            <div>
                <div class="brand-logo mb-4">
                    Vibe<span class="fw-light">Mart</span>
                </div>
                <div class="brand-chip mb-3">
                    <span class="badge bg-success rounded-pill">New</span>
                    <span>Multi-role e‑commerce platform</span>
                </div>
                <h2 class="fw-semibold mb-3">Create your account</h2>
                <p class="mb-4 text-sm">
                    Become a <strong>customer</strong>, <strong>seller</strong>, or <strong>admin</strong> and manage your store with a secure OTP‑based login system.
                </p>
                <ul class="small ps-3">
                    <li>Secure authentication with email OTP</li>
                    <li>Role‑based access for Admin / Seller / Customer</li>
                    <li>Powered by Laravel, PostgreSQL & Redis queues</li>
                </ul>
            </div>

            <div class="small text-secondary">
                <span class="opacity-75">Already registered?</span>
                <a href="{{ route('login.view') }}" class="link-light fw-semibold text-decoration-none ms-1">
                    Sign in instead →
                </a>
            </div>
        </div>

        <!-- Right form panel -->
        <div class="col-lg-7 form-panel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">Sign up to VibeMart</h4>
                    <div class="text-muted small">Fill in your details to receive a verification OTP.</div>
                </div>
                <span class="badge rounded-pill bg-light text-secondary border">
                    Step 1 of 2 · <span class="text-primary fw-semibold">Account</span>
                </span>
            </div>

            <!-- Alert messages -->
            <div id="register-alert"></div>

            <form id="registerForm" novalidate>
                <div class="row g-3">
                    <!-- Name -->
                    <div class="col-md-6">
                        <label class="form-label">Full name</label>
                        <div class="position-relative input-with-icon">
                            <span class="input-icon">
                                <i class="bi bi-person"></i>
                            </span>
                            <input name="name" class="form-control" placeholder="John Doe" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label">Email address</label>
                        <div class="position-relative input-with-icon">
                            <span class="input-icon">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input name="email" type="email" class="form-control" placeholder="you@example.com" required>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <div class="position-relative input-with-icon">
                            <span class="input-icon">
                                <i class="bi bi-telephone"></i>
                            </span>
                            <input name="phone" class="form-control" placeholder="9876543210" required>
                        </div>
                    </div>

                    <!-- User type -->
                    <div class="col-md-6">
                        <label class="form-label">Account type</label>
                        <div class="position-relative input-with-icon">
                            <span class="input-icon">
                                <i class="bi bi-person-badge"></i>
                            </span>
                            <select name="type" class="form-select" required>
                                <option value="" disabled selected>Select role</option>
                                <option value="customer">Customer</option>
                                <option value="seller">Seller</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mt-1 small text-muted">
                            <span class="badge-role bg-light text-secondary border me-1">Customer</span>
                            <span class="badge-role bg-light text-secondary border me-1">Seller</span>
                            <span class="badge-role bg-light text-secondary border">Admin</span>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <div class="position-relative input-with-icon">
                            <span class="input-icon">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input name="password" type="password" class="form-control" placeholder="Min 8 characters" required>
                        </div>
                    </div>

                    <!-- Confirm password -->
                    <div class="col-md-6">
                        <label class="form-label">Confirm password</label>
                        <div class="position-relative input-with-icon">
                            <span class="input-icon">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input name="confirm_password" type="password" class="form-control" required>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <div class="position-relative input-with-icon">
                            <span class="input-icon">
                                <i class="bi bi-geo-alt"></i>
                            </span>
                            <textarea name="address" class="form-control" rows="2" placeholder="House / Street / Landmark" required></textarea>
                        </div>
                    </div>

                    <!-- City / State / Pincode / Country -->
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input name="city" class="form-control" placeholder="Ahmedabad" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">State</label>
                        <input name="state" class="form-control" placeholder="Gujarat" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Pincode</label>
                        <input name="pincode" class="form-control" placeholder="380001" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Country</label>
                        <input name="country" class="form-control" value="India" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
                    <div class="form-check small text-muted">
                        <input class="form-check-input" type="checkbox" id="termsCheck" checked>
                        <label class="form-check-label" for="termsCheck">
                            I agree to the terms & privacy policy.
                        </label>
                    </div>
                    <small class="text-muted">You will receive an OTP on email for verification.</small>
                </div>

                <button class="btn btn-primary w-100 py-2" type="submit" id="submitBtn">
                    <span class="spinner-border spinner-border-sm me-2 d-none" id="loadingSpinner"></span>
                    Create account & send OTP
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Icons + Bootstrap JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const form       = document.getElementById('registerForm');
const alertBox   = document.getElementById('register-alert');
const submitBtn  = document.getElementById('submitBtn');
const spinner    = document.getElementById('loadingSpinner');

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

    // UI loading state
    submitBtn.disabled = true;
    spinner.classList.remove('d-none');

    try {
        const res = await fetch('/api/auth/register', {
            method: 'POST',
            headers: {
                'Content-Type':'application/json',
                'Accept':'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });

        const json = await res.json();

        if (json.success) {
    showAlert('success', `${json.message}<br><small class="text-success">OTP sent to <strong>${json.data.email}</strong></small>`);

    // Small delay so user can see the message, then redirect to OTP page
    setTimeout(() => {
        // pass email as query param so you can prefill it on OTP page if you want
        const email = encodeURIComponent(json.data.email);
        window.location.href = `/verify-otp?email=${email}`;
    }, 800);
}
 else {
            showAlert('danger', json.message || 'Validation failed.');

            if (json.errors) {
                Object.entries(json.errors).forEach(([field, message]) => {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        input.insertAdjacentHTML('afterend',
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
