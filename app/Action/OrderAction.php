<?php


namespace App\Action;


use App\Models\User;
use App\Traits\OrderTrait;
use Illuminate\Support\Facades\DB;

class OrderAction
{
    use OrderTrait;

    public function TransferCartToOrder(User $user)
    {
        $main_cart = $user->main_cart;
        $Carts = $main_cart->cart;
        if($Carts){
            foreach ($Carts as $cart){
                DB::transaction(function () use ($cart,$user) {
                    $order = $this->CreateOrder(
                                $user->id,
                                $cart->restaurant_id,
                                $cart->total,
                                $cart->delivery_cost,
                                $cart->tax,
                            );
                    $CartItem = $cart
                        ->items()
                        ->get(['item_id','quantity'])
                        ->toArray();
                    $this->CreateItems($order,$CartItem);
                    $cart->delete();
                });
            }
            $main_cart->delete();
        }else{
            ///// main cart empty
            throw new \Exception('main cart empty ',500);
        }
    }

}
