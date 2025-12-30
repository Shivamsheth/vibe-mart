{{-- resources/views/seller/products/index.blade.php --}}
@extends('layouts.seller')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'My Products')
@section('page_title', 'My Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h6 class="mb-0">All Products ({{ $products->total() ?? 0 }})</h6>
    <a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i>Add Product
    </a>
</div>

<div class="row g-4 g-lg-3" id="productsContainer">
    @forelse($products as $product)
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 product-card-wrapper">
        <div class="card-soft h-100 position-relative overflow-hidden">
            {{-- Product Image - FIXED PREVIEW --}}
            <div class="position-relative overflow-hidden p-3 image-container" style="height: 180px;">
                @if($product->images->first())
                    <img src="{{ Storage::url($product->images->first()->path) }}" 
                         alt="{{ $product->name }}" 
                         class="product-image rounded object-fit-cover w-100 h-100 shadow-sm"
                         style="transition: transform 0.3s ease-in-out; object-fit: cover;"
                         loading="lazy">
                    {{-- FALLBACK - Fixed positioning --}}
                    <div class="fallback-image bg-secondary rounded d-flex align-items-center justify-content-center position-absolute top-0 start-0 w-100 h-100 d-none z-3">
                        <i class="fas fa-image text-muted fs-4"></i>
                    </div>
                @else
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center w-100 h-100">
                        <i class="fas fa-image text-muted fs-3"></i>
                    </div>
                @endif
            </div>

            {{-- Product Info --}}
            <div class="card-body p-3 pt-2">
                <div class="mb-2">
                    @if($product->category)
                        <small class="badge bg-secondary bg-opacity-50 text-xs px-2 py-1">
                            {{ $product->category->name }}
                        </small>
                    @endif
                </div>
                
                <h6 class="card-title mb-2 lh-sm">
                    <a href="{{ route('seller.products.edit', $product) }}" class="text-white text-decoration-none fw-semibold hover-text-primary">
                        {{ Str::limit($product->name, 35) }}
                    </a>
                </h6>
                
                @if($product->short_description)
                    <small class="text-muted-soft d-block mb-2">{{ Str::limit($product->short_description, 50) }}</small>
                @endif

                {{-- Price --}}
                <div class="mb-3">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <div class="d-flex align-items-baseline gap-1 mb-1">
                            <span class="h6 mb-0 fw-bold text-success">₹{{ number_format($product->sale_price, 0) }}</span>
                            <span class="text-muted-soft fs-6"><s>₹{{ number_format($product->price, 0) }}</s></span>
                        </div>
                    @else
                        <span class="h6 mb-0 fw-bold text-primary">₹{{ number_format($product->price, 0) }}</span>
                    @endif
                </div>

                {{-- Stock Status --}}
                <div class="mb-2">
                    <span class="badge {{ $product->stock_quantity <= ($product->stock_alert ?? 0) ? 'bg-warning text-dark' : 'bg-success' }} fs-6 px-2 py-1 w-100">
                        {{ $product->stock_quantity }} {{ $product->stock_quantity === 1 ? 'item' : 'items' }} in stock
                    </span>
                </div>

                {{-- Status Badge --}}
                @php
                    $statusClass = match($product->status) {
                        'active' => 'bg-success',
                        'inactive' => 'bg-secondary',
                        'pending' => 'bg-warning text-dark',
                        default => 'bg-secondary'
                    };
                    $statusLabel = match($product->status) {
                        'active' => 'Active',
                        'inactive' => 'Inactive', 
                        'pending' => 'Pending',
                        default => 'Unknown'
                    };
                    $statusIcon = match($product->status) {
                        'active' => 'fa-check-circle',
                        'inactive' => 'fa-pause-circle',
                        'pending' => 'fa-clock',
                        default => 'fa-question-circle'
                    };
                    $nextAction = match($product->status) {
                        'pending' => 'Activate',
                        'active' => 'Deactivate', 
                        'inactive' => 'Activate',
                        default => 'Unknown'
                    };
                    $nextStatus = match($product->status) {
                        'pending' => 'active',
                        'active' => 'inactive',
                        'inactive' => 'active',
                        default => 'pending'
                    };
                @endphp
                <div class="mb-3">
                    <span class="badge {{ $statusClass }} fs-6 px-2 py-1 w-100 d-block">
                        <i class="fas {{ $statusIcon }} me-1"></i>{{ $statusLabel }}
                    </span>
                </div>

                {{-- Action Buttons --}}
                <div class="d-grid gap-1">
                    <a href="{{ route('seller.products.edit', $product) }}" 
                       class="btn btn-outline-light btn-sm fw-semibold py-1 px-2">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <button class="btn btn-outline-warning btn-sm fw-semibold py-1 px-2 toggle-status" 
                            data-product-id="{{ $product->id }}"
                            data-current-status="{{ $product->status }}"
                            data-next-status="{{ $nextStatus }}"
                            data-action="{{ strtolower($nextAction) }}">
                        <i class="fas {{ $product->status === 'pending' ? 'fa-check' : ($product->status === 'active' ? 'fa-eye-slash' : 'fa-eye') }} me-1"></i>
                        {{ $nextAction }}
                    </button>
                    <button class="btn btn-outline-danger btn-sm fw-semibold py-1 px-2 delete-product" 
                            data-product-id="{{ $product->id }}">
                        <i class="fas fa-trash me-1"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 text-muted-soft empty-state">
            <i class="fas fa-box-open fa-4x mb-4 d-block opacity-50"></i>
            <h4 class="mb-3">No products found</h4>
            <p class="lead mb-4">Get started by creating your first product.</p>
            <a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-lg px-4">
                <i class="fas fa-plus me-2"></i>Create Your First Product
            </a>
        </div>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($products->hasPages())
<div class="row">
    <div class="col-12">
        <nav aria-label="Products pagination" class="d-flex justify-content-center">
            {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
        </nav>
    </div>
</div>
@endif

<div id="products-alert" class="mt-3"></div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alertBox = document.getElementById('products-alert');

    // 🔥 FIXED IMAGE PREVIEW + HOVER SCALE
    document.querySelectorAll('.product-card-wrapper').forEach(wrapper => {
        const image = wrapper.querySelector('.product-image');
        const fallback = wrapper.querySelector('.fallback-image');
        
        if (image) {
            // Fix image load error
            image.addEventListener('error', function() {
                this.style.display = 'none';
                if (fallback) fallback.style.display = 'flex';
            });
            
            // Image hover scale effect
            wrapper.addEventListener('mouseenter', function() {
                // Scale image
                if (image) {
                    image.style.transform = 'scale(1.1)';
                }
                // Lift card
                this.querySelector('.card-soft').style.transform = 'translateY(-8px)';
            });
            
            wrapper.addEventListener('mouseleave', function() {
                // Reset image scale
                if (image) {
                    image.style.transform = 'scale(1)';
                }
                // Reset card lift
                this.querySelector('.card-soft').style.transform = 'translateY(0)';
            });
        }
    });

    // Toggle status
    document.querySelectorAll('.toggle-status').forEach(btn => {
        btn.addEventListener('click', async function() {
            const productId = this.dataset.productId;
            const action = this.dataset.action;
            
            if (!confirm(`Are you sure you want to ${action.replace(/^\w/, c => c.toUpperCase())} this product?`)) {
                return;
            }

            const originalHTML = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';

            try {
                const res = await fetch(`/seller/products/${productId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 
                                       document.querySelector('input[name="_token"]')?.value || Laravel.csrfToken()
                    }
                });

                const json = await res.json();
                
                if (json.success) {
                    location.reload();
                } else {
                    showAlert(json.message || `Failed to ${action} product`, 'danger');
                }
            } catch (err) {
                console.error('Toggle error:', err);
                showAlert('Network error. Please try again.', 'danger');
            } finally {
                this.disabled = false;
                this.innerHTML = originalHTML;
            }
        });
    });

    // Delete product
    document.querySelectorAll('.delete-product').forEach(btn => {
        btn.addEventListener('click', async function() {
            const productId = this.dataset.productId;
            
            if (!confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                return;
            }

            const originalHTML = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Deleting...';

            try {
                const res = await fetch(`/seller/products/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 
                                       document.querySelector('input[name="_token"]')?.value || Laravel.csrfToken()
                    }
                });

                const json = await res.json();
                
                if (json.success) {
                    location.reload();
                } else {
                    showAlert(json.message || 'Failed to delete product', 'danger');
                }
            } catch (err) {
                console.error('Delete error:', err);
                showAlert('Network error. Please try again.', 'danger');
            } finally {
                this.disabled = false;
                this.innerHTML = originalHTML;
            }
        });
    });

    function showAlert(message, type) {
        alertBox.innerHTML = `<div class="alert alert-${type} py-2 mb-0 shadow-sm">${message}</div>`;
        setTimeout(() => {
            alertBox.innerHTML = '';
        }, 5000);
    }
});
</script>
@endpush
    