@extends('layouts.app')

@section('title', 'Đăng ký tài khoản - KÜCHEN PORTAL')

@section('content')
<div class="row justify-content-center align-items-center py-4">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark font-monospace mb-1 tracking-wide">
                <span class="text-primary fw-bolder">KÜCHEN</span> PORTAL
            </h3>
            <p class="text-muted small mb-0">Tạo tài khoản mới để trải nghiệm phân quyền 3 vai trò</p>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4 bg-white">
            <h5 class="fw-bold text-dark mb-1">Đăng ký tài khoản</h5>
            <p class="text-muted small mb-4">Nhập thông tin nhân sự và chỉ định vai trò nghiệp vụ</p>

            @if($errors->any())
                <div class="alert alert-danger py-2 px-3 small border-0 rounded-3 mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold text-secondary">Họ và tên</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" id="name" class="form-control border-start-0" placeholder="Nguyễn Văn A" value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold text-secondary">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control border-start-0" placeholder="user@kuchen.vn" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label small fw-semibold text-secondary">Vai trò nghiệp vụ (*)</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="sale" {{ old('role') === 'sale' ? 'selected' : '' }}>SALE (Tạo yêu cầu điều chỉnh)</option>
                        <option value="warehouse_manager" {{ old('role') === 'warehouse_manager' ? 'selected' : '' }}>QUẢN LÝ KHO (Phê duyệt / Từ chối)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>ADMIN TỔNG (Toàn quyền hệ thống)</option>
                    </select>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <label for="password" class="form-label small fw-semibold text-secondary">Mật khẩu</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                    </div>
                    <div class="col-6">
                        <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Xác nhận</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm rounded-3">
                    <i class="bi bi-check-circle me-1"></i> Tạo tài khoản & Đăng nhập
                </button>
            </form>

            <div class="text-center mt-4">
                <span class="text-muted small">Đã có tài khoản? </span>
                <a href="{{ route('login') }}" class="small fw-bold text-primary text-decoration-none">
                    Quay lại đăng nhập
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
