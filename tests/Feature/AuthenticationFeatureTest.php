<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Đăng nhập tài khoản');
        $response->assertSee('Đăng nhập nhanh với vai trò');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'login_test@kuchen.vn',
            'password' => bcrypt('password123'),
            'role' => 'sale',
        ]);

        $response = $this->post('/login', [
            'email' => 'login_test@kuchen.vn',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/orders');
        $this->assertAuthenticatedAs($user);
    }

    public function test_cannot_login_with_invalid_password(): void
    {
        User::create([
            'name' => 'Test User',
            'email' => 'login_test@kuchen.vn',
            'password' => bcrypt('password123'),
            'role' => 'sale',
        ]);

        $response = $this->post('/login', [
            'email' => 'login_test@kuchen.vn',
            'password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_register_new_account(): void
    {
        $response = $this->post('/register', [
            'name' => 'Nhân Viên Mới',
            'email' => 'newbie@kuchen.vn',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'warehouse_manager',
        ]);

        $response->assertRedirect('/orders');
        $this->assertDatabaseHas('users', [
            'email' => 'newbie@kuchen.vn',
            'role' => 'warehouse_manager',
        ]);
        $this->assertAuthenticated();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::create([
            'name' => 'Logout User',
            'email' => 'logout@kuchen.vn',
            'password' => bcrypt('password123'),
            'role' => 'sale',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_quick_login_switch_user_redirects_to_orders_from_login_page(): void
    {
        $saleUser = User::create([
            'name' => 'Nguyễn Văn A',
            'email' => 'sale_quick@kuchen.vn',
            'password' => bcrypt('password123'),
            'role' => 'sale',
        ]);

        $response = $this->from('/login')->get('/switch-user/sale?redirect=orders');

        $response->assertRedirect('/orders');
        $this->assertAuthenticatedAs($saleUser);
    }
}
