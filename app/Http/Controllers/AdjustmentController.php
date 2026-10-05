<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectAdjustmentRequest;
use App\Http\Requests\StoreAdjustmentRequest;
use App\Models\Order;
use App\Models\OrderAdjustment;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\AdjustmentService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AdjustmentController extends Controller
{
    protected AdjustmentService $adjustmentService;

    public function __construct(AdjustmentService $adjustmentService)
    {
        $this->adjustmentService = $adjustmentService;
    }

    /**
     * Danh sách yêu cầu điều chỉnh (Bài 4, Bài 5, Bài 6)
     * Yêu cầu quyền: order.adjustment.view
     */
    public function index(Request $request): View
    {
        Gate::authorize('order.adjustment.view');

        $status = $request->query('status', '');
        $orderId = $request->query('order_id', '');

        $adjustments = OrderAdjustment::query()
            ->with(['order', 'creator', 'reviewer'])
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($orderId !== '', function ($query) use ($orderId) {
                $query->where('order_id', $orderId);
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('adjustments.index', compact('adjustments', 'status', 'orderId'));
    }

    /**
     * Màn hình tạo yêu cầu điều chỉnh (Bài 3, Bài 5)
     * Yêu cầu quyền: order.adjustment.create
     */
    public function create(Request $request): View|RedirectResponse
    {
        Gate::authorize('order.adjustment.create');

        $orderId = $request->query('order_id');
        if (!$orderId) {
            return redirect()->route('orders.index')->with('error', 'Vui lòng chọn đơn hàng cần điều chỉnh.');
        }

        $order = Order::with(['items.productVariant.product', 'pendingAdjustment'])->find($orderId);
        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Đơn hàng không tồn tại.');
        }

        // Kiểm tra điều kiện nghiệp vụ
        if (!$order->canBeAdjusted()) {
            return redirect()->route('orders.index')->with('error', 'Đơn hàng đã xuất kho hoặc đã hủy, không thể yêu cầu điều chỉnh.');
        }

        if ($order->pendingAdjustment) {
            return redirect()->route('orders.index')->with('error', "Đơn hàng đang có yêu cầu {$order->pendingAdjustment->code} đang chờ duyệt.");
        }

        $variants = ProductVariant::with('product')->get();

        return view('adjustments.create', compact('order', 'variants'));
    }

    /**
     * Xử lý lưu yêu cầu điều chỉnh (Bài 3, Bài 5)
     * Yêu cầu quyền: order.adjustment.create
     */
    public function store(StoreAdjustmentRequest $request): RedirectResponse
    {
        Gate::authorize('order.adjustment.create');

        $creatorId = Auth::id();
        if (!$creatorId) {
            abort(401, 'Vui lòng đăng nhập để thực hiện chức năng này.');
        }

        try {
            $adjustment = $this->adjustmentService->createAdjustment(
                (int) $request->validated('order_id'),
                $request->validated(),
                $creatorId
            );

            return redirect()->route('orders.index')
                ->with('success', "Tạo yêu cầu điều chỉnh {$adjustment->code} thành công và đang chờ Quản lý kho phê duyệt!");
        } catch (DomainException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Chi tiết yêu cầu điều chỉnh (Bài 4, Bài 5, Bài 6)
     * Yêu cầu quyền: order.adjustment.view
     */
    public function show(int $id): View|RedirectResponse
    {
        $adjustment = OrderAdjustment::with([
            'order.items.productVariant.product',
            'creator',
            'reviewer',
            'items.orderItem.productVariant.product',
        ])->findOrFail($id);

        Gate::authorize('order.adjustment.view', $adjustment);

        return view('adjustments.show', compact('adjustment'));
    }

    /**
     * Phê duyệt yêu cầu điều chỉnh (Bài 4, Bài 5)
     * Yêu cầu quyền: order.adjustment.approve (Quản lý kho, Admin - SALE BỊ CẤM, Không tự duyệt)
     */
    public function approve(int $id): RedirectResponse
    {
        $adjustment = OrderAdjustment::findOrFail($id);

        Gate::authorize('order.adjustment.approve', $adjustment);

        $reviewerId = Auth::id();
        if (!$reviewerId) {
            abort(401, 'Vui lòng đăng nhập để thực hiện chức năng này.');
        }

        try {
            $adjustment = $this->adjustmentService->approveAdjustment($id, $reviewerId);

            return redirect()->route('adjustments.show', $adjustment->id)
                ->with('success', "Đã phê duyệt yêu cầu {$adjustment->code} thành công! Đơn hàng {$adjustment->order->order_code} đã được cập nhật số lượng mới.");
        } catch (DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Từ chối yêu cầu điều chỉnh (Bài 4, Bài 5)
     * Yêu cầu quyền: order.adjustment.reject (Quản lý kho, Admin - SALE BỊ CẤM)
     */
    public function reject(RejectAdjustmentRequest $request, int $id): RedirectResponse
    {
        $adjustment = OrderAdjustment::findOrFail($id);

        Gate::authorize('order.adjustment.reject', $adjustment);

        $reviewerId = Auth::id();
        if (!$reviewerId) {
            abort(401, 'Vui lòng đăng nhập để thực hiện chức năng này.');
        }

        try {
            $adjustment = $this->adjustmentService->rejectAdjustment(
                $id,
                $request->validated('rejected_reason'),
                $reviewerId
            );

            return redirect()->route('adjustments.show', $adjustment->id)
                ->with('success', "Đã từ chối yêu cầu {$adjustment->code}. Dữ liệu đơn hàng được giữ nguyên.");
        } catch (DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
