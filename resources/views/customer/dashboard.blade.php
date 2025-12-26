{{-- resources/views/customer/dashboard.blade.php --}}
@extends('layouts.customer')

@section('title','VibeMart - Dashboard')
@section('page_title','Dashboard')

@section('content')
<div class="row g-3 mb-3">
    {{-- My Orders --}}
    <div class="col-md-3">
        <div class="card-soft p-3 h-100">
            <div class="small text-muted-soft mb-1">My Orders</div>
            <div class="h4 mb-1">Orders</div>
            <div class="small text-muted-soft mb-3">Track all your orders</div>
            <button class="btn btn-sm btn-outline-light">View Orders</button>
        </div>
    </div>

    {{-- Wishlist --}}
    <div class="col-md-3">
        <div class="card-soft p-3 h-100">
            <div class="small text-muted-soft mb-1">Wishlist</div>
            <div class="h4 mb-1">Saved</div>
            <div class="small text-muted-soft mb-3">Saved products</div>
            <button class="btn btn-sm btn-outline-light">View Wishlist</button>
        </div>
    </div>

    {{-- Wallet --}}
    <div class="col-md-3">
        <div class="card-soft p-3 h-100">
            <div class="small text-muted-soft mb-1">Wallet</div>
            <div class="h4 mb-1">₹ 2,450</div>
            <div class="small text-muted-soft mb-3">Available balance</div>
            <button class="btn btn-sm btn-outline-light">Add Money</button>
        </div>
    </div>

    {{-- Profile --}}
    <div class="col-md-3">
        <div class="card-soft p-3 h-100">
            <div class="small text-muted-soft mb-1">Profile</div>
            <div class="h4 mb-1">Account</div>
            <div class="small text-muted-soft mb-3">Manage your details</div>
            <button class="btn btn-sm btn-outline-light">Edit Profile</button>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Recent Orders --}}
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
                        <th>Order</th>
                        <th>Product</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>#1001</td>
                        <td>iPhone 15 Pro</td>
                        <td>2 days ago</td>
                        <td>₹ 89,999</td>
                        <td>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                Delivered
                            </span>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-light">Track</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#1000</td>
                        <td>Boat Headphones</td>
                        <td>5 days ago</td>
                        <td>₹ 1,499</td>
                        <td>
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                Processing
                            </span>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-light">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#999</td>
                        <td>Nike Shoes</td>
                        <td>1 week ago</td>
                        <td>₹ 5,999</td>
                        <td>
                            <span class="badge bg-info-subtle text-info border border-info-subtle">
                                Shipped
                            </span>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-light">Track</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Summary + Quick links --}}
    <div class="col-lg-4 d-flex flex-column gap-3">
        <div class="card-soft p-3">
            <h6 class="mb-2">Order Summary</h6>
            <div class="d-flex justify-content-between mb-1 small">
                <span class="text-muted-soft">Total Orders</span>
                <span>12</span>
            </div>
            <div class="d-flex justify-content-between mb-1 small">
                <span class="text-muted-soft">Delivered</span>
                <span>9</span>
            </div>
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-muted-soft">Pending</span>
                <span>2</span>
            </div>
            <hr class="border-secondary my-2">
            <div class="d-flex justify-content-between fw-semibold small">
                <span>Total Spent</span>
                <span>₹ 1,25,450</span>
            </div>
        </div>

        <div class="card-soft p-3">
            <h6 class="mb-2">Quick Links</h6>
            <a href="#" class="d-block small text-decoration-none mb-2">
                <i class="bi bi-shop me-2"></i>Continue Shopping
            </a>
            <a href="#" class="d-block small text-decoration-none mb-2">
                <i class="bi bi-credit-card me-2"></i>Payment Methods
            </a>
            <a href="#" class="d-block small text-decoration-none">
                <i class="bi bi-headset me-2"></i>Contact Support
            </a>
        </div>
    </div>
</div>
@endsection
