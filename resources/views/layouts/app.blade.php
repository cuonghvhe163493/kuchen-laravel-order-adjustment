<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KÜCHEN PORTAL - Quản lý Đơn hàng')</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --color-primary: #2563eb;
            --color-primary-hover: #1d4ed8;
            --color-secondary: #0f172a;
            --color-accent: #ea580c;
            --color-background: #f8fafc;
            --color-foreground: #0f172a;
            --color-card: #ffffff;
            --color-border: #e2e8f0;
            --color-muted: #64748b;
        }
        body {
            background-color: var(--color-background);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--color-foreground);
            letter-spacing: -0.01em;
            -webkit-font-smoothing: antialiased;
        }
        /* Modern Glassmorphic Navbar */
        .navbar-kuchen {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 4px 12px rgba(0, 0, 0, 0.03);
        }
        .brand-badge {
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
            color: white;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.82rem;
            letter-spacing: 1.5px;
            box-shadow: 0 2px 6px rgba(234, 88, 12, 0.25);
        }
        .navbar-brand .brand-title {
            color: #0f172a;
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -0.02em;
        }
        .navbar-kuchen .nav-link {
            color: #475569;
            font-weight: 500;
            font-size: 0.92rem;
            padding: 0.5rem 0.85rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .navbar-kuchen .nav-link:hover {
            color: var(--color-primary);
            background-color: #f1f5f9;
        }
        .navbar-kuchen .nav-link.active {
            color: var(--color-primary);
            font-weight: 700;
            background-color: #eff6ff;
        }
        /* Elevation & Cards */
        .card {
            border: 1px solid var(--color-border);
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 6px 16px -2px rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.02), 0 10px 24px -4px rgba(0, 0, 0, 0.06);
        }
        /* Modern Buttons */
        .btn {
            font-weight: 600;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.88rem;
            transition: all 0.18s ease;
        }
        .btn-primary {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
        }
        .btn-primary:hover {
            background-color: var(--color-primary-hover);
            border-color: var(--color-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }
        /* Modern Soft Badges */
        .badge {
            font-weight: 600;
            padding: 0.4em 0.75em;
            border-radius: 6px;
            font-size: 0.78rem;
            letter-spacing: 0.01em;
        }
        .badge-channel-sale { background-color: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }
        .badge-channel-shopee { background-color: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
        .badge-channel-tiktok { background-color: #f1f5f9; color: #0f172a; border: 1px solid #e2e8f0; }
        .badge-channel-lazada { background-color: #f0f9ff; color: #0369a1; border: 1px solid #e0f2fe; }
        .badge-channel-retail { background-color: #f8fafc; color: #475569; border: 1px solid #cbd5e1; }
        
        .badge-status-pending { background-color: #fefce8; color: #a16207; border: 1px solid #fef08a; }
        .badge-status-confirmed { background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-status-exported { background-color: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-status-cancelled { background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        .table > :not(caption) > * > * {
            padding: 0.9rem 0.85rem;
            border-bottom-color: #f1f5f9;
        }
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-kuchen sticky-top mb-4">
        <div class="container-fluid px-3 px-md-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('orders.index') }}">
                <span class="brand-badge">KÜCHEN</span>
                <span class="brand-title">PORTAL IT</span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                            <i class="bi bi-box-seam me-1 text-primary"></i> Đơn hàng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('adjustments.*') ? 'active' : '' }}" href="{{ route('adjustments.index') }}">
                            <i class="bi bi-arrow-left-right me-1 text-primary"></i> Yêu cầu điều chỉnh
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0 pt-2 pt-lg-0 border-top border-lg-0 border-slate-200">
                    @auth
                        <div class="dropdown w-100 w-lg-auto">
                            <button class="btn btn-sm btn-white bg-white border dropdown-toggle d-flex align-items-center justify-content-between gap-2 w-100 w-lg-auto shadow-sm" type="button" data-bs-toggle="dropdown">
                                <span class="d-flex align-items-center gap-2 text-dark">
                                    <i class="bi bi-person-circle text-primary"></i>
                                    <span class="fw-semibold">{{ auth()->user()->name }}</span>
                                </span>
                                <span class="badge bg-primary-subtle text-primary text-uppercase ms-auto">{{ auth()->user()->role }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border rounded-3 p-2 w-100 w-lg-auto" style="min-width: 240px;">
                                <li><h6 class="dropdown-header text-uppercase small fw-bold text-muted">Chuyển vai trò</h6></li>
                                <li>
                                    <a class="dropdown-item rounded-2 py-2 d-flex justify-content-between align-items-center {{ auth()->user()->role === 'sale' ? 'active' : '' }}" href="{{ route('user.switch', 'sale') }}">
                                        <span class="fw-medium">SALE (Đi đơn)</span>
                                        <span class="badge bg-primary ms-2">sale</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-2 py-2 d-flex justify-content-between align-items-center {{ auth()->user()->role === 'warehouse_manager' ? 'active' : '' }}" href="{{ route('user.switch', 'warehouse_manager') }}">
                                        <span class="fw-medium">QUẢN LÝ KHO</span>
                                        <span class="badge bg-success ms-2">kho</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-2 py-2 d-flex justify-content-between align-items-center {{ auth()->user()->role === 'admin' ? 'active' : '' }}" href="{{ route('user.switch', 'admin') }}">
                                        <span class="fw-medium">ADMIN TỔNG</span>
                                        <span class="badge bg-danger ms-2">admin</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="container-fluid px-3 px-md-4 pb-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
