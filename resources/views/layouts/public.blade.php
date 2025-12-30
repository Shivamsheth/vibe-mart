{{-- resources/views/layouts/public.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VibeMart')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --purple: #6366f1;
            --purple-dark: #4f46e5;
            --bg-dark: #0a0a1a;
            --card-bg: rgba(15, 15, 41, 0.85);
            --border: rgba(99, 102, 241, 0.2);
            --text: #e2e8f0;
            --text-muted: #94a3b8;
        }
        
        * { 
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            line-height: 1.5;
        }
        
        body {
            background: var(--bg-dark);
            color: var(--text);
            padding-top: 80px;
        }
        
        /* 🔥 COMPACT card - exact screenshot match */
        .card-soft {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            backdrop-filter: blur(16px);
            transition: all 0.25s ease;
            overflow: hidden;
        }
        
        .card-soft:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(99, 102, 241, 0.15);
            border-color: var(--purple);
        }
        
        /* 🔥 COMPACT navbar */
        .navbar {
            background: rgba(10, 10, 26, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 0;
            margin-bottom: 2rem;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 600;
            background: linear-gradient(135deg, var(--purple), #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav-link {
            color: var(--text-muted) !important;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            margin: 0 0.125rem;
        }
        
        .nav-link:hover {
            color: var(--text) !important;
            background: rgba(99, 102, 241, 0.1);
        }
        
        /* 🔥 COMPACT buttons */
        .btn {
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--purple), var(--purple-dark));
            border: none;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.3);
        }
        
        /* 🔥 COMPACT forms */
        .form-control, .form-select {
            background: rgba(15, 15, 41, 0.8) !important;
            border: 1px solid var(--border) !important;
            border-radius: 8px !important;
            color: var(--text) !important;
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem !important;
            height: 38px;
        }
        
        .form-control:focus, .form-select:focus {
            background: rgba(15, 15, 41, 0.95) !important;
            border-color: var(--purple) !important;
            box-shadow: 0 0 0 0.125rem rgba(99, 102, 241, 0.2);
            color: var(--text) !important;
        }
        
        .input-group-text {
            background: rgba(15, 15, 41, 0.8) !important;
            border-color: var(--border) !important;
            border-radius: 8px 0 0 8px !important;
            color: var(--text-muted);
            font-size: 0.875rem;
        }
        
        .text-muted-soft { color: var(--text-muted) !important; }
        .text-xs { font-size: 0.75rem !important; }
        
        /* 🔥 Perfect spacing */
        .container-fluid { padding-left: 1.5rem; padding-right: 1.5rem; }
        .mb-3 { margin-bottom: 1rem !important; }
        .p-3 { padding: 1rem !important; }
        .py-2 { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }
        
        /* 🔥 Table & pagination */
        .table-dark { --bs-table-bg: transparent; }
        .pagination .page-link {
            border: 1px solid var(--border);
            background: var(--card-bg);
            color: var(--text-muted);
            border-radius: 6px;
            font-size: 0.875rem;
        }
        
        .pagination .page-item.active .page-link {
            background: var(--purple);
            border-color: var(--purple);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-semibold" href="{{ route('home') }}">
                <i class="fas fa-shopping-bag me-1"></i>VibeMart
            </a>
            <button class="navbar-toggler p-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="btn btn-outline-light btn-sm me-2 px-3 py-1" href="{{ route('login.view') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm px-3 py-1" href="{{ route('register.view') }}">
                                <i class="fas fa-user-plus me-1"></i>Join
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center px-2 py-1" href="#" data-bs-toggle="dropdown">
                                <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&size=28&background=6366f1&color=fff' }}" 
                                     class="rounded-circle me-1" style="width:28px;height:28px;">
                                <span class="d-none d-md-inline text-xs">{{ Str::limit(auth()->user()->name, 12) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1" style="background: var(--card-bg); border: 1px solid var(--border); border-radius: 12px; min-width: 220px;">
                                <li><span class="dropdown-item-text px-3 py-2 small text-muted-soft">{{ auth()->user()->email }}</span></li>
                                <li><hr class="dropdown-divider mx-2 my-1"></li>
                                @if(auth()->user()->type === 'admin')
                                <li>
                                    <a class="dropdown-item-text px-3 py-2 small text-muted-soft" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard</a></li>
                                @endif
                                @if(auth()->user()->type === 'customer')
                                <li>
                                    <a class="dropdown-item-text px-3 py-2 small text-muted-soft" href="{{ route('customer.dashboard') }}"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard</a></li>
                                @endif
                                @if(auth()->user()->type === 'seller')
                                <li><a class="dropdown-item-text px-3 py-2 small text-muted-soft" href="{{ route('seller.dashboard') }}"><i class="fas fa-store me-2 text-warning"></i>Seller</a></li>
                                @endif
                                <li><hr class="dropdown-divider mx-2 my-1"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item w-100 text-start px-3 py-1 border-0 bg-transparent" type="submit">
                                            <div class="dropdown-item-text px-3 py-2 small text-muted-soft">
                                                                                            <i class="fas fa-sign-out-alt me-2 text-danger"></i>
                                                Logout
                                            </div>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="container-fluid py-4 px-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show card-soft mb-3 p-2" role="alert">
                <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
                <button class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
