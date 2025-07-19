<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return Response
     * 
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param Request
     * 
     * @return Response
     * 
     * @throws Exception
     * @throws ResponseException
     * 
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * 
     * 
     */
    public function show(Restaurant $restaurant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param Restaurant $restaurant
     * @return Response
     * 
     * @throws Exception
     * @throws ResponseException
     * 
     * 
     */
    public function edit(Restaurant $restaurant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * 
     * 
     * @param Restaurant $restaurant
     * @param Request $request
     * @return Response
     * 
     * @throws Exception
     * @throws ValidationException
     * @throws ResponseException
     * 
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * 
     * 
     * 
     */
    public function destroy(Restaurant $restaurant)
    {
        //
    }
}
