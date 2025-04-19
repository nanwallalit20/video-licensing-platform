<?php

namespace App\Providers;


use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderMeta;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        View::composer('components.navbar.buyer', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                $totalTitles = OrderMeta::whereHas('getOrder', function($query) use ($user) {
                    $query->where('buyer_id', $user->id)
                          ->where('order_status', OrderStatus::Approved);
                })->count();

                $view->with('totalTitles', $totalTitles);
            }
        });
    }
}
