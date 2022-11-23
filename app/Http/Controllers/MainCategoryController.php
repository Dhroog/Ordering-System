<?php

namespace App\Http\Controllers;

use App\Http\Resources\Categories\MainCategoryResource;
use App\Models\Main_category;
use App\Http\Requests\Categories\Main\StoreMain_categoryRequest;
use App\Http\Requests\Categories\Main\UpdateMain_categoryRequest;

class MainCategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Main_category::class, 'Main_category');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return MainCategoryResource::collection(Main_category::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Categories\Main\StoreMain_categoryRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreMain_categoryRequest $request)
    {
        Main_category::create($request->validated());
        return $this->CreatedResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Main_category  $main_category
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Main_category $main_category)
    {
        return  $this->DataResponse($main_category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Categories\Main\UpdateMain_categoryRequest  $request
     * @param  \App\Models\Main_category  $main_category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateMain_categoryRequest $request, Main_category $main_category)
    {
        $main_category->update( $request->validated() );
        return $this->UpdatedResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Main_category  $main_category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Main_category $main_category)
    {
        $main_category->delete();
        return $this->DeletedResponse();
    }
}
