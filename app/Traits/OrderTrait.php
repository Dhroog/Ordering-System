<?php


namespace App\Traits;


use App\Models\Order;

trait OrderTrait
{
    public function CreateOrder($user_id,$restaurant_id,$total,$delivery_cost,$tax): Order
    {
        $order = new Order();
        $order->user_id = $user_id;
        $order->restaurant_id = $restaurant_id;
        $order->total = $total;
        $order->delivery_cost = $delivery_cost;
        $order->tax = $tax;
        $order->save();
        return $order;
    }

    protected function CreateItems(Order $order,$data)
    {
        $order->items()->createMany($data);
    }

}
