@extends('layouts.public')

@section('title', 'Secure Checkout - VibeMart')

@section('content')
<div class="row g-4 g-lg-5">

    {{-- 🔥 LEFT: Checkout Form --}}
    <div class="col-lg-7">
        <div class="card-soft p-lg-5 p-4 shadow-xl rounded-3">

            <h3 class="fw-bold mb-4">
                <i class="fas fa-lock text-success me-2"></i>
                Secure Checkout
            </h3>

            <form id="checkoutForm">

                {{-- ================= Customer Info ================= --}}
                <h6 class="fw-semibold mb-3 text-primary">
                    <i class="fas fa-user me-2"></i>Customer Information
                </h6>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control form-control-lg" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control form-control-lg" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control form-control-lg" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Delivery Address</label>
                    <textarea name="address" rows="2" class="form-control form-control-lg" required></textarea>
                </div>

                {{-- ================= Payment Method ================= --}}
                <h6 class="fw-semibold mb-3 text-primary">
                    <i class="fas fa-credit-card me-2"></i>Payment Method
                </h6>

                <div class="mb-3">
                    <select name="payment_method" id="payment_method" class="form-select form-select-lg" required>
                        <option value="">Select Payment Method</option>
                        <option value="card">💳 Credit / Debit Card</option>
                        <option value="upi">📱 UPI</option>
                        <option value="cash">💵 Cash (Not Allowed)</option>
                    </select>
                </div>

                {{-- ================= Card Details ================= --}}
                <div id="cardSection" class="d-none mt-3">

                    <div class="alert alert-info small mb-3">
                        <i class="fas fa-shield-alt me-1"></i>
                        Your card details are securely encrypted.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Card Number</label>
                        <input type="text" name="card_number" class="form-control form-control-lg" placeholder="1234 5678 9012 3456">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expiry</label>
                            <input type="text" name="expiry" class="form-control form-control-lg" placeholder="MM/YY">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">CVV</label>
                            <input type="password" name="cvv" class="form-control form-control-lg">
                        </div>
                    </div>
                </div>

                {{-- ================= Submit ================= --}}
                <button class="btn btn-success btn-lg w-100 py-3 fw-bold mt-4 shadow-lg">
                    <i class="fas fa-lock me-2"></i>Place Secure Order
                </button>
            </form>

            <div id="checkoutResponse" class="mt-4"></div>
        </div>
    </div>

    {{-- 🔥 RIGHT: Order Summary --}}
    <div class="col-lg-5">
        <div class="card-soft-summary p-lg-5 p-4 shadow-xl rounded-3 sticky-top">

            <h4 class="fw-bold mb-4">
                <i class="fas fa-receipt text-primary me-2"></i>
                Order Summary
            </h4>

            {{-- Products --}}
            @foreach(session('cart', []) as $item)
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="me-2">
                        <div class="fw-semibold">{{ Str::limit($item['name'], 40) }}</div>
                        <small class="text-muted-soft">Qty: {{ $item['quantity'] }}</small>
                    </div>
                    <div class="fw-semibold">
                        ₹{{ number_format($item['price'] * $item['quantity'], 0) }}
                    </div>
                </div>
            @endforeach

            <hr class="border-custom">

            {{-- Totals --}}
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted-soft">Subtotal</span>
                <span class="fw-semibold">₹{{ number_format($subtotal ?? 0, 0) }}</span>
            </div>

            <div class="d-flex justify-content-between mb-2 text-success">
                <span class="text-muted-soft">Shipping</span>
                <span class="fw-semibold">FREE</span>
            </div>

            <hr class="border-custom">

            <div class="d-flex justify-content-between fs-4 fw-bold">
                <span>Total</span>
                <span class="text-primary">₹{{ number_format($subtotal ?? 0, 0) }}</span>
            </div>

            {{-- Trust Badges --}}
            <div class="text-center mt-4 pt-3 border-top-custom">
                <i class="fas fa-lock text-success fs-4 me-2"></i>
                <i class="fas fa-shield-alt text-info fs-4 me-2"></i>
                <small class="d-block mt-2 text-muted-soft">
                    100% Secure • Encrypted Payments
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const paymentMethod = document.getElementById('payment_method');
const cardSection = document.getElementById('cardSection');
const form = document.getElementById('checkoutForm');
const responseBox = document.getElementById('checkoutResponse');

paymentMethod.addEventListener('change', () => {
    cardSection.classList.toggle('d-none', paymentMethod.value !== 'card');
});

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    responseBox.innerHTML = '';

    const res = await fetch("{{ url('/api/checkout/order') }}", {
        method: 'POST',
        headers: { 'Accept': 'application/json' },
        body: new FormData(form)
    });

    const data = await res.json();

    responseBox.innerHTML = `
        <div class="alert ${res.ok ? 'alert-success' : 'alert-danger'} shadow-sm">
            <pre class="mb-0">${JSON.stringify(data, null, 2)}</pre>
        </div>
    `;
});
</script>
@endpush
