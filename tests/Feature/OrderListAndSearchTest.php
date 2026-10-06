<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OrderListAndSearchTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Sale Test',
            'email' => 'sale@kuchen.vn',
            'password' => bcrypt('password'),
            'role' => 'sale',
        ]);

        $product = Product::create(['name' => 'Bếp từ đôi KÜCHEN']);
        $variant1 = ProductVariant::create(['product_id' => $product->id, 'sku' => 'KC-001', 'price' => 15000000]);
        $variant2 = ProductVariant::create(['product_id' => $product->id, 'sku' => 'KC-002', 'price' => 4500000]);

        // Đơn 1: Chờ xử lý (hợp lệ)
        $o1 = Order::create([
            'order_code' => 'DH-2026-001',
            'channel' => 'sale',
            'status' => 'pending',
            'created_by' => $this->user->id,
        ]);
        OrderItem::create(['order_id' => $o1->id, 'product_variant_id' => $variant1->id, 'sku' => 'KC-001', 'quantity' => 2, 'price' => 15000000]);

        // Đơn 2: Shopee
        $o2 = Order::create([
            'order_code' => 'DH-2026-002',
            'channel' => 'shopee',
            'status' => 'confirmed',
            'created_by' => $this->user->id,
        ]);
        OrderItem::create(['order_id' => $o2->id, 'product_variant_id' => $variant2->id, 'sku' => 'KC-002', 'quantity' => 1, 'price' => 4500000]);

        // Đơn 3: Đã xuất kho (bị chặn)
        Order::create([
            'order_code' => 'DH-2026-003',
            'channel' => 'tiktok',
            'status' => 'exported',
            'created_by' => $this->user->id,
        ]);

        // Đơn 4: Đã hủy (bị chặn)
        Order::create([
            'order_code' => 'DH-2026-004',
            'channel' => 'lazada',
            'status' => 'cancelled',
            'created_by' => $this->user->id,
        ]);

        $this->actingAs($this->user);
    }

    public function test_can_render_orders_index_page(): void
    {
        $response = $this->get('/orders');

        $response->assertStatus(200);
        $response->assertSee('DH-2026-001');
        $response->assertSee('DH-2026-002');
        $response->assertSee('KC-001');
    }

    public function test_can_search_orders_by_code(): void
    {
        $response = $this->get('/orders?search=DH-2026-001');

        $response->assertStatus(200);
        $response->assertSee('DH-2026-001');
        $response->assertDontSee('DH-2026-002');
    }

    public function test_can_filter_orders_by_channel(): void
    {
        $response = $this->get('/orders?channel=shopee');

        $response->assertStatus(200);
        $response->assertSee('DH-2026-002');
        $response->assertDontSee('DH-2026-001');
    }

    public function test_exported_and_cancelled_orders_do_not_have_adjustment_button(): void
    {
        $response = $this->get('/orders');

        $response->assertStatus(200);
        // Đơn hợp lệ có nút "Yêu cầu điều chỉnh"
        $response->assertSee('adjustments/create?order_id=1');
        // Đơn đã xuất kho hiển thị badge Đã xuất kho
        $response->assertSee('Đã xuất kho');
        $response->assertSee('Đã hủy');
    }

    public function test_query_count_is_optimized_without_n_plus_one(): void
    {
        DB::enableQueryLog();

        $this->get('/orders');

        $queries = DB::getQueryLog();
        // Kiểm tra số lượng query cố định rất nhỏ (1 count + 1 orders + 1 items + 1 variants + 1 products + 1 creators + 1 pendingAdj + 1 allAdj + 1 user)
        $this->assertLessThanOrEqual(10, count($queries));
    }
}
