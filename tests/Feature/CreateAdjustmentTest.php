<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderAdjustment;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateAdjustmentTest extends TestCase
{
    use RefreshDatabase;

    private User $saleUser;
    private Order $validOrder;
    private OrderItem $item1;
    private OrderItem $item2;
    private ProductVariant $variant1;
    private ProductVariant $variant2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->saleUser = User::create([
            'name' => 'Sale Kuchen',
            'email' => 'sale@kuchen.vn',
            'password' => bcrypt('password'),
            'role' => 'sale',
        ]);

        $p1 = Product::create(['name' => 'Bếp từ đôi KÜCHEN']);
        $this->variant1 = ProductVariant::create(['product_id' => $p1->id, 'sku' => 'KC-001', 'price' => 15000000]);

        $p2 = Product::create(['name' => 'Nồi chiên không dầu KÜCHEN']);
        $this->variant2 = ProductVariant::create(['product_id' => $p2->id, 'sku' => 'KC-002', 'price' => 4500000]);

        // Đơn DH-2026-001: KC-001 số lượng 2; KC-002 số lượng 1
        $this->validOrder = Order::create([
            'order_code' => 'DH-2026-001',
            'channel' => 'sale',
            'status' => 'pending',
            'created_by' => $this->saleUser->id,
        ]);

        $this->item1 = OrderItem::create([
            'order_id' => $this->validOrder->id,
            'product_variant_id' => $this->variant1->id,
            'sku' => 'KC-001',
            'quantity' => 2,
            'price' => 15000000,
        ]);

        $this->item2 = OrderItem::create([
            'order_id' => $this->validOrder->id,
            'product_variant_id' => $this->variant2->id,
            'sku' => 'KC-002',
            'quantity' => 1,
            'price' => 4500000,
        ]);
    }

    /**
     * Tiêu chí 1: SALE tạo yêu cầu hợp lệ; đơn gốc chưa thay đổi
     */
    public function test_sale_can_create_valid_adjustment_and_original_order_items_remain_unchanged(): void
    {
        $this->actingAs($this->saleUser);

        // Đề bài: SKU KC-001 từ 2 thành 3; SKU KC-002 từ 1 thành 2
        $payload = [
            'order_id' => $this->validOrder->id,
            'reason' => 'Khách hàng liên hệ xin tăng số lượng sản phẩm',
            'items' => [
                [
                    'order_item_id' => $this->item1->id,
                    'new_sku' => 'KC-001',
                    'new_quantity' => 3,
                ],
                [
                    'order_item_id' => $this->item2->id,
                    'new_sku' => 'KC-002',
                    'new_quantity' => 2,
                ],
            ],
        ];

        $response = $this->post('/adjustments', $payload);

        $response->assertRedirect('/orders');
        $response->assertSessionHas('success');

        // Kiểm tra bản ghi yêu cầu đã được tạo
        $adjustment = OrderAdjustment::where('order_id', $this->validOrder->id)->first();
        $this->assertNotNull($adjustment);
        $this->assertEquals('pending', $adjustment->status);
        $this->assertEquals('ADJ-000001', $adjustment->code);
        $this->assertCount(2, $adjustment->items);

        // Kiểm tra chi tiết dòng điều chỉnh lưu đúng giá trị cũ và mới
        $adjItem1 = $adjustment->items()->where('order_item_id', $this->item1->id)->first();
        $this->assertEquals(2, $adjItem1->old_quantity);
        $this->assertEquals(3, $adjItem1->new_quantity);

        $adjItem2 = $adjustment->items()->where('order_item_id', $this->item2->id)->first();
        $this->assertEquals(1, $adjItem2->old_quantity);
        $this->assertEquals(2, $adjItem2->new_quantity);

        // TUYỆT ĐỐI QUAN TRỌNG: Đơn hàng gốc order_items KHÔNG BỊ THAY ĐỔI
        $this->assertEquals(2, $this->item1->fresh()->quantity);
        $this->assertEquals(1, $this->item2->fresh()->quantity);
    }

    /**
     * Tiêu chí 2: Đơn đã xuất kho không được tạo yêu cầu
     */
    public function test_exported_order_cannot_create_adjustment(): void
    {
        $this->actingAs($this->saleUser);

        $exportedOrder = Order::create([
            'order_code' => 'DH-EXPORTED',
            'channel' => 'shopee',
            'status' => 'exported',
            'created_by' => $this->saleUser->id,
        ]);

        $item = OrderItem::create([
            'order_id' => $exportedOrder->id,
            'product_variant_id' => $this->variant1->id,
            'sku' => 'KC-001',
            'quantity' => 1,
            'price' => 1000,
        ]);

        $payload = [
            'order_id' => $exportedOrder->id,
            'reason' => 'Xin sửa đơn đã xuất',
            'items' => [
                [
                    'order_item_id' => $item->id,
                    'new_sku' => 'KC-001',
                    'new_quantity' => 2,
                ],
            ],
        ];

        $response = $this->post('/adjustments', $payload);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('order_adjustments', ['order_id' => $exportedOrder->id]);
    }

    /**
     * Tiêu chí 3: Không cho phép có đồng thời hai yêu cầu pending cho cùng đơn
     */
    public function test_cannot_have_two_pending_adjustments_for_the_same_order(): void
    {
        $this->actingAs($this->saleUser);

        // Tạo sẵn 1 yêu cầu pending
        OrderAdjustment::create([
            'code' => 'ADJ-EXISTING',
            'order_id' => $this->validOrder->id,
            'created_by' => $this->saleUser->id,
            'status' => 'pending',
            'reason' => 'Yêu cầu 1 đang chờ xử lý',
        ]);

        $payload = [
            'order_id' => $this->validOrder->id,
            'reason' => 'Cố tình gửi thêm yêu cầu thứ 2',
            'items' => [
                [
                    'order_item_id' => $this->item1->id,
                    'new_sku' => 'KC-001',
                    'new_quantity' => 5,
                ],
            ],
        ];

        $response = $this->post('/adjustments', $payload);

        $response->assertSessionHas('error');
        // Vẫn chỉ có 1 yêu cầu duy nhất
        $this->assertEquals(1, OrderAdjustment::where('order_id', $this->validOrder->id)->count());
    }

    /**
     * Tiêu chí 4: Validation - Bắt buộc lý do, SKU phải tồn tại, số lượng phải nguyên dương
     */
    public function test_validation_rules_for_adjustment_creation(): void
    {
        $this->actingAs($this->saleUser);

        // 1. Lý do trống
        $response1 = $this->post('/adjustments', [
            'order_id' => $this->validOrder->id,
            'reason' => '',
            'items' => [
                ['order_item_id' => $this->item1->id, 'new_sku' => 'KC-001', 'new_quantity' => 2],
            ],
        ]);
        $response1->assertSessionHasErrors('reason');

        // 2. SKU không tồn tại
        $response2 = $this->post('/adjustments', [
            'order_id' => $this->validOrder->id,
            'reason' => 'Lý do hợp lệ',
            'items' => [
                ['order_item_id' => $this->item1->id, 'new_sku' => 'INVALID-SKU-999', 'new_quantity' => 2],
            ],
        ]);
        $response2->assertSessionHasErrors('items.0.new_sku');

        // 3. Số lượng bằng 0 hoặc âm
        $response3 = $this->post('/adjustments', [
            'order_id' => $this->validOrder->id,
            'reason' => 'Lý do hợp lệ',
            'items' => [
                ['order_item_id' => $this->item1->id, 'new_sku' => 'KC-001', 'new_quantity' => 0],
            ],
        ]);
        $response3->assertSessionHasErrors('items.0.new_quantity');
    }
}
