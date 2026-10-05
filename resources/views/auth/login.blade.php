@extends('layouts.app')

@section('title', 'Đăng nhập - KÜCHEN PORTAL')

@section('content')
<div class="row justify-content-center align-items-center py-4">
    <div class="col-12 col-md-8 col-lg-5">
        <!-- Logo & Brand Header -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center gap-2 mb-2">
                <span class="brand-badge fs-6 px-3 py-1">KÜCHEN</span>
                <span class="fw-bold fs-4 text-dark font-monospace">ENTERPRISE</span>
            </div>
            <p class="text-muted small mb-0">Hệ sinh thái điều phối đơn hàng & kiểm soát kho vận KÜCHEN</p>
        </div>

        <!-- Card Đăng nhập chính -->
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4 bg-white">
            <h5 class="fw-bold text-dark mb-1">Đăng nhập tài khoản</h5>
            <p class="text-muted small mb-4">Vui lòng đăng nhập với vai trò để truy cập cổng nghiệp vụ</p>

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
                    <span><i class="bi bi-boxes me-2"></i>Đăng nhập nhanh với <strong>QUẢN LÝ KHO</strong></span>
                    <span class="badge bg-success">kho</span>
                </a>
                <a href="{{ route('user.switch', 'admin') }}" class="btn btn-outline-danger btn-sm d-flex justify-content-between align-items-center py-2 px-3">
                    <span><i class="bi bi-shield-lock me-2"></i>Đăng nhập nhanh với <strong>ADMIN TỔNG</strong></span>
                    <span class="badge bg-danger">admin</span>
                </a>
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
@endsection
