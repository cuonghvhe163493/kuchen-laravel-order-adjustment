<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderAdjustment;
use App\Models\OrderAdjustmentItem;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with comprehensive realistic KÜCHEN data.
     */
    public function run(): void
    {
        // 1. Tạo các tài khoản người dùng theo 3 vai trò bắt buộc
        $sale = User::create([
            'name' => 'Nhân viên Sale KÜCHEN',
            'email' => 'sale@kuchen.vn',
            'password' => Hash::make('password'),
            'role' => 'sale',
        ]);

        $saleMinh = User::create([
            'name' => 'Nguyễn Văn Minh (Sale Shopee/TikTok)',
            'email' => 'sale.minh@kuchen.vn',
            'password' => Hash::make('password'),
            'role' => 'sale',
        ]);

        $warehouse = User::create([
            'name' => 'Quản lý kho KÜCHEN',
            'email' => 'kho@kuchen.vn',
            'password' => Hash::make('password'),
            'role' => 'warehouse_manager',
        ]);

        $warehouseNam = User::create([
            'name' => 'Lê Quốc Kho (Kho Miền Nam)',
            'email' => 'kho.nam@kuchen.vn',
            'password' => Hash::make('password'),
            'role' => 'warehouse_manager',
        ]);

        $admin = User::create([
            'name' => 'Quản trị viên Hệ thống',
            'email' => 'admin@kuchen.vn',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Tạo 10 sản phẩm & biến thể chuẩn danh mục thiết bị gia dụng cao cấp KÜCHEN
        $productsData = [
            ['name' => 'Bếp từ đôi KÜCHEN GL-889', 'sku' => 'KC-001', 'price' => 15000000],
            ['name' => 'Nồi chiên không dầu KÜCHEN 12L', 'sku' => 'KC-002', 'price' => 4500000],
            ['name' => 'Máy rửa bát âm tủ KÜCHEN DW-14', 'sku' => 'KC-003', 'price' => 22000000],
            ['name' => 'Robot hút bụi lau nhà Laser KÜCHEN', 'sku' => 'KC-004', 'price' => 12500000],
            ['name' => 'Máy hút mùi kính cong KÜCHEN RH-70', 'sku' => 'KC-005', 'price' => 8900000],
            ['name' => 'Nồi cơm cao tần áp suất KÜCHEN IH-18', 'sku' => 'KC-006', 'price' => 6200000],
            ['name' => 'Máy lọc không khí KÜCHEN AP-45', 'sku' => 'KC-007', 'price' => 9800000],
            ['name' => 'Máy lọc nước RO ion kiềm KÜCHEN Pure', 'sku' => 'KC-008', 'price' => 18500000],
            ['name' => 'Lò nướng đối lưu KÜCHEN OV-60', 'sku' => 'KC-009', 'price' => 14200000],
            ['name' => 'Máy ép chậm KÜCHEN SJ-250', 'sku' => 'KC-010', 'price' => 3800000],
        ];

        $variants = [];
        foreach ($productsData as $data) {
            $product = Product::create(['name' => $data['name']]);
            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $data['sku'],
                'price' => $data['price'],
            ]);
            $variants[$data['sku']] = $variant;
        }

        // 3. Đơn mẫu chuẩn theo đề bài: DH-2026-001 (KC-001: 2 cái, KC-002: 1 cái)
        $order1 = Order::create([
            'order_code' => 'DH-2026-001',
            'channel' => 'sale',
            'status' => 'pending',
            'created_by' => $sale->id,
            'created_at' => now()->subDays(10),
        ]);

        $item1_1 = OrderItem::create([
            'order_id' => $order1->id,
            'product_variant_id' => $variants['KC-001']->id,
            'sku' => 'KC-001',
            'quantity' => 2,
            'price' => $variants['KC-001']->price,
        ]);

        $item1_2 = OrderItem::create([
            'order_id' => $order1->id,
            'product_variant_id' => $variants['KC-002']->id,
            'sku' => 'KC-002',
            'quantity' => 1,
            'price' => $variants['KC-002']->price,
        ]);

        // 4. Tạo thêm 44 đơn hàng đa kênh với các trạng thái thực tế
        $channels = ['sale', 'shopee', 'tiktok', 'lazada', 'retail'];
        $statuses = ['pending', 'confirmed', 'exported', 'cancelled'];
        $skuKeys = array_keys($variants);

        for ($i = 2; $i <= 45; $i++) {
            $code = sprintf('DH-2026-%03d', $i);
            $channel = $channels[($i - 1) % count($channels)];
            
            // Phân bổ trạng thái: 50% pending, 25% confirmed, 15% exported, 10% cancelled
            if ($i % 10 === 0) {
                $status = 'cancelled';
            } elseif ($i % 5 === 0) {
                $status = 'exported';
            } elseif ($i % 3 === 0) {
                $status = 'confirmed';
            } else {
                $status = 'pending';
            }

            $creator = ($i % 2 === 0) ? $sale : $saleMinh;

            $order = Order::create([
                'order_code' => $code,
                'channel' => $channel,
                'status' => $status,
                'created_by' => $creator->id,
                'created_at' => now()->subHours(46 - $i),
            ]);

            // Thêm từ 1 đến 3 dòng hàng cho mỗi đơn
            $itemCount = ($i % 3) + 1;
            $selectedSkus = array_slice($skuKeys, ($i * 2) % count($skuKeys), $itemCount);
            if (empty($selectedSkus)) {
                $selectedSkus = [$skuKeys[0]];
            }

            foreach ($selectedSkus as $sku) {
                $v = $variants[$sku];
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $v->id,
                    'sku' => $v->sku,
                    'quantity' => ($i % 4) + 1,
                    'price' => $v->price,
                ]);
            }
        }

        // 5. Tạo các yêu cầu điều chỉnh mẫu (Pending, Approved, Rejected) để kiểm thử ngay
        // Yêu cầu mẫu 1: Đơn DH-2026-005 đang PENDING chờ kho duyệt
        $order5 = Order::where('order_code', 'DH-2026-005')->first();
        if ($order5 && $order5->items->isNotEmpty()) {
            $firstItem = $order5->items->first();
            $adj1 = OrderAdjustment::create([
                'code' => 'ADJ-000001',
                'order_id' => $order5->id,
                'created_by' => $sale->id,
                'status' => 'pending',
                'reason' => 'Khách hàng gọi hotline báo tăng thêm 1 sản phẩm trước khi chuyển hàng.',
                'created_at' => now()->subHours(5),
            ]);

            OrderAdjustmentItem::create([
                'order_adjustment_id' => $adj1->id,
                'order_item_id' => $firstItem->id,
                'old_sku' => $firstItem->sku,
                'new_sku' => $firstItem->sku,
                'old_quantity' => $firstItem->quantity,
                'new_quantity' => $firstItem->quantity + 1,
            ]);
        }

        // Yêu cầu mẫu 2: Đơn DH-2026-006 đã APPROVED (Duyệt thành công bởi Quản lý kho)
        $order6 = Order::where('order_code', 'DH-2026-006')->first();
        if ($order6 && $order6->items->isNotEmpty()) {
            $item = $order6->items->first();
            $oldQty = $item->quantity;
            $newQty = $oldQty + 2;

            $adj2 = OrderAdjustment::create([
                'code' => 'ADJ-000002',
                'order_id' => $order6->id,
                'created_by' => $saleMinh->id,
                'status' => 'approved',
                'reason' => 'Đổi sang SKU khuyến mãi và nâng số lượng combo bếp từ.',
                'reviewed_by' => $warehouse->id,
                'reviewed_at' => now()->subHours(3),
                'created_at' => now()->subHours(6),
            ]);

            OrderAdjustmentItem::create([
                'order_adjustment_id' => $adj2->id,
                'order_item_id' => $item->id,
                'old_sku' => $item->sku,
                'new_sku' => $item->sku,
                'old_quantity' => $oldQty,
                'new_quantity' => $newQty,
            ]);

            // Cập nhật số lượng mới vào order_items (đúng nghiệp vụ đã duyệt)
            $item->update(['quantity' => $newQty]);
        }

        // Yêu cầu mẫu 3: Đơn DH-2026-007 đã REJECTED (Từ chối bởi Quản lý kho)
        $order7 = Order::where('order_code', 'DH-2026-007')->first();
        if ($order7 && $order7->items->isNotEmpty()) {
            $item = $order7->items->first();
            $adj3 = OrderAdjustment::create([
                'code' => 'ADJ-000003',
                'order_id' => $order7->id,
                'created_by' => $sale->id,
                'status' => 'rejected',
                'reason' => 'Xin đổi sang model máy rửa bát DW-14.',
                'reviewed_by' => $warehouse->id,
                'rejected_reason' => 'Kho tổng hiện tại đã hết suất máy rửa bát DW-14 đợt khuyến mãi, vui lòng tư vấn model khác.',
                'reviewed_at' => now()->subHours(2),
                'created_at' => now()->subHours(4),
            ]);

            OrderAdjustmentItem::create([
                'order_adjustment_id' => $adj3->id,
                'order_item_id' => $item->id,
                'old_sku' => $item->sku,
                'new_sku' => 'KC-003',
                'old_quantity' => $item->quantity,
                'new_quantity' => $item->quantity,
            ]);
        }

        // Yêu cầu mẫu 4: Đơn DH-2026-008 có 2 lần điều chỉnh trong quá khứ (Lịch sử đơn hàng Bài 6)
        $order8 = Order::where('order_code', 'DH-2026-008')->first();
        if ($order8 && $order8->items->isNotEmpty()) {
            $item = $order8->items->first();

            // Lần 1: Bị từ chối
            $adj4_1 = OrderAdjustment::create([
                'code' => 'ADJ-000004',
                'order_id' => $order8->id,
                'created_by' => $sale->id,
                'status' => 'rejected',
                'reason' => 'Khách muốn tăng gấp 5 lần số lượng.',
                'reviewed_by' => $warehouse->id,
                'rejected_reason' => 'Vượt quá hạn mức số lượng tồn sẵn sàng xuất kho trong ngày.',
                'reviewed_at' => now()->subDays(2),
                'created_at' => now()->subDays(3),
            ]);

            OrderAdjustmentItem::create([
                'order_adjustment_id' => $adj4_1->id,
                'order_item_id' => $item->id,
                'old_sku' => $item->sku,
                'new_sku' => $item->sku,
                'old_quantity' => $item->quantity,
                'new_quantity' => $item->quantity * 5,
            ]);

            // Lần 2: Điều chỉnh lại hợp lý và được duyệt
            $adj4_2 = OrderAdjustment::create([
                'code' => 'ADJ-000005',
                'order_id' => $order8->id,
                'created_by' => $sale->id,
                'status' => 'approved',
                'reason' => 'Khách đồng ý nhận trước thêm 1 sản phẩm thay vì tăng 5 sản phẩm.',
                'reviewed_by' => $warehouseNam->id,
                'reviewed_at' => now()->subDays(1),
                'created_at' => now()->subDays(2),
            ]);

            OrderAdjustmentItem::create([
                'order_adjustment_id' => $adj4_2->id,
                'order_item_id' => $item->id,
                'old_sku' => $item->sku,
                'new_sku' => $item->sku,
                'old_quantity' => $item->quantity,
                'new_quantity' => $item->quantity + 1,
            ]);

            $item->update(['quantity' => $item->quantity + 1]);
        }
    }
}
