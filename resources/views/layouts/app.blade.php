<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KÜCHEN PORTAL - Quản lý Đơn hàng')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --kuchen-primary: #0d3b66;
            --kuchen-accent: #f4d35e;
            --kuchen-dark: #1f2937;
        }
        body {
            background-color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #334155;
        }
        .navbar-kuchen {
            background-color: var(--kuchen-primary);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .brand-badge {
            background-color: #e09f3e;
            color: white;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .badge-channel-sale { background-color: #2563eb; color: #fff; }
        .badge-channel-shopee { background-color: #ea580c; color: #fff; }
        .badge-channel-tiktok { background-color: #0f172a; color: #fff; }
        .badge-channel-lazada { background-color: #0284c7; color: #fff; }
        .badge-channel-retail { background-color: #475569; color: #fff; }
        .badge-status-pending { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-status-confirmed { background-color: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }
        .badge-status-exported { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-status-cancelled { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .table-hover tbody tr:hover {
            background-color: #f1f5f9;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-kuchen sticky-top mb-4">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('orders.index') }}">
                <span class="brand-badge">KÜCHEN</span>
                <span>PORTAL IT</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-warning"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }} fw-medium" href="{{ route('orders.index') }}">
                            <i class="bi bi-receipt me-1"></i> Đơn hàng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('adjustments.*') ? 'active' : '' }} fw-medium" href="{{ route('adjustments.index') }}">
                            <i class="bi bi-card-checklist me-1"></i> Yêu cầu điều chỉnh
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-light dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                <span>{{ auth()->user()->name }}</span>
                                <span class="badge bg-warning text-dark text-uppercase">{{ auth()->user()->role }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><h6 class="dropdown-header">Chuyển vai trò kiểm thử (Bài 5)</h6></li>
                                <li>
                                    <a class="dropdown-item d-flex justify-content-between align-items-center {{ auth()->user()->role === 'sale' ? 'active' : '' }}" href="{{ route('user.switch', 'sale') }}">
                                        <span>SALE (Đi đơn)</span>
                                        <span class="badge bg-primary ms-2">sale</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex justify-content-between align-items-center {{ auth()->user()->role === 'warehouse_manager' ? 'active' : '' }}" href="{{ route('user.switch', 'warehouse_manager') }}">
                                        <span>QUẢN LÝ KHO</span>
                                        <span class="badge bg-success ms-2">kho</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex justify-content-between align-items-center {{ auth()->user()->role === 'admin' ? 'active' : '' }}" href="{{ route('user.switch', 'admin') }}">
                                        <span>ADMIN TỔNG</span>
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

    <main class="container-fluid px-4 pb-5">
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
