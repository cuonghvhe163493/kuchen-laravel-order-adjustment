<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Hiển thị trang đăng nhập chuyên nghiệp
     */
    public function showLogin(): View
    {
        if (Auth::check()) {
            return redirect()->route('orders.index');
        }

        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập chuẩn doanh nghiệp (Enterprise Authentication)
     * - Rate Limiting chống Brute-Force (Tối đa 5 lần/phút)
     * - Hỗ trợ đăng nhập bằng Email hoặc Mã/Tên người dùng (@kuchen.vn)
     * - Session Regeneration chống Session Fixation
     */
    public function login(Request $request): RedirectResponse
    {
        $input = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Vui lòng nhập tài khoản email hoặc tên nhân viên.',
            'password.required' => 'Vui lòng nhập mật khẩu xác thực.',
        ]);

        // Tạo khóa định danh Throttle theo IP và thông tin đăng nhập
        $throttleKey = Str::transliterate(Str::lower($input['email']) . '|' . $request->ip());

        // Kiểm tra Brute-Force
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Cảnh báo bảo mật: Bạn đã nhập sai quá nhiều lần. Vui lòng đợi {$seconds} giây trước khi thử lại.",
            ])->onlyInput('email');
        }

        // Linh hoạt xử lý: nếu người dùng nhập 'admin' hay 'sale' mà không có đuôi '@'
        $email = trim($input['email']);
        if (!str_contains($email, '@')) {
            $email = $email . '@kuchen.vn';
        }

        $credentials = [
            'email' => $email,
            'password' => $input['password'],
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $user = Auth::user();
            return redirect()->intended(route('orders.index'))
                ->with('success', "Xác thực thành công! Chào mừng đồng chí {$user->name} ({$user->role_display_name}) trở lại hệ thống KÜCHEN Enterprise.");
        }

        // Tăng bộ đếm thử sai (thời gian tính trong 60 giây)
        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Tài khoản hoặc mật khẩu không chính xác trong cơ sở dữ liệu KÜCHEN.',
        ])->onlyInput('email');
    }

    /**
     * Hiển thị trang đăng ký tài khoản nhân sự
     */
    public function showRegister(): View
    {
        if (Auth::check()) {
            return redirect()->route('orders.index');
        }

        return view('auth.register');
    }

    /**
     * Xử lý đăng ký tài khoản nhân sự mới và liên kết RBAC trong DB
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:sale,warehouse_manager,admin'],
        ], [
            'name.required' => 'Họ và tên nhân sự là bắt buộc.',
            'email.required' => 'Email công vụ là bắt buộc.',
            'email.unique' => 'Địa chỉ email này đã được cấp phát trong hệ thống.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu bảo mật phải có tối thiểu 6 ký tự.',
            'password.confirmed' => 'Xác nhận lại mật khẩu không trùng khớp.',
            'role.required' => 'Vui lòng chọn vai trò làm việc.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('orders.index')
            ->with('success', "Đã khởi tạo thành công tài khoản [{$user->name}] với vai trò {$user->role_display_name} và đồng bộ phân quyền RBAC vào Database!");
    }

    /**
     * Xử lý đăng xuất an toàn (Invalidate Session & CSRF Token Regeneration)
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Bạn đã đăng xuất an toàn khỏi KÜCHEN ENTERPRISE PORTAL.');
    }
}
