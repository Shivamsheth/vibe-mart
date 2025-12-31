{{-- resources/views/home.blade.php --}}
@extends('layouts.public')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h6 class="mb-0 fw-semibold">All Products ({{ $products->total() ?? 0 }})</h6>
    
</div>

<div class="row align-items-center g-2 mb-4">
    <div class="col-md-3">
        <select class="form-select form-select-sm" id="categoryFilter">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    
    <div class="col-md-9 text-md-end">
        <div class="d-flex align-items-center gap-2 justify-content-end">
            <span class="text-muted-soft small me-2">Sort:</span>
            <select class="form-select form-select-sm" id="sortSelect" style="width: 160px;">
                <option value="latest">Latest</option>
                <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>Price: Low</option>
                <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Price: High</option>
            </select>
            <span class="text-muted-soft small">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }}</span>
        </div>
    </div>
</div>

{{-- 🔥 PERFECTLY ALIGNED GRID - NO OVERLAP --}}
<div class="row g-3 g-xl-2 g-lg-3 mb-4" id="productsContainer">
    @forelse($products as $product)
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
        {{-- 🔥 PERFECTLY ALIGNED PRODUCT CARD --}}
        <div class="product-card h-100 d-flex flex-column overflow-hidden">
            {{-- Image Section - Fixed Height --}}
            <div class="position-relative flex-shrink-0 image-section p-3" style="height: 180px;">
                @if($product->images->first())
                    <img src="{{ Storage::url($product->images->first()->path) }}" 
                         class="product-image w-100 h-100 rounded-2 object-fit-cover shadow-sm border"
                         alt="{{ $product->name }}"
                         loading="lazy">
                    <div class="fallback-image position-absolute top-0 start-0 w-100 h-100 bg-secondary-subtle rounded-2 d-none align-items-center justify-content-center">
                        <i class="fas fa-image text-muted fs-4"></i>
                    </div>
                @else
                    <div class="w-100 h-100 bg-secondary-subtle rounded-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-image text-muted fs-3"></i>
                    </div>
                @endif

                {{-- 🔥 POSITIONED BADGES - NO OVERLAP --}}
                @if($product->sale_price && $product->sale_price < $product->price)
                    <div class="position-absolute top-2 start-3 badge bg-danger text-white small px-2 py-1 shadow-sm z-2">
                        <small>{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF</small>
                    </div>
                @endif

                @if($product->stock_quantity <= 3)
                    <div class="position-absolute bottom-2 start-3 badge bg-warning text-dark small px-2 py-1 shadow-sm z-2">
                        <small>{{ $product->stock_quantity }} left</small>
                    </div>
                @endif

                {{-- Quick Actions - Top Right --}}
               
            </div>

            {{-- Content - Flexible Height --}}
            <div class="card-body p-3 flex-grow-1 d-flex flex-column">
                {{-- Category --}}
                @if($product->category)
                    <div class="mb-2">
                        <small class="badge bg-primary-subtle text-primary text-xs px-2 py-1">
                            {{ $product->category->name }}
                        </small>
                         
                    </div>

                    <div class="position-absolute top-2 end-3 p-2 z-3">
                    <button class="btn btn-sm p-1 text-white-50 wishlist-btn shadow-none border-0 bg-transparent rounded-circle" 
                            data-product-id="{{ $product->id }}" title="Wishlist" style="width: 32px; height: 32px;">
                        <i class="far fa-heart fs-5"></i>
                    </button>
                </div>
                @endif

                {{-- Title --}}
                <h6 class="product-title mb-2 flex-grow-1">
                    <a href="{{ route('product.show', $product->slug) }}" class="text-white text-decoration-none fw-semibold small lh-sm">
                        {{ Str::limit($product->name, 35) }}
                    </a>
                </h6>

                {{-- Brand --}}
                @if($product->brand)
                    <small class="text-muted-soft d-block mb-2 brand-text small">{{ $product->brand }}</small>
                @endif

                {{-- Price --}}
                <div class="mb-3 price-section">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <div class="d-flex align-items-baseline gap-1 mb-1">
                            <span class="fw-bold text-success fs-5 mb-0">₹{{ number_format($product->sale_price, 0) }}</span>
                            <span class="text-muted-soft text-xs line-through">₹{{ number_format($product->price, 0) }}</span>
                        </div>
                        <small class="text-success fw-semibold bg-success-subtle px-2 py-1 rounded-pill small">
                            Save {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                        </small>
                    @else
                        <span class="fw-bold text-primary fs-5 mb-0">₹{{ number_format($product->price, 0) }}</span>
                    @endif
                </div>

                {{-- Stock --}}
                <div class="mb-3">
                    <span class="badge {{ $product->stock_quantity <= 5 ? 'bg-warning text-dark' : 'bg-success' }} px-2 py-1 w-100 small">
                        <i class="fas {{ $product->stock_quantity <= 5 ? 'fa-exclamation-triangle me-1' : 'fa-check me-1' }}"></i>
                        {{ $product->stock_quantity }} in stock
                    </span>
                </div>

                {{-- Buttons - Fixed Bottom --}}
                <div class="mt-auto">
                    @auth
                        <div class="d-grid gap-1">
                            <button class="btn btn-primary btn-sm fw-semibold py-2 px-3 add-to-cart shadow-sm" 
                                    data-product-id="{{ $product->id }}">
                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                            </button>
                            <a href="{{ route('product.show', $product->slug) }}" 
                               class="btn btn-outline-light btn-sm fw-semibold py-2 px-3">
                                <i class="fas fa-eye me-2"></i>Quick View
                            </a>
                        </div>
                    @else
                        <a href="{{ route('login.view') }}?redirect={{ urlencode(route('product.show', $product->slug)) }}" 
                           class="btn btn-primary btn-sm fw-semibold py-2 px-3 w-100 shadow-sm">
                            <i class="fas fa-shopping-cart me-2"></i>Login to Buy
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-3x mb-3 text-muted-soft opacity-50"></i>
                <h5 class="mb-2 text-muted-soft">No products found</h5>
                <p class="text-muted-soft mb-4 small">Try adjusting your search or filters</p>
                <a href="{{ route('home') }}" class="btn btn-primary btn-sm px-4">
                    <i class="fas fa-redo me-1"></i>Clear Filters
                </a>
            </div>
        </div>
    @endforelse
</div>

@if(isset($products) && $products->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const products = document.querySelectorAll('.col-xl-2, .col-lg-3, .col-md-4');
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const sortSelect = document.getElementById('sortSelect');

    // Server-side sorting
    if (sortSelect) {
        const urlParams = new URLSearchParams(window.location.search);
        sortSelect.value = urlParams.get('sort') || 'latest';
        sortSelect.addEventListener('change', function() {
            const params = new URLSearchParams(window.location.search);
            params.set('sort', this.value);
            window.location.search = params.toString();
        });
    }

    

    // Client-side filtering
    if (searchInput && categoryFilter) {
        function filterProducts() {
            const term = searchInput.value.toLowerCase();
            const category = categoryFilter.value;
            
            products.forEach(product => {
                const name = product.dataset.name || '';
                const cat = product.dataset.category;
                const matches = (!term || name.includes(term)) && (!category || cat == category);
                product.style.display = matches ? '' : 'none';
            });
        }

        let filterTimeout;
        searchInput.addEventListener('input', () => {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(filterProducts, 250);
        });
        categoryFilter.addEventListener('change', filterProducts);
    }

    // Add to cart
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!{{ auth()->check() ? 'true' : 'false' }}) {
                window.location.href = `/login?redirect=${encodeURIComponent(window.location.href)}`;
                return;
            }
            const original = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check-circle me-2"></i>Added!';
            this.classList.add('btn-success');
            setTimeout(() => {
                this.innerHTML = original;
                this.classList.remove('btn-success');
            }, 2000);
        });
    });

    // PERFECT HOVER - No overlap
    document.querySelectorAll('.product-card').forEach((card, index) => {
        const wrapper = card.closest('.col-xl-2, .col-lg-3, .col-md-4');
        const img = card.querySelector('.product-image');
        
        wrapper.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-6px)';
            if (img) img.style.transform = 'scale(1.05)';
        });
        wrapper.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
            if (img) img.style.transform = 'scale(1)';
        });
    });

    // ADDED: Global navbar search handler
        const globalSearch = document.getElementById('globalSearch');
        const searchBtn = document.getElementById('searchBtn');

        // Already handles case-insensitive + spaces
function performGlobalSearch() {
    const term = globalSearch.value.trim();  // "iPhone 14" → "iphone 14"
    const params = new URLSearchParams(window.location.search);
    if (term) {
        params.set('search', term);  // ?search=iPhone 14
    } else {
        params.delete('search');
    }
    window.location.search = params.toString();
}


        globalSearch.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') performGlobalSearch();
        });
        searchBtn.addEventListener('click', performGlobalSearch);


    // Wishlist toggle
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const icon = this.querySelector('i');
            icon.classList.toggle('far');
            icon.classList.toggle('fas');
            icon.classList.toggle('text-danger');
            this.style.color = icon.classList.contains('text-danger') ? '#ef4444' : 'rgba(255,255,255,0.5)';
        });
    });
});
</script>

<style>
/* 🔥 PERFECT ALIGNMENT - NO OVERLAP */
.product-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(255,255,255,0.1);
    background: rgba(15, 15, 41, 0.8);
    backdrop-filter: blur(12px);
    height: 100%;
}

.product-card:hover {
    border-color: rgba(79, 70, 229, 0.5);
    box-shadow: 0 12px 32px rgba(79, 70, 229, 0.15);
}

.product-image {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 8px !important;
}

.image-section {
    overflow: hidden;
}

.product-title a {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-size: 0.88rem !important;
}

.price-section .fs-5 {
    font-size: 1.1rem !important;
    font-weight: 700;
}

.col-xl-2, .col-lg-3, .col-md-4 {
    display: flex !important;
}

.col-xl-2 > *, .col-lg-3 > *, .col-md-4 > * {
    width: 100%;
}

/* Badge positioning - NO OVERLAP */
.position-absolute {
    z-index: 2;
}

.end-3 { right: 1rem; }
.start-3 { left: 1rem; }
.top-3 { top: 1rem; }
.bottom-3 { bottom: 1rem; }

/* Responsive fixes */
@media (max-width: 768px) {
    .image-section { height: 160px !important; }
    .product-title a { font-size: 0.85rem !important; }
}
</style>
@endpush
