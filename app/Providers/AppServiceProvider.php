<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Cart_item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('is_admin', function(User $user) {
            return $user->is_admin == 1;
        });

        Gate::define('RateMyOrder', function(User $user,Order $order) {
            return $user->id === $order->user_id;
        });

        Gate::define('IsMyItem', function(User $user,$cart_id) {
            $user_id =
                Cart::findOrFail($cart_id)
                ->main_cart
                ->user_id;
            return $user->id === $user_id;
        });

    }
}
