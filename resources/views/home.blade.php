{{-- resources/views/home.blade.php --}}
@extends('layouts.public')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
{{-- 🔥 COMPACT Header --}}
<div class="row align-items-center g-3 mb-3">
    <div class="col-md-6">
        <h6 class="mb-0 fw-semibold">All Products ({{ $products->total() ?? 0 }})</h6>
    </div>
    <div class="col-md-6">
        <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" id="searchInput" placeholder="Search products..." value="{{ request('search') }}">
        </div>
    </div>
</div>

{{-- 🔥 COMPACT Filters --}}
<div class="row align-items-center g-2 mb-4">
    <div class="col-lg-3 col-md-4">
        <select class="form-select form-select-sm" id="categoryFilter">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-2 col-md-2">
        <div class="d-flex gap-1">
            <button class="btn btn-outline-light btn-sm px-2 active" id="gridView" title="Grid">
                <i class="fas fa-th"></i>
            </button>
            <button class="btn btn-outline-light btn-sm px-2" id="listView" title="List">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>
    <div class="col-lg-7 col-md-6">
        <div class="d-flex align-items-center gap-2 justify-content-end">
            <span class="text-xs text-muted-soft">Sort:</span>
            <select class="form-select form-select-sm" id="sortSelect" style="width: 140px;">
                <option value="latest">Latest</option>
                <option value="price-low">Price: Low</option>
                <option value="price-high">Price: High</option>
            </select>
            <span class="text-xs text-muted-soft">{{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }}</span>
        </div>
    </div>
</div>

{{-- 🔥 PERFECT Product Grid --}}
<div class="row g-3 g-lg-2" id="productsContainer">
    @forelse($products as $product)
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 product-item" 
         data-category="{{ $product->category_id }}" 
         data-price="{{ $product->sale_price ?? $product->price }}"
         data-name="{{ strtolower($product->name) }}">
        <div class="card-soft h-100">
            <div class="position-relative p-2" style="height: 160px;">
                @if($product->images->first())
                    <img src="{{ Storage::url($product->images->first()->path) }}" 
                         class="rounded w-100 h-100 object-fit-cover" 
                         alt="{{ $product->name }}"
                         style="transition: transform 0.2s ease;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center position-absolute top-0 start-0 w-100 h-100 d-none">
                        <i class="fas fa-image text-muted"></i>
                    </div>
                @else
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center w-100 h-100">
                        <i class="fas fa-image text-muted fs-4"></i>
                    </div>
                @endif
            </div>
            
            <div class="card-body p-2 pt-1">
                @if($product->category)
                    <small class="badge bg-secondary bg-opacity-50 text-xs px-2 py-0 mb-1 d-block">
                        {{ $product->category->name }}
                    </small>
                @endif
                
                <h6 class="mb-1 lh-sm text-xs" style="font-size: 0.85rem !important;">
                    <a href="{{ route('product.show', $product->slug) }}" class="text-white text-decoration-none fw-medium">
                        {{ Str::limit($product->name, 30) }}
                    </a>
                </h6>
                
                @if($product->brand)
                    <small class="text-muted-soft d-block mb-1 text-xs">{{ $product->brand }}</small>
                @endif
                
                <div class="mb-2">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <div class="d-flex align-items-baseline gap-1 mb-1">
                            <span class="fw-bold text-success fs-5 mb-0">₹{{ number_format($product->sale_price, 0) }}</span>
                            <span class="text-muted-soft text-xs"><s>₹{{ number_format($product->price, 0) }}</s></span>
                        </div>
                    @else
                        <span class="fw-bold text-primary fs-5 mb-0">₹{{ number_format($product->price, 0) }}</span>
                    @endif
                </div>
                
                <div class="mb-2">
                    <span class="badge {{ $product->stock_quantity <= 5 ? 'bg-warning text-dark' : 'bg-success' }} text-xs px-2 w-100 py-1">
                        {{ $product->stock_quantity }} in stock
                    </span>
                </div>
                
                <div class="d-grid gap-1">
                    @auth
                        <button class="btn btn-primary btn-sm py-1 text-xs add-to-cart" data-product-id="{{ $product->id }}">
                            <i class="fas fa-shopping-cart me-1"></i>Add Cart
                        </button>
                    @else
                        <a href="{{ route('login.view') }}" class="btn btn-primary btn-sm py-1 text-xs">
                            <i class="fas fa-shopping-cart me-1"></i>Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card-soft text-center py-5 px-4">
            <i class="fas fa-box-open fa-3x mb-3 text-muted-soft d-block"></i>
            <h5 class="mb-2 text-muted-soft">No products found</h5>
            <p class="text-muted-soft mb-4 small">Try adjusting your search or filters</p>
            <a href="{{ route('home') }}" class="btn btn-primary btn-sm px-4">
                <i class="fas fa-redo me-1"></i>Clear Filters
            </a>
        </div>
    </div>
    @endforelse
</div>

{{-- 🔥 Compact Pagination --}}
@if($products->hasPages())
<div class="row">
    <div class="col-12">
        <nav class="d-flex justify-content-center mt-4">
            {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
        </nav>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const sortSelect = document.getElementById('sortSelect');
    const products = document.querySelectorAll('.product-item');

    function filterProducts() {
        const term = searchInput.value.toLowerCase();
        const category = categoryFilter.value;
        
        Array.from(products).forEach(product => {
            const name = product.dataset.name || '';
            const cat = product.dataset.category;
            const matches = (!term || name.includes(term)) && (!category || cat == category);
            product.style.display = matches ? 'block' : 'none';
        });
    }

    searchInput.addEventListener('input', () => setTimeout(filterProducts, 250));
    categoryFilter.addEventListener('change', filterProducts);

    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!{{ auth()->check() ? 'true' : 'false' }}) {
                window.location.href = '/login';
                return;
            }
            const original = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check me-1"></i>Added!';
            setTimeout(() => this.innerHTML = original, 1500);
        });
    });

    // Compact hover
    document.querySelectorAll('.product-item').forEach(item => {
        item.addEventListener('mouseenter', () => {
            item.querySelector('.card-soft').style.transform = 'translateY(-4px)';
        });
        item.addEventListener('mouseleave', () => {
            item.querySelector('.card-soft').style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush
