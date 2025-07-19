<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Transform\Dashboard\Inertia\V1\ProductTransform;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return Response
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category_id', 'price_range', 'status', 'sort', 'direction']);
        
        $products = Product::filter($filters)
            ->with('category')
            ->when(!isset($filters['sort']), function ($query) {
                return $query->latest();
            })
            ->paginate(10)
            ->withQueryString();
            
        // Transform products using ProductTransform
        $products = ProductTransform::paginated($products);
        
        return Inertia::render('Dashboard/Inertia/V1/Products/Index', [
            'products' => $products,
            'categories' => Category::all(['id', 'name']),
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Dashboard/Inertia/V1/Products/Create')
            ->with([
                'categories' => Category::all(['id', 'name']),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param Request
     * 
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     * 
     * @param Product $product
     * @return Response
     * 
     * @throws Exception
     * @throws ResponseException
     * 
     * 
     */
    public function show(Product $product)
    {
       return Inertia::render('Dashboard/Inertia/V1/Products/Show', [
            'product' => ProductTransform::single($product),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param Product $product
     * @return Response
     * 
     * @throws Exception
     * @throws ResponseException
     * 
     * 
     */
    public function edit(Product $product)
    {
        return Inertia::render('Dashboard/Inertia/V1/Products/Edit')
            ->with([
                'product' => ProductTransform::single($product),
                'categories' => Category::all(['id', 'name']),
            ]);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param Product $product
     * @param StoreProductRequest $request
     * @return Response
     * 
     * @throws Exception
     * @throws ValidationException
     * @throws ResponseException
     * 
     */
    public function update(StoreProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Show delete confirmation modal
     * 
     * @param Product $product
     * @return Response
     */
    public function delete(Product $product)
    {
        return Inertia::render('Dashboard/Inertia/V1/Products/Delete')
            ->with([
                'product' => ProductTransform::single($product),
            ]);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Product $product
     * @return Response
     * 
     * @throws Exception
     * @throws ResponseException
     *
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product deleted successfully.');
    }

}