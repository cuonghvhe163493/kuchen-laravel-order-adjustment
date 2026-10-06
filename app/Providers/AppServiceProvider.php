<?php

namespace App\Providers;

use App\Models\OrderAdjustment;
use App\Models\User;
use App\Policies\OrderAdjustmentPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Đăng ký Policy cho OrderAdjustment
        Gate::policy(OrderAdjustment::class, OrderAdjustmentPolicy::class);

        // Đăng ký các Permission quản lý động từ Database RBAC
        Gate::define('order.view', function (User $user) {
            return $user->hasPermission('order.view');
        });

        Gate::define('order.adjustment.view', [OrderAdjustmentPolicy::class, 'view']);
        Gate::define('order.adjustment.create', [OrderAdjustmentPolicy::class, 'create']);
        Gate::define('order.adjustment.approve', [OrderAdjustmentPolicy::class, 'approve']);
        Gate::define('order.adjustment.reject', [OrderAdjustmentPolicy::class, 'reject']);

        Gate::define('role.view', function (User $user) {
            return $user->hasPermission('role.view');
        });

        Gate::define('system.manage', function (User $user) {
            return $user->hasPermission('system.manage');
        });

        // View Composer tự động chia sẻ số lượng yêu cầu chờ duyệt cho Header Layout
        view()->composer('layouts.app', function ($view) {
            $pendingCount = 0;
            if (auth()->check()) {
                $pendingCount = OrderAdjustment::where('status', 'pending')->count();
            }
            $view->with('globalPendingAdjustmentsCount', $pendingCount);
        });
    }
}
