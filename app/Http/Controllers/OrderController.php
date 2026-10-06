<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderAdjustment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Danh sách và tìm kiếm đơn hàng đa kênh (Bài 2 & Nâng cấp Doanh nghiệp)
     */
    public function index(Request $request): View
    {
        $search = trim($request->query('search', ''));
        $channel = $request->query('channel', '');
        $status = $request->query('status', '');

        $orders = Order::query()
            ->with([
                'items.productVariant.product',
                'creator',
                'adjustments',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where('order_code', 'like', "%{$search}%");
            })
            ->when($channel !== '', function ($query) use ($channel) {
                $query->where('channel', $channel);
            })
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $channels = [
            'sale' => 'Sale Trực Tiếp',
            'shopee' => 'Shopee',
            'tiktok' => 'TikTok Shop',
            'lazada' => 'Lazada',
            'retail' => 'Bán Lẻ',
        ];

        $statuses = [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'exported' => 'Đã xuất kho',
            'cancelled' => 'Đã hủy',
        ];

        return view('orders.index', compact('orders', 'channels', 'statuses', 'search', 'channel', 'status'));
    }
}
