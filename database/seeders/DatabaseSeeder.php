<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tạo 3 tài khoản theo 3 phân quyền bắt buộc
        $sale = User::create([
            'name' => 'Nhân viên Sale KÜCHEN',
            'email' => 'sale@kuchen.vn',
            'password' => Hash::make('password'),
            'role' => 'sale',
        ]);

        $warehouse = User::create([
            'name' => 'Quản lý kho KÜCHEN',
            'email' => 'kho@kuchen.vn',
            'password' => Hash::make('password'),
            'role' => 'warehouse_manager',
        ]);

        $admin = User::create([
            'name' => 'Quản trị viên Hệ thống',
            'email' => 'admin@kuchen.vn',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Tạo danh mục sản phẩm và biến thể (SKU) KÜCHEN
        $p1 = Product::create(['name' => 'Bếp từ đôi KÜCHEN']);
        $v1 = ProductVariant::create(['product_id' => $p1->id, 'sku' => 'KC-001', 'price' => 15000000]);

        $p2 = Product::create(['name' => 'Nồi chiên không dầu KÜCHEN']);
        $v2 = ProductVariant::create(['product_id' => $p2->id, 'sku' => 'KC-002', 'price' => 4500000]);

        $p3 = Product::create(['name' => 'Máy rửa bát âm tủ KÜCHEN']);
        $v3 = ProductVariant::create(['product_id' => $p3->id, 'sku' => 'KC-003', 'price' => 22000000]);

        $p4 = Product::create(['name' => 'Robot hút bụi lau nhà KÜCHEN']);
        $v4 = ProductVariant::create(['product_id' => $p4->id, 'sku' => 'KC-004', 'price' => 12500000]);

        // 3. Tạo các đơn hàng mẫu theo bối cảnh đề bài
        // Đơn 1: Đơn mẫu DH-2026-001 (chuẩn bị để test tạo yêu cầu điều chỉnh ở Bài 3)
        $order1 = Order::create([
            'order_code' => 'DH-2026-001',
            'channel' => 'sale',
            'status' => 'pending',
            'created_by' => $sale->id,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_variant_id' => $v1->id,
            'sku' => 'KC-001',
            'quantity' => 2,
            'price' => $v1->price,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_variant_id' => $v2->id,
            'sku' => 'KC-002',
            'quantity' => 1,
            'price' => $v2->price,
        ]);

        // Đơn 2: Đơn Shopee đang chờ xử lý
        $order2 = Order::create([
            'order_code' => 'DH-2026-002',
            'channel' => 'shopee',
            'status' => 'pending',
            'created_by' => $sale->id,
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_variant_id' => $v3->id,
            'sku' => 'KC-003',
            'quantity' => 1,
            'price' => $v3->price,
        ]);

        // Đơn 3: Đã xuất kho (để test chặn tạo yêu cầu)
        $order3 = Order::create([
            'order_code' => 'DH-2026-003',
            'channel' => 'tiktok',
            'status' => 'exported',
            'created_by' => $sale->id,
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_variant_id' => $v2->id,
            'sku' => 'KC-002',
            'quantity' => 2,
            'price' => $v2->price,
        ]);

        // Đơn 4: Đã hủy (để test chặn tạo yêu cầu)
        $order4 = Order::create([
            'order_code' => 'DH-2026-004',
            'channel' => 'lazada',
            'status' => 'cancelled',
            'created_by' => $sale->id,
        ]);

        OrderItem::create([
            'order_id' => $order4->id,
            'product_variant_id' => $v4->id,
            'sku' => 'KC-004',
            'quantity' => 1,
            'price' => $v4->price,
        ]);
    }
}
