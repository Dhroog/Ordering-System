<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Service\CartService;
use App\Service\MainCartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected MainCartService $mainCartService;
    public function __construct(MainCartService $mainCartService)
    {
        $this->mainCartService = $mainCartService;
    }

    public function AddItem(Request $request)
    {
        $request->validate([
            'item_id' => ['required','exists:items,id'],
            'quantity' => ['required','integer']
        ]);
        $this->mainCartService->SetUser(auth()->user());
       return $this->mainCartService->AddItem($request->item_id,$request->quantity)
           ? $this->SuccessResponse()
           : $this->failureResponse();
    }

    public function IncreaseItem(Request $request)
    {
        $request->validate([
            'cart_id' => ['required','exists:carts,id'],
            'cart_item_id' => ['required','exists:cart_items,id'],
            'quantity' => ['required','integer']
        ]);
        $this->mainCartService->SetUser(auth()->user());
        return $this->mainCartService->IncreaseItem($request->cart_id,$request->cart_item_id,$request->quantity)
            ? $this->SuccessResponse()
            : $this->failureResponse();
    }

    public function ReduceItem(Request $request)
    {
        $request->validate([
            'cart_id' => ['required','exists:carts,id'],
            'cart_item_id' => ['required','exists:cart_items,id'],
            'quantity' => ['required','integer']
        ]);
        $this->mainCartService->SetUser(auth()->user());
        return $this->mainCartService->ReduceItem(
            $request->cart_id,
            $request->cart_item_id,
            $request->quantity)
            ? $this->SuccessResponse()
            : $this->failureResponse();
    }

    public function DeleteItem(Request $request)
    {
        $request->validate([
            'cart_id' => ['required','exists:carts,id'],
            'cart_item_id' => ['required','exists:cart_items,id'],
        ]);
        $this->mainCartService->SetUser(auth()->user());
        return $this->mainCartService->DeleteItem(
            $request->cart_id,
            $request->cart_item_id)
            ? $this->SuccessResponse()
            : $this->failureResponse();
    }

    public function DeleteSubCart(Request $request,CartService $cartService)
    {
        $request->validate([
            'cart_id' => ['required','exists:carts,id'],
        ]);

        $this->mainCartService->SetUser(auth()->user());
        $mainCart = $this->mainCartService->GetMainCart();
        $cartService->SetCartByID($request->cart_id)->DeleteCart($mainCart);
        return $this->SuccessResponse();
    }

    public function GetCart()
    {

    }
}
