@extends('layouts.app')

@section('title', 'Danh sách đơn hàng đa kênh - KÜCHEN PORTAL')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <!-- Header Danh sách Đơn hàng -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1 text-dark">
                    <i class="bi bi-box-seam me-2 text-primary"></i>Danh sách Đơn hàng Đa kênh
                </h4>
                <p class="text-muted small mb-0">Quản lý, điều phối và tra cứu đơn hàng hệ sinh thái KÜCHEN (Shopee, TikTok, Lazada, Bán lẻ)</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-secondary border px-3 py-2 shadow-sm">
                    <i class="bi bi-layers me-1 text-primary"></i> Tổng số: <strong>{{ $orders->total() }}</strong> đơn
                </span>
                @if(($globalPendingAdjustmentsCount ?? 0) > 0)
                    <a href="{{ route('adjustments.index', ['status' => 'pending']) }}" class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 text-decoration-none shadow-sm">
                        <i class="bi bi-clock-history me-1"></i> <strong>{{ $globalPendingAdjustmentsCount }}</strong> phiếu chờ duyệt
                    </a>
                @endif
            </div>
        </div>

        <!-- Filter Quick Tabs theo Trạng thái Đơn -->
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ route('orders.index', array_merge(request()->except('status', 'page'), [])) }}" 
               class="btn btn-sm {{ empty($status) ? 'btn-primary' : 'btn-outline-secondary bg-white' }} px-3 rounded-pill shadow-sm">
                Tất cả đơn
            </a>
            <a href="{{ route('orders.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}" 
               class="btn btn-sm {{ ($status ?? '') === 'pending' ? 'btn-warning text-dark' : 'btn-outline-secondary bg-white' }} px-3 rounded-pill shadow-sm">
                <i class="bi bi-hourglass-split me-1"></i> Chờ xử lý
            </a>
            <a href="{{ route('orders.index', array_merge(request()->except('page'), ['status' => 'confirmed'])) }}" 
               class="btn btn-sm {{ ($status ?? '') === 'confirmed' ? 'btn-info text-dark' : 'btn-outline-secondary bg-white' }} px-3 rounded-pill shadow-sm">
                <i class="bi bi-check2-circle me-1"></i> Đã xác nhận
            </a>
            <a href="{{ route('orders.index', array_merge(request()->except('page'), ['status' => 'exported'])) }}" 
               class="btn btn-sm {{ ($status ?? '') === 'exported' ? 'btn-success text-white' : 'btn-outline-secondary bg-white' }} px-3 rounded-pill shadow-sm">
                <i class="bi bi-truck me-1"></i> Đã xuất kho
            </a>
            <a href="{{ route('orders.index', array_merge(request()->except('page'), ['status' => 'cancelled'])) }}" 
               class="btn btn-sm {{ ($status ?? '') === 'cancelled' ? 'btn-danger text-white' : 'btn-outline-secondary bg-white' }} px-3 rounded-pill shadow-sm">
                <i class="bi bi-x-circle me-1"></i> Đã hủy
            </a>
        </div>

        <!-- Card Bộ lọc & Tìm kiếm -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('orders.index') }}" class="row g-2 align-items-center">
                    <div class="col-12 col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   class="form-control border-start-0" 
                                   placeholder="Tìm kiếm mã đơn hàng (ví dụ: DH-2026-XXX)..." 
                                   value="{{ $search }}">
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <select name="channel" class="form-select">
                            <option value="">-- Tất cả kênh bán hàng --</option>
                            @foreach($channels as $key => $label)
                                <option value="{{ $key }}" {{ $channel === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <select name="status" class="form-select">
                            <option value="">-- Tất cả trạng thái đơn --</option>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ ($status ?? '') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i> Lọc
                        </button>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary" title="Xóa bộ lọc">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bảng danh sách đơn hàng -->
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted border-bottom" style="font-size: 0.76rem; letter-spacing: 0.05em;">
                        <tr>
                            <th class="ps-3" style="width: 50px;">#</th>
                            <th style="width: 160px;">Mã đơn</th>
                            <th style="width: 130px;">Kênh bán</th>
                            <th style="width: 140px;">Trạng thái đơn</th>
                            <th>Chi tiết mặt hàng</th>
                            <th style="width: 150px;" class="text-end">Tổng tiền (VNĐ)</th>
                            <th style="width: 150px;">Thời gian tạo</th>
                            <th class="pe-3 text-end" style="width: 210px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($orders as $index => $order)
                            <tr>
                                <td class="ps-3 text-muted small fw-medium">
                                    {{ $orders->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="fw-bold text-dark font-monospace">{{ $order->order_code }}</div>
                                    @if($order->creator)
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            <i class="bi bi-person me-1"></i>{{ $order->creator->name }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $channelClasses = [
                                            'sale' => 'badge-channel-sale',
                                            'shopee' => 'badge-channel-shopee',
                                            'tiktok' => 'badge-channel-tiktok',
                                            'lazada' => 'badge-channel-lazada',
                                            'retail' => 'badge-channel-retail',
                                        ];
                                        $channelClass = $channelClasses[$order->channel] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $channelClass }} text-uppercase">
                                        {{ $channels[$order->channel] ?? $order->channel }}
                                    </span>
                                </td>
                                <td>
                                    @if($order->status === 'pending')
                                        <span class="badge badge-status-pending d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-clock-history"></i> Chờ xử lý
                                        </span>
                                    @elseif($order->status === 'confirmed')
                                        <span class="badge badge-status-confirmed d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-check-circle"></i> Đã xác nhận
                                        </span>
                                    @elseif($order->status === 'exported')
                                        <span class="badge badge-status-exported d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-box-arrow-right"></i> Đã xuất kho
                                        </span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="badge badge-status-cancelled d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-x-circle"></i> Đã hủy
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0 small">
                                        @foreach($order->items as $item)
                                            <li class="mb-1 d-flex align-items-center gap-2">
                                                <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.72rem;">
                                                    {{ $item->sku }}
                                                </span>
                                                <span class="fw-medium text-dark text-truncate" style="max-width: 250px;" title="{{ $item->productVariant->product->name ?? 'Sản phẩm' }}">
                                                    {{ $item->productVariant->product->name ?? 'Sản phẩm' }}
                                                </span>
                                                <span class="badge bg-slate-100 text-dark fw-bold ms-auto" style="background-color: #f1f5f9;">
                                                    ×{{ $item->quantity }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold text-dark font-monospace">
                                        {{ $order->formatted_total_amount }}
                                    </div>
                                    <div class="text-muted small" style="font-size: 0.72rem;">
                                        {{ $order->items->count() }} sản phẩm
                                    </div>
                                </td>
                                <td class="small text-muted font-monospace" style="font-size: 0.8rem;">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="pe-3 text-end">
                                    @if($order->pendingAdjustment)
                                        {{-- Trường hợp 1: Đơn đang có yêu cầu chờ duyệt --}}
                                        <a href="{{ route('adjustments.show', $order->pendingAdjustment->id) }}" 
                                           class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle text-decoration-none px-2 py-2 d-inline-flex align-items-center gap-1 shadow-sm" 
                                           title="Yêu cầu {{ $order->pendingAdjustment->code }} đang chờ kho xử lý">
                                            <i class="bi bi-clock-history"></i>
                                            Chờ duyệt: <strong>{{ $order->pendingAdjustment->code }}</strong>
                                        </a>
                                    @elseif(!$order->canBeAdjusted())
                                        {{-- Trường hợp 2: Đơn đã xuất kho hoặc đã hủy --}}
                                        <span class="badge bg-light text-muted border px-2 py-2 d-inline-flex align-items-center gap-1"
                                              title="Không thể tạo yêu cầu do đơn đã xuất kho hoặc đã hủy">
                                            <i class="bi bi-lock-fill"></i>
                                            {{ $order->status === 'exported' ? 'Đã xuất kho' : 'Đã hủy' }}
                                        </span>
                                    @else
                                        {{-- Trường hợp 3: Đơn đủ điều kiện điều chỉnh --}}
                                        @can('order.adjustment.create')
                                            <a href="{{ route('adjustments.create', ['order_id' => $order->id]) }}" 
                                               class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 shadow-sm">
                                                <i class="bi bi-pencil-square"></i>
                                                Yêu cầu điều chỉnh
                                            </a>
                                        @else
                                            <span class="badge bg-light text-muted border px-2 py-2" title="Chỉ SALE hoặc Admin mới có quyền tạo yêu cầu">
                                                <i class="bi bi-lock me-1"></i> Chỉ SALE tạo
                                            </span>
                                        @endcan
                                    @endif

                                    @if($order->adjustments->isNotEmpty())
                                        <div class="mt-1">
                                            <a href="{{ route('adjustments.index', ['order_id' => $order->id]) }}" 
                                               class="badge bg-light text-primary border text-decoration-none" 
                                               title="Xem các yêu cầu điều chỉnh của đơn hàng này">
                                                <i class="bi bi-clock-history me-1"></i> Lịch sử ({{ $order->adjustments->count() }})
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                    <p class="mb-0">Không tìm thấy đơn hàng nào phù hợp với điều kiện tìm kiếm.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Phân trang chuẩn 20 đơn/trang -->
            @if($orders->hasPages())
                <div class="card-footer bg-white border-top-0 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 py-3">
                    <div class="small text-muted">
                        Hiển thị từ {{ $orders->firstItem() }} đến {{ $orders->lastItem() }} trên tổng số {{ $orders->total() }} đơn
                    </div>
                    <div>
                        {{ $orders->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
