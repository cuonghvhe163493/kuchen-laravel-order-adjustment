@extends('layouts.app')

@section('title', 'Đăng nhập - KÜCHEN PORTAL & Thiết Bị Gia Dụng Cao Cấp')

@section('content')
<div class="container py-3 py-lg-4">
    <!-- Breadcrumb / System Title Header -->
    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center gap-2 mb-2">
            <span class="brand-badge fs-6 px-3 py-1">KÜCHEN</span>
            <span class="fw-bold fs-4 text-dark font-monospace">ENTERPRISE 3D PORTAL</span>
        </div>
        <p class="text-muted small mb-0">Hệ sinh thái điều phối đơn hàng & kiểm soát kho vận thiết bị nhà bếp chuẩn Đức</p>
    </div>

    <div class="row g-4 align-items-stretch justify-content-center">
        <!-- ==========================================
             CỘT 1: 3D INTERACTIVE PRODUCT SHOWCASE CARD 
             (CSS Perspective + GSAP 3D Hover Tilt + Specular Glare)
             ========================================== -->
        <div class="col-12 col-xl-6 col-lg-6">
            <div class="tilt-perspective-container h-100">
                <div class="product-3d-card h-100 d-flex flex-column justify-content-between p-4 p-md-5 rounded-4" id="kuchenProductCard">
                    <!-- Moving Highlight Glare Layer -->
                    <div class="card-specular-glare" id="productCardGlare"></div>

                    <!-- Header: Top Badges & German Quality Seal (Layer Z-depth: 30px) -->
                    <div class="card-depth-header position-relative z-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-1">
                                <i class="bi bi-patch-check-fill text-danger"></i> GERMAN PRECISION
                            </span>
                            <span class="badge bg-dark bg-opacity-75 text-white px-3 py-2 rounded-pill font-monospace border border-secondary border-opacity-50">
                                <i class="bi bi-cpu me-1 text-info"></i> SMART IOT 2026
                            </span>
                        </div>
                    </div>

                    <!-- Middle: Visual 3D Product Mockup & Holographic Glow (Layer Z-depth: 65px) -->
                    <div class="card-depth-product text-center my-3 position-relative z-3">
                        <div class="product-visual-stage mx-auto position-relative">
                            <!-- Background Ambient Hologram Halo -->
                            <div class="hologram-glow"></div>
                            
                            <!-- Product Illustration Container with True 3D Floating Layer -->
                            <div class="product-device-mockup py-3">
                                <div class="induction-cooktop mx-auto p-3 rounded-4 shadow-lg">
                                    <div class="glass-surface p-3 rounded-3 d-flex flex-column justify-content-between h-100 position-relative">
                                        <!-- Dual Induction Burners Glowing VFX -->
                                        <div class="d-flex justify-content-around align-items-center py-2">
                                            <div class="burner-ring burner-left">
                                                <div class="burner-core"></div>
                                                <span class="burner-label font-monospace">BOOST 3700W</span>
                                            </div>
                                            <div class="burner-ring burner-right">
                                                <div class="burner-core"></div>
                                                <span class="burner-label font-monospace">FLEX 3700W</span>
                                            </div>
                                        </div>
                                        <!-- Smart Touch Display Panel -->
                                        <div class="control-panel d-flex justify-content-between align-items-center px-3 py-2 rounded-2 mt-3">
                                            <span class="font-monospace text-info small fw-bold">
                                                <i class="bi bi-wifi me-1"></i>CONNECTED
                                            </span>
                                            <span class="badge bg-danger text-white font-monospace px-2">KÜCHEN Ku-9800</span>
                                            <span class="font-monospace text-warning small fw-bold">
                                                <i class="bi bi-shield-check me-1"></i>READY
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Title & Subtitle (Layer Z-depth: 40px) -->
                        <div class="mt-3">
                            <h4 class="fw-extrabold text-white mb-1 tracking-tight">KÜCHEN MasterChef Pro KU-9800</h4>
                            <p class="text-white-50 small mb-0">Bếp từ thông minh mặt kính Schott Ceran Miradur & Chip Inverter Siemens</p>
                        </div>
                    </div>

                    <!-- Footer: Tech Specs & Live Warehouse Status (Layer Z-depth: 25px) -->
                    <div class="card-depth-footer position-relative z-3 mt-4 pt-3 border-top border-white border-opacity-10">
                        <div class="row g-2 mb-3">
                            <div class="col-6 col-sm-3">
                                <div class="spec-pill text-center p-2 rounded-3">
                                    <div class="text-white-50 small font-monospace" style="font-size: 0.72rem;">CÔNG SUẤT</div>
                                    <div class="text-white fw-bold small">7,400 W</div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div class="spec-pill text-center p-2 rounded-3">
                                    <div class="text-white-50 small font-monospace" style="font-size: 0.72rem;">KÍNH CƯỜNG LỰC</div>
                                    <div class="text-white fw-bold small">Ceran 9.5H</div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div class="spec-pill text-center p-2 rounded-3">
                                    <div class="text-white-50 small font-monospace" style="font-size: 0.72rem;">KHO TRUNG TÂM</div>
                                    <div class="text-success fw-bold small">142 Chiếc</div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div class="spec-pill text-center p-2 rounded-3">
                                    <div class="text-white-50 small font-monospace" style="font-size: 0.72rem;">BẢO HÀNH</div>
                                    <div class="text-warning fw-bold small">10 Năm</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-white-50 small d-block" style="font-size: 0.75rem;">GIÁ NIÊM YẾT HỆ THỐNG</span>
                                <span class="text-warning fw-bold fs-5 font-monospace">28.900.000 ₫</span>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-2 py-1 small">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>Sẵn sàng xuất kho
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==========================================
             CỘT 2: LOGIN FORM CARD 
             (Hỗ trợ 3D Tilt mượt mà, Form đầy đủ & 1-Click Role Switcher)
             ========================================== -->
        <div class="col-12 col-xl-5 col-lg-6">
            <div class="tilt-perspective-container h-100">
                <div class="login-3d-card h-100 card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white d-flex flex-column justify-content-between position-relative" id="kuchenLoginCard">
                    <!-- Subtle Specular Glare cho Login Card -->
                    <div class="card-specular-glare" id="loginCardGlare"></div>

                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold text-dark mb-0">Đăng nhập tài khoản</h5>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 small">
                                <i class="bi bi-shield-lock me-1"></i>Bảo mật 2FA
                            </span>
                        </div>
                        <p class="text-muted small mb-4">Vui lòng đăng nhập với vai trò để truy cập cổng nghiệp vụ KÜCHEN</p>

                        @if($errors->any())
                            <div class="alert alert-danger py-2 px-3 small border-0 rounded-3 mb-3">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label small fw-semibold text-secondary">Email làm việc</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                           placeholder="ví dụ: sale@kuchen.vn" 
                                           value="{{ old('email', 'sale@kuchen.vn') }}" 
                                           required 
                                           autofocus>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="password" class="form-label small fw-semibold text-secondary mb-0">Mật khẩu</label>
                                    <span class="small text-muted" style="font-size: 0.78rem;">Mặc định: password</span>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" 
                                           name="password" 
                                           id="password" 
                                           class="form-control border-start-0" 
                                           placeholder="Nhập mật khẩu..." 
                                           value="password" 
                                           required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                                    <label class="form-check-label small text-muted" for="remember">
                                        Ghi nhớ đăng nhập
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm rounded-3">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập hệ thống
                            </button>
                        </form>

                        <div class="position-relative my-4">
                            <hr class="text-muted opacity-25">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-muted fw-medium" style="font-size: 0.75rem;">
                                HOẶC KIỂM THỬ NHANH 1-CLICK
                            </span>
                        </div>

                        <!-- 1-Click Role Switcher Demo -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('user.switch', 'sale') }}" class="btn btn-outline-primary btn-sm d-flex justify-content-between align-items-center py-2 px-3">
                                <span><i class="bi bi-person-workspace me-2"></i>Đăng nhập nhanh với vai trò <strong>SALE</strong></span>
                                <span class="badge bg-primary">sale</span>
                            </a>
                            <a href="{{ route('user.switch', 'warehouse_manager') }}" class="btn btn-outline-success btn-sm d-flex justify-content-between align-items-center py-2 px-3">
                                <span><i class="bi bi-boxes me-2"></i>Đăng nhập nhanh với vai trò <strong>QUẢN LÝ KHO</strong></span>
                                <span class="badge bg-success">kho</span>
                            </a>
                            <a href="{{ route('user.switch', 'admin') }}" class="btn btn-outline-danger btn-sm d-flex justify-content-between align-items-center py-2 px-3">
                                <span><i class="bi bi-shield-lock me-2"></i>Đăng nhập nhanh với vai trò <strong>ADMIN TỔNG</strong></span>
                                <span class="badge bg-danger">admin</span>
                            </a>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <span class="text-muted small">Chưa có tài khoản kiểm thử? </span>
                        <a href="{{ route('register') }}" class="small fw-bold text-primary text-decoration-none">
                            Đăng ký vai trò mới
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     CSS STYLES CHO HIỆU ỨNG 3D VÀ GIAO DIỆN SANG TRỌNG
     ========================================== -->
<style>
/* CSS Perspective Container */
.tilt-perspective-container {
    perspective: 1200px;
    perspective-origin: center center;
}

/* 3D Product Card Base */
.product-3d-card {
    background: radial-gradient(circle at 20% 20%, #1e293b 0%, #0f172a 70%, #020617 100%);
    border: 1px solid rgba(255, 255, 255, 0.12);
    box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.5), 0 0 30px rgba(37, 99, 235, 0.12);
    transform-style: preserve-3d;
    transform: rotateX(0deg) rotateY(0deg);
    position: relative;
    overflow: hidden;
    will-change: transform;
    cursor: grab;
    transition: box-shadow 0.4s ease;
}

.product-3d-card:active {
    cursor: grabbing;
}

/* 3D Login Card Base */
.login-3d-card {
    transform-style: preserve-3d;
    transform: rotateX(0deg) rotateY(0deg);
    will-change: transform;
    overflow: hidden;
    position: relative;
    transition: box-shadow 0.3s ease;
}

/* Specular Glare / Highlight Layer (Di chuyển theo vị trí chuột) */
.card-specular-glare {
    position: absolute;
    inset: 0;
    pointer-events: none;
    border-radius: inherit;
    background: radial-gradient(circle 350px at 50% 50%, rgba(255, 255, 255, 0.28) 0%, rgba(255, 255, 255, 0.05) 50%, transparent 80%);
    opacity: 0;
    mix-blend-mode: overlay;
    z-index: 10;
    will-change: opacity, background;
}

/* Layer Parallax Depth Z-axis */
.card-depth-header {
    transform: translateZ(28px);
    transform-style: preserve-3d;
}
.card-depth-product {
    transform: translateZ(55px);
    transform-style: preserve-3d;
}
.card-depth-footer {
    transform: translateZ(24px);
    transform-style: preserve-3d;
}

/* Product Mockup & Holographic Glow */
.product-visual-stage {
    max-width: 420px;
}
.hologram-glow {
    position: absolute;
    width: 280px;
    height: 180px;
    background: radial-gradient(ellipse, rgba(37, 99, 235, 0.45) 0%, rgba(234, 88, 12, 0.25) 50%, transparent 75%);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    filter: blur(40px);
    pointer-events: none;
    z-index: 0;
}
.induction-cooktop {
    background: linear-gradient(135deg, #1e293b 0%, #0b0f19 100%);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6), inset 0 1px 1px rgba(255, 255, 255, 0.2);
    position: relative;
    z-index: 2;
}
.glass-surface {
    background: rgba(15, 23, 42, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.08);
}
.burner-ring {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    border: 2px dashed rgba(249, 115, 22, 0.7);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    position: relative;
    box-shadow: 0 0 25px rgba(234, 88, 12, 0.35);
    transition: all 0.3s ease;
}
.burner-right {
    border-color: rgba(56, 189, 248, 0.7);
    box-shadow: 0 0 25px rgba(56, 189, 248, 0.35);
}
.burner-core {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: radial-gradient(circle, #f97316 0%, #ea580c 70%, transparent 100%);
    opacity: 0.85;
    animation: pulseGlow 2.5s infinite alternate ease-in-out;
}
.burner-right .burner-core {
    background: radial-gradient(circle, #38bdf8 0%, #0284c7 70%, transparent 100%);
}
@keyframes pulseGlow {
    0% { transform: scale(0.9); opacity: 0.6; }
    100% { transform: scale(1.1); opacity: 1; filter: drop-shadow(0 0 8px #f97316); }
}
.burner-label {
    font-size: 0.65rem;
    color: rgba(255, 255, 255, 0.85);
    margin-top: 4px;
    letter-spacing: 0.5px;
}
.control-panel {
    background: rgba(2, 6, 23, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.08);
}
.spec-pill {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(8px);
}
</style>
@endsection

@section('scripts')
<!-- GSAP (GreenSock) CDN cho hiệu ứng 3D Physics Animation siêu mượt -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    /**
     * Hàm khởi tạo 3D Hover Tilt Effect với CSS Perspective, GSAP và Moving Highlight Glare
     * @param {string} cardId - ID phần tử Card cần áp dụng
     * @param {string} glareId - ID phần tử Lớp ánh sáng di động (Specular Glare)
     * @param {number} maxTilt - Độ nghiêng tối đa (độ)
     */
    function setup3DTiltCard(cardId, glareId, maxTilt = 16) {
        const card = document.getElementById(cardId);
        const glare = document.getElementById(glareId);

        if (!card || !glare || typeof gsap === 'undefined') return;

        let bounds = null;

        function updateBounds() {
            bounds = card.getBoundingClientRect();
        }

        // Khi chuột bắt đầu đi vào Card
        card.addEventListener('mouseenter', function() {
            updateBounds();
            gsap.to(glare, {
                opacity: 1,
                duration: 0.3,
                ease: 'power2.out'
            });
        });

        // Khi chuột di chuyển trên bề mặt Card
        card.addEventListener('mousemove', function(e) {
            if (!bounds) updateBounds();

            const mouseX = e.clientX - bounds.left;
            const mouseY = e.clientY - bounds.top;

            // Chuẩn hóa tọa độ từ [-1, 1] với tâm là 0
            const normX = (mouseX / bounds.width - 0.5) * 2;
            const normY = (mouseY / bounds.height - 0.5) * 2;

            // Tính toán góc xoay 3D (đảo chiều trục Y/X để tilt theo hướng chuột tự nhiên)
            const rotateY = normX * maxTilt;
            const rotateX = -normY * maxTilt;

            // GSAP tween card rotation
            gsap.to(card, {
                rotationX: rotateX,
                rotationY: rotateY,
                transformPerspective: 1200,
                duration: 0.25,
                ease: 'power2.out',
                overwrite: 'auto'
            });

            // Tính toán vị trí tâm nguồn sáng Glare theo phần trăm (%)
            const glareX = Math.round((mouseX / bounds.width) * 100);
            const glareY = Math.round((mouseY / bounds.height) * 100);

            // Cập nhật Radial Gradient tâm nguồn sáng trượt theo đầu chuột
            glare.style.background = `radial-gradient(circle 380px at ${glareX}% ${glareY}%, rgba(255, 255, 255, 0.32) 0%, rgba(255, 255, 255, 0.04) 55%, transparent 80%)`;
        });

        // Khi chuột rời khỏi Card: Reset mượt mà về trạng thái ban đầu bằng Elastic / Power3 ease
        card.addEventListener('mouseleave', function() {
            gsap.to(card, {
                rotationX: 0,
                rotationY: 0,
                duration: 0.85,
                ease: 'elastic.out(1, 0.45)',
                overwrite: 'auto'
            });

            gsap.to(glare, {
                opacity: 0,
                duration: 0.5,
                ease: 'power2.out'
            });

            bounds = null;
        });

        // Cập nhật lại kích thước khung khi resize màn hình
        window.addEventListener('resize', function() {
            bounds = null;
        });
    }

    // 1. Kích hoạt hiệu ứng 3D Tilt mạnh mẽ (±15°) cho Card Sản phẩm KÜCHEN Flagship
    setup3DTiltCard('kuchenProductCard', 'productCardGlare', 15);

    // 2. Kích hoạt hiệu ứng 3D Tilt nhẹ nhàng (±6°) cho Card Đăng nhập để trải nghiệm công nghệ đồng bộ
    setup3DTiltCard('kuchenLoginCard', 'loginCardGlare', 6);
});
</script>
@endsection
