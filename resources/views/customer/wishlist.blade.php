{{-- resources/views/customer/wishlist.blade.php --}}
@extends('layouts.public')

@section('title', 'My Wishlist - VibeMart')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $wishlist = $wishlist ?? collect();

@endphp


@section('content')
<div class="row g-4 g-lg-5">

    {{-- 🔥 LEFT: Wishlist Items --}}
    <div class="col-lg-8">
        <div class="card-soft p-lg-5 p-4 shadow-xl rounded-3">

            @if($wishlist->isEmpty())
                <div class="text-center py-10">
                    <i class="fas fa-heart fa-5x text-muted-soft opacity-25 mb-4"></i>
                    <h4 class="mb-3 text-muted-soft fw-semibold">Your wishlist is empty</h4>
                    <p class="text-muted-soft mb-5">Save items you love and buy them later.</p>
                    <a href="{{ route('home') }}"
                       class="btn btn-primary btn-lg px-5 py-3 fw-semibold shadow-lg">
                        <i class="fas fa-store me-2"></i>Browse Products
                    </a>
                </div>
            @else
                <div class="table-responsive table-responsive-custom">
                    <table class="table table-dark table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-end">Price</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($wishlist as $item)
                            @continue(!$item->product)

                            @php
                                $product = $item->product;
                                $image = $product->main_image
                                    ? Storage::url($product->main_image->path)
                                    : asset('images/no-image.jpg');
                            @endphp

                            <tr>
                                {{-- Product --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $image }}"
                                             width="64" height="64"
                                             class="rounded shadow-sm object-fit-cover"
                                             onerror="this.src='{{ asset('images/no-image.jpg') }}'">

                                        <div>
                                            <div class="fw-semibold product-name">
                                                {{ Str::limit($product->name, 45) }}
                                            </div>
                                            <small class="text-muted-soft">Saved item</small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Price --}}
                                <td class="text-end fw-bold text-success">
                                    ₹{{ number_format($product->final_price, 0) }}
                                </td>

                                {{-- Actions --}}
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">

                                        <a href="{{ route('product.show', $product->slug) }}"
                                           class="btn btn-outline-light btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <button class="btn btn-success btn-sm add-to-cart"
                                                data-product-id="{{ $product->id }}">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>

                                        <button class="btn btn-danger btn-sm remove-wishlist"
                                                data-product-id="{{ $product->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('home') }}" class="btn btn-outline-light">
                        ← Continue Shopping
                    </a>
                </div>
            @endif

        </div>
    </div>

    {{-- 🔥 RIGHT: Wishlist Summary --}}
    <div class="col-lg-4">
        <div class="card-soft-summary p-4 shadow-xl rounded-3 sticky-top">

            <h4 class="fw-bold mb-3">
                <i class="fas fa-heart text-danger me-2"></i>Wishlist Summary
            </h4>

            <div class="d-flex justify-content-between mb-2">
                <span>Total Items</span>
                <span>{{ $wishlist->count() }}</span>
            </div>

            <hr>

            <div class="text-center">
                <small class="text-muted-soft">
                    ❤️ Items saved for later purchase
                </small>
            </div>

        </div>
    </div>
</div>
@endsection



    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        /* ===============================
        REMOVE FROM WISHLIST
        =============================== */
        document.querySelectorAll('.remove-wishlist').forEach(btn => {
            btn.addEventListener('click', async () => {

                if (!confirm('Remove this item from wishlist?')) return;

                try {
                    const res = await fetch('{{ route("wishlist.add") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            product_id: btn.dataset.productId
                        })
                    });

                    const data = await res.json();

                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.error || 'Failed to remove item');
                    }
                } catch {
                    alert('Network error. Try again.');
                }
            });
        });

        /* ===============================
        ADD TO CART FROM WISHLIST
        =============================== */
        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', async () => {

                btn.disabled = true;

                try {
                    const res = await fetch('{{ route("cart.add") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            product_id: btn.dataset.productId,
                            quantity: 1
                        })
                    });

                    const data = await res.json();

                    if (data.success) {
                        alert('Added to cart');
                    } else {
                        alert(data.error || 'Failed to add to cart');
                    }
                } catch {
                    alert('Network error');
                } finally {
                    btn.disabled = false;
                }
            });
        });

    });
    </script>
    @endpush
