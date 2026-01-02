    {{-- resources/views/customer/cart.blade.php --}}
    @extends('layouts.public')

    @section('title', 'Shopping Cart - VibeMart')

    @section('content')
    <div class="row g-4 g-lg-5">

        {{-- 🔥 LEFT: Cart Items --}}
        <div class="col-lg-8">
            <div class="card-soft p-lg-5 p-4 shadow-xl rounded-3">

                @if(empty($cart))
                    <div class="text-center py-10">
                        <i class="fas fa-shopping-cart fa-5x text-muted-soft opacity-25 mb-4"></i>
                        <h4 class="mb-3 text-muted-soft fw-semibold">Your cart is empty</h4>
                        <p class="text-muted-soft mb-5">Looks like you haven't added anything yet.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-5 py-3 fw-semibold shadow-lg">
                            <i class="fas fa-store me-2"></i>Start Shopping
                        </a>
                    </div>
                @else
                    <div class="table-responsive table-responsive-custom">
                        <table class="table table-dark table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($cart as $id => $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $item['image'] }}"
                                                style="width:64px;height:64px;object-fit:cover"
                                                class="rounded shadow-sm"
                                                onerror="this.src='{{ asset('images/no-image.jpg') }}'">
                                            <div>
                                                <div class="fw-semibold product-name">
                                                    {{ Str::limit($item['name'], 45) }}
                                                </div>
                                                <small class="text-muted-soft">
                                                    ₹{{ number_format($item['price'], 0) }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Quantity --}}
                                    <td class="text-center">
                                        <button class="btn btn-sm qty-minus" data-id="{{ $id }}">−</button>
                                        <span class="mx-2 fw-bold qty-display" data-id="{{ $id }}">
                                            {{ $item['quantity'] }}
                                        </span>
                                        <button class="btn btn-sm qty-plus" data-id="{{ $id }}">+</button>
                                    </td>

                                    {{-- Unit Price --}}
                                    <td class="text-end">
                                        ₹{{ number_format($item['price'], 0) }}
                                    </td>

                                    {{-- Total --}}
                                    <td class="text-end">
                                        ₹{{ number_format($item['price'] * $item['quantity'], 0) }}
                                    </td>

                                    {{-- Remove --}}
                                    <td class="text-center">
                                        <form method="POST"
                                            action="{{ route('cart.remove', $id) }}"
                                            class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-light">
                            ← Continue Shopping
                        </a>

                        <form method="POST" action="{{ route('cart.clear') }}" class="clear-cart-form">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                Clear Cart
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        {{-- 🔥 RIGHT: Order Summary --}}
        <div class="col-lg-4">
            <div class="card-soft-summary p-4 shadow-xl rounded-3 sticky-top">

                <h4 class="fw-bold mb-3">
                    <i class="fas fa-receipt text-primary me-2"></i>Order Summary
                </h4>

                <div class="d-flex justify-content-between mb-2">
                    <span>{{ $totalItems }} Items</span>
                    <span>₹{{ number_format($subtotal, 0) }}</span>
                </div>

                <div class="d-flex justify-content-between text-success mb-3">
                    <span>Shipping</span>
                    <span>FREE</span>
                </div>

                <hr>

                <div class="d-flex justify-content-between fw-bold fs-4 mb-4">
                    <span>Total</span>
                    <span class="text-primary">
                        ₹{{ number_format($subtotal, 0) }}
                    </span>
                </div>

                {{-- 🔥 Checkout Button --}}
                <button type="button"
                        id="checkoutBtn"
                        class="btn btn-success btn-lg w-100 fw-bold"
                        {{ empty($cart) ? 'disabled' : '' }}>
                    <i class="fas fa-credit-card me-2"></i>
                    Proceed to Checkout
                </button>

                <div class="text-center mt-3">
                    <small class="text-muted-soft">
                        🔒 Secure checkout • Free shipping over ₹999
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔐 Server-trusted data --}}
    <script>
        window.checkoutCart   = @json($cart);
        window.checkoutTotal  = {{ $subtotal }};
    </script>
    @endsection

    @push('scripts')
    @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ===============================
       CHECKOUT BUTTON HANDLER
    =============================== */

    const checkoutBtn = document.getElementById('checkoutBtn');

    if (!checkoutBtn) return;

    checkoutBtn.addEventListener('click', function () {

        // Prevent double click
        if (checkoutBtn.disabled) return;

        checkoutBtn.disabled = true;
        const originalHTML = checkoutBtn.innerHTML;

        checkoutBtn.innerHTML =
            '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

        fetch('{{ route("checkout.order-summary") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw response;
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.redirect) {
                // ✅ Redirect ONLY to GET route
                window.location.href = data.redirect;
            } else {
                throw new Error(data.message || 'Checkout failed'); 
            }
        })
        .catch(async (error) => {
            let message = 'Something went wrong. Please try again.';

            if (error.json) {
                const err = await error.json();
                message = err.message || message;
            }

            alert(message);

            checkoutBtn.disabled = false;
            checkoutBtn.innerHTML = originalHTML;
        });
    });

});
</script>
@endpush

