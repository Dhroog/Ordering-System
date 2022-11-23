<?php

namespace App\Http\Controllers;

use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use App\Http\Requests\Restaurant\StoreRestaurantRequest;
use App\Http\Requests\Restaurant\UpdateRestaurantRequest;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Restaurant::class, 'Restaurant');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $res = Restaurant::Order($request->get('type'))->paginate();
        return RestaurantResource::collection($res);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Restaurant\StoreRestaurantRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRestaurantRequest $request)
    {
        Restaurant::create($request->validated());
        return $this->CreatedResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Restaurant $restaurant)
    {
        return  $this->DataResponse($restaurant);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Restaurant\UpdateRestaurantRequest  $request
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant)
    {
        $restaurant->update( $request->validated() );
        return $this->UpdatedResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();
        return $this->DeletedResponse();
    }
}
