<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VibeMart - Sign in</title>
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
        .input-with-icon input { padding-left: 2.2rem; }
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
        .link-muted { color: #6b7280; }
        .link-muted:hover { color: #4f46e5; }
        @media (max-width: 991.98px) {
            .brand-panel { display: none; }
            .form-panel { border-radius: 1rem; }
        }
    </style>
</head>
<body>
<div class="auth-wrapper px-3">
    <div class="row g-0 shadow-lg rounded-4 overflow-hidden">
        <div class="col-lg-5 brand-panel d-flex flex-column justify-content-between">
            <div>
                <div class="brand-logo mb-4">
                    Vibe<span class="fw-light">Mart</span>
                </div>
                <h2 class="fw-semibold mb-2">Welcome back</h2>
                <p class="mb-4 small text-light">
                    Sign in to manage your orders, products, or admin dashboard using your verified account.
                </p>
                <ul class="small ps-3">
                    <li>Login with email or phone + password.</li>
                    <li>Email must be verified via OTP before login.</li>
                    <li>Secure API powered by Laravel Sanctum.</li>
                </ul>
            </div>
            <div class="small text-secondary">
                <span class="opacity-75">New to VibeMart?</span>
                <a href="{{ route('register.view') }}" class="link-light fw-semibold text-decoration-none ms-1">
                    Create an account →
                </a>
            </div>
        </div>

        <div class="col-lg-7 form-panel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">Sign in to your account</h4>
                    <div class="text-muted small">
                        Use the same email / phone you registered with.
                    </div>
                </div>
                <span class="badge rounded-pill bg-light text-secondary border">
                    Step · <span class="text-primary fw-semibold">Login</span>
                </span>
            </div>

            <div id="login-alert"></div>

            <form id="loginForm" novalidate>
                <div class="mb-3">
                    <label class="form-label">Email or phone</label>
                    <div class="position-relative input-with-icon">
                        <span class="input-icon">
                            <i class="bi bi-person-circle"></i>
                        </span>
                        <input name="email" class="form-control"
                               placeholder="you@example.com or 9876543210" required>
                    </div>
                </div>

                <div class="mb-1">
                    <label class="form-label d-flex justify-content-between">
                        <span>Password</span>
                        <a href="#" class="small link-muted text-decoration-none">Forgot password?</a>
                    </label>
                    <div class="position-relative input-with-icon">
                        <span class="input-icon">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input name="password" type="password" class="form-control"
                               placeholder="Your password" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 small">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberCheck">
                        <label class="form-check-label" for="rememberCheck">
                            Keep me signed in
                        </label>
                    </div>
                    <span class="text-muted">Email must be verified.</span>
                </div>

                <button class="btn btn-primary w-100 py-2" type="submit" id="submitBtn">
                    <span class="spinner-border spinner-border-sm me-2 d-none" id="loadingSpinner"></span>
                    Sign in
                </button>

                <div class="text-center mt-3 small text-muted">
                    Don’t have an account?
                    <a href="{{ route('register.view') }}" class="text-primary text-decoration-none">
                        Register now
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
console.log('LOGIN SCRIPT LOADED');

const form      = document.getElementById('loginForm');
const alertBox  = document.getElementById('login-alert');
const submitBtn = document.getElementById('submitBtn');
const spinner   = document.getElementById('loadingSpinner');

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
    console.log('LOGIN SUBMIT FIRED');

    alertBox.innerHTML = '';
    const data = Object.fromEntries(new FormData(form));

    submitBtn.disabled = true;
    spinner.classList.remove('d-none');

    try {
        const res  = await fetch('/api/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type':'application/json',
                'Accept':'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });

        const json = await res.json();
        console.log('LOGIN JSON:', json);

        if (res.ok && json.success) {
            showAlert('success', json.message || 'Login successful. Redirecting...');

            let target = '/';
            if (json.data && json.data.redirect_to) {
                target = json.data.redirect_to;
            }
            if (json.redirect_to) {
                target = json.redirect_to;
            }

            setTimeout(() => {
                window.location.href = target;
            }, 800);
        } else {
            showAlert('danger', json.message || 'Login failed.');
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
