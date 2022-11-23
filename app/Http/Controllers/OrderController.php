<?php

namespace App\Http\Controllers;

use App\Action\OrderAction;
use App\Jobs\RatingJob;
use App\Models\Order;
use App\Service\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function Order(OrderAction $orderAction)
    {
        try{
            $user = auth()->user();
            $orderAction->TransferCartToOrder($user);
            return $this->SuccessResponse();
        }catch (\Exception $exception){
            return $this->failureResponse($exception->getMessage());
        }

    }

    public function GetMyOrder()
    {
        try{
            $data = auth()->user()->orders;
            return $data;
        }catch (\Exception $exception){
            return $this->failureResponse($exception->getMessage());
        }

    }

    public function RateMyOrder(Request $request)
    {
        $request->validate([
            'order_id' => ['required'],
            'rating' => ['required','integer','min:1','max:5']
        ]);
        try{
            $order = Order::findOrFail($request->order_id);
            $this->authorize('RateMyOrder',$order);
            if($order->rate != 0 ) return $this->failureResponse('This order has already been rated');
            $order->rate = $request->rating;
            $order->save();
            RatingJob::dispatch($order->rate , $order->restaurant_id);
            return $this->SuccessResponse();
        }catch (\Exception $exception){
            return $this->failureResponse($exception->getMessage());
        }

    }

    public function GetOrders()
    {
        $this->authorize('is_admin');
        try{
            $data = Order::Paginate();
            return $data;
        }catch (\Exception $exception){
            return $this->failureResponse($exception->getMessage());
        }

    }

    public function AddItem(Request $request,Order $order)
    {
        $request->validate([
            'item_id' => ['required','exists:items,id'],
            'quantity' => ['required','integer']
        ]);
        $this->authorize('is_admin');
        try{
            $this->orderService->SetOrder($order);
            return $this->orderService->AddItem($request->item_id,$request->quantity)
                ? $this->SuccessResponse()
                : $this->failureResponse();
        }catch (\Exception $exception){
            return $this->failureResponse($exception->getMessage());
        }

    }

    public function IncreaseItem(Request $request,Order $order)
    {
        $request->validate([
            'order_item_id' => ['required','exists:order_items,id'],
            'quantity' => ['required','integer']
        ]);
        $this->authorize('is_admin');
        try{
            $this->orderService->SetOrder($order);
            return $this->orderService->IncreaseItem($request->order_item_id,$request->quantity)
                ? $this->SuccessResponse()
                : $this->failureResponse();
        }catch (\Exception $exception){
            return $this->failureResponse($exception->getMessage());
        }

    }

    public function ReduceItem(Request $request,Order $order)
    {
        $request->validate([
            'order_item_id' => ['required','exists:order_items,id'],
            'quantity' => ['required','integer']
        ]);
        $this->authorize('is_admin');
        try{
            $this->orderService->SetOrder($order);
            return $this->orderService->ReduceItem($request->order_item_id,$request->quantity)
                ? $this->SuccessResponse()
                : $this->failureResponse();
        }catch (\Exception $exception){
            return $this->failureResponse($exception->getMessage());
        }

    }

    public function DeleteItem(Request $request,Order $order)
    {
        $request->validate([
            'order_item_id' => ['required','exists:order_items,id'],
        ]);
        $this->authorize('is_admin');
        try{
            $this->orderService->SetOrder($order);
            return $this->orderService->DeleteItem(
                null,
                $request->order_item_id)
                ? $this->SuccessResponse()
                : $this->failureResponse();
        }catch (\Exception $exception){
            return $this->failureResponse($exception->getMessage());
        }

    }

    public function DeleteOrder(Order $order)
    {
        $this->authorize('is_admin');
        try{
            $this->orderService->SetOrder($order);
            $this->orderService->DeleteOrder();
            return $this->SuccessResponse();
        }catch (\Exception $exception){
            return $this->failureResponse($exception->getMessage());
        }

    }

}
