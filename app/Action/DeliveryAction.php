<?php


namespace App\Action;


use App\Models\Restaurant;
use App\Models\User;
use App\Traits\GeomatricTrait;

class DeliveryAction
{
    use GeomatricTrait;
    public function CalculateDeliveryCost(User $user,$restaurant_id)
    {
        $restaurant = Restaurant::findOrFail($restaurant_id);
        //return 100;
         return $this->distance(
                $user->lat,
                $user->lng,
                $restaurant->lat,
                $restaurant->lng,
                'M'
            );
    }


}
