@extends('layouts.app')

@section('title', 'Đăng ký tài khoản nhân sự - KÜCHEN ENTERPRISE')

@section('content')
<div class="container py-3 py-lg-4 position-relative z-2">
    <!-- Header Thương Hiệu KÜCHEN Chữ Thuần Túy -->
    <div class="text-center mb-4">
        <h3 class="fw-bold text-dark font-monospace mb-1 tracking-wide">
            <span class="text-primary fw-bolder">KÜCHEN</span> PORTAL
        </h3>
        <p class="text-muted small mb-0">Hệ sinh thái điều phối đơn hàng & kiểm soát kho vận công nghệ Đức</p>
    </div>

    <div class="row g-4 align-items-stretch justify-content-center">
        <!-- ========================================================
             CỘT 1: CARD GIỚI THIỆU PHÂN QUYỀN RBAC & SIÊU XE ĐỨC 3D
             ======================================================== -->
        <div class="col-12 col-xl-6 col-lg-6">
            <div class="tilt-perspective-container h-100">
                <div class="car-3d-card h-100 d-flex flex-column justify-content-between p-4 p-md-5 rounded-4" id="registerHeroCard">
                    <!-- Lớp Phản Chiếu Ánh Sáng Specular Glare -->
                    <div class="card-specular-glare" id="registerHeroGlare"></div>

                    <!-- Header Xe Đức (Depth Z: 30px) -->
                    <div class="card-depth-header position-relative z-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-1">
                                <i class="bi bi-shield-fill-check text-danger"></i> GERMAN PRECISION
                            </span>
                            <span class="badge bg-dark bg-opacity-75 text-info px-3 py-2 rounded-pill font-monospace border border-info border-opacity-25">
                                <i class="bi bi-cpu me-1"></i> RBAC ENTERPRISE v1.2
                            </span>
                        </div>
                    </div>

                    <!-- Nội dung Công Nghệ & Vai Trò (Depth Z: 50px) -->
                    <div class="position-relative z-3 my-auto py-3">
                        <h4 class="fw-extrabold text-white mb-2 tracking-tight">GIA NHẬP ĐỘI NGŨ KÜCHEN</h4>
                        <p class="text-white-50 small mb-4">Mỗi nhân sự được cấp quyền tự động thông qua hệ thống Role-Based Access Control trong Database MySQL.</p>

                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                                <div class="p-2 rounded bg-primary text-white fs-5">
                                    <i class="bi bi-person-workspace"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white small">Nhân viên SALE (Kinh doanh)</div>
                                    <div class="text-white-50 small" style="font-size: 0.75rem;">Theo dõi đơn hàng đa kênh, gửi yêu cầu điều chỉnh phân loại hoặc số lượng.</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                                <div class="p-2 rounded bg-success text-white fs-5">
                                    <i class="bi bi-boxes"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white small">Quản lý KHO (Warehouse Manager)</div>
                                    <div class="text-white-50 small" style="font-size: 0.75rem;">Phê duyệt điều chỉnh, xuất kho, áp dụng nguyên tắc Four-Eyes kiểm soát chéo.</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                                <div class="p-2 rounded bg-danger text-white fs-5">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white small">Quản trị viên ADMIN TỔNG</div>
                                    <div class="text-white-50 small" style="font-size: 0.75rem;">Toàn quyền quản trị tham số, ma trận phân quyền và cấu hình vận hành.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer (Depth Z: 25px) -->
                    <div class="card-depth-footer position-relative z-3 mt-3 pt-3 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center">
                        <span class="text-white-50 small font-monospace" style="font-size: 0.72rem;">MÃ HÓA BẬC CAO: BCRYPT 12 ROUNDS</span>
                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-2 py-1 small">
                            <i class="bi bi-check-circle me-1"></i>DATABASE SYNC READY
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================
             CỘT 2: FORM ĐĂNG KÝ TÀI KHOẢN SẠCH SẼ, CHUYÊN NGHIỆP
             ======================================================== -->
        <div class="col-12 col-xl-5 col-lg-6">
            <div class="tilt-perspective-container h-100">
                <div class="login-3d-clean-card h-100 card border-0 rounded-4 p-4 p-md-5 d-flex flex-column justify-content-between position-relative shadow-sm" id="registerFormCard">
                    <!-- Specular Glare -->
                    <div class="card-specular-glare" id="registerFormGlare"></div>

                    <div class="position-relative z-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold text-dark mb-0">Đăng ký tài khoản</h5>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 small">
                                <i class="bi bi-person-plus me-1"></i>Tạo mới
                            </span>
                        </div>
                        <p class="text-muted small mb-4">Khởi tạo tài khoản nhân sự và tự động kích hoạt quyền trong Database</p>

                        @if($errors->any())
                            <div class="alert alert-danger py-2 px-3 small border-0 rounded-3 mb-3 shadow-sm">
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.post') }}" id="registerForm">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label small fw-semibold text-secondary">Họ và tên nhân sự (*)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" id="name" class="form-control border-start-0" placeholder="Ví dụ: Nguyễn Văn Hoàng" value="{{ old('name') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label small fw-semibold text-secondary">Email công vụ (*)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" id="email" class="form-control border-start-0" placeholder="hoang.nv@kuchen.vn" value="{{ old('email') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label small fw-semibold text-secondary">Vai trò làm việc (*)</label>
                                <select name="role" id="role" class="form-select" required>
                                    <option value="sale" {{ old('role') === 'sale' ? 'selected' : '' }}>SALE — Nhân viên kinh doanh (Tạo điều chỉnh đơn)</option>
                                    <option value="warehouse_manager" {{ old('role') === 'warehouse_manager' ? 'selected' : '' }}>QUẢN LÝ KHO — Xét duyệt / Từ chối điều chỉnh</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>ADMIN TỔNG — Quản trị viên toàn hệ thống</option>
                                </select>
                            </div>

                            <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <label for="regPassword" class="form-label small fw-semibold text-secondary">Mật khẩu (*)</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="regPassword" class="form-control border-end-0" placeholder="Tối thiểu 6 ký tự" minlength="6" required>
                                        <button class="btn btn-outline-secondary border text-muted" type="button" id="toggleRegPassBtn">
                                            <i class="bi bi-eye" id="toggleRegPassIcon"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Xác nhận (*)</label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" id="regPasswordConfirm" class="form-control border-end-0" placeholder="Nhập lại mật khẩu" required>
                                        <button class="btn btn-outline-secondary border text-muted" type="button" id="toggleConfirmPassBtn">
                                            <i class="bi bi-eye" id="toggleConfirmPassIcon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm rounded-3 d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-check-circle"></i>
                                <span>Khởi tạo tài khoản & Đăng nhập</span>
                            </button>
                        </form>
                    </div>

                    <div class="text-center mt-4 position-relative z-3">
                        <span class="text-muted small">Đã có tài khoản nhân sự? </span>
                        <a href="{{ route('login') }}" class="small fw-bold text-primary text-decoration-none">
                            Quay lại trang Đăng nhập
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* 3D Tilt perspective containers */
.tilt-perspective-container {
    perspective: 1200px;
}
.car-3d-card {
    background: radial-gradient(circle at 20% 20%, #1e293b 0%, #0f172a 75%, #020617 100%);
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.4), 0 0 30px rgba(37, 99, 235, 0.15);
    position: relative;
    overflow: hidden;
}
.login-3d-clean-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.08);
    position: relative;
    overflow: hidden;
}
.card-specular-glare {
    position: absolute;
    inset: 0;
    pointer-events: none;
    border-radius: inherit;
    z-index: 2;
    opacity: 0;
    transition: opacity 0.3s ease;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupPassToggle(btnId, inputId, iconId) {
        const btn = document.getElementById(btnId);
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (btn && input && icon) {
            btn.addEventListener('click', function() {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                }
            });
        }
    }
    setupPassToggle('toggleRegPassBtn', 'regPassword', 'toggleRegPassIcon');
    setupPassToggle('toggleConfirmPassBtn', 'regPasswordConfirm', 'toggleConfirmPassIcon');
});
</script>
@endsection
