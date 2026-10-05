@extends('layouts.app')

@section('title', 'Danh sách đơn hàng - KÜCHEN PORTAL')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-1 text-dark">
                    <i class="bi bi-box-seam me-2 text-primary"></i>Danh sách Đơn hàng
                </h4>
                <p class="text-muted small mb-0">Quản lý và tra cứu đơn hàng đa kênh hệ sinh thái KÜCHEN</p>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-secondary border px-3 py-2">
                    <i class="bi bi-layers me-1"></i> Tổng số: <strong>{{ $orders->total() }}</strong> đơn
                </span>
            </div>
        </div>

        <!-- Card Bộ lọc & Tìm kiếm -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('orders.index') }}" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   class="form-control border-start-0" 
                                   placeholder="Nhập mã đơn hàng..." 
                                   value="{{ $search }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select name="channel" class="form-select">
                            <option value="">-- Tất cả kênh bán --</option>
                            @foreach($channels as $key => $label)
                                <option value="{{ $key }}" {{ $channel === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-3">
                            <i class="bi bi-funnel me-1"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Đặt lại
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bảng danh sách đơn hàng -->
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase small text-muted">
                        <tr>
                            <th class="ps-3" style="width: 50px;">STT</th>
                            <th style="width: 150px;">Mã đơn</th>
                            <th style="width: 140px;">Kênh bán</th>
                            <th style="width: 150px;">Trạng thái đơn</th>
                            <th>Sản phẩm / SKU / Số lượng</th>
                            <th style="width: 180px;">Thời gian tạo</th>
                            <th class="pe-3 text-end" style="width: 220px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $index => $order)
                            <tr>
                                <td class="ps-3 text-muted small">
                                    {{ $orders->firstItem() + $index }}
                                </td>
                                <td>
                                    <span class="fw-bold text-primary">{{ $order->order_code }}</span>
                                    @if($order->creator)
                                        <div class="small text-muted" style="font-size: 0.78rem;">
                                            Bởi: {{ $order->creator->name }}
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
                                    <span class="badge {{ $channelClass }} px-2 py-1">
                                        {{ $channels[$order->channel] ?? strtoupper($order->channel) }}
                                    </span>
                                </td>
                                <td>
                                    @if($order->status === 'pending')
                                        <span class="badge badge-status-pending px-2 py-1">
                                            <i class="bi bi-hourglass-split me-1"></i>Chờ xử lý
                                        </span>
                                    @elseif($order->status === 'confirmed')
                                        <span class="badge badge-status-confirmed px-2 py-1">
                                            <i class="bi bi-check-circle me-1"></i>Đã xác nhận
                                        </span>
                                    @elseif($order->status === 'exported')
                                        <span class="badge badge-status-exported px-2 py-1">
                                            <i class="bi bi-box-arrow-right me-1"></i>Đã xuất kho
                                        </span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="badge badge-status-cancelled px-2 py-1">
                                            <i class="bi bi-x-circle me-1"></i>Đã hủy
                                        </span>
                                    @else
                                        <span class="badge bg-secondary px-2 py-1">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0 small">
                                        @foreach($order->items as $item)
                                            <li class="mb-1 d-flex align-items-center gap-1">
                                                <i class="bi bi-dot text-primary fs-5"></i>
                                                <span class="fw-medium">
                                                    {{ $item->productVariant->product->name ?? 'Sản phẩm' }}
                                                </span>
                                                <span class="badge bg-light text-dark border px-1" style="font-size: 0.75rem;">
                                                    SKU: {{ $item->sku }}
                                                </span>
                                                <span class="fw-bold text-danger ms-1">
                                                    × {{ $item->quantity }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="small text-muted">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="pe-3 text-end">
                                    @if($order->pendingAdjustment)
                                        {{-- Trường hợp 1: Đơn đang có yêu cầu chờ duyệt --}}
                                        <span class="badge bg-warning text-dark border border-warning px-2 py-2 d-inline-flex align-items-center gap-1" 
                                              title="Yêu cầu {{ $order->pendingAdjustment->code }} đang chờ kho xử lý">
                                            <i class="bi bi-clock-history"></i>
                                            Đang chờ duyệt: <strong>{{ $order->pendingAdjustment->code }}</strong>
                                        </span>
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
                                               class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
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
                                <td colspan="7" class="text-center py-5 text-muted">
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
                <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center py-3">
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
