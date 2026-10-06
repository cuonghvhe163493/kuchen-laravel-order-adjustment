<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KÜCHEN ENTERPRISE - Hệ thống Quản trị & Điều phối Đơn hàng')</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }
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
        @media (min-width: 992px) {
            .w-lg-auto {
                width: auto !important;
            }
        }
        .border-slate-200 {
            border-color: #e2e8f0 !important;
        }
        body {
            background-color: var(--color-background);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--color-foreground);
            letter-spacing: -0.01em;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        /* Modern Glassmorphic Clean White Navbar */
        .navbar-kuchen {
            background: rgba(255, 255, 255, 0.94) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid #e2e8f0 !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 4px 12px rgba(0, 0, 0, 0.03);
        }
        .navbar-brand .brand-title {
            color: #0f172a !important;
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
        /* Elevation & Clean White Cards */
        .card {
            border: 1px solid var(--color-border) !important;
            border-radius: 14px;
            background: #ffffff !important;
            color: var(--color-foreground) !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 6px 16px -2px rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04), 0 12px 24px -4px rgba(37, 99, 235, 0.06);
        }
        .card-header {
            background-color: #f8fafc !important;
            border-bottom: 1px solid var(--color-border) !important;
            color: #0f172a !important;
        }
        .card-footer {
            background-color: #f8fafc !important;
            border-top: 1px solid var(--color-border) !important;
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
        .btn-white, .btn-outline-secondary {
            background-color: #ffffff !important;
            border-color: #cbd5e1 !important;
            color: #334155 !important;
        }
        .btn-white:hover, .btn-outline-secondary:hover {
            background-color: #f8fafc !important;
            border-color: #94a3b8 !important;
            color: #0f172a !important;
        }
        /* Soft Badges */
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

        /* Crisp Table */
        .table {
            --bs-table-bg: transparent;
            --bs-table-color: #1e293b;
            --bs-table-border-color: #f1f5f9;
        }
        .table > :not(caption) > * > * {
            padding: 0.9rem 0.85rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .table-hover tbody tr:hover > * {
            background-color: #f8fafc !important;
        }
        th {
            color: #475569 !important;
            font-weight: 600;
        }

        /* Form Controls & Inputs */
        .form-control, .form-select {
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
        }
        .form-control:focus, .form-select:focus {
            background-color: #ffffff !important;
            border-color: #2563eb !important;
            color: #0f172a !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
        .form-control::placeholder {
            color: #94a3b8 !important;
        }
        .input-group-text {
            background-color: #f8fafc !important;
            border: 1px solid #cbd5e1 !important;
            color: #64748b !important;
        }

        /* Dropdowns & Modals */
        .dropdown-menu {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            color: #0f172a;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        .dropdown-item {
            color: #334155;
        }
        .dropdown-item:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }
        .dropdown-divider {
            border-top-color: #f1f5f9 !important;
        }

        /* Pagination */
        .pagination .page-link {
            background-color: #ffffff;
            border-color: #e2e8f0;
            color: #475569;
        }
        .pagination .page-link:hover {
            background-color: #eff6ff;
            color: var(--color-primary);
            border-color: #bfdbfe;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            color: #ffffff;
        }

        /* Live Pulse Dot */
        .status-dot-pulse {
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 2s infinite;
        }
        @keyframes pulse-green {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Canvas Nền Chấm Tròn Li Ti Tương Tác Antigravity Toàn Bộ Màn Hình */
        .antigravity-dot-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
        }
        nav, main, footer {
            position: relative;
            z-index: 1;
        }

        /* Footer KÜCHEN Doanh nghiệp */
        .kuchen-footer {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            color: #475569;
            margin-top: auto;
        }
        .kuchen-footer a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.15s ease;
        }
        .kuchen-footer a:hover {
            color: #2563eb;
        }
    </style>
</head>
<body class="@yield('body-class')">
    <!-- Canvas Chấm Tròn Tương Tác 3D & Làn Sóng Nước Khi Click (Antigravity Global Canvas) -->
    <canvas id="globalAntigravityCanvas" class="antigravity-dot-canvas"></canvas>

    <!-- HEADER / NAVIGATION BAR CHUẨN DOANH NGHIỆP -->
    <nav class="navbar navbar-expand-lg navbar-kuchen sticky-top mb-4">
        <div class="container-fluid px-3 px-md-4">
            <a class="navbar-brand fw-bold fs-5 text-dark font-monospace text-decoration-none d-flex align-items-center gap-2" href="{{ route('orders.index') }}">
                <span class="text-primary fw-bolder">KÜCHEN</span>
                <span class="text-secondary fw-semibold">PORTAL</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle py-1 px-2 font-sans" style="font-size: 0.68rem; letter-spacing: 0.5px;">ENTERPRISE v1.2</span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                                <i class="bi bi-box-seam me-1 text-primary"></i> Đơn hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('adjustments.*') ? 'active' : '' }} d-inline-flex align-items-center" href="{{ route('adjustments.index') }}">
                                <i class="bi bi-arrow-left-right me-1 text-primary"></i>
                                <span>Yêu cầu điều chỉnh</span>
                                @if(($globalPendingAdjustmentsCount ?? 0) > 0)
                                    <span class="badge bg-danger rounded-pill ms-2 px-2 py-1 shadow-sm" style="font-size: 0.68rem;" title="Có {{ $globalPendingAdjustmentsCount }} yêu cầu chờ xử lý">
                                        {{ $globalPendingAdjustmentsCount }} chờ duyệt
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                                <i class="bi bi-shield-check me-1 text-success"></i> Phân quyền DB (RBAC)
                            </a>
                        </li>
                    </ul>
                @else
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3"></ul>
                @endauth

                <!-- Cụm Trạng thái Hệ thống & Hồ sơ Nhân sự -->
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0 pt-2 pt-lg-0 border-top border-lg-0 border-slate-200">
                    <!-- Trạng thái Kết nối Realtime -->
                    <div class="d-none d-xl-flex align-items-center gap-2 px-2 py-1 rounded bg-light border text-muted small" style="font-size: 0.75rem;">
                        <span class="status-dot-pulse"></span>
                        <span>Máy chủ KÜCHEN <strong>Trực tuyến</strong></span>
                    </div>

                    @auth
                        <!-- Dropdown Hồ sơ Nhân sự Đẳng Cấp Cao -->
                        <div class="dropdown w-100 w-lg-auto">
                            <button class="btn btn-sm btn-white bg-white border dropdown-toggle d-flex align-items-center justify-content-between gap-2 w-100 w-lg-auto shadow-sm" type="button" data-bs-toggle="dropdown">
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size: 0.78rem;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="fw-semibold text-dark">{{ auth()->user()->name }}</span>
                                <span class="badge {{ auth()->user()->role === 'admin' ? 'bg-danger' : (auth()->user()->role === 'warehouse_manager' ? 'bg-success' : 'bg-primary') }} text-uppercase ms-1" style="font-size: 0.7rem;">
                                    {{ auth()->user()->role }}
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border rounded-3 p-2 w-100 w-lg-auto" style="min-width: 270px;">
                                <li class="px-2 py-2 border-bottom mb-2 bg-light rounded-2">
                                    <div class="fw-bold text-dark">{{ auth()->user()->name }}</div>
                                    <div class="text-muted small font-monospace" style="font-size: 0.75rem;">{{ auth()->user()->email }}</div>
                                    <div class="mt-1 d-flex align-items-center gap-1">
                                        <i class="bi bi-shield-check text-primary"></i>
                                        <span class="small fw-semibold text-primary">{{ auth()->user()->role_display_name }}</span>
                                    </div>
                                </li>

                                <li><a class="dropdown-item rounded-2 py-2 d-flex align-items-center gap-2" href="{{ route('roles.index') }}">
                                    <i class="bi bi-key text-success"></i>
                                    <span>Xem quyền hạn trong DB</span>
                                </a></li>

                                <li><h6 class="dropdown-header text-uppercase small fw-bold text-muted mt-2 pt-1 border-top">Chuyển vai trò thử nghiệm</h6></li>
                                <li>
                                    <a class="dropdown-item rounded-2 py-1 d-flex justify-content-between align-items-center {{ auth()->user()->role === 'sale' ? 'active' : '' }}" href="{{ route('user.switch', 'sale') }}">
                                        <span class="small">Nhân viên SALE</span>
                                        <span class="badge bg-primary">sale</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-2 py-1 d-flex justify-content-between align-items-center {{ auth()->user()->role === 'warehouse_manager' ? 'active' : '' }}" href="{{ route('user.switch', 'warehouse_manager') }}">
                                        <span class="small">Quản lý KHO</span>
                                        <span class="badge bg-success">kho</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-2 py-1 d-flex justify-content-between align-items-center {{ auth()->user()->role === 'admin' ? 'active' : '' }}" href="{{ route('user.switch', 'admin') }}">
                                        <span class="small">Quản trị ADMIN</span>
                                        <span class="badge bg-danger">admin</span>
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider my-2"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item rounded-2 py-2 text-danger d-flex align-items-center gap-2 fw-semibold">
                                            <i class="bi bi-box-arrow-right"></i>
                                            <span>Đăng xuất an toàn</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('login') }}" class="btn btn-sm {{ request()->routeIs('login') ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-sm {{ request()->routeIs('register') ? 'btn-primary' : 'btn-outline-secondary' }}">
                                <i class="bi bi-person-plus me-1"></i> Đăng ký
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- KHU VỰC NỘI DUNG CHÍNH (MAIN BODY) -->
    <main class="container-fluid px-3 px-md-4 pb-5 flex-grow-1">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0" role="alert">
                <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                <div class="fw-medium">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                <div class="fw-medium">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- FOOTER CHUẨN DOANH NGHIỆP KÜCHEN HOÀN CHỈNH -->
    <footer class="kuchen-footer pt-5 pb-4 mt-5">
        <div class="container-fluid px-3 px-md-4">
            <div class="row g-4 mb-4">
                <!-- Cột 1: Thương hiệu KÜCHEN & Tiêu chuẩn Đức -->
                <div class="col-12 col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <h5 class="fw-bold text-dark font-monospace mb-0">
                            <span class="text-primary fw-bolder">KÜCHEN</span> ENTERPRISE
                        </h5>
                    </div>
                    <p class="small text-muted mb-3" style="max-width: 360px;">
                        Hệ thống lõi quản trị chuỗi cung ứng, điều phối đơn hàng đa kênh (Shopee, TikTok, Lazada, Bán lẻ) và kiểm soát kho vận chính xác theo tiêu chuẩn kỹ nghệ CHLB Đức.
                    </p>
                    <div class="d-flex gap-2 align-items-center">
                        <span class="badge bg-light text-secondary border px-2 py-1">
                            <i class="bi bi-shield-check text-success me-1"></i>ISO 9001:2015
                        </span>
                        <span class="badge bg-light text-secondary border px-2 py-1">
                            <i class="bi bi-cpu text-primary me-1"></i>German Precision
                        </span>
                    </div>
                </div>

                <!-- Cột 2: Phân hệ Nghiệp vụ & Quản trị -->
                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold text-dark mb-3 small text-uppercase">Phân hệ Nghiệp vụ</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><a href="{{ route('orders.index') }}"><i class="bi bi-chevron-right text-primary me-1" style="font-size: 0.7rem;"></i>Tra cứu đơn hàng</a></li>
                        <li><a href="{{ route('adjustments.index') }}"><i class="bi bi-chevron-right text-primary me-1" style="font-size: 0.7rem;"></i>Yêu cầu điều chỉnh</a></li>
                        <li><a href="{{ route('roles.index') }}"><i class="bi bi-chevron-right text-primary me-1" style="font-size: 0.7rem;"></i>Ma trận Phân quyền DB</a></li>
                        <li><span class="text-muted"><i class="bi bi-lock me-1"></i>Four-Eyes Principle</span></li>
                    </ul>
                </div>

                <!-- Cột 3: Trung tâm Kho vận & Hotline -->
                <div class="col-6 col-lg-3">
                    <h6 class="fw-bold text-dark mb-3 small text-uppercase">Kho vận & Hỗ trợ kỹ thuật</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><i class="bi bi-geo-alt text-primary me-2"></i><strong>Kho Bắc:</strong> KCN Quang Minh, Hà Nội</li>
                        <li><i class="bi bi-geo-alt text-primary me-2"></i><strong>Kho Nam:</strong> KCN Sóng Thần, Bình Dương</li>
                        <li><i class="bi bi-telephone text-primary me-2"></i><strong>Tổng đài:</strong> 1900 8888 (24/7)</li>
                        <li><i class="bi bi-envelope text-primary me-2"></i><strong>Kỹ thuật:</strong> support@kuchen.vn</li>
                    </ul>
                </div>

                <!-- Cột 4: Tiêu chuẩn Bảo mật & Vận hành -->
                <div class="col-12 col-lg-3">
                    <h6 class="fw-bold text-dark mb-3 small text-uppercase">Bảo mật & Công nghệ</h6>
                    <p class="small text-muted mb-2">
                        Kiến trúc RBAC thuần Database MySQL, phòng chống tấn công Brute-force & CSRF token động, bảo vệ phân quyền đa tầng.
                    </p>
                    <div class="p-2 rounded bg-light border small text-muted font-monospace" style="font-size: 0.75rem;">
                        <div>&bull; Mã hóa: Bcrypt (Rounds: 12)</div>
                        <div>&bull; Session: Database Encrypted</div>
                        <div>&bull; Phiên bản: v1.2.0-Enterprise</div>
                    </div>
                </div>
            </div>

            <!-- Chân trang Bản quyền Copyright -->
            <div class="pt-3 border-top border-slate-200 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 small text-muted">
                <div>
                    &copy; {{ date('Y') }} <strong>KÜCHEN VIETNAM</strong> &bull; Bản quyền thuộc về Tập đoàn Thiết bị Gia dụng KÜCHEN.
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Hệ thống Vận hành Chính thức</span>
                    <a href="#top" class="text-muted text-decoration-none"><i class="bi bi-arrow-up-circle me-1"></i>Đầu trang</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Global Antigravity Interactive Dot Grid & Click Wave Shockwave Engine -->
    <script>
    (function initGlobalAntigravity() {
        const canvas = document.getElementById('globalAntigravityCanvas');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        let width = canvas.width = window.innerWidth;
        let height = canvas.height = window.innerHeight;

        const spacing = 32;
        const dots = [];
        const ripples = [];

        const mouse = {
            x: -9999,
            y: -9999,
            radius: 145 // Bán kính từ trường chuột
        };

        const isDarkTheme = () => document.body.classList.contains('antigravity-theme');

        function createGrid() {
            dots.length = 0;
            const cols = Math.ceil(width / spacing) + 1;
            const rows = Math.ceil(height / spacing) + 1;

            for (let c = 0; c < cols; c++) {
                for (let r = 0; r < rows; r++) {
                    const baseX = c * spacing;
                    const baseY = r * spacing;
                    dots.push({
                        baseX, baseY,
                        x: baseX, y: baseY,
                        baseRadius: 1.4,
                        radius: 1.4,
                        targetRadius: 1.4,
                        alpha: 0.18,
                        targetAlpha: 0.18,
                        targetColor: isDarkTheme() ? '148, 163, 184' : '100, 116, 139',
                        color: isDarkTheme() ? '148, 163, 184' : '100, 116, 139'
                    });
                }
            }
        }

        createGrid();

        window.addEventListener('resize', function() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
            createGrid();
        });

        // Theo dõi tọa độ chuột toàn màn hình
        window.addEventListener('mousemove', function(e) {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });

        document.addEventListener('mouseleave', function() {
            mouse.x = -9999;
            mouse.y = -9999;
        });

        // Hiệu ứng làn sóng lan tỏa khi CLICK (Water Ripple Shockwave)
        window.addEventListener('pointerdown', function(e) {
            ripples.push({
                x: e.clientX,
                y: e.clientY,
                radius: 0,
                maxRadius: Math.max(width, height) * 0.75,
                speed: 14,
                width: 70,
                intensity: 1.0
            });
        });

        function render() {
            ctx.clearRect(0, 0, width, height);
            const dark = isDarkTheme();

            // Cập nhật và vẽ các vòng sóng nước lan tỏa (Ripples)
            for (let rIdx = ripples.length - 1; rIdx >= 0; rIdx--) {
                const rp = ripples[rIdx];
                rp.radius += rp.speed;
                rp.intensity = Math.max(0, 1 - (rp.radius / rp.maxRadius));

                if (rp.radius > 5) {
                    ctx.beginPath();
                    ctx.arc(rp.x, rp.y, rp.radius, 0, Math.PI * 2);
                    ctx.lineWidth = 2.4;
                    ctx.strokeStyle = dark
                        ? `rgba(56, 189, 248, ${rp.intensity * 0.45})`
                        : `rgba(37, 99, 235, ${rp.intensity * 0.35})`;
                    ctx.stroke();
                }

                if (rp.intensity <= 0.01 || rp.radius >= rp.maxRadius) {
                    ripples.splice(rIdx, 1);
                }
            }

            // Cập nhật và render từng chấm tròn li ti
            for (let i = 0; i < dots.length; i++) {
                const dot = dots[i];

                let targetX = dot.baseX;
                let targetY = dot.baseY;
                let targetRadius = dot.baseRadius;
                let targetAlpha = dark ? 0.18 : 0.22;
                let targetColor = dark ? '148, 163, 184' : '100, 116, 139';

                // 1. Phản ứng với chuột (Hover): Nở to thêm rõ rệt và đẩy nhẹ
                const dxMouse = mouse.x - dot.x;
                const dyMouse = mouse.y - dot.y;
                const distMouse = Math.hypot(dxMouse, dyMouse);

                if (distMouse < mouse.radius) {
                    const factor = 1 - (distMouse / mouse.radius);
                    targetX = dot.baseX - (dxMouse / (distMouse || 1)) * factor * 16;
                    targetY = dot.baseY - (dyMouse / (distMouse || 1)) * factor * 16;
                    // Nở to rõ rệt (từ 1.4px lên tới ~5.6px)
                    targetRadius = dot.baseRadius + factor * 4.2;
                    targetAlpha = 0.25 + factor * 0.75;
                    targetColor = dark ? '56, 189, 248' : '37, 99, 235';
                }

                // 2. Phản ứng với làn sóng khi CLICK (Water Ripple Shockwave)
                for (let rIdx = 0; rIdx < ripples.length; rIdx++) {
                    const rp = ripples[rIdx];
                    const dxWave = dot.x - rp.x;
                    const dyWave = dot.y - rp.y;
                    const distWave = Math.hypot(dxWave, dyWave);
                    const diff = Math.abs(distWave - rp.radius);

                    if (diff < rp.width) {
                        const waveFactor = (1 - diff / rp.width) * rp.intensity;
                        // Sóng đẩy chấm tròn văng ra ngoài theo hướng lan tỏa
                        targetX += (dxWave / (distWave || 1)) * waveFactor * 24;
                        targetY += (dyWave / (distWave || 1)) * waveFactor * 24;
                        // Chấm phồng to lên như chạm vào làn sóng
                        targetRadius = Math.max(targetRadius, dot.baseRadius + waveFactor * 5.2);
                        targetAlpha = Math.max(targetAlpha, 0.35 + waveFactor * 0.65);
                        targetColor = dark ? '56, 189, 248' : '37, 99, 235';
                    }
                }

                // Hồi vị mượt mà (Smooth Damping Interpolation)
                dot.x += (targetX - dot.x) * 0.16;
                dot.y += (targetY - dot.y) * 0.16;
                dot.radius += (targetRadius - dot.radius) * 0.18;
                dot.alpha += (targetAlpha - dot.alpha) * 0.18;
                dot.color = targetColor;

                // Vẽ chấm tròn chính
                ctx.beginPath();
                ctx.arc(dot.x, dot.y, dot.radius, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(${dot.color}, ${dot.alpha})`;
                ctx.fill();

                // Nếu chấm nở to (trên 3.2px), vẽ thêm quầng hào quang mềm (Glowing Ring)
                if (dot.radius > 3.2) {
                    ctx.beginPath();
                    ctx.arc(dot.x, dot.y, dot.radius + 2, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(${dot.color}, ${dot.alpha * 0.28})`;
                    ctx.fill();
                }
            }

            requestAnimationFrame(render);
        }

        render();
    })();
    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
