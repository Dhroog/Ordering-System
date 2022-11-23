<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});






Route::middleware(['auth:sanctum','MyVerified'])->group(function (){
    Route::apiResources([
        'restaurant' => RestaurantController::class,
        'main-category' => MainCategoryController::class,
        'category' => CategoryController::class,
        'item' => ItemController::class,
        'user' => UserController::class,
        'offer' => OfferController::class
    ]);
    Route::prefix('cart')->group(function (){

        Route::post('AddItem',[CartController::class,'AddItem']);
        Route::post('IncreaseItem',[CartController::class,'IncreaseItem']);
        Route::post('ReduceItem',[CartController::class,'ReduceItem']);
        Route::post('DeleteItem',[CartController::class,'DeleteItem']);
        Route::post('DeleteSubCart',[CartController::class,'DeleteSubCart']);
        Route::get('GetCart',[CartController::class,'GetCart']);

    });

    Route::get('Order',[OrderController::class,'Order']);
    Route::get('GetOrders',[OrderController::class,'GetOrders']);
    Route::get('GetMyOrder',[OrderController::class,'GetMyOrder']);
    Route::post('RateMyOrder',[OrderController::class,'RateMyOrder']);

    Route::prefix('order')->group(function (){

        Route::post('AddItem/{order}',[OrderController::class,'AddItem']);
        Route::post('IncreaseItem/{order}',[OrderController::class,'IncreaseItem']);
        Route::post('ReduceItem/{order}',[OrderController::class,'ReduceItem']);
        Route::post('DeleteItem/{order}',[OrderController::class,'DeleteItem']);
        Route::Delete('Delete/{order}',[OrderController::class,'DeleteOrder']);

    });

});

require __DIR__.'/auth.php';
