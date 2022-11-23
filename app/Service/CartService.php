<?php


namespace App\Service;


use App\Action\DeliveryAction;
use App\Models\Cart;
use App\Models\Cart_item;
use App\Models\Main_cart;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CartService
{
    protected User $user;
    protected Cart $cart;
    protected ItemService $itemService;
    protected DeliveryAction $deliveryAction;

    public function __construct(ItemService $itemService,DeliveryAction $deliveryAction)
    {
        $this->itemService = $itemService;
        $this->deliveryAction = $deliveryAction;
    }

    public function SetCartByID($id): static
    {
        $this->cart =  Cart::findOrFail($id);
        $this->user = $this->cart->main_cart->user;
        return $this;
    }

    public function SetCart(Cart $cart): static
    {
        $this->cart = $cart;
        $this->user = $cart->main_cart->user;
        return $this;
    }

    public function AddItem($item_id,int $quantity,Main_cart $main_cart): bool
    {
        if(
        $this->itemService
            ->SetItem($item_id)
            ->SetQuantity($quantity)
            ->checkInventory()
        ){
            DB::transaction(function () use ($quantity,$main_cart) {
                $delivery_cost = $this->deliveryAction->CalculateDeliveryCost($this->user,$this->cart->restaurant_id);
                $tax = $this->itemService->GetRestaurantTax();
                $this->itemService
                    ->ReduceInventory()
                    ->save();
                $this->cart->total += $this->itemService->CalculateTotalPrice();
                $this->cart->delivery_cost = $delivery_cost;
                $this->cart->tax = $tax;
                $this->cart->save();
                $this->cart->items()->create([
                    'item_id' => $this->itemService->GetItem()->id,
                    'quantity' => $quantity
                ]);
                $main_cart->total += $this->itemService->CalculateTotalPrice() + $delivery_cost + $tax;
                $main_cart->save();
            });
            return true;
        }
        return false;
    }

    public function IncreaseItem(int $cart_item_id,int $quantity,Main_cart $main_cart): bool
    {
        $cart_item = Cart_item::findOrFail($cart_item_id);
        if($this->cart->id != $cart_item->cart->id)
            return false;
        if(
        $this->itemService
            ->SetItem($cart_item->item_id)
            ->SetQuantity($quantity)
            ->checkInventory()
        ){
            DB::transaction(function () use ($quantity,$cart_item,$main_cart) {
                $this->itemService
                    ->ReduceInventory()
                    ->save();
                $this->cart->total += $this->itemService->CalculateTotalPrice();
                $this->cart->save();
                $cart_item->quantity += $quantity;
                $cart_item->save();
                $main_cart->total += $this->itemService->CalculateTotalPrice();
                $main_cart->save();
            });
            return true;
        }
        return false;
    }

    public function ReduceItem($cart_item_id,$quantity,Main_cart $main_cart): bool
    {
        $cart_item = Cart_item::findOrFail($cart_item_id);
        if($this->cart->id != $cart_item->cart->id)
            return false;
        if($cart_item->quantity > $quantity){
            DB::transaction(function () use ($quantity,$cart_item,$main_cart) {
                $this->itemService
                    ->SetItem($cart_item->item_id)
                    ->SetQuantity($quantity)
                    ->IncreaseInventory()
                    ->save();
                $this->cart->total -= $this->itemService->CalculateTotalPrice();
                $this->cart->save();
                $cart_item->quantity -= $quantity;
                $cart_item->save();
                $main_cart->total -= $this->itemService->CalculateTotalPrice();
                $main_cart->save();
            });
        }else {
            ///Delete cart_item
           return $this->DeleteItem($cart_item,$main_cart);
        }
        return true;
    }

    public function DeleteItem($cart_item,$main_cart,$cart_item_id = null): bool
    {
        $cart_item = $cart_item_id
            ? Cart_item::findOrFail($cart_item_id)
            :$cart_item;

        if($this->cart->id != $cart_item->cart->id)
            return false;

        $quantity = $cart_item->quantity;
        DB::transaction(function () use ($quantity,$cart_item,$main_cart) {
            $this->itemService
                ->SetItem($cart_item->item_id)
                ->SetQuantity($quantity)
                ->IncreaseInventory()
                ->save();
            $this->cart->total -= $this->itemService->CalculateTotalPrice();
            $this->cart->save();
            $cart_item->delete();
            $main_cart->total -= $this->itemService->CalculateTotalPrice();
            $main_cart->save();
        });
        return true;
    }

    public function DeleteCart($main_cart)
    {
        foreach ( $this->cart->items as $cart_item ){
            $this->DeleteItem($cart_item,$main_cart);
        }
        $this->cart->delete();
        $main_cart->total -= ( $this->cart->delivery_cost + $this->cart->tax );
        $main_cart->save();
    }

}
