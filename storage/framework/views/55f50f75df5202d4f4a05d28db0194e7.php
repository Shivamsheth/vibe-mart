<?php $__env->startSection('title', 'VibeMart Admin - Dashboard'); ?>
<?php $__env->startSection('page_title', 'Dashboard'); ?>
<?php $__env->startSection('breadcrumb', 'Admin / Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-3">
    <!-- Today’s Orders -->
    <div class="col-md-3">
        <div class="card-soft p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted-soft small">Today’s Orders</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle">
                    +12%
                </span>
            </div>
            <div class="h3 mb-1">24</div>
            <div class="small text-muted-soft">
                Compared to yesterday
            </div>
        </div>
    </div>

    <!-- Revenue -->
    <div class="col-md-3">
        <div class="card-soft p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted-soft small">Revenue (Today)</span>
            </div>
            <div class="h3 mb-1">₹ 18,540</div>
            <div class="small text-muted-soft">
                Approximate · demo data
            </div>
        </div>
    </div>

    <!-- Active Customers -->
    <div class="col-md-3">
        <div class="card-soft p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted-soft small">Active Customers</span>
            </div>
            <div class="h3 mb-1"><?php echo e($stats['active_customers'] ?? 0); ?></div>
            
        </div>
    </div>

    <!-- Low stock -->
    <div class="col-md-3">
        <div class="card-soft p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted-soft small">Low Stock</span>
            </div>
            <div class="h3 mb-1"><?php echo e($stats['low_stock']); ?></div>
            <div class="small text-muted-soft">
                Products below threshold
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Recent Orders -->
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
                            <th>Total</th>
                            <th>Status</th>
                            <th>Placed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#1001</td>
                            <td>Rahul Sharma</td>
                            <td>₹ 2,499</td>
                            <td>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    Completed
                                </span>
                            </td>
                            <td>10 min ago</td>
                        </tr>
                        <tr>
                            <td>#1000</td>
                            <td>Ananya Patel</td>
                            <td>₹ 1,299</td>
                            <td>
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                    Pending
                                </span>
                            </td>
                            <td>30 min ago</td>
                        </tr>
                        <tr>
                            <td>#999</td>
                            <td>Vikas Mehta</td>
                            <td>₹ 4,050</td>
                            <td>
                                <span class="badge bg-info-subtle text-info border border-info-subtle">
                                    Processing
                                </span>
                            </td>
                            <td>1 hr ago</td>
                        </tr>
                        <tr>
                            <td>#998</td>
                            <td>Guest</td>
                            <td>₹ 899</td>
                            <td>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                    Cancelled
                                </span>
                            </td>
                            <td>2 hr ago</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right column: Top categories + System status -->
    <div class="col-lg-4">
        <div class="card-soft p-3 mb-3">
            <h6 class="mb-2">Top categories</h6>
            <ul class="list-unstyled small mb-0">
                <li class="d-flex justify-content-between mb-1">
                    <span>Electronics</span>
                    <span class="text-muted-soft">42%</span>
                </li>
                <li class="d-flex justify-content-between mb-1">
                    <span>Fashion</span>
                    <span class="text-muted-soft">28%</span>
                </li>
                <li class="d-flex justify-content-between mb-1">
                    <span>Groceries</span>
                    <span class="text-muted-soft">18%</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span>Home & Living</span>
                    <span class="text-muted-soft">12%</span>
                </li>
            </ul>
        </div>

        <div class="card-soft p-3">
            <h6 class="mb-2">System status</h6>
            <ul class="list-unstyled small mb-0">
                <li class="d-flex justify-content-between mb-1">
                    <span>API</span>
                    <span class="text-success">
                        <i class="bi bi-circle-fill me-1"></i>Online
                    </span>
                </li>
                <li class="d-flex justify-content-between mb-1">
                    <span>Database</span>
                    <span class="text-success">
                        <i class="bi bi-circle-fill me-1"></i>Healthy
                    </span>
                </li>
                <li class="d-flex justify-content-between">
                    <span>Queues</span>
                    <span class="text-success">
                        <i class="bi bi-circle-fill me-1"></i>Processing
                    </span>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\major-project\vibe-mart\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>