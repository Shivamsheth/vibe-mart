{{-- resources/views/admin/sellers/products.blade.php --}}
@extends('layouts.admin')
@section('page_title', $seller->name . ' - Products')
@section('breadcrumb', 'Admin / Sellers / ' . $seller->name . ' / Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="vm-page-title">{{ $seller->name }}'s Products</div>
        <div class="vm-breadcrumb">
            Total: {{ $stats['total_products'] }} | 
            Active: {{ $stats['active_products'] }}
        </div>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-light">
        <i class="bi bi-arrow-left me-2"></i>Back to Products
    </a>
</div>

<div class="row g-3">
    @forelse($products as $product)
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card-soft h-100">
            <div class="position-relative overflow-hidden" style="height: 200px;">
                @if($product->primaryImage)
                    <img src="{{ Storage::url($product->primaryImage->path) }}" 
                         class="w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                @elseif($product->images->first())
                    <img src="{{ Storage::url($product->images->first()->path) }}" 
                         class="w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                @else
                    <div class="w-100 h-100 bg-secondary-subtle d-flex align-items-center justify-content-center">
                        <i class="fas fa-image text-muted fs-1"></i>
                    </div>
                @endif
                
                {{-- Status Badge --}}
                <span class="position-absolute top-2 end-2 badge {{ $product->status == 'active' ? 'bg-success' : 'bg-warning' }} shadow">
                    {{ ucfirst($product->status) }}
                </span>
            </div>
            <div class="card-body p-3">
                @if($product->category)
                    <small class="badge bg-primary-subtle text-primary px-2 py-1 mb-2">
                        {{ $product->category->name }}
                    </small>
                @endif
                
                <h6 class="mb-2">
                    <a href="#" class="text-white text-decoration-none">{{ Str::limit($product->name, 40) }}</a>
                </h6>
                
                {{-- Price --}}
                <div class="mb-3">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <div class="d-flex align-items-baseline gap-1 mb-1">
                            <span class="fw-bold text-success fs-5">₹{{ number_format($product->sale_price, 0) }}</span>
                            <span class="text-muted-soft text-xs line-through fw-semibold">₹{{ number_format($product->price, 0) }}</span>
                        </div>
                    @else
                        <span class="fw-bold text-primary fs-5">₹{{ number_format($product->price, 0) }}</span>
                    @endif
                </div>
                
                {{-- Stock --}}
                <div class="mb-3">
                    <small class="text-muted-soft">
                        <i class="bi bi-box-seam me-1"></i>
                        {{ $product->stock_quantity }} in stock
                        @if($product->manage_stock && $product->stock_quantity <= $product->stock_alert)
                            <span class="badge bg-warning ms-1">Low Stock</span>
                        @endif
                    </small>
                </div>
                
                {{-- Actions --}}
                <div class="d-grid gap-1">
                    <a href="#" class="btn btn-primary btn-sm">Edit</a>
                    <button class="btn btn-outline-light btn-sm">Toggle Status</button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-3x mb-3 text-muted-soft opacity-50"></i>
            <h5 class="mb-2 text-muted-soft">No products found</h5>
            <p class="text-muted-soft">This seller has no products yet.</p>
        </div>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="mt-4">
    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>
@endsection
