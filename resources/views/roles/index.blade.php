@extends('layouts.app')

@section('title', 'Quản trị Phân quyền Database (RBAC) - KÜCHEN PORTAL')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <!-- Tiêu đề trang & Badge trạng thái -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                        <i class="bi bi-database-check me-1"></i>DATABASE-DRIVEN RBAC
                    </span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                        <i class="bi bi-shield-lock-fill me-1"></i>FOUR-EYES ENFORCED
                    </span>
                </div>
                <h4 class="fw-bold mb-1 text-dark">
                    <i class="bi bi-shield-check me-2 text-primary"></i>Ma trận Phân quyền & Quản trị Vai trò (RBAC)
                </h4>
                <p class="text-muted small mb-0">Toàn bộ vai trò và quyền hạn được lưu trữ, kiểm soát và xác thực trực tiếp từ cơ sở dữ liệu MySQL</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                    <i class="bi bi-arrow-left"></i> Về danh sách đơn
                </a>
            </div>
        </div>

        <!-- Thống kê nhanh 4 Card -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-3 bg-primary-subtle text-primary fs-4">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-medium">Tổng vai trò (Roles)</div>
                            <div class="fs-4 fw-bold text-dark">{{ $roles->count() }} vai trò</div>
                            <div class="small text-muted" style="font-size: 0.75rem;">admin, kho, sale</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-3 bg-success-subtle text-success fs-4">
                            <i class="bi bi-key-fill"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-medium">Tổng quyền hạn (Permissions)</div>
                            <div class="fs-4 fw-bold text-dark">
                                {{ $permissionsByModule->flatten()->count() }} quyền
                            </div>
                            <div class="small text-muted" style="font-size: 0.75rem;">Định nghĩa trong DB</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-3 bg-info-subtle text-info fs-4">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-medium">Nhân sự kích hoạt</div>
                            <div class="fs-4 fw-bold text-dark">{{ $users->count() }} tài khoản</div>
                            <div class="small text-muted" style="font-size: 0.75rem;">Đã liên kết bảng role_user</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-3 bg-warning-subtle text-warning-emphasis fs-4">
                            <i class="bi bi-eye-fill"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-medium">Nguyên tắc Four-Eyes</div>
                            <div class="fs-5 fw-bold text-dark">Kích hoạt 100%</div>
                            <div class="small text-muted" style="font-size: 0.75rem;">Chặn tự phê duyệt chính mình</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BẢNG 1: MA TRẬN PHÂN QUYỀN (ROLE - PERMISSION MATRIX) -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-grid-3x3-gap-fill text-primary"></i>
                    Bảng Ma trận Phân quyền vai trò chi tiết (Role-Permission Matrix)
                </h6>
                <span class="badge bg-light text-secondary border">Truy vấn từ bảng permissions & permission_role</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20%;">Phân hệ (Module)</th>
                            <th style="width: 35%;">Tên Quyền & Mã định danh DB</th>
                            @foreach($roles as $role)
                                <th class="text-center" style="width: 15%;">
                                    <span class="badge {{ $role->name === 'admin' ? 'bg-danger' : ($role->name === 'warehouse_manager' ? 'bg-success' : 'bg-primary') }} text-uppercase px-2 py-1">
                                        {{ $role->name }}
                                    </span>
                                    <div class="small text-muted fw-normal mt-1" style="font-size: 0.72rem;">{{ $role->display_name }}</div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissionsByModule as $moduleName => $modulePermissions)
                            @foreach($modulePermissions as $index => $perm)
                                <tr>
                                    @if($index === 0)
                                        <td rowspan="{{ $modulePermissions->count() }}" class="fw-bold text-dark bg-light bg-opacity-25 border-end">
                                            <i class="bi bi-folder2-open text-primary me-1"></i> {{ $moduleName }}
                                        </td>
                                    @endif
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $perm->display_name }}</div>
                                        <div class="text-muted small font-monospace" style="font-size: 0.75rem;">
                                            <code>{{ $perm->name }}</code> &bull; {{ $perm->description }}
                                        </div>
                                    </td>
                                    @foreach($roles as $role)
                                        @php
                                            $hasPerm = $role->permissions->contains('id', $perm->id);
                                        @endphp
                                        <td class="text-center">
                                            @if($hasPerm)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill" title="Có quyền">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Cho phép
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted border px-2 py-1 rounded-pill" title="Không có quyền">
                                                    <i class="bi bi-dash-circle me-1"></i> Chặn
                                                </span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-light bg-opacity-50 py-3">
                <div class="small text-muted d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle text-primary"></i>
                    <span><strong>Lưu ý nghiệp vụ:</strong> Đối với quyền <code>order.adjustment.approve</code> và <code>order.adjustment.reject</code>, hệ thống áp dụng đồng thời nguyên tắc <em>Four-Eyes Principle (SoD)</em>: Bất kể tài khoản có quyền trong DB, nếu tài khoản đó là người tạo (creator) của chính yêu cầu điều chỉnh thì quyền phê duyệt/từ chối sẽ tự động bị vô hiệu hóa để chống xung đột lợi ích.</span>
                </div>
            </div>
        </div>

        <!-- BẢNG 2: DANH SÁCH NHÂN SỰ VÀ VAI TRÒ ĐƯỢC GÁN TRONG DATABASE -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-people text-primary"></i>
                    Danh sách Tài khoản Nhân sự & Vai trò trong Database
                </h6>
                <span class="badge bg-light text-secondary border">Bảng users liên kết role_user</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 8%;">ID</th>
                            <th style="width: 25%;">Họ và tên nhân sự</th>
                            <th style="width: 20%;">Email công vụ</th>
                            <th style="width: 18%;">Vai trò trong DB</th>
                            <th style="width: 18%;">Quyền hạn sở hữu</th>
                            <th style="width: 14%;" class="text-end">Thao tác thử nghiệm</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td class="font-monospace text-muted">#{{ $u->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $u->name }}</div>
                                            @if(auth()->id() === $u->id)
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.68rem;">Đang đăng nhập</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="font-monospace text-secondary small">{{ $u->email }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $u->role === 'admin' ? 'bg-danger' : ($u->role === 'warehouse_manager' ? 'bg-success' : 'bg-primary') }} text-uppercase">
                                        {{ $u->role }}
                                    </span>
                                    <span class="text-muted small ms-1">({{ $u->role_display_name }})</span>
                                </td>
                                <td>
                                    @php
                                        $userPermCount = $u->allPermissions()->count();
                                    @endphp
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-shield-check text-success me-1"></i>{{ $userPermCount }} quyền trong DB
                                    </span>
                                </td>
                                <td class="text-end">
                                    @if(auth()->id() !== $u->id)
                                        <a href="{{ route('user.switch', ['role' => $u->role, 'redirect' => 'roles']) }}" 
                                           class="btn btn-sm btn-outline-primary py-1 px-2 shadow-none" 
                                           style="font-size: 0.72rem;"
                                           title="Chuyển sang tài khoản với vai trò {{ $u->role }}">
                                            <i class="bi bi-arrow-repeat me-1"></i>Đóng vai {{ strtoupper($u->role) }}
                                        </a>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.72rem;">
                                            <i class="bi bi-person-check me-1"></i>Hiện tại
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
