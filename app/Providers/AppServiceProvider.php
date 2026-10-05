<?php

namespace App\Providers;

use App\Models\OrderAdjustment;
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

        // Đăng ký các Permission bắt buộc theo đề bài (Bài 5)
        Gate::define('order.adjustment.view', [OrderAdjustmentPolicy::class, 'view']);
        Gate::define('order.adjustment.create', [OrderAdjustmentPolicy::class, 'create']);
        Gate::define('order.adjustment.approve', [OrderAdjustmentPolicy::class, 'approve']);
        Gate::define('order.adjustment.reject', [OrderAdjustmentPolicy::class, 'reject']);
    }
}
