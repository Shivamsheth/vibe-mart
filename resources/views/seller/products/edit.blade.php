{{-- resources/views/seller/products/edit.blade.php --}}
@extends('layouts.seller')

@section('title', 'Edit Product')
@section('page_title', 'Edit Product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="mb-0">
        <i class="fas fa-edit me-1"></i>{{ $product->name }}
    </h6>
    <a href="{{ route('seller.products.index') }}" class="btn btn-outline-light btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Back to Products
    </a>
</div>

<div class="card-soft p-3">
    <form id="editProductForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div class="row g-3">
            {{-- Category --}}
            <div class="col-md-4">
                <label class="form-label text-muted-soft small">Category</label>
                <select name="category_id" class="form-select form-select-sm bg-dark text-light border-secondary">
                    <option value="">Select category</option>
                    <option value="3" {{ $product->category_id == 3 ? 'selected' : '' }}>Mobiles & Accessories</option>
                    <option value="4" {{ $product->category_id == 4 ? 'selected' : '' }}>Computers & Laptops</option>
                    <option value="5" {{ $product->category_id == 5 ? 'selected' : '' }}>TV & Audio</option>
                    <option value="6" {{ $product->category_id == 6 ? 'selected' : '' }}>Home Appliances</option>
                </select>
            </div>

            {{-- Name --}}
            <div class="col-md-8">
                <label class="form-label text-muted-soft small">Product name</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $product->name) }}"
                       class="form-control form-control-sm bg-dark text-light border-secondary"
                       required>
            </div>

            {{-- Short description --}}
            <div class="col-md-8">
                <label class="form-label text-muted-soft small">Short description</label>
                <input type="text"
                       name="short_description"
                       value="{{ old('short_description', $product->short_description) }}"
                       class="form-control form-control-sm bg-dark text-light border-secondary">
            </div>

            {{-- Brand --}}
            <div class="col-md-4">
                <label class="form-label text-muted-soft small">Brand</label>
                <input type="text"
                       name="brand"
                       value="{{ old('brand', $product->brand) }}"
                       class="form-control form-control-sm bg-dark text-light border-secondary">
            </div>

            {{-- Price --}}
            <div class="col-md-4">
                <label class="form-label text-muted-soft small">Price</label>
                <input type="number"
                       step="0.01"
                       name="price"
                       value="{{ old('price', $product->price) }}"
                       class="form-control form-control-sm bg-dark text-light border-secondary"
                       required>
            </div>

            {{-- Sale price --}}
            <div class="col-md-4">
                <label class="form-label text-muted-soft small">Sale price</label>
                <input type="number"
                       step="0.01"
                       name="sale_price"
                       value="{{ old('sale_price', $product->sale_price) }}"
                       class="form-control form-control-sm bg-dark text-light border-secondary">
            </div>

            {{-- Cost price --}}
            <div class="col-md-4">
                <label class="form-label text-muted-soft small">Cost price</label>
                <input type="number"
                       step="0.01"
                       name="cost_price"
                       value="{{ old('cost_price', $product->cost_price) }}"
                       class="form-control form-control-sm bg-dark text-light border-secondary">
            </div>

            {{-- Stock qty --}}
            <div class="col-md-4">
                <label class="form-label text-muted-soft small">Stock quantity</label>
                <input type="number"
                       name="stock_quantity"
                       value="{{ old('stock_quantity', $product->stock_quantity) }}"
                       class="form-control form-control-sm bg-dark text-light border-secondary">
            </div>

            {{-- Stock alert --}}
            <div class="col-md-4">
                <label class="form-label text-muted-soft small">Stock alert</label>
                <input type="number"
                       name="stock_alert"
                       value="{{ old('stock_alert', $product->stock_alert) }}"
                       class="form-control form-control-sm bg-dark text-light border-secondary">
            </div>

            {{-- Manage stock --}}
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" 
                           type="checkbox" 
                           value="1" 
                           id="manageStockCheck" 
                           name="manage_stock" 
                           {{ $product->manage_stock ? 'checked' : '' }}>
                    <label class="form-check-label text-muted-soft small" for="manageStockCheck">
                        Manage stock
                    </label>
                </div>
            </div>

            {{-- Status --}}
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" 
                           type="checkbox" 
                           value="1" 
                           id="statusCheck" 
                           name="is_active" 
                           {{ $product->is_active ? 'checked' : '' }}>
                    <label class="form-check-label text-muted-soft small" for="statusCheck">
                        Active
                    </label>
                </div>
            </div>

            {{-- Description --}}
            <div class="col-12">
                <label class="form-label text-muted-soft small">Description</label>
                <textarea name="description"
                          rows="4"
                          class="form-control form-control-sm bg-dark text-light border-secondary">{{ old('description', $product->description) }}</textarea>
            </div>

            {{-- Current Images --}}
            @if($product->images->count() > 0)
            <div class="col-12">
                <label class="form-label text-muted-soft small">Current images</label>
                <div class="row g-2 mb-3">
                    @foreach($product->images as $image)
                    <div class="col-md-2 col-sm-3 col-4 position-relative">
                        <img src="{{ asset('storage/' . $image->path) }}" 
                             alt="Product image" 
                             class="rounded img-thumbnail w-100"
                             style="height: 80px; object-fit: cover;">
                        <button type="button" 
                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-image" 
                                data-image-id="{{ $image->id }}"
                                style="width: 22px; height: 22px; padding: 0;">
                            <i class="fas fa-times fs-6"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- New Images --}}
            <div class="col-12">
                <label class="form-label text-muted-soft small">Add new images (optional)</label>
                <input type="file"
                       name="images[]"
                       multiple
                       accept="image/*"
                       class="form-control form-control-sm bg-dark text-light border-secondary">
                <div class="form-text text-muted-soft small">
                    JPG, JPEG, PNG, or WEBP files (max 5MB each)
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-sm" id="submitBtn">
                <i class="fas fa-save me-1"></i>Update Product
            </button>
            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-light btn-sm">
                Cancel
            </a>
        </div>

        <div id="product-alert" class="mt-3 small"></div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const form = document.getElementById('editProductForm');
const alertBox = document.getElementById('product-alert');
const btn = document.getElementById('submitBtn');

form.addEventListener('submit', async function (e) {
    e.preventDefault();
    alertBox.innerHTML = '';
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';

    const formData = new FormData(form);
    formData.append('_method', 'POST'); // 🔥 Laravel method spoofing

    try {
        const res = await fetch(`/seller/products/{{ $product->id }}`, { // 🔥 FIXED: /seller/ not /api/seller/
            method: 'POST', // 🔥 FIXED: POST not PUT
            body: formData, // 🔥 FormData - NO Content-Type header
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        const json = await res.json();

        if (json.success) {
            alertBox.innerHTML = 
                '<div class="alert alert-success py-1 mb-0">Product updated successfully.</div>';
            setTimeout(() => window.location.href = "{{ route('seller.products.index') }}", 1500);
        } else {
            let msg = json.message || 'Failed to update product.';
            if (json.errors) {
                const firstError = Object.values(json.errors)[0]?.[0];
                if (firstError) msg = firstError;
            }
            alertBox.innerHTML = 
                '<div class="alert alert-danger py-1 mb-0">' + msg + '</div>';
        }
    } catch (err) {
        console.error(err);
        alertBox.innerHTML = 
            '<div class="alert alert-danger py-1 mb-0">Network error while updating product.</div>';
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i>Update Product';
    }
});

// Remove image - 🔥 FIXED URL
document.querySelectorAll('.remove-image').forEach(btn => {
    btn.addEventListener('click', async function() {
        const imageId = this.dataset.imageId;
        if (!confirm('Remove this image?')) return;

        try {
            const res = await fetch(`/seller/products/images/${imageId}`, { // 🔥 FIXED: /seller/
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const json = await res.json();
            if (json.success) {
                this.parentElement.remove();
            }
        } catch (err) {
            console.error(err);
        }
    });
});
</script>
@endpush

