@extends('layouts.app')

@section('title', 'Danh sách yêu cầu điều chỉnh - KÜCHEN PORTAL')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1 text-dark">
                    <i class="bi bi-card-checklist me-2 text-primary"></i>Danh Sách Yêu Cầu Điều Chỉnh
                </h4>
                <p class="text-muted small mb-0">Quản lý và xét duyệt các yêu cầu điều chỉnh đơn hàng trước khi xuất kho</p>
            </div>
            <div>
                <span class="badge bg-light text-secondary border px-3 py-2">
                    Tổng số: <strong>{{ $adjustments->total() }}</strong> yêu cầu
                </span>
            </div>
        </div>

        <!-- Bộ lọc trạng thái & đơn hàng -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body p-3 d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3">
                <div class="d-flex align-items-center">
                    <div class="btn-group w-100 flex-wrap" role="group">
                        <a href="{{ route('adjustments.index', array_filter(['order_id' => $orderId])) }}" 
                           class="btn btn-sm {{ $status === '' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Tất cả
                        </a>
                        <a href="{{ route('adjustments.index', array_filter(['order_id' => $orderId, 'status' => 'pending'])) }}" 
                           class="btn btn-sm {{ $status === 'pending' ? 'btn-warning text-dark' : 'btn-outline-secondary' }}">
                            Chờ duyệt
                        </a>
                        <a href="{{ route('adjustments.index', array_filter(['order_id' => $orderId, 'status' => 'approved'])) }}" 
                           class="btn btn-sm {{ $status === 'approved' ? 'btn-success' : 'btn-outline-secondary' }}">
                            Đã duyệt
                        </a>
                        <a href="{{ route('adjustments.index', array_filter(['order_id' => $orderId, 'status' => 'rejected'])) }}" 
                           class="btn btn-sm {{ $status === 'rejected' ? 'btn-danger' : 'btn-outline-secondary' }}">
                            Đã từ chối
                        </a>
                    </div>
                </div>

                @if($orderId)
                    <div class="d-flex align-items-center justify-content-between justify-content-md-end gap-2 flex-wrap">
                        <span class="badge bg-info-subtle text-info border border-info px-3 py-2">
                            <i class="bi bi-funnel-fill me-1"></i> Đang lọc đơn #<strong>{{ $orderId }}</strong>
                        </span>
                        <a href="{{ route('adjustments.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Xem tất cả đơn
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bảng danh sách yêu cầu -->
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase small text-muted">
                        <tr>
                            <th class="ps-3" style="width: 50px;">#</th>
                            <th style="width: 150px;">Mã yêu cầu</th>
                            <th style="width: 150px;">Mã đơn hàng</th>
                            <th style="width: 140px;">Người gửi</th>
                            <th>Lý do điều chỉnh</th>
                            <th style="width: 140px;">Trạng thái</th>
                            <th style="width: 170px;">Thời gian gửi</th>
                            <th class="pe-3 text-end" style="width: 140px;">Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adjustments as $index => $adj)
                            <tr>
                                <td class="ps-3 text-muted small">
                                    {{ $adjustments->firstItem() + $index }}
                                </td>
                                <td>
                                    <a href="{{ route('adjustments.show', $adj->id) }}" class="fw-bold text-decoration-none">
                                        {{ $adj->code }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $adj->order->order_code ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="small">{{ $adj->creator->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="text-truncate d-inline-block small" style="max-width: 320px;">
                                        {{ $adj->reason }}
                                    </span>
                                </td>
                                <td>
                                    @if($adj->status === 'pending')
                                        <span class="badge bg-warning text-dark border border-warning px-2 py-1">
                                            <i class="bi bi-clock-history me-1"></i>Chờ duyệt
                                        </span>
                                    @elseif($adj->status === 'approved')
                                        <span class="badge bg-success px-2 py-1">
                                            <i class="bi bi-check-circle me-1"></i>Đã duyệt
                                        </span>
                                    @elseif($adj->status === 'rejected')
                                        <span class="badge bg-danger px-2 py-1">
                                            <i class="bi bi-x-circle me-1"></i>Từ chối
                                        </span>
                                    @endif
                                </td>
                                <td class="small text-muted">
                                    {{ $adj->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="pe-3 text-end">
                                    <a href="{{ route('adjustments.show', $adj->id) }}" class="btn btn-sm btn-outline-primary">
                                        Xem & Xử lý
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                    <p class="mb-0">Chưa có yêu cầu điều chỉnh nào trong danh mục này.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($adjustments->hasPages())
                <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center py-3">
                    <div class="small text-muted">
                        Hiển thị {{ $adjustments->firstItem() }} - {{ $adjustments->lastItem() }} trên {{ $adjustments->total() }} yêu cầu
                    </div>
                    <div>
                        {{ $adjustments->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
