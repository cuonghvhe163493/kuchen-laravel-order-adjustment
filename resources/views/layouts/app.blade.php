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
            --color-background: #060913;
            --color-foreground: #f8fafc;
            --color-card: rgba(15, 23, 42, 0.82);
            --color-border: rgba(255, 255, 255, 0.1);
            --color-muted: #94a3b8;
        }
        body, body.antigravity-theme {
            background-color: #060913 !important;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #f8fafc;
            letter-spacing: -0.01em;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }
        /* Modern Glassmorphic Dark Navbar */
        .navbar-kuchen {
            background: rgba(10, 15, 29, 0.85) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }
        .brand-badge {
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
            color: white;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.82rem;
            letter-spacing: 1.5px;
            box-shadow: 0 2px 8px rgba(234, 88, 12, 0.35);
        }
        .navbar-brand .brand-title {
            color: #f8fafc !important;
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -0.02em;
        }
        .navbar-kuchen .nav-link {
            color: #94a3b8;
            font-weight: 500;
            font-size: 0.92rem;
            padding: 0.5rem 0.85rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .navbar-kuchen .nav-link:hover {
            color: #38bdf8;
            background-color: rgba(255, 255, 255, 0.06);
        }
        .navbar-kuchen .nav-link.active {
            color: #ffffff;
            font-weight: 700;
            background-color: rgba(37, 99, 235, 0.25);
            border: 1px solid rgba(37, 99, 235, 0.4);
        }
        /* Elevation & Dark Cards */
        .card {
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 14px;
            background: rgba(15, 23, 42, 0.82) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            color: #f8fafc !important;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.6);
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.7), 0 0 25px rgba(56, 189, 248, 0.08);
            border-color: rgba(255, 255, 255, 0.18) !important;
        }
        .card-header {
            background-color: rgba(255, 255, 255, 0.03) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #f8fafc !important;
        }
        .card-footer {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
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
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.35);
        }
        .btn-primary:hover {
            background-color: var(--color-primary-hover);
            border-color: var(--color-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.45);
        }
        .btn-white, .btn-outline-secondary {
            background-color: rgba(15, 23, 42, 0.8) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            color: #f1f5f9 !important;
        }
        .btn-white:hover, .btn-outline-secondary:hover {
            background-color: rgba(30, 41, 59, 0.9) !important;
            border-color: rgba(255, 255, 255, 0.25) !important;
            color: #ffffff !important;
        }
        /* Modern Soft Badges in Dark Theme */
        .badge {
            font-weight: 600;
            padding: 0.4em 0.75em;
            border-radius: 6px;
            font-size: 0.78rem;
            letter-spacing: 0.01em;
        }
        .badge-channel-sale { background-color: rgba(37, 99, 235, 0.2); color: #93c5fd; border: 1px solid rgba(147, 197, 253, 0.3); }
        .badge-channel-shopee { background-color: rgba(234, 88, 12, 0.2); color: #fdba74; border: 1px solid rgba(253, 186, 116, 0.3); }
        .badge-channel-tiktok { background-color: rgba(255, 255, 255, 0.1); color: #f1f5f9; border: 1px solid rgba(255, 255, 255, 0.2); }
        .badge-channel-lazada { background-color: rgba(2, 132, 199, 0.2); color: #7dd3fc; border: 1px solid rgba(125, 211, 252, 0.3); }
        .badge-channel-retail { background-color: rgba(100, 116, 139, 0.2); color: #cbd5e1; border: 1px solid rgba(203, 213, 225, 0.3); }
        
        .badge-status-pending { background-color: rgba(202, 138, 4, 0.2); color: #fef08a; border: 1px solid rgba(254, 240, 138, 0.3); }
        .badge-status-confirmed { background-color: rgba(37, 99, 235, 0.2); color: #93c5fd; border: 1px solid rgba(147, 197, 253, 0.3); }
        .badge-status-exported { background-color: rgba(22, 163, 74, 0.2); color: #86efac; border: 1px solid rgba(134, 239, 172, 0.3); }
        .badge-status-cancelled { background-color: rgba(220, 38, 38, 0.2); color: #fca5a5; border: 1px solid rgba(252, 165, 165, 0.3); }

        /* Dark Table */
        .table {
            --bs-table-bg: transparent;
            --bs-table-color: #e2e8f0;
            --bs-table-border-color: rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
        }
        .table > :not(caption) > * > * {
            padding: 0.9rem 0.85rem;
            background-color: transparent !important;
            color: #e2e8f0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }
        .table-hover tbody tr:hover > * {
            background-color: rgba(255, 255, 255, 0.04) !important;
            color: #ffffff;
        }
        th {
            color: #94a3b8 !important;
            font-weight: 600;
        }

        /* Form Controls & Inputs */
        .form-control, .form-select {
            background-color: rgba(15, 23, 42, 0.85) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #f8fafc !important;
        }
        .form-control:focus, .form-select:focus {
            background-color: rgba(15, 23, 42, 0.95) !important;
            border-color: #38bdf8 !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.25);
        }
        .form-control::placeholder {
            color: #64748b !important;
        }
        .input-group-text {
            background-color: rgba(30, 41, 59, 0.85) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #94a3b8 !important;
        }

        /* Dropdowns & Modals */
        .dropdown-menu {
            background-color: #0f172a !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #f8fafc;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.7);
        }
        .dropdown-item {
            color: #cbd5e1;
        }
        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }
        .dropdown-divider {
            border-top-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* Text Overrides */
        .text-dark {
            color: #f8fafc !important;
        }
        .text-muted {
            color: #94a3b8 !important;
        }
        .text-secondary {
            color: #cbd5e1 !important;
        }
        .bg-light {
            background-color: rgba(255, 255, 255, 0.04) !important;
        }
        .bg-white {
            background-color: rgba(15, 23, 42, 0.82) !important;
        }
        .border-light {
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        /* Pagination in Dark */
        .pagination .page-link {
            background-color: rgba(15, 23, 42, 0.8);
            border-color: rgba(255, 255, 255, 0.1);
            color: #94a3b8;
        }
        .pagination .page-link:hover {
            background-color: rgba(30, 41, 59, 0.9);
            color: #38bdf8;
            border-color: rgba(56, 189, 248, 0.3);
        }
        .pagination .page-item.active .page-link {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            color: #ffffff;
        }
        .pagination .page-item.disabled .page-link {
            background-color: rgba(15, 23, 42, 0.4);
            border-color: rgba(255, 255, 255, 0.05);
            color: #475569;
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
        nav, main {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body class="@yield('body-class', 'antigravity-theme')">
    <!-- Canvas Chấm Tròn Tương Tác 3D & Làn Sóng Nước Khi Click (Antigravity Global Canvas) -->
    <canvas id="globalAntigravityCanvas" class="antigravity-dot-canvas"></canvas>
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
                @if(!request()->routeIs('login', 'register'))
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
                @else
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3"></ul>
                @endif
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0 pt-2 pt-lg-0 border-top border-lg-0 border-slate-200">
                    @if(request()->routeIs('login', 'register'))
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('login') }}" class="btn btn-sm {{ request()->routeIs('login') ? 'btn-primary' : 'btn-outline-primary' }}">
                                Đăng nhập
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-sm {{ request()->routeIs('register') ? 'btn-primary' : 'btn-outline-primary' }}">
                                Đăng ký
                            </a>
                        </div>
                    @elseif(auth()->check())
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
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item rounded-2 py-2 text-danger d-flex align-items-center gap-2">
                                            <i class="bi bi-box-arrow-right"></i>
                                            <span>Đăng xuất</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">
                                Đăng nhập
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-sm btn-primary">
                                Đăng ký
                            </a>
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
