<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resend OTP</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h3 class="text-center mb-4">Resend OTP</h3>

            <div id="resend-alert"></div>

            <form id="resendForm">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" required>
                </div>
                <button class="btn btn-warning w-100" type="submit">Resend OTP</button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('resendForm').addEventListener('submit', async e => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(e.target));

    const res = await fetch('/api/auth/resend-otp', {
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'Accept':'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    });

    const json = await res.json();
    document.getElementById('resend-alert').innerHTML =
        `<div class="alert alert-${json.success ? 'success':'danger'}">${json.message}</div>`;
});
</script>
</body>
</html>
