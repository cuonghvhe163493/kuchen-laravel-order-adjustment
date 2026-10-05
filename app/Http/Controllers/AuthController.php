<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Hiển thị trang đăng nhập chuyên nghiệp
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập thủ công bằng email & password
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('orders.index'))
                ->with('success', 'Đăng nhập thành công! Chào mừng trở lại, ' . Auth::user()->name);
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác hoặc không tồn tại.',
        ])->onlyInput('email');
    }

    /**
     * Hiển thị trang đăng ký tài khoản
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký tài khoản mới
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:sale,warehouse_manager,admin'],
        ], [
            'name.required' => 'Họ và tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.unique' => 'Email này đã được sử dụng trên hệ thống.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có tối thiểu 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'role.required' => 'Vui lòng chọn vai trò làm việc.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        Auth::login($user);

        return redirect()->route('orders.index')
            ->with('success', "Đăng ký thành công tài khoản [{$user->name}] với vai trò " . strtoupper($user->role) . "!");
    }

    /**
     * Xử lý đăng xuất
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Bạn đã đăng xuất an toàn khỏi KÜCHEN PORTAL.');
    }
}
