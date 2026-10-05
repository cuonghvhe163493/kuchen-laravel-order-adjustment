<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\OrderAdjustment;
use App\Models\OrderAdjustmentItem;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderAdjustmentRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order_adjustment_with_multiple_items_and_relationships_work(): void
    {
        // 1. Tạo User (Sale & Kho)
        $sale = User::create([
            'name' => 'Sale Test',
            'email' => 'sale_test@kuchen.vn',
            'password' => bcrypt('password'),
            'role' => 'sale',
        ]);

        $warehouse = User::create([
            'name' => 'Warehouse Test',
            'email' => 'warehouse_test@kuchen.vn',
            'password' => bcrypt('password'),
            'role' => 'warehouse_manager',
        ]);

        // 2. Tạo Product & Variant
        $product = Product::create(['name' => 'Bếp từ Test']);
        $variant1 = ProductVariant::create(['product_id' => $product->id, 'sku' => 'KC-001', 'price' => 1000]);
        $variant2 = ProductVariant::create(['product_id' => $product->id, 'sku' => 'KC-002', 'price' => 2000]);

        // 3. Tạo Order & Items
        $order = Order::create([
            'order_code' => 'DH-TEST-001',
            'channel' => 'sale',
            'status' => 'pending',
            'created_by' => $sale->id,
        ]);

        $item1 = OrderItem::create([
            'order_id' => $order->id,
            'product_variant_id' => $variant1->id,
            'sku' => 'KC-001',
            'quantity' => 2,
            'price' => 1000,
        ]);

        $item2 = OrderItem::create([
            'order_id' => $order->id,
            'product_variant_id' => $variant2->id,
            'sku' => 'KC-002',
            'quantity' => 1,
            'price' => 2000,
        ]);

        // 4. Tạo OrderAdjustment (Bài 1)
        $adjustment = OrderAdjustment::create([
            'code' => 'ADJ-TEST-000001',
            'order_id' => $order->id,
            'created_by' => $sale->id,
            'status' => 'pending',
            'reason' => 'Khách hàng đổi ý muốn tăng số lượng',
        ]);

        // 5. Thêm nhiều dòng điều chỉnh (Lines)
        $adjItem1 = OrderAdjustmentItem::create([
            'order_adjustment_id' => $adjustment->id,
            'order_item_id' => $item1->id,
            'old_sku' => 'KC-001',
            'new_sku' => 'KC-001',
            'old_quantity' => 2,
            'new_quantity' => 3,
        ]);

        $adjItem2 = OrderAdjustmentItem::create([
            'order_adjustment_id' => $adjustment->id,
            'order_item_id' => $item2->id,
            'old_sku' => 'KC-002',
            'new_sku' => 'KC-002',
            'old_quantity' => 1,
            'new_quantity' => 2,
        ]);

        // 6. Kiểm tra các Relationship
        $this->assertCount(2, $adjustment->items);
        $this->assertEquals($order->id, $adjustment->order->id);
        $this->assertEquals($sale->id, $adjustment->creator->id);
        $this->assertTrue($adjustment->isPending());
        $this->assertTrue($order->canBeAdjusted());
        $this->assertTrue($order->hasPendingAdjustment());
        $this->assertEquals('ADJ-TEST-000001', $order->pendingAdjustment->code);

        // Giả lập duyệt
        $adjustment->update([
            'status' => 'approved',
            'reviewed_by' => $warehouse->id,
            'reviewed_at' => now(),
        ]);

        $this->assertTrue($adjustment->fresh()->isApproved());
        $this->assertEquals($warehouse->id, $adjustment->fresh()->reviewer->id);
    }
}
