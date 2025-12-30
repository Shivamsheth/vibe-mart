{{-- resources/views/seller/dashboard.blade.php --}}
@extends('layouts.seller')

@section('title','VibeMart Seller - Dashboard')
@section('page_title','Dashboard')

@section('content')
<div class="row g-3 mb-3">
    {{-- Today’s Sales --}}
    <div class="col-md-3">
        <div class="card-soft p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted-soft small">Today’s Sales</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle">
                    +{{ $stats['sales_growth'] ?? '0' }}%
                </span>
            </div>
            <div class="h3 mb-1">₹ {{ number_format($stats['today_sales'] ?? 0, 0) }}</div>
            <div class="small text-muted-soft">Compared to yesterday</div>
        </div>
    </div>

    {{-- Orders received --}}
    <div class="col-md-3">
        <div class="card-soft p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted-soft small">Orders (Today)</span>
            </div>
            <div class="h3 mb-1">{{ $stats['today_orders'] ?? 0 }}</div>
            <div class="small text-muted-soft">New orders placed</div>
        </div>
    </div>

    {{-- Active products --}}
    <div class="col-md-3">
        <div class="card-soft p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted-soft small">Active Products</span>
            </div>
            <div class="h3 mb-1">{{ $stats['active_products'] ?? 0 }}</div>
            <div class="small text-muted-soft">Visible in storefront</div>
        </div>
    </div>

    {{-- Low stock --}}
    <div class="col-md-3">
        <div class="card-soft p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted-soft small">Low Stock</span>
            </div>
            <div class="h3 mb-1">{{ $stats['low_stock'] ?? 11 }}</div>
            <div class="small text-muted-soft">Need restocking</div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Recent orders --}}
    <div class="col-lg-8">
        <div class="card-soft p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Recent Orders</h6>
                <a href="#" class="small text-primary text-decoration-none">View all</a>
            </div>
            <div class="table-responsive small">
                <table class="table table-dark table-borderless align-middle mb-0" style="background:transparent;">
                    <thead class="small text-muted-soft border-bottom border-slate-800">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Placed</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- <tr>
                        <td>#S-1201</td>
                        <td>{{ $recentOrders[0]['customer_name'] ?? '' }}</td>
                        <td>{{ $recentOrders[0]['items_count'] ?? 0 }}</td>
                        <td>₹ {{ number_format($recentOrders[0]['total'] ?? 0, 0) }}</td>
                        <td>
                            <span class="badge bg-info-subtle text-info border border-info-subtle">{{ $recentOrders[0]['status_label'] ?? 'Processing' }}</span>
                        </td>
                        <td>{{ $recentOrders[0]['created_at_diff'] ?? '15 min ago' }}</td>
                    </tr>
                    <tr>
                        <td>#S-1200</td>
                        <td>{{ $recentOrders[1]['customer_name'] ?? 'Ananya Patel' }}</td>
                        <td>{{ $recentOrders[1]['items_count'] ?? 1 }}</td>
                        <td>₹ {{ number_format($recentOrders[1]['total'] ?? 799, 0) }}</td>
                        <td>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">{{ $recentOrders[1]['status_label'] ?? 'Dispatched' }}</span>
                        </td>
                        <td>{{ $recentOrders[1]['created_at_diff'] ?? '45 min ago' }}</td>
                    </tr>
                    <tr>
                        <td>#S-1199</td>
                        <td>{{ $recentOrders[2]['customer_name'] ?? 'Guest' }}</td>
                        <td>{{ $recentOrders[2]['items_count'] ?? 2 }}</td>
                        <td>₹ {{ number_format($recentOrders[2]['total'] ?? 1499, 0) }}</td>
                        <td>
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">{{ $recentOrders[2]['status_label'] ?? 'Pending' }}</span>
                        </td>
                        <td>{{ $recentOrders[2]['created_at_diff'] ?? '1 hr ago' }}</td>
                    </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Right column --}}
    <div class="col-lg-4 d-flex flex-column gap-3">
        <div class="card-soft p-3">
            <h6 class="mb-2">Sales Summary</h6>
            <div class="d-flex justify-content-between small mb-1">
                <span class="text-muted-soft">Today</span>
                <span>₹ {{ number_format($stats['today_sales'] ?? 0, 0) }}</span>
            </div>
            <div class="d-flex justify-content-between small mb-1">
                <span class="text-muted-soft">This week</span>
                <span>₹ {{ number_format($stats['week_sales'] ?? 0, 0) }}</span>
            </div>
            <div class="d-flex justify-content-between small mb-2">
                <span class="text-muted-soft">This month</span>
                <span>₹ {{ number_format($stats['month_sales'] ?? 0, 0) }}</span>
            </div>
            <hr class="border-secondary my-2">
            <div class="d-flex justify-content-between small fw-semibold">
                <span>Payout pending</span>
                <span>₹ {{ number_format($stats['payout_pending'] ?? 0, 0) }}</span>
            </div>
        </div>

        <div class="card-soft p-3">
            <h6 class="mb-3">Quick Actions</h6>
            <a href="{{ route('seller.products.index') }}" class="d-block text-decoration-none mb-2">
                <i class="fas fa-box me-2 text-primary"></i>
                <span class="small">Manage Products</span>
            </a>
            <a href="{{ route('seller.products.create') }}" class="d-block text-decoration-none mb-2">
                <i class="fas fa-plus-circle me-2 text-success"></i>
                <span class="small">Add New Product</span>
            </a>
            <a href="#" class="d-block text-decoration-none mb-2">
                <i class="bi bi-card-list me-2 "></i>
                <span class="small">Update Stock</span>
            </a>
            <a href="#" class="d-block text-decoration-none">
                <i class="fas fa-headset me-2 text-warning"></i>
                <span class="small">Seller Support</span>
            </a>
        </div>
    </div>
</div>
@endsection
