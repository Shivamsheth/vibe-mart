{{-- resources/views/layouts/customer.blade.php --}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title','VibeMart')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root{
            --vm-bg:#0b1220;
            --vm-surface:#020617;
            --vm-surface-soft:#0f172a;
            --vm-border:#1f2937;
            --vm-text:#e5e7eb;
            --vm-muted:#9ca3af;
            --vm-primary:#4f46e5;
        }
        *{box-sizing:border-box;}
        body{
            margin:0;
            min-height:100vh;
            background:radial-gradient(circle at top left,#4f46e5 0,var(--vm-bg) 55%,#020617 100%);
            color:var(--vm-text);
            font-family:system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
        }

        .vm-admin-wrapper{
            display:flex;
            min-height:100vh;
        }

        /* Sidebar */
        .vm-sidebar{
            width:240px;
            background:var(--vm-surface);
            border-right:1px solid var(--vm-border);
            display:flex;
            flex-direction:column;
            padding:16px 14px;
        }
        .vm-brand{
            font-weight:700;
            letter-spacing:.08em;
            text-transform:uppercase;
            font-size:.9rem;
        }
        .vm-brand span{color:var(--vm-primary);}
        .vm-nav{
            margin-top:18px;
            list-style:none;
            padding:0;
        }
        .vm-nav li+li{margin-top:6px;}
        .vm-nav-link{
            display:flex;
            align-items:center;
            gap:10px;
            padding:8px 10px;
            border-radius:8px;
            color:var(--vm-muted);
            text-decoration:none;
            font-size:.88rem;
        }
        .vm-nav-link i{font-size:1rem;}
        .vm-nav-link.active,
        .vm-nav-link:hover{
            background:rgba(79,70,229,.1);
            color:var(--vm-text);
        }
        .vm-sidebar-footer{
            margin-top:auto;
            font-size:.78rem;
            color:var(--vm-muted);
        }

        /* Main */
        .vm-main{
            flex:1;
            display:flex;
            flex-direction:column;
        }
        .vm-topbar{
            height:56px;
            border-bottom:1px solid var(--vm-border);
            background:rgba(2,6,23,.9);
            backdrop-filter:blur(10px);
            display:flex;
            align-items:center;
            padding:0 24px;
            justify-content:space-between;
        }
        .vm-topbar-title{
            font-size:.9rem;
            color:var(--vm-muted);
        }
        .vm-topbar-title span{
            color:var(--vm-text);
            font-weight:500;
        }
        .vm-user{
            display:flex;
            align-items:center;
            gap:10px;
            font-size:.86rem;
            color:var(--vm-muted);
        }
        .vm-avatar{
            width:30px;height:30px;
            border-radius:999px;
            background:var(--vm-primary);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:.8rem;
            font-weight:600;
        }

        .vm-content{
            padding:22px 26px 32px;
        }
        .vm-page-header{
            display:flex;
            justify-content:space-between;
            align-items:flex-end;
            margin-bottom:18px;
        }
        .vm-page-title{
            font-size:1.2rem;
            font-weight:600;
        }
        .vm-breadcrumb{
            font-size:.78rem;
            color:var(--vm-muted);
        }

        .card-soft{
            background:var(--vm-surface-soft);
            border-radius:14px;
            border:1px solid var(--vm-border);
        }

        .text-muted-soft{color:var(--vm-muted);}

        @media (max-width: 991.98px){
            .vm-sidebar{display:none;}
            .vm-main{flex:1;}
        }
    </style>

    @stack('styles')
</head>
<body>
<div class="vm-admin-wrapper">
    {{-- Sidebar --}}
    <aside class="vm-sidebar">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="vm-brand">
                Vibe<span>Mart</span>
            </div>
        </div>

        <ul class="vm-nav">
            <li>
                <a href="{{ route('home') }}"
                   class="vm-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" class="vm-nav-link">
                    <i class="bi bi-cart3"></i>
                    <span>My Orders</span>
                </a>
            </li>
            <li>
                <a href="#" class="vm-nav-link">
                    <i class="bi bi-heart"></i>
                    <span>Wishlist</span>
                </a>
            </li>
            <li>
                <a href="#" class="vm-nav-link">
                    <i class="bi bi-wallet2"></i>
                    <span>Wallet</span>
                </a>
            </li>
            <li>
                <a href="#" class="vm-nav-link">
                    <i class="bi bi-person"></i>
                    <span>Profile</span>
                </a>
            </li>
        </ul>

        <div class="vm-sidebar-footer mt-3">
            <div>Logged in as</div>
            <div class="text-light">{{ auth()->user()->name ?? 'Customer' }}</div>
        </div>
    </aside>

    {{-- Main --}}
    <div class="vm-main">
        {{-- Topbar --}}
        <header class="vm-topbar">
            <div class="vm-topbar-title">
                Home / Dashboard ·
                <span>@yield('page_title','Dashboard')</span>
            </div>
            <div class="vm-user">
                <div class="vm-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'C',0,1)) }}
                </div>
                <span>{{ auth()->user()->email ?? 'customer@example.com' }}</span>
            </div>
        </header>

        {{-- Page content --}}
        <main class="vm-content">
            <div class="vm-page-header">
                <div>
                    <div class="vm-page-title">@yield('page_title','Dashboard')</div>
                    <div class="vm-breadcrumb">
                        Home / <span class="text-muted-soft">Your Dashboard</span>
                    </div>
                </div>
            </div>

            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
