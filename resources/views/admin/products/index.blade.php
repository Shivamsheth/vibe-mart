{{-- resources/views/admin/products/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'VibeMart Admin - Products')
@section('page_title', 'Products Management')
@section('breadcrumb', 'Admin / Products')

@section('content')
{{-- 🔥 BEAUTIFUL STATS ROW --}}
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-3 col-sm-6">
        <div class="card-soft p-4 text-center hover-lift">
            <div class="text-primary mb-2">
                <i class="bi bi-box-seam fs-2"></i>
            </div>
            <h3 class="mb-1 fw-bold">{{ $stats['total_products'] ?? 0 }}</h3>
            <small class="text-muted-soft">Total Products</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-3 col-sm-6">
        <div class="card-soft p-4 text-center hover-lift">
            <div class="text-success mb-2">
                <i class="bi bi-check-circle-fill fs-2"></i>
            </div>
            <h3 class="mb-1 fw-bold">{{ $stats['total_active_products'] ?? 0 }}</h3>
            <small class="text-muted-soft">Active</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-3 col-sm-6">
        <div class="card-soft p-4 text-center hover-lift">
            <div class="text-warning mb-2">
                <i class="bi bi-pause-circle-fill fs-2"></i>
            </div>
            <h3 class="mb-1 fw-bold">{{ $stats['total_inactive_products'] ?? 0 }}</h3>
            <small class="text-muted-soft">Inactive</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-3 col-sm-6">
        <div class="card-soft p-4 text-center hover-lift">
            <div class="text-danger mb-2">
                <i class="bi bi-exclamation-triangle-fill fs-2"></i>
            </div>
            <h3 class="mb-1 fw-bold">{{ $stats['low_stock'] ?? 0 }}</h3>
            <small class="text-muted-soft">Low Stock</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-3 col-sm-6">
        <div class="card-soft p-4 text-center hover-lift">
            <div class="text-secondary mb-2">
                <i class="bi bi-x-circle-fill fs-2"></i>
            </div>
            <h3 class="mb-1 fw-bold">{{ $stats['out_of_stock'] ?? 0 }}</h3>
            <small class="text-muted-soft">Out of Stock</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-3 col-sm-6">
        <div class="card-soft p-4 text-center hover-lift">
            <div class="text-info mb-2">
                <i class="bi bi-person-badge-fill fs-2"></i>
            </div>
            <h3 class="mb-1 fw-bold">{{ $sellers->total() }}</h3>
            <small class="text-muted-soft">Active Sellers</small>
        </div>
    </div>
</div>

{{-- 🔥 PROFESSIONAL SELLERS TABLE --}}
<div class="card-soft p-3">
    <div class="card-header d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-person-lines-fill me-2"></i>
            Sellers ({{ $sellers->total() }})
        </h5>
        <div class="d-flex gap-2">
            <input type="text" class="form-control form-control-sm" style="width: 250px;" 
                   id="sellerSearch" placeholder="Search sellers...">
            <select class="form-select form-select-sm" id="statusFilter" style="width: 140px;">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0  ">
            <thead class="table-light table-dark">
                <tr>
                    <th style="width: 60px;"></th>
                    <th style="width: 250px;">Seller</th>
                    <th style="width: 100px;" class="text-center">Products</th>
                    <th style="width: 120px;" class="text-center">Active</th>
                    <th style="width: 100px;" class="text-center">Status</th>
                    <th style="width: 120px;" class="text-center">Joined</th>
                    <th style="width: 160px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sellers as $seller)
                <tr class="seller-row" data-name="{{ strtolower($seller->name) }}" data-status="{{ $seller->is_active ? 'active' : 'inactive' }}">
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="seller{{ $seller->id }}">
                            <label class="form-check-label" for="seller{{ $seller->id }}"></label>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="vm-avatar" style="width: 42px; height: 42px; font-size: 1rem;">
                                {{ strtoupper(substr($seller->name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold text-truncate" style="max-width: 200px;" title="{{ $seller->name }}">
                                    {{ $seller->name }}
                                </div>
                                <small class="text-muted-soft text-truncate d-block" style="max-width: 200px;" title="{{ $seller->email }}">
                                    {{ $seller->email }}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-primary fs-6 px-3 py-2 fw-semibold">
                            {{ $seller->products_count ?? 0 }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-success fs-6 px-3 py-2 fw-semibold">
                            {{ $seller->products_count_active ?? 0 }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $seller->is_active ? 'bg-success' : 'bg-warning' }} fs-6 px-3 py-2">
                            {{ $seller->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <small class="text-muted-soft fw-semibold">{{ $seller->created_at }}</small>
                        <div class="text-xs text-muted-soft">{{ $seller->created_at->diffForHumans() }}</div>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin.sellers.products', $seller->id) }}" 
                               class="btn btn-primary px-3" title="View Products">
                                <i class="bi bi-box-seam"></i>
                            </a>
                            <button class="btn btn-outline-light px-2" title="Edit Profile">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-warning px-2 dropdown-toggle dropdown-toggle-split" 
                                        data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-eye me-2"></i>View Profile</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-toggle-{{ $seller->is_active ? 'on' : 'off' }} me-2"></i>{{ $seller->is_active ? 'Deactivate' : 'Activate' }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted-soft">
                            <i class="bi bi-people-fill fs-1 mb-3 opacity-50"></i>
                            <h5 class="mb-3">No sellers found</h5>
                            <p class="mb-0">No active sellers available.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- 🔥 PAGINATION --}}
    <div class="card-footer bg-transparent border-0 pt-3 pb-3">
        <div class="row align-items-center g-3">
            <div class="col-md-6">
                <small class="text-muted-soft">
                    Showing {{ $sellers->firstItem() ?? 0 }} to {{ $sellers->lastItem() ?? 0 }} 
                    of {{ $sellers->total() }} sellers
                </small>
            </div>
            <div class="col-md-6">
                {{ $sellers->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 🔥 SEARCH + FILTER
    const sellerSearch = document.getElementById('sellerSearch');
    const statusFilter = document.getElementById('statusFilter');
    
    function filterSellers() {
        const searchTerm = sellerSearch.value.toLowerCase();
        const status = statusFilter.value;
        
        document.querySelectorAll('.seller-row').forEach(row => {
            const name = row.dataset.name;
            const rowStatus = row.dataset.status;
            
            const matchesSearch = !searchTerm || name.includes(searchTerm);
            const matchesStatus = !status || rowStatus === status;
            
            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }
    
    sellerSearch.addEventListener('input', filterSellers);
    statusFilter.addEventListener('change', filterSellers);
    
    // 🔥 SELECT ALL CHECKBOX
    const checkboxes = document.querySelectorAll('.form-check-input');
    const selectAll = document.querySelector('#selectAll');
    
    // Bulk actions...
});
</script>
@endpush

@push('styles')
<style>
.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(79, 70, 229, 0.15);
}
.table-hover tbody tr:hover {
    background-color: rgba(79, 70, 229, 0.1) !important;
}
.btn-group .dropdown-toggle-split::after {
    margin-left: 0;
}
.table{
    border:none,groove;
}
</style>
@endpush
@endsection
