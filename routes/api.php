<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\RestaurantController;
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


Route::middleware('auth:sanctum')->group(function (){
    Route::apiResources([
        'restaurant' => RestaurantController::class,
        'main-category' => MainCategoryController::class,
        'category' => CategoryController::class,
        'item' => ItemController::class
    ]);
    Route::post('AddItem',[CartController::class,'AddItem']);
    Route::post('IncreaseItem',[CartController::class,'IncreaseItem']);
    Route::post('ReduceItem',[CartController::class,'ReduceItem']);
    Route::post('DeleteItem',[CartController::class,'DeleteItem']);
    Route::post('DeleteSubCart',[CartController::class,'DeleteSubCart']);

});

require __DIR__.'/auth.php';
