{{-- resources/views/seller/products/create.blade.php --}}
@extends('layouts.seller')

@section('title', 'Add Product')
@section('page_title', 'Add New Product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="mb-0">
        <i class="fas fa-plus me-1"></i>Create Product
    </h6>
    <a href="{{ route('seller.products.index') }}" class="btn btn-outline-light btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Back to Products
    </a>
</div>

<div class="card-soft p-3">
    <form id="productForm" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            {{-- Category --}}
            <div class="col-md-4">
                <label class="form-label text-muted-soft small">Category <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select form-select-sm bg-dark text-light border-secondary" required>
                    <option value="">Select category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Name --}}
            <div class="col-md-8">
                <label class="form-label text-muted-soft small">Product name <span class="text-danger">*</span></label>
                <input type="text"
                       name="name"
                       class="form-control form-control-sm bg-dark text-light border-secondary"
                       placeholder="iPhone 15 Pro"
                       required>
            </div>

            {{-- Short description --}}
            <div class="col-md-8">
                <label class="form-label text-muted-soft small">Short description</label>
                <input type="text"
                       name="short_description"
                       class="form-control form-control-sm bg-dark text-light border-secondary"
                       placeholder="iPhone 15 Pro 256GB Natural Titanium">
            </div>

            {{-- Brand --}}
            <div class="col-md-4">
                <label class="form-label text-muted-soft small">Brand <span class="text-danger">*</span></label>
                <input type="text"
                       name="brand"
                       class="form-control form-control-sm bg-dark text-light border-secondary"
                       placeholder="Apple"
                       required>
            </div>

            {{-- Price --}}
            <div class="col-md-4">
                <label class="form-label text-muted-soft small">Price <span class="text-danger">*</span></label>
                <input type="number"
                       step="0.01"
                       min="0.01"
                       name="price"
                       class="form-control form-control-sm bg-dark text-light border-secondary"
                       placeholder="99999"
                       required>
            </div>

            {{-- Sale price --}}
            <div class="col-md-4">
                <label class="form-label text-muted-soft small">Sale price</label>
                <input type="number"
                       step="0.01"
                       min="0"
                       name="sale_price"
                       class="form-control form-control-sm bg-dark text-light border-secondary"
                       placeholder="94999">
            </div>

            {{-- Cost price --}}
            <div class="col-md-4">
                <label class="form-label text-muted-soft small">Cost price</label>
                <input type="number"
                       step="0.01"
                       min="0"
                       name="cost_price"
                       class="form-control form-control-sm bg-dark text-light border-secondary">
            </div>

            {{-- Stock qty --}}
            <div class="col-md-4">
                <label class="form-label text-muted-soft small">Stock quantity <span class="text-danger">*</span></label>
                <input type="number"
                       min="0"
                       name="stock_quantity"
                       class="form-control form-control-sm bg-dark text-light border-secondary"
                       value="10"
                       required>
            </div>

            {{-- Stock alert --}}
            <div class="col-md-4">
                <label class="form-label text-muted-soft small">Stock alert</label>
                <input type="number"
                       min="0"
                       name="stock_alert"
                       class="form-control form-control-sm bg-dark text-light border-secondary"
                       value="2">
            </div>

            {{-- Manage stock --}}
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input"
                           type="checkbox"
                           value="1"
                           id="manageStockCheck"
                           name="manage_stock">
                    <label class="form-check-label text-muted-soft small" for="manageStockCheck">
                        Manage stock
                    </label>
                </div>
            </div>

            {{-- Description --}}
            <div class="col-12">
                <label class="form-label text-muted-soft small">Description</label>
                <textarea name="description"
                          rows="4"
                          class="form-control form-control-sm bg-dark text-light border-secondary"
                          placeholder="Enter full product description..."></textarea>
            </div>

            {{-- Images --}}
            <div class="col-12">
                <label class="form-label text-muted-soft small">Product images <span class="text-danger">*</span></label>
                <input type="file"
                       name="images[]"
                       multiple
                       accept="image/jpeg,image/jpg,image/png,image/webp"
                       class="form-control form-control-sm bg-dark text-light border-secondary"
                       id="imageInput">
                <div class="form-text text-muted-soft small">
                    Select 1-8 JPG, JPEG, PNG, or WEBP files (max 5MB each)
                </div>
                <div id="imagePreview" class="mt-2"></div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-sm" id="submitBtn">
                <i class="fas fa-save me-1"></i>Save Product
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
const form = document.getElementById('productForm');
const alertBox = document.getElementById('product-alert');
const btn = document.getElementById('submitBtn');
const imageInput = document.getElementById('imageInput');
const imagePreview = document.getElementById('imagePreview');

let uploadedImageUrls = [];

// Image preview on select
imageInput.addEventListener('change', function() {
    imagePreview.innerHTML = '';
    const files = this.files;
    
    if (files.length > 8) {
        showAlert('Maximum 8 images allowed.', 'warning');
        this.value = '';
        return;
    }

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (file.size > 5 * 1024 * 1024) {
            showAlert(`Image ${i+1} exceeds 5MB limit.`, 'danger');
            this.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'img-preview d-inline-block me-2 mb-2';
            div.style.maxWidth = '100px';
            div.style.maxHeight = '100px';
            div.innerHTML = `
                <img src="${e.target.result}" class="img-thumbnail rounded" style="width:100%;height:100%;object-fit:cover;">
                <small class="text-muted">${file.name}</small>
            `;
            imagePreview.appendChild(div);
        };
        reader.readAsDataURL(file);
    }
});

form.addEventListener('submit', async function (e) {
    e.preventDefault();
    alertBox.innerHTML = '';
    btn.disabled = true;

    const imageFiles = imageInput.files;
    
    if (imageFiles.length === 0) {
        showAlert('Please select at least one product image.', 'danger');
        btnReset();
        return;
    }

    // 🔥 STEP 1: Upload images (LOCAL STORAGE)
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Uploading images...';
    
    const imageFormData = new FormData();
    imageFormData.append('_token', document.querySelector('input[name="_token"]').value);
    Array.from(imageFiles).forEach(file => {
        imageFormData.append('images[]', file);
    });

    try {
        const imageRes = await fetch('{{ route("seller.products.upload-images-first") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: imageFormData
        });

        const imageJson = await imageRes.json();

        if (!imageRes.ok || !imageJson.success) {
            let msg = imageJson.message || 'Image upload failed.';
            if (imageJson.errors) {
                const firstError = Object.values(imageJson.errors)[0]?.[0];
                if (firstError) msg = firstError;
            }
            showAlert(msg, 'danger');
            return btnReset();
        }

        uploadedImageUrls = imageJson.data.image_urls;
        
        // 🔥 STEP 2: Create product with image URLs
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Creating product...';
        
        const productFormData = new FormData(form);
        // Clear file input from FormData
        const imageInputData = productFormData.getAll('images[]');
        imageInputData.forEach(() => productFormData.delete('images[]'));
        productFormData.append('image_urls', JSON.stringify(uploadedImageUrls));

        const productRes = await fetch('{{ route("seller.products.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: productFormData
        });

        const productJson = await productRes.json();

        if (productRes.ok && productJson.success) {
            alertBox.innerHTML = '<div class="alert alert-success py-1 mb-0">Product created successfully! Redirecting...</div>';
            setTimeout(() => window.location.href = "{{ route('seller.products.index') }}", 1500);
        } else {
            let msg = productJson.message || 'Failed to create product.';
            if (productJson.errors) {
                const firstError = Object.values(productJson.errors)[0]?.[0];
                if (firstError) msg = firstError;
            }
            showAlert(msg, 'danger');
        }
    } catch (err) {
        console.error(err);
        showAlert('Network error occurred.', 'danger');
    } finally {
        btnReset();
    }
});

function showAlert(message, type) {
    alertBox.innerHTML = `<div class="alert alert-${type} py-1 mb-0">${message}</div>`;
}

function btnReset() {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-save me-1"></i>Save Product';
}
</script>
@endpush
