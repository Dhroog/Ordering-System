<?php


namespace App\Service;


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

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function SetCartByID($id)
    {
        $this->cart =  Cart::findOrFail($id);
        return $this;
    }

    public function SetCart(Cart $cart)
    {
        $this->cart = $cart;
        return $this;
    }

    public function AddItem($item_id,int $quantity,Main_cart $main_cart)
    {
        if(
        $this->itemService
            ->SetItem($item_id)
            ->SetQuantity($quantity)
            ->checkInventory()
        ){
            DB::transaction(function () use ($quantity,$main_cart) {
                $this->itemService
                    ->ReduceInventory()
                    ->save();
                $this->cart->total += $this->itemService->CalculateTotalPrice();
                $this->cart->save();
                $this->cart->items()->create([
                    'item_id' => $this->itemService->GetItem()->id,
                    'quantity' => $quantity
                ]);
                $main_cart->total += $this->itemService->CalculateTotalPrice();
                $main_cart->save();
            });
            return true;
        }
        return false;
    }

    public function IncreaseItem(int $cart_item_id,int $quantity,Main_cart $main_cart)
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

    public function ReduceItem($cart_item_id,$quantity,Main_cart $main_cart)
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

    public function DeleteItem($cart_item,$main_cart,$cart_item_id = null)
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
    }

}
