<?php


namespace App\Service;


use App\Models\Item;

class ItemService
{
    protected Item $Item;
    protected int $quantity;
    public function GetItem(): Item
    {
        return $this->Item;
    }
    public function SetItem($id): static
    {
        $this->Item =  Item::findOrFail($id);
        return $this;
    }
    public function SetQuantity($quantity): static
    {
        $this->quantity =  $quantity;
        return $this;
    }
    public function SetAll($id,$quantity): static
    {
        $this->product =  Item::findOrFail($id);
        $this->quantity =  $quantity;
        return $this;
    }
    public function checkInventory():bool
    {
        return $this->Item->inventory >= $this->quantity;
    }
    public function ReduceInventory(): static
    {
        $this->Item->inventory -= $this->quantity;
        return $this;
    }
    public function IncreaseInventory(): static
    {
        $this->Item->inventory += $this->quantity;
        return $this;
    }
    public function save(): static
    {
        $this->Item->save();
        return $this;
    }
    public function CalculateTotalPrice(): float|int
    {
        return $this->Item->price * $this->quantity;
    }
    public function GetRestaurantId()
    {
        return $this->GetItem()->category->Restaurant->id;
    }
}
