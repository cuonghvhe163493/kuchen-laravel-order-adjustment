<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDemoUserAuthenticated
{
    /**
     * Đảm bảo luôn có phiên làm việc với tài khoản demo hợp lệ
     * Phục vụ môi trường kiểm thử/vấn đáp đa vai trò của KÜCHEN PORTAL.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            $defaultUser = User::where('role', 'sale')->first() ?? User::first();
            if ($defaultUser) {
                Auth::login($defaultUser);
            }
        }

        return $next($request);
    }
}
