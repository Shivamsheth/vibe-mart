<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title','VibeMart Seller')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        .vm-admin-wrapper{display:flex;min-height:100vh;}

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
        .vm-nav{margin-top:18px;list-style:none;padding:0;}
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
            transition: all 0.2s ease;
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

        .vm-main{flex:1;display:flex;flex-direction:column;}
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
        .vm-topbar-title{font-size:.9rem;color:var(--vm-muted);}
        .vm-topbar-title span{color:var(--vm-text);font-weight:500;}
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

        .vm-content{padding:22px 26px 32px;}
        .vm-page-header{
            display:flex;
            justify-content:space-between;
            align-items:flex-end;
            margin-bottom:18px;
        }
        .vm-page-title{font-size:1.2rem;font-weight:600;}
        .vm-breadcrumb{font-size:.78rem;color:var(--vm-muted);}
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
                <a href="{{ route('seller.dashboard') }}"
                   class="vm-nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('seller.products.index') }}"
                   class="vm-nav-link {{ request()->routeIs('seller.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
            </li>
            <li>
                <a href="#" class="vm-nav-link">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li>
                <a href="#" class="vm-nav-link">
                    <i class="fas fa-chart-line"></i>
                    <span>Analytics</span>
                </a>
            </li>
            <li>
                <a href="#" class="vm-nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>

        <div class="vm-sidebar-footer mt-3">
            <div>Logged in as</div>
            <div class="text-light">{{ auth()->user()->name ?? 'Seller' }}</div>
        </div>
    </aside>

    {{-- Main --}}
    <div class="vm-main">
        <header class="vm-topbar">
            <div class="vm-topbar-title">
                Seller / <span>@yield('page_title','Dashboard')</span>
            </div>
            <div class="vm-user">
                <div class="vm-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'S',0,1)) }}
                </div>
                <span>{{ auth()->user()->name ?? 'seller' }}</span>
            </div>
        </header>

        <main class="vm-content">
            <div class="vm-page-header">
                <div>
                    <div class="vm-page-title">@yield('page_title','Dashboard')</div>
                    <div class="vm-breadcrumb">
                        Seller / <span class="text-muted-soft">@yield('breadcrumb', 'Dashboard')</span>
                    </div>
                </div>
            </div>

            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
@stack('scripts')
</body>
</html>
