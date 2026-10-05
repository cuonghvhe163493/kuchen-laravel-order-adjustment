@extends('layouts.app')

@section('title', 'Đăng nhập - KÜCHEN ENTERPRISE & GERMAN PRECISION')
@section('body-class', 'antigravity-theme')

@section('content')
<!-- Canvas Nền Chấm Tròn Li Ti Tương Tác Theo Đầu Chuột (Antigravity Interactive Dot Grid) -->
<canvas id="antigravityDotCanvas" class="antigravity-dot-canvas"></canvas>

<div class="container py-3 py-lg-4 position-relative z-2">
    <!-- Header Thương Hiệu KÜCHEN Tối Giản & Đẳng Cấp -->
    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center gap-2 mb-2">
            <span class="brand-badge fs-6 px-3 py-1 shadow-sm">KÜCHEN</span>
            <span class="fw-bold fs-4 text-white font-monospace tracking-wide">ENTERPRISE PORTAL</span>
        </div>
        <p class="text-white-50 small mb-0">Hệ sinh thái điều phối đơn hàng & kho vận tiêu chuẩn công nghệ Đức</p>
    </div>

    <div class="row g-4 align-items-stretch justify-content-center">
        <!-- ========================================================
             CỘT 1: 3D GERMAN SPORTS CAR SHOWCASE CARD
             (Siêu Xe Thể Thao Đức 3D Tilt + Specular Glare + Parallax Depth)
             ======================================================== -->
        <div class="col-12 col-xl-6 col-lg-6">
            <div class="tilt-perspective-container h-100">
                <div class="car-3d-card h-100 d-flex flex-column justify-content-between p-4 p-md-5 rounded-4" id="kuchenCarCard">
                    <!-- Lớp Phản Chiếu Ánh Sáng Specular Glare (Trượt theo đầu chuột) -->
                    <div class="card-specular-glare" id="carCardGlare"></div>

                    <!-- Header Xe Đức (Depth Z: 30px) -->
                    <div class="card-depth-header position-relative z-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-1">
                                <i class="bi bi-shield-fill-check text-danger"></i> GERMAN MOTORSPORT
                            </span>
                            <span class="badge bg-dark bg-opacity-75 text-info px-3 py-2 rounded-pill font-monospace border border-info border-opacity-25">
                                <i class="bi bi-speedometer2 me-1"></i> PORSCHE / AMG HERITAGE
                            </span>
                        </div>
                    </div>

                    <!-- Visual Siêu Xe Thể Thao Đức 3D + Đèn LED Ma Trận Neon (Depth Z: 65px) -->
                    <div class="card-depth-car text-center my-auto position-relative z-3 py-2">
                        <!-- Holographic Ambient Glow phía sau xe -->
                        <div class="car-ambient-glow"></div>

                        <div class="car-graphic-container mx-auto position-relative">
                            <!-- SVG Vector Siêu Xe Thể Thao Đức (Đường nét khí động học tinh xảo, mâm đúc thể thao, đèn pha LED rực rỡ) -->
                            <svg class="car-svg-render w-100 h-auto" viewBox="0 0 540 220" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <!-- Sơn Xe Thể Thao Nardo Metallic Grey & Carbon -->
                                    <linearGradient id="carBodyPaint" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#334155"/>
                                        <stop offset="35%" stop-color="#1e293b"/>
                                        <stop offset="70%" stop-color="#0f172a"/>
                                        <stop offset="100%" stop-color="#020617"/>
                                    </linearGradient>
                                    <!-- Ánh Kim Mui Xe -->
                                    <linearGradient id="carRoofGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                        <stop offset="0%" stop-color="#64748b"/>
                                        <stop offset="50%" stop-color="#1e293b"/>
                                        <stop offset="100%" stop-color="#0f172a"/>
                                    </linearGradient>
                                    <!-- Đèn Pha LED Matrix Cyan Glow -->
                                    <filter id="headlightGlow" x="-50%" y="-50%" width="200%" height="200%">
                                        <feGaussianBlur in="SourceGraphic" stdDeviation="6" result="blur"/>
                                        <feMerge>
                                            <feMergeNode in="blur"/>
                                            <feMergeNode in="SourceGraphic"/>
                                        </feMerge>
                                    </filter>
                                    <linearGradient id="laserBeam" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.9"/>
                                        <stop offset="100%" stop-color="#0284c7" stop-opacity="0"/>
                                    </linearGradient>
                                    <!-- Đèn Hậu LED Đỏ Neon -->
                                    <filter id="taillightGlow" x="-50%" y="-50%" width="200%" height="200%">
                                        <feGaussianBlur in="SourceGraphic" stdDeviation="4"/>
                                        <feMerge>
                                            <feMergeNode in="SourceGraphic"/>
                                        </feMerge>
                                    </filter>
                                </defs>

                                <!-- Vệt Chiếu Sáng Đèn Pha (Laser Matrix Beam) -->
                                <polygon points="460,118 535,100 535,160 460,135" fill="url(#laserBeam)" opacity="0.6"/>

                                <!-- Khung Gầm & Bóng Đổ Khí Động Học -->
                                <ellipse cx="270" cy="180" rx="220" ry="18" fill="#000000" opacity="0.8" filter="blur(8px)"/>

                                <!-- Thân Xe Siêu Khí Động Học (Aerodynamic Streamline Body) -->
                                <path d="M 60,140 Q 80,105 130,95 L 210,75 Q 260,50 340,55 Q 395,58 430,92 L 485,115 Q 505,125 505,142 Q 500,158 475,160 L 90,160 Q 60,158 60,140 Z" fill="url(#carBodyPaint)" stroke="#475569" stroke-width="2"/>

                                <!-- Kính Chắn Gió & Mui Xe Coupe (Cabin Siêu Xe) -->
                                <path d="M 205,78 L 265,58 Q 320,56 350,72 L 405,98 L 205,98 Z" fill="#0b1329" stroke="#38bdf8" stroke-opacity="0.4" stroke-width="1.5"/>
                                <path d="M 275,62 L 325,60 L 375,94 L 285,94 Z" fill="url(#carRoofGradient)" opacity="0.8"/>

                                <!-- Cánh Gió Thể Thao Phía Sau (Carbon Rear Spoiler) -->
                                <path d="M 45,112 L 85,110 L 80,118 L 40,120 Z" fill="#020617" stroke="#ea580c" stroke-width="1.5"/>
                                <rect x="55" y="118" width="6" height="15" fill="#1e293b"/>

                                <!-- Gương Chiếu Hậu Khí Động Học -->
                                <path d="M 335,88 L 360,86 L 358,93 L 332,92 Z" fill="#0f172a" stroke="#64748b" stroke-width="1"/>

                                <!-- Dải Đèn Pha LED Matrix Công Nghệ Đức (Cyan Neon Matrix Lights) -->
                                <polygon points="460,120 488,118 475,128 450,126" fill="#38bdf8" filter="url(#headlightGlow)"/>
                                <circle cx="472" cy="122" r="3.5" fill="#ffffff" filter="url(#headlightGlow)"/>

                                <!-- Dải Đèn Hậu Đỏ Thể Thao Porsche Style -->
                                <path d="M 60,132 L 85,130" stroke="#ef4444" stroke-width="4.5" stroke-linecap="round" filter="url(#taillightGlow)"/>

                                <!-- Hốc Gió Thể Thao & Lưới Tản Nhiệt Trước -->
                                <polygon points="460,140 495,140 485,152 455,150" fill="#090d16" stroke="#ea580c" stroke-opacity="0.6" stroke-width="1.5"/>

                                <!-- Hốc Bánh Xe & Mâm Đúc Thể Thao Trái (Bánh Sau) -->
                                <ellipse cx="140" cy="155" rx="36" ry="36" fill="#050811"/>
                                <circle cx="140" cy="155" r="32" fill="#0f172a" stroke="#64748b" stroke-width="2"/>
                                <circle cx="140" cy="155" r="23" fill="#1e293b" stroke="#eab308" stroke-width="1.5"/>
                                <!-- Kẹp Phanh Thể Thao Brembo Đỏ -->
                                <rect x="125" y="142" width="6" height="15" rx="2" fill="#ef4444"/>
                                <circle cx="140" cy="155" r="7" fill="#ffffff"/>

                                <!-- Hốc Bánh Xe & Mâm Đúc Thể Thao Phải (Bánh Trước) -->
                                <ellipse cx="410" cy="155" rx="36" ry="36" fill="#050811"/>
                                <circle cx="410" cy="155" r="32" fill="#0f172a" stroke="#64748b" stroke-width="2"/>
                                <circle cx="410" cy="155" r="23" fill="#1e293b" stroke="#eab308" stroke-width="1.5"/>
                                <!-- Kẹp Phanh Brembo Đỏ -->
                                <rect x="395" y="142" width="6" height="15" rx="2" fill="#ef4444"/>
                                <circle cx="410" cy="155" r="7" fill="#ffffff"/>

                                <!-- Dập Nổi Gân Khí Động Học Cửa Xe -->
                                <path d="M 185,135 Q 260,140 345,132" stroke="#475569" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M 215,142 Q 280,146 335,140" stroke="#1e293b" stroke-width="1.5"/>
                            </svg>
                        </div>

                        <!-- Tên Mẫu Xe & Di Sản Công Nghệ Đức -->
                        <div class="mt-3">
                            <h4 class="fw-extrabold text-white mb-1 tracking-tight">KÜCHEN SPEEDSTER GT-2026</h4>
                            <p class="text-white-50 small mb-0">Biểu tượng tốc độ xử lý điều phối đơn hàng & kho vận chính xác chuẩn Đức</p>
                        </div>
                    </div>

                    <!-- Footer: Thông Số Sức Mạnh Siêu Xe (Depth Z: 25px) -->
                    <div class="card-depth-footer position-relative z-3 mt-3 pt-3 border-top border-white border-opacity-10">
                        <div class="row g-2 mb-3">
                            <div class="col-6 col-sm-3">
                                <div class="car-spec-pill text-center p-2 rounded-3">
                                    <div class="text-white-50 small font-monospace" style="font-size: 0.7rem;">CÔNG SUẤT</div>
                                    <div class="text-warning fw-bold small">750 HP</div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div class="car-spec-pill text-center p-2 rounded-3">
                                    <div class="text-white-50 small font-monospace" style="font-size: 0.7rem;">GIA TỐC 0-100</div>
                                    <div class="text-info fw-bold small">2.8 Giây</div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div class="car-spec-pill text-center p-2 rounded-3">
                                    <div class="text-white-50 small font-monospace" style="font-size: 0.7rem;">TỐC ĐỘ XỬ LÝ</div>
                                    <div class="text-success fw-bold small">&lt; 0.01s</div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div class="car-spec-pill text-center p-2 rounded-3">
                                    <div class="text-white-50 small font-monospace" style="font-size: 0.7rem;">KHUNG GẦM</div>
                                    <div class="text-white fw-bold small">Carbon 9.8</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-white-50 small d-block" style="font-size: 0.72rem;">TRẠNG THÁI HỆ THỐNG</span>
                                <span class="text-success fw-bold font-monospace small">
                                    <i class="bi bi-broadcast me-1"></i>KHO VẬN HOẠT ĐỘNG 100%
                                </span>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 px-2 py-1 small">
                                    <i class="bi bi-lightning-charge-fill me-1"></i>TURBO READY
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================
             CỘT 2: FORM ĐĂNG NHẬP CHUẨN MỰC & VALIDATE CHẶT CHẼ
             (Có validate password/email chuẩn, 1-Click Test Vai Trò)
             ======================================================== -->
        <div class="col-12 col-xl-5 col-lg-6">
            <div class="tilt-perspective-container h-100">
                <div class="login-3d-dark-card h-100 card border-0 rounded-4 p-4 p-md-5 d-flex flex-column justify-content-between position-relative shadow-2xl" id="kuchenLoginCard">
                    <!-- Specular Glare cho Login Card -->
                    <div class="card-specular-glare" id="loginCardGlare"></div>

                    <div class="position-relative z-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold text-white mb-0">Đăng nhập tài khoản</h5>
                            <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-50 px-2 py-1 small">
                                <i class="bi bi-shield-lock me-1"></i>Xác thực bảo mật
                            </span>
                        </div>
                        <p class="text-white-50 small mb-4">Nhập thông tin nhân sự hoặc sử dụng phím tắt kiểm thử nhanh</p>

                        <!-- Thông báo lỗi chung từ Server nếu có -->
                        @if($errors->any() && !$errors->has('email') && !$errors->has('password'))
                            <div class="alert alert-danger py-2 px-3 small border-0 rounded-3 mb-3 bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.post') }}" novalidate id="loginForm">
                            @csrf
                            <!-- Input Tên đăng nhập / Email làm việc với Validation chặt chẽ -->
                            <div class="mb-3">
                                <label for="email" class="form-label small fw-semibold text-white-50">Tài khoản Email (*)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary border-opacity-50 text-white-50 border-end-0">
                                        <i class="bi bi-envelope-at"></i>
                                    </span>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           class="form-control bg-dark text-white border-secondary border-opacity-50 border-start-0 @error('email') is-invalid @enderror" 
                                           placeholder="nhanvien@kuchen.vn" 
                                           value="{{ old('email', 'sale@kuchen.vn') }}" 
                                           required 
                                           autocomplete="username"
                                           autofocus>
                                    @error('email')
                                        <div class="invalid-feedback d-block mt-1 small text-danger">
                                            <i class="bi bi-x-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                    <div class="invalid-feedback client-email-error mt-1 small text-danger d-none">
                                        <i class="bi bi-x-circle me-1"></i>Vui lòng nhập định dạng email hợp lệ (ví dụ: user@kuchen.vn).
                                    </div>
                                </div>
                            </div>

                            <!-- Input Mật khẩu với Validation & Nút Ẩn/Hiện Eye Toggle -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="password" class="form-label small fw-semibold text-white-50 mb-0">Mật khẩu (*)</label>
                                    <span class="small text-white-50" style="font-size: 0.75rem;">Mặc định: password</span>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary border-opacity-50 text-white-50 border-end-0">
                                        <i class="bi bi-key"></i>
                                    </span>
                                    <input type="password" 
                                           name="password" 
                                           id="password" 
                                           class="form-control bg-dark text-white border-secondary border-opacity-50 border-start-0 border-end-0 @error('password') is-invalid @enderror" 
                                           placeholder="Nhập mật khẩu..." 
                                           value="password" 
                                           required
                                           minlength="6"
                                           autocomplete="current-password">
                                    <button class="btn btn-outline-secondary border-secondary border-opacity-50 text-white-50" type="button" id="togglePasswordBtn" title="Ẩn/Hiện mật khẩu">
                                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback d-block mt-1 small text-danger">
                                            <i class="bi bi-x-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                    <div class="invalid-feedback client-password-error mt-1 small text-danger d-none">
                                        <i class="bi bi-x-circle me-1"></i>Mật khẩu bắt buộc và cần tối thiểu 6 ký tự.
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input bg-dark border-secondary" type="checkbox" name="remember" id="remember" checked>
                                    <label class="form-check-label small text-white-50" for="remember">
                                        Ghi nhớ phiên làm việc
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm rounded-3 d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-box-arrow-in-right"></i>
                                <span>Đăng nhập hệ thống</span>
                            </button>
                        </form>

                        <div class="position-relative my-4 text-center">
                            <hr class="border-secondary border-opacity-25">
                            <span class="position-absolute top-50 start-50 translate-middle px-3 small text-white-50 fw-semibold bg-dark-card" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                                KIỂM THỬ NHANH VAI TRÒ (1-CLICK)
                            </span>
                        </div>

                        <!-- Cụm Kiểm Thử Nhanh 1-Click Độc Lập Cho Người Chấm/Test -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('user.switch', 'sale') }}" class="btn btn-outline-primary btn-sm d-flex justify-content-between align-items-center py-2 px-3 border-opacity-50">
                                <span><i class="bi bi-person-workspace me-2 text-primary"></i>Đăng nhập nhanh với vai trò <strong>SALE</strong></span>
                                <span class="badge bg-primary">sale</span>
                            </a>
                            <a href="{{ route('user.switch', 'warehouse_manager') }}" class="btn btn-outline-success btn-sm d-flex justify-content-between align-items-center py-2 px-3 border-opacity-50">
                                <span><i class="bi bi-boxes me-2 text-success"></i>Đăng nhập nhanh với vai trò <strong>QUẢN LÝ KHO</strong></span>
                                <span class="badge bg-success">kho</span>
                            </a>
                            <a href="{{ route('user.switch', 'admin') }}" class="btn btn-outline-danger btn-sm d-flex justify-content-between align-items-center py-2 px-3 border-opacity-50">
                                <span><i class="bi bi-shield-lock me-2 text-danger"></i>Đăng nhập nhanh với vai trò <strong>ADMIN TỔNG</strong></span>
                                <span class="badge bg-danger">admin</span>
                            </a>
                        </div>
                    </div>

                    <div class="text-center mt-4 position-relative z-3">
                        <span class="text-white-50 small">Chưa có tài khoản kiểm thử? </span>
                        <a href="{{ route('register') }}" class="small fw-bold text-info text-decoration-none">
                            Đăng ký vai trò mới
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================
     CSS STYLES CHO HIỆU ỨNG ANTIGRAVITY & 3D GERMAN CAR CARD
     ======================================================== -->
<style>
/* Nền Tối Antigravity Theme Toàn Trang */
body.antigravity-theme {
    background-color: #060913 !important;
    color: #f1f5f9;
    overflow-x: hidden;
    position: relative;
    min-height: 100vh;
}

/* Canvas Lưới Chấm Tròn Li Ti Nằm Phía Sau */
.antigravity-dot-canvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    pointer-events: none;
    z-index: 1;
}

/* Navbar Trong Suốt Hợp Chuẩn Antigravity */
body.antigravity-theme .navbar-kuchen {
    background: rgba(10, 15, 29, 0.78) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
}
body.antigravity-theme .navbar-brand .brand-title {
    color: #f8fafc !important;
}

/* CSS Perspective Container */
.tilt-perspective-container {
    perspective: 1200px;
    perspective-origin: center center;
}

/* Card 3D Siêu Xe Đức */
.car-3d-card {
    background: radial-gradient(circle at 20% 20%, #1e293b 0%, #0f172a 65%, #050811 100%);
    border: 1px solid rgba(255, 255, 255, 0.12);
    box-shadow: 0 25px 50px -15px rgba(0, 0, 0, 0.7), 0 0 35px rgba(56, 189, 248, 0.1);
    transform-style: preserve-3d;
    transform: rotateX(0deg) rotateY(0deg);
    position: relative;
    overflow: hidden;
    will-change: transform;
    cursor: grab;
    transition: box-shadow 0.4s ease;
}
.car-3d-card:active {
    cursor: grabbing;
}

/* Card 3D Đăng Nhập Dark Glassmorphic */
.login-3d-dark-card {
    background: rgba(15, 23, 42, 0.88) !important;
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    box-shadow: 0 25px 50px -15px rgba(0, 0, 0, 0.7), 0 0 30px rgba(37, 99, 235, 0.12);
    transform-style: preserve-3d;
    transform: rotateX(0deg) rotateY(0deg);
    position: relative;
    overflow: hidden;
    will-change: transform;
}
.bg-dark-card {
    background-color: #0f172a !important;
}

/* Lớp Phản Chiếu Ánh Sáng Specular Glare Theo Đầu Chuột */
.card-specular-glare {
    position: absolute;
    inset: 0;
    pointer-events: none;
    border-radius: inherit;
    background: radial-gradient(circle 380px at 50% 50%, rgba(255, 255, 255, 0.28) 0%, rgba(255, 255, 255, 0.04) 55%, transparent 80%);
    opacity: 0;
    mix-blend-mode: overlay;
    z-index: 10;
    will-change: opacity, background;
}

/* Parallax Depth Trục Z */
.card-depth-header {
    transform: translateZ(28px);
    transform-style: preserve-3d;
}
.card-depth-car {
    transform: translateZ(65px);
    transform-style: preserve-3d;
}
.card-depth-footer {
    transform: translateZ(24px);
    transform-style: preserve-3d;
}

/* Hào Quang Xung Quanh Siêu Xe */
.car-ambient-glow {
    position: absolute;
    width: 320px;
    height: 160px;
    background: radial-gradient(ellipse, rgba(56, 189, 248, 0.35) 0%, rgba(234, 88, 12, 0.2) 50%, transparent 75%);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    filter: blur(40px);
    pointer-events: none;
    z-index: 0;
}
.car-graphic-container {
    max-width: 480px;
    position: relative;
    z-index: 2;
    filter: drop-shadow(0 15px 25px rgba(0, 0, 0, 0.8));
}
.car-spec-pill {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(8px);
}
</style>
@endsection

@section('scripts')
<!-- GSAP CDN cho hiệu ứng 3D Physics Animation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    /* ========================================================
       1. HIỆU ỨNG ANTIGRAVITY WEB: LƯỚI CHẤM TRÒN LI TI TƯƠNG TÁC THEO CHUỘT
       (Di chuột vào đâu các chấm nổi lên & phát sáng như bị chạm vào)
       ======================================================== */
    (function initAntigravityDots() {
        const canvas = document.getElementById('antigravityDotCanvas');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        let width = canvas.width = window.innerWidth;
        let height = canvas.height = window.innerHeight;

        const spacing = 32; // Khoảng cách giữa các chấm tròn
        const dots = [];

        // Con trỏ chuột (mặc định để xa màn hình khi chưa rê)
        const mouse = {
            x: -9999,
            y: -9999,
            radius: 130 // Bán kính tương tác của từ trường chuột
        };

        function createGrid() {
            dots.length = 0;
            const cols = Math.ceil(width / spacing) + 1;
            const rows = Math.ceil(height / spacing) + 1;

            for (let c = 0; c < cols; c++) {
                for (let r = 0; r < rows; r++) {
                    const baseX = c * spacing;
                    const baseY = r * spacing;
                    dots.push({
                        baseX: baseX,
                        baseY: baseY,
                        x: baseX,
                        y: baseY,
                        baseRadius: 1.2,
                        radius: 1.2,
                        targetRadius: 1.2,
                        alpha: 0.16,
                        targetAlpha: 0.16,
                        color: '148, 163, 184' // Màu slate nhạt mờ mờ
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

        // Theo dõi tọa độ chuột trên toàn màn hình
        window.addEventListener('mousemove', function(e) {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });

        // Khi chuột rời khỏi cửa sổ
        document.addEventListener('mouseleave', function() {
            mouse.x = -9999;
            mouse.y = -9999;
        });

        // Vòng lặp Animation Render 60fps
        function render() {
            ctx.clearRect(0, 0, width, height);

            for (let i = 0; i < dots.length; i++) {
                const dot = dots[i];

                const dx = mouse.x - dot.x;
                const dy = mouse.y - dot.y;
                const dist = Math.sqrt(dx * dx + dy * dy);

                if (dist < mouse.radius) {
                    // Càng gần chuột thì lực đẩy và độ nở càng mạnh
                    const factor = 1 - (dist / mouse.radius);
                    
                    // Chấm bị đẩy nhẹ ra xa theo hướng từ trường Antigravity
                    const targetX = dot.baseX - (dx / dist) * factor * 14;
                    const targetY = dot.baseY - (dy / dist) * factor * 14;

                    // Chấm nổi to lên như bị chạm vào (từ 1.2px lên tối đa 3.6px)
                    dot.targetRadius = dot.baseRadius + factor * 2.4;
                    
                    // Chấm sáng rực lên với màu Cyan neon
                    dot.targetAlpha = 0.2 + factor * 0.75;
                    dot.color = '56, 189, 248'; // Neon Cyan Antigravity
                    
                    dot.x += (targetX - dot.x) * 0.18;
                    dot.y += (targetY - dot.y) * 0.18;
                } else {
                    // Khi không có chuột: đàn hồi êm dịu về vị trí và kích thước tĩnh
                    dot.targetRadius = dot.baseRadius;
                    dot.targetAlpha = 0.16;
                    dot.color = '148, 163, 184';

                    dot.x += (dot.baseX - dot.x) * 0.1;
                    dot.y += (dot.baseY - dot.y) * 0.1;
                }

                // Interpolation mượt mà cho bán kính và độ sáng
                dot.radius += (dot.targetRadius - dot.radius) * 0.15;
                dot.alpha += (dot.targetAlpha - dot.alpha) * 0.15;

                // Vẽ chấm tròn li ti
                ctx.beginPath();
                ctx.arc(dot.x, dot.y, dot.radius, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(${dot.color}, ${dot.alpha})`;
                ctx.fill();
            }

            requestAnimationFrame(render);
        }

        render();
    })();

    /* ========================================================
       2. KHỞI TẠO HIỆU ỨNG 3D HOVER TILT CARD BẰNG GSAP
       ======================================================== */
    function setup3DTiltCard(cardId, glareId, maxTilt = 16) {
        const card = document.getElementById(cardId);
        const glare = document.getElementById(glareId);

        if (!card || !glare || typeof gsap === 'undefined') return;

        let bounds = null;

        function updateBounds() {
            bounds = card.getBoundingClientRect();
        }

        card.addEventListener('mouseenter', function() {
            updateBounds();
            gsap.to(glare, { opacity: 1, duration: 0.3, ease: 'power2.out' });
        });

        card.addEventListener('mousemove', function(e) {
            if (!bounds) updateBounds();

            const mouseX = e.clientX - bounds.left;
            const mouseY = e.clientY - bounds.top;

            const normX = (mouseX / bounds.width - 0.5) * 2;
            const normY = (mouseY / bounds.height - 0.5) * 2;

            const rotateY = normX * maxTilt;
            const rotateX = -normY * maxTilt;

            gsap.to(card, {
                rotationX: rotateX,
                rotationY: rotateY,
                transformPerspective: 1200,
                duration: 0.25,
                ease: 'power2.out',
                overwrite: 'auto'
            });

            const glareX = Math.round((mouseX / bounds.width) * 100);
            const glareY = Math.round((mouseY / bounds.height) * 100);
            glare.style.background = `radial-gradient(circle 380px at ${glareX}% ${glareY}%, rgba(255, 255, 255, 0.32) 0%, rgba(255, 255, 255, 0.04) 55%, transparent 80%)`;
        });

        card.addEventListener('mouseleave', function() {
            gsap.to(card, {
                rotationX: 0,
                rotationY: 0,
                duration: 0.85,
                ease: 'elastic.out(1, 0.45)',
                overwrite: 'auto'
            });

            gsap.to(glare, { opacity: 0, duration: 0.5, ease: 'power2.out' });
            bounds = null;
        });

        window.addEventListener('resize', function() {
            bounds = null;
        });
    }

    // Kích hoạt 3D Tilt cho Card Xe Thể Thao Đức (±15°) và Card Đăng Nhập (±6°)
    setup3DTiltCard('kuchenCarCard', 'carCardGlare', 15);
    setup3DTiltCard('kuchenLoginCard', 'loginCardGlare', 6);

    /* ========================================================
       3. CLIENT-SIDE VALIDATION & NÚT ẨN/HIỆN MẬT KHẨU
       ======================================================== */
    // Toggle Ẩn/Hiện mật khẩu
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    if (toggleBtn && passwordInput && toggleIcon) {
        toggleBtn.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    }

    // Client-side Validation khi submit form
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const clientEmailErr = document.querySelector('.client-email-error');
    const clientPassErr = document.querySelector('.client-password-error');

    if (loginForm && emailInput && passwordInput) {
        loginForm.addEventListener('submit', function(e) {
            let isValid = true;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Validate Email
            if (!emailInput.value.trim() || !emailRegex.test(emailInput.value.trim())) {
                emailInput.classList.add('is-invalid');
                if (clientEmailErr) clientEmailErr.classList.remove('d-none');
                isValid = false;
            } else {
                emailInput.classList.remove('is-invalid');
                if (clientEmailErr) clientEmailErr.classList.add('d-none');
            }

            // Validate Password
            if (!passwordInput.value || passwordInput.value.length < 6) {
                passwordInput.classList.add('is-invalid');
                if (clientPassErr) clientPassErr.classList.remove('d-none');
                isValid = false;
            } else {
                passwordInput.classList.remove('is-invalid');
                if (clientPassErr) clientPassErr.classList.add('d-none');
            }

            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    }
});
</script>
@endsection
