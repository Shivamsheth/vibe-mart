{{-- resources/views/customer/cart.blade.php --}}
@extends('layouts.public')

@section('title', 'Shopping Cart')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-soft p-4 shadow-lg">
            @if(empty($cart))
                <div class="text-center py-8">
                    <i class="fas fa-shopping-cart fa-4x text-muted-soft mb-4"></i>
                    <h5 class="mb-3 text-muted-soft">Your cart is empty</h5>
                    <p class="text-muted mb-4">Add some products to get started!</p>
                    <a href="{{ route('home') }}" class="btn btn-primary px-4">
                        <i class="fas fa-store me-2"></i>Start Shopping
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-center" style="width: 150px;">Quantity</th>
                                <th class="text-end" style="width: 130px;">Price</th>
                                <th class="text-end" style="width: 120px;">Total</th>
                                <th class="text-center" style="width: 80px;">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $id => $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $item['image'] }}" class="rounded shadow-sm" 
                                             style="width: 60px; height: 60px; object-fit: cover;" alt="">
                                        <div>
                                            <h6 class="mb-1">{{ Str::limit($item['name'], 40) }}</h6>
                                            <small class="text-muted">₹{{ number_format($item['price'], 0) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <button class="btn btn-sm btn-outline-light qty-minus" data-id="{{ $id }}">-</button>
                                        <span class="qty-display badge bg-secondary px-3 py-2 fs-6">{{ $item['quantity'] }}</span>
                                        <button class="btn btn-sm btn-outline-light qty-plus" data-id="{{ $id }}">+</button>
                                    </div>
                                </td>
                                <td class="text-end">
                                    ₹{{ number_format($item['price'], 0) }}
                                </td>
                                <td class="text-end fw-bold text-success h6">
                                    ₹{{ number_format($item['price'] * $item['quantity'], 0) }}
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('cart.remove', $id) }}" class="d-inline delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm p-2 text-danger shadow-none border-0 bg-transparent" 
                                                onclick="return confirm('Remove this item?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('home') }}" class="btn btn-outline-light flex-grow-1">
                        <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                    </a>
                    <form method="POST" action="{{ route('cart.clear') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger clear-cart-btn" onclick="return confirm('Clear entire cart? This cannot be undone.')">
                            <i class="fas fa-trash me-1"></i>Clear Cart
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card-soft p-4 sticky-top" style="top: 2rem;">
            <h5 class="mb-4">Order Summary</h5>
            <div class="mb-3 d-flex justify-content-between">
                <span>{{ $totalItems ?? 0 }} Items</span>
                <span class="h5">₹{{ number_format($subtotal ?? 0, 0) }}</span>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-between h3 mb-4 fw-bold text-primary">
                <span>Total:</span>
                <span>₹{{ number_format($subtotal ?? 0, 0) }}</span>
            </div>
            @if(!empty($cart))
            <a href="#" class="btn btn-success w-100 btn-lg mb-3">
                <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
            </a>
            @endif
            <div class="text-center py-3 border-top">
                <small class="text-muted">🛡️ Secure checkout</small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity +/- buttons
    document.querySelectorAll('.qty-plus, .qty-minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.id;
            const isPlus = this.classList.contains('qty-plus');
            const display = document.querySelector(`[data-id="${productId}"]`).closest('td').querySelector('.qty-display');
            let currentQty = parseInt(display.textContent);
            let newQty = isPlus ? currentQty + 1 : Math.max(1, currentQty - 1);
            
            fetch('{{ route("cart.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: newQty
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    display.textContent = newQty;
                    // Reload page to update totals (or implement live update)
                    setTimeout(() => location.reload(), 100);
                }
            });
        });
    });

    // Form delete confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush
