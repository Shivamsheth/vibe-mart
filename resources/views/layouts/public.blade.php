{{-- resources/views/layouts/public.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VibeMart - Premium Shopping')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --purple: #6366f1; --purple-dark: #4f46e5; --bg-dark: #0a0a1a;
            --card-bg: rgba(15, 15, 41, 0.85); --border: rgba(99, 102, 241, 0.2);
            --text: #e2e8f0; --text-muted: #94a3b8; --success: #10b981;
        }
        
        * { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-weight: 500; line-height: 1.5; 
        }
        
        body {
            background: linear-gradient(135deg, var(--bg-dark) 0%, #1a1a3a 100%);
            color: var(--text);
            min-height: 100vh;
            padding-top: 85px;
        }
        
        /* 🔥 NAVBAR - Fixed & Glassmorphism */
        .navbar {
            background: rgba(10, 10, 26, 0.97);
            backdrop-filter: blur(25px);
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        
        .navbar-brand {
            font-size: 1.5rem; font-weight: 700;
            background: linear-gradient(135deg, var(--purple), #8b5cf6);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }
        
        /* 🔥 CENTRAL SEARCH BAR */
        .navbar-center-search {
            max-width: 800px; flex: 1; margin: 0 1rem;
        }
        
        .navbar-center-search .input-group {
            background: rgba(15, 15, 41, 0.95);
            border: 1.5px solid var(--border);
            border-radius: 25px;
            backdrop-filter: blur(20px);
            overflow: hidden;
            height: 44px;
            transition: all 0.3s ease;
        }
        
        .navbar-center-search .input-group:focus-within {
            border-color: var(--purple);
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.15);
        }
        
        .navbar-center-search .form-control {
            background: transparent !important; border: none !important;
            border-radius: 0 !important; color: var(--text) !important;
            font-size: 0.95rem; padding: 0.625rem 1.25rem; height: 44px;
        }
        
        .navbar-center-search .input-group-text {
            background: transparent !important; border: none !important;
            border-radius: 25px 0 0 25px !important; color: var(--text-muted);
            padding: 0.625rem 1rem; font-size: 1rem;
        }
        
        .navbar-center-search .form-control::placeholder { color: var(--text-muted); }
        .navbar-center-search .form-control:focus { box-shadow: none; background: transparent !important; }
        
        /* 🔥 NAV LINKS */
        .nav-link {
            color: var(--text-muted) !important; font-size: 0.9rem;
            font-weight: 500; padding: 0.75rem 1.25rem !important;
            border-radius: 12px; margin: 0 0.25rem; transition: all 0.2s;
            position: relative; overflow: hidden;
        }
        
        .nav-link:hover {
            color: var(--text) !important; background: rgba(99, 102, 241, 0.15);
            transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,0.2);
        }
        
        /* 🔥 CART BADGE - ANIMATED */
        .cart-badge {
            min-width: 20px; height: 20px; font-size: 0.7rem;
            font-weight: 700; animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        /* 🔥 BUTTONS */
        .btn { border-radius: 12px; font-size: 0.875rem; font-weight: 600; padding: 0.625rem 1.5rem; transition: all 0.2s; }
        .btn-primary { 
            background: linear-gradient(135deg, var(--purple), var(--purple-dark));
            border: none; box-shadow: 0 4px 15px rgba(99,102,241,0.3);
        }
        .btn-primary:hover { 
            transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99,102,241,0.4);
            background: linear-gradient(135deg, var(--purple-dark), var(--purple));
        }
        
        /* 🔥 FORMS */
        .form-control, .form-select {
            background: rgba(15, 15, 41, 0.9) !important;
            border: 1.5px solid var(--border) !important;
            border-radius: 12px !important; color: var(--text) !important;
            font-size: 0.9rem; padding: 0.75rem 1rem !important; height: 42px;
            backdrop-filter: blur(12px);
        }
        
        .form-control:focus, .form-select:focus {
            background: rgba(15, 15, 41, 0.98) !important;
            border-color: var(--purple) !important;
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.15);
            color: var(--text) !important;
        }
        
        /* 🔥 CARDS & ALERTS */
        .card-soft {
            background: var(--card-bg); backdrop-filter: blur(20px);
            border: 1.5px solid var(--border); border-radius: 20px;
        }
        
        .alert { 
            border: none; border-radius: 12px; backdrop-filter: blur(12px);
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .text-muted-soft { color: var(--text-muted) !important; }
        .text-xs { font-size: 0.75rem !important; }
        
        /* 🔥 LAYOUT */
        .container-fluid { padding-left: 2rem; padding-right: 2rem; }
        @media (max-width: 768px) { .container-fluid { padding-left: 1rem; padding-right: 1rem; } }
        
        /* 🔥 TABLES */
        .table-dark { --bs-table-bg: transparent; --bs-table-color: var(--text); }
        .table-dark th { border-top: none; color: var(--text); font-weight: 600; }
        .table-dark tbody tr:hover { background: rgba(99,102,241,0.08); }
        
        /* 🔥 PAGINATION */
        .pagination .page-link {
            border: 1.5px solid var(--border); background: var(--card-bg);
            color: var(--text-muted); border-radius: 10px; font-weight: 500;
            margin: 0 0.25rem; backdrop-filter: blur(10px);
        }
        
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--purple), var(--purple-dark));
            border-color: var(--purple); color: white; box-shadow: 0 4px 12px rgba(99,102,241,0.3);
        }
        
        /* 🔥 RESPONSIVE */
        @media (max-width: 992px) {
            .navbar-center-search { margin: 0 0.5rem; order: 3; width: 100%; margin-top: 0.5rem; }
            .navbar-brand { order: 1; }
            .navbar-nav.ms-auto { order: 2; }
        }
    </style>
</head>
<body>
    <!-- 🔥 NAVBAR WITH LIVE CART -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="fas fa-shopping-bag me-1"></i>VibeMart
            </a>

            <!-- 🔥 CENTER SEARCH -->
            <div class="navbar-center-search mx-auto">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control border-0" 
                           id="globalSearch" placeholder="Search 50K+ products..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-primary px-4" id="searchBtn" title="Search">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- 🔥 RIGHT: CART + USER -->
            <div class="navbar-nav ms-auto align-items-center">
                @guest
                    <!-- Guest: Login/Register -->
                    <a class="nav-link me-2" href="{{ route('login.view') }}" title="Login">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                    <a class="btn btn-primary btn-sm" href="{{ route('register.view') }}">
                        <i class="fas fa-user-plus me-1"></i>Join Free
                    </a>
                @else
                    
                    <!-- User Dropdown -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center px-3 py-2" href="#" 
                           data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&size=32&background=6366f1&color=fff' }}" 
                                 class="rounded-circle me-2 shadow-sm" style="width:32px;height:32px;" alt="Profile">
                            <span class="d-none d-lg-inline text-xs fw-semibold">{{ Str::limit(auth()->user()->name, 15) }}</span>
                            <span class="d-lg-none"><i class="fas fa-chevron-down ms-1"></i></span>
                        </a>
                        
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" 
                            style="background: var(--card-bg); border: 1px solid var(--border); border-radius: 16px; min-width: 240px; backdrop-filter: blur(20px);">
                            <!-- Profile Header -->
                            <li class="px-3 py-2 border-bottom" style="border-color: var(--border) !important;">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&size=40&background=6366f1&color=fff' }}" 
                                         class="rounded-circle" style="width:40px;height:40px;">
                                    <div>
                                        <div class="fw-semibold text-muted-soft">{{ Str::limit(auth()->user()->name, 20) }}</div>
                                        <small class="text-muted-soft">{{ Str::limit(auth()->user()->email, 25) }}</small>
                                    </div>
                                </div>
                            </li>
                            
                            <li><hr class="dropdown-divider mx-2 my-1 opacity-25"></li>
                            
                            <!-- Menu Items -->
                            <li><a class="dropdown-item text-muted-soft px-3 py-2 rounded-2" href="{{ route('customer.profile') }}"><i class="fas fa-user me-3 text-primary"></i>Profile</a></li>
                            <li><a class="dropdown-item text-muted-soft px-3 py-2 rounded-2" href="{{ route('customer.cart') }}"><i class="fas fa-shopping-cart me-3 text-warning"></i>Cart ({{ session('cart_count', 0) }})</a></li>
                            <li><a class="dropdown-item px-3 py-2 text-muted-soft rounded-2" href="{{ route('customer.wishlist') }}"><i class="fas fa-heart me-3 text-danger"></i>Wishlist</a></li>
                            <li><a class="dropdown-item px-3 py-2 text-muted-soft rounded-2" href="{{ route('customer.orders') }}"><i class="fas fa-box me-3 text-success"></i>Orders</a></li>
                            
                            @if(auth()->user()->type === 'seller')
                            <li><hr class="dropdown-divider mx-2 my-1 opacity-25"></li>
                            <li><a class="dropdown-item px-3 py-2 text-warning fw-semibold" href="{{ route('seller.dashboard') }}"><i class="fas fa-store me-3"></i>Seller Dashboard</a></li>
                            @endif
                            
                            <li><hr class="dropdown-divider mx-2 my-1 opacity-25"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="px-1">
                                    @csrf
                                    <button class="dropdown-item w-100 text-muted-soft text-start px-3 py-2 rounded-2 border-0 bg-transparent text-danger-hover" type="submit">
                                        <i class="fas fa-sign-out-alt me-3" style="color:red"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <!-- 🔥 MAIN CONTENT -->
    <main class="container-fluid py-4 px-lg-4 px-md-3 px-2">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show position-relative card-soft mb-4 p-3 shadow-lg" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fs-4 text-success"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button class="btn-close btn-close-white position-absolute end-0 top-50 translate-middle-y me-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-relative card-soft mb-4 p-3 shadow-lg" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-3 fs-4 text-danger"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button class="btn-close btn-close-white position-absolute end-0 top-50 translate-middle-y me-2" data-bs-dismiss="alert"></button>
        </div>
        @endif
        
        @yield('content')
    </main>

    <!-- 🔥 BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- 🔥 GLOBAL SCRIPTS -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 🔥 GLOBAL SEARCH
        const globalSearch = document.getElementById('globalSearch');
        const searchBtn = document.getElementById('searchBtn');
        
        if (globalSearch && searchBtn) {
            function performSearch() {
                const term = globalSearch.value.trim();
                const params = new URLSearchParams(window.location.search);
                if (term) params.set('search', term);
                else params.delete('search');
                window.location.search = params.toString();
            }
            
            globalSearch.addEventListener('keypress', e => {
                if (e.key === 'Enter') performSearch();
            });
            searchBtn.addEventListener('click', performSearch);
        }

        // 🔥 ALERT AUTO DISMISS
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
    </script>
    
    @stack('scripts')
</body>
</html>
