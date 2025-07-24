<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRestaurantRequest;
use App\Models\Restaurant;
use App\Transform\Dashboard\Inertia\V1\RestaurantTransform;
use Inertia\Inertia;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'sort', 'direction']);
        
        $restaurants = Restaurant::when($filters['search'] ?? null, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%')
                      ->orWhere('address', 'like', '%' . $search . '%');
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when(!isset($filters['sort']), function ($query) {
                return $query->latest();
            })
            ->withCount('menus')
            ->paginate(10)
            ->withQueryString();
            
        $restaurants = RestaurantTransform::paginated($restaurants);
        
        return Inertia::render('Dashboard/Inertia/V1/Restaurants/Index', [
            'restaurants' => $restaurants,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::modal('Dashboard/Inertia/V1/Restaurants/Create')
            ->baseRoute('dashboard.restaurants.index');
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param StoreRestaurantRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRestaurantRequest $request)
    {
        $restaurant = Restaurant::create($request->validated());

        return redirect()->route('dashboard.restaurants.index')
            ->with('success', 'Restaurant created successfully.');
    }

    /**
     * Display the specified resource.
     * 
     * @param Restaurant $restaurant
     * @return \Inertia\Response
     */
    public function show(Restaurant $restaurant)
    {
        return Inertia::render('Dashboard/Inertia/V1/Restaurants/Show', [
            'restaurant' => RestaurantTransform::single($restaurant),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param Restaurant $restaurant
     * @return \Inertia\Response
     */
    public function edit(Restaurant $restaurant)
    {
        return Inertia::modal('Dashboard/Inertia/V1/Restaurants/Edit')
            ->with([
                'restaurant' => RestaurantTransform::single($restaurant),
            ])->baseRoute('dashboard.restaurants.index');
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param StoreRestaurantRequest $request
     * @param Restaurant $restaurant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreRestaurantRequest $request, Restaurant $restaurant)
    {
        $restaurant->update($request->validated());

        return redirect()->route('dashboard.restaurants.index')
            ->with('success', 'Restaurant updated successfully.');
    }

    /**
     * Show delete confirmation modal
     * 
     * @param Restaurant $restaurant
     * @return \Inertia\Response
     */
    public function delete(Restaurant $restaurant)
    {
        return Inertia::modal('Dashboard/Inertia/V1/Restaurants/Delete')
            ->with([
                'restaurant' => RestaurantTransform::single($restaurant),
            ])->baseRoute('dashboard.restaurants.index');
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Restaurant $restaurant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();

        return redirect()->route('dashboard.restaurants.index')
            ->with('success', 'Restaurant deleted successfully.');
    }
}