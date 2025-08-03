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
     * @return \Inertia\Response
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
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::modal('Dashboard/Inertia/V1/Products/Create',[
        ])->baseRoute('products.create');
       
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param StoreProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     * 
     * @param Product $product
     * @return \Inertia\Response
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
     * @return \Inertia\Response
     */
    public function edit(Product $product)
    {
        return Inertia::modal('Dashboard/Inertia/V1/Products/Edit')
            ->with([
                'product' => ProductTransform::single($product),
                'categories' => Category::all(['id', 'name']),
            ])->baseRoute('products.index');
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param StoreProductRequest $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Show delete confirmation modal
     * 
     * @param Product $product
     * @return \Inertia\Response
     */
    public function delete(Product $product)
    {
        return Inertia::modal('Dashboard/Inertia/V1/Products/Delete')
            ->with([
                'product' => ProductTransform::single($product),
            ])->baseRoute('products.index');
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

}