<!-- @extends('layouts.public')

@section('title', 'Secure Checkout - VibeMart')

@section('content')
<div class="row g-4 g-lg-5">

    {{-- 🔥 LEFT: Checkout --}}
    <div class="col-lg-7">
        <div class="vm-card p-lg-5 p-4">

            <h3 class="fw-bold mb-4">
                <i class="fas fa-lock text-success me-2"></i>
                Secure Checkout
            </h3>

            <form id="checkoutForm">

                {{-- Customer --}}
                <h6 class="section-title">Customer Details</h6>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control vm-input" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control vm-input" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control vm-input" required>
                </div>

                {{-- Address --}}
<h6 class="section-title mt-4">
    <i class="fas fa-map-marker-alt me-2"></i>Delivery Address
</h6>

<div class="mb-3">
    <label class="form-label">Address Line 1</label>
    <input type="text"
           name="address_line1"
           class="form-control vm-input"
           placeholder="House no, Building, Street"
           required>
</div>

<div class="mb-3">
    <label class="form-label">Address Line 2</label>
    <input type="text"
           name="address_line2"
           class="form-control vm-input"
           placeholder="Area, Locality, Landmark (optional)">
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">City</label>
        <input type="text"
               name="city"
               class="form-control vm-input"
               required>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">State</label>
        <input type="text"
               name="state"
               class="form-control vm-input"
               required>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Pincode</label>
        <input type="text"
               name="pincode"
               class="form-control vm-input"
               required>
    </div>
</div>


                {{-- Payment --}}
                <h6 class="section-title">Payment Method</h6>

                <select name="payment_method" id="payment_method" class="form-select vm-input mb-3" required>
                    <option value="">Select Method</option>
                    <option value="card">💳 Card</option>
                    <option value="upi">📱 UPI</option>
                    <option value="cash">💵 Cash (Not Allowed)</option>
                </select>

                {{-- Card --}}
                <div id="cardSection" class="d-none">
                    <div class="vm-info-box mb-3">
                        <i class="fas fa-shield-alt me-2"></i> Secure & Encrypted Payment
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Card Number</label>
                        <input type="text" class="form-control vm-input" placeholder="1234 5678 9012 3456">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expiry</label>
                            <input type="text" class="form-control vm-input" placeholder="MM/YY">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">CVV</label>
                            <input type="password" class="form-control vm-input">
                        </div>
                    </div>
                </div>

                <button class="btn vm-btn-success w-100 py-3 fw-bold mt-4">
                    <i class="fas fa-credit-card me-2"></i>
                    Pay Securely
                </button>
            </form>

            {{-- Spinner & Result --}}
            <div id="paymentState" class="mt-4 text-center d-none"></div>
        </div>
    </div>

    {{-- 🔥 RIGHT: Summary --}}
    <div class="col-lg-5">
        <div class="vm-summary p-lg-5 p-4 sticky-top">

            <h4 class="fw-bold mb-4">
                <i class="fas fa-receipt text-primary me-2"></i>
                Order Summary
            </h4>

            @foreach(session('cart', []) as $item)
                <div class="d-flex justify-content-between mb-3">
                    <span>{{ Str::limit($item['name'], 35) }} × {{ $item['quantity'] }}</span>
                    <span>₹{{ number_format($item['price'] * $item['quantity'], 0) }}</span>
                </div>
            @endforeach

            <hr>

            <div class="d-flex justify-content-between fs-4 fw-bold">
                <span>Total</span>
                <span class="text-primary">₹{{ number_format($subtotal ?? 0, 0) }}</span>
            </div>

            <div class="text-center mt-4 pt-3 border-top-custom">
                <i class="fas fa-lock text-success fs-4"></i>
                <small class="d-block mt-2 text-muted-soft">
                    Secure checkout • Free shipping over ₹999
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
.vm-card, .vm-summary {
    background: linear-gradient(145deg, rgba(15,15,41,0.95), rgba(25,25,65,0.95));
    border-radius: 18px;
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 20px 50px rgba(0,0,0,.5);
}

.vm-input {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    color: #fff;
}

.vm-input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 .2rem rgba(99,102,241,.25);
}

.vm-btn-success {
    background: linear-gradient(135deg,#22c55e,#16a34a);
    border: none;
}

.section-title {
    font-weight: 600;
    margin-bottom: 12px;
    color: #a5b4fc;
}

.vm-info-box {
    background: rgba(99,102,241,0.12);
    padding: 10px 14px;
    border-radius: 10px;
    font-size: .9rem;
}
</style>
@endpush
@push('scripts')
<script>
const form = document.getElementById('checkoutForm');
const stateBox = document.getElementById('paymentState');
const cardSection = document.getElementById('cardSection');

document.getElementById('payment_method').addEventListener('change', e => {
    cardSection.classList.toggle('d-none', e.target.value !== 'card');
});

form.addEventListener('submit', e => {
    e.preventDefault();

    form.classList.add('d-none');
    stateBox.classList.remove('d-none');
    stateBox.innerHTML = `
        <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
        <h5>Processing payment...</h5>
    `;

    setTimeout(() => {
        const states = ['success','declined','error'];
        const result = states[Math.floor(Math.random()*states.length)];

        if (result === 'success') {
            stateBox.innerHTML = `
                <i class="fas fa-check-circle text-success display-4 mb-3"></i>
                <h4>Payment Successful</h4>
                <p>Your order has been placed successfully.</p>
            `;
        } else if (result === 'declined') {
            stateBox.innerHTML = `
                <i class="fas fa-times-circle text-danger display-4 mb-3"></i>
                <h4>Payment Declined</h4>
                <p>Please try another payment method.</p>
            `;
        } else {
            stateBox.innerHTML = `
                <i class="fas fa-exclamation-triangle text-warning display-4 mb-3"></i>
                <h4>Something Went Wrong</h4>
                <p>Please retry after some time.</p>
            `;
        }
    }, 2500);
});
</script>
@endpush -->
