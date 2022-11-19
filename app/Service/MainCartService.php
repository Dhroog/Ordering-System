<?php


namespace App\Service;


use App\Models\Cart;
use App\Models\Main_cart;
use App\Models\User;

class MainCartService
{
    protected User $user;
    protected Main_cart $main_cart;
    protected CartService $cartService;
    protected ItemService $itemService;

    public function __construct(CartService $cartService,ItemService $itemService)
    {
        $this->cartService = $cartService;
        $this->itemService = $itemService;
    }

    public function SetUser(User $user)
    {
        $this->user = $user;
        $this->main_cart =  $this->GetMainCart();
    }

    public function SetMainCart(Main_cart $main_cart)
    {
        $this->main_cart = $main_cart;
    }

    public function GetMainCart()
    {
        return $this->user->main_cart ?? $this->user
                ->main_cart()
                ->create(['total' => 0]);
    }

    public function AddItem($item_id , $quantity)
    {
        $restaurant_id = $this->itemService->SetItem($item_id)->GetRestaurantId();
        $cart = $this->main_cart->cart()->where('restaurant_id',$restaurant_id)->first()//->get()->get(0)
              ?? $this->main_cart->cart()->create(['restaurant_id' => $restaurant_id]);
        $cart_item = $cart->items()->where('item_id',$item_id)->first();

        if($cart_item){
           return $this->cartService->SetCart( $cart)->IncreaseItem($cart_item->id,$quantity,$this->main_cart);
        }else{
           return $this->cartService->SetCart($cart)->AddItem($item_id,$quantity,$this->main_cart);
        }
    }

    public function IncreaseItem($cart_id,$cart_item_id,$quantity)
    {
       return $this->cartService
            ->SetCartByID($cart_id)
            ->IncreaseItem($cart_item_id,$quantity,$this->main_cart);
    }

    public function ReduceItem($cart_id,$cart_item_id,$quantity)
    {
       return $this->cartService
            ->SetCartByID($cart_id)
            ->ReduceItem($cart_item_id,$quantity,$this->main_cart);
    }

    public function DeleteItem($cart_id,$cart_item_id)
    {
        return $this->cartService
            ->SetCartByID($cart_id)
            ->DeleteItem(null,$this->main_cart,$cart_item_id);
    }

}
