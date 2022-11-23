<?php


namespace App\Service;


use App\Action\DeliveryAction;
use App\Models\Cart;
use App\Models\Cart_item;
use App\Models\Main_cart;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected Order $order;
    protected ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function SetOrder(Order $order): static
    {
        $this->order = $order;
        return $this;
    }

    public function AddItem($item_id,int $quantity): bool
    {
        if(
        $this->itemService
            ->SetItem($item_id)
            ->SetQuantity($quantity)
            ->checkInventory()
        ){
            DB::transaction(function () use ($quantity) {
                $this->itemService
                    ->ReduceInventory()
                    ->save();
                $this->order->total += $this->itemService->CalculateTotalPrice();
                $this->order->save();
                $this->order->items()->create([
                    'item_id' => $this->itemService->GetItem()->id,
                    'quantity' => $quantity
                ]);
            });
            return true;
        }
        return false;
    }

    public function IncreaseItem(int $order_item_id,int $quantity): bool
    {
        $order_item = Order_item::findOrFail($order_item_id);
        if($this->order->id != $order_item->order_id)
            return false;
        if(
        $this->itemService
            ->SetItem($order_item->item_id)
            ->SetQuantity($quantity)
            ->checkInventory()
        ){
            DB::transaction(function () use ($quantity,$order_item) {
                $this->itemService
                    ->ReduceInventory()
                    ->save();
                $this->order->total += $this->itemService->CalculateTotalPrice();
                $this->order->save();
                $order_item->quantity += $quantity;
                $order_item->save();
            });
            return true;
        }
        return false;
    }

    public function ReduceItem($order_item_id,$quantity): bool
    {
        $order_item = Order_item::findOrFail($order_item_id);
        if($this->order->id != $order_item->order_id)
            return false;
        if($order_item->quantity > $quantity){
            DB::transaction(function () use ($quantity,$order_item) {
                $this->itemService
                    ->SetItem($order_item->item_id)
                    ->SetQuantity($quantity)
                    ->IncreaseInventory()
                    ->save();
                $this->order->total -= $this->itemService->CalculateTotalPrice();
                $this->order->save();
                $order_item->quantity -= $quantity;
                $order_item->save();
            });
        }else {
            ///Delete cart_item
            return $this->DeleteItem($order_item);
        }
        return true;
    }

    public function DeleteItem($order_item,$order_item_id = null): bool
    {
        $order_item = $order_item_id
            ? Order_item::findOrFail($order_item_id)
            :$order_item;

        if($this->order->id != $order_item->order_id)
            return false;

        $quantity = $order_item->quantity;
        DB::transaction(function () use ($quantity,$order_item) {
            $this->itemService
                ->SetItem($order_item->item_id)
                ->SetQuantity($quantity)
                ->IncreaseInventory()
                ->save();
            $this->order->total -= $this->itemService->CalculateTotalPrice();
            $this->order->save();
            $order_item->delete();
        });
        return true;
    }

    public function DeleteOrder()
    {
        foreach ( $this->order->items as $order_item ){
            $this->DeleteItem($order_item);
        }
        $this->order->delete();
    }
}
