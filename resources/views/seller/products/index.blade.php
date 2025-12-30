{{-- resources/views/seller/products/index.blade.php --}}
@extends('layouts.seller')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'My Products')
@section('page_title', 'My Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="mb-0">All Products ({{ $products->total() ?? 0 }})</h6>
    <a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i>Add Product
    </a>
</div>

<div class="card-soft">
    <div class="table-responsive">
        <table class="table table-dark table-sm mb-0">
            <thead>
                <tr>
                    <th width="60">Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->images->first())
                            <img src="{{ Storage::url($product->images->first()->path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="rounded" 
                                 style="width: 40px; height: 40px; object-fit: cover;"
                                 onerror="this.nextElementSibling.style.display='flex'; this.style.display='none';">
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center fallback-image" 
                                 style="width: 40px; height: 40px; display: none;">
                                <i class="fas fa-image text-muted fs-6"></i>
                            </div>
                        @else
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-image text-muted fs-6"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-medium">{{ Str::limit($product->name, 30) }}</div>
                        @if($product->short_description)
                            <small class="text-muted-soft">{{ Str::limit($product->short_description, 50) }}</small>
                        @endif
                        @if($product->category)
                            <small class="badge bg-secondary bg-opacity-50 text-xs">{{ $product->category->name }}</small>
                        @endif
                    </td>
                    <td>
                        <div class="fw-medium">₹{{ number_format($product->sale_price ?? $product->price, 0) }}</div>
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <small class="text-muted-soft">
                                <s>₹{{ number_format($product->price, 0) }}</s>
                            </small>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $product->stock_quantity <= ($product->stock_alert ?? 0) ? 'bg-warning' : 'bg-success' }}">
                            {{ $product->stock_quantity }} in stock
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('seller.products.edit', $product) }}" 
                               class="btn btn-outline-light border-0 p-1" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-outline-warning border-0 p-1 toggle-status" 
                                    data-product-id="{{ $product->id }}"
                                    data-status="{{ $product->is_active ? 'deactivate' : 'activate' }}"
                                    title="{{ $product->is_active ? 'Deactivate' : 'Activate' }}">
                                <i class="fas {{ $product->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                            </button>
                            <button class="btn btn-outline-danger border-0 p-1 delete-product" 
                                    data-product-id="{{ $product->id }}" 
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted-soft">
                        <i class="fas fa-box-open fa-3x mb-3 d-block opacity-50"></i>
                        <div class="h5 mb-2">No products found</div>
                        <p class="mb-3">Get started by creating your first product.</p>
                        <a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Create Product
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div class="card-footer bg-transparent border-0 py-2">
        {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<div id="products-alert" class="mt-3"></div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alertBox = document.getElementById('products-alert');

    // Toggle status
    document.querySelectorAll('.toggle-status').forEach(btn => {
        btn.addEventListener('click', async function() {
            const productId = this.dataset.productId;
            const action = this.dataset.status;
            
            if (!confirm(`Are you sure you want to ${action} this product?`)) return;

            try {
                const res = await fetch(`{{ url('seller') }}/products/${productId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value
                    }
                });

                const json = await res.json();
                if (json.success) {
                    location.reload();
                } else {
                    showAlert(json.message || 'Failed to update status', 'danger');
                }
            } catch (err) {
                showAlert('Network error', 'danger');
            }
        });
    });

    // Delete product
    document.querySelectorAll('.delete-product').forEach(btn => {
        btn.addEventListener('click', async function() {
            const productId = this.dataset.productId;
            if (!confirm('Are you sure you want to delete this product? This action cannot be undone.')) return;

            try {
                const res = await fetch(`{{ url('seller') }}/products/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value
                    }
                });

                const json = await res.json();
                if (json.success) {
                    location.reload();
                } else {
                    showAlert(json.message || 'Failed to delete product', 'danger');
                }
            } catch (err) {
                showAlert('Network error', 'danger');
            }
        });
    });

    function showAlert(message, type) {
        alertBox.innerHTML = `<div class="alert alert-${type} py-2 mb-0">${message}</div>`;
        setTimeout(() => alertBox.innerHTML = '', 5000);
    }
});
</script>
@endpush
