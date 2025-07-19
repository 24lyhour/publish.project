<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Models\Category;
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
            
        // Transform products to include category name
        $products->through(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'category' => $product->category?->name ?? 'No Category',
                'category_id' => $product->category_id,
                'imageUrl' => $product->imageUrl,
                'quantity' => $product->quantity,
                'status' => $product->status,
                'size' => $product->size,
                'ingredients' => $product->ingredients,
                'allergens' => $product->allergens,
                'extras' => $product->extras,
                'type' => $product->type,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        });
        
        return Inertia::render('Products/Index', [
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
        return Inertia::modal('Products/Modals/CreateProduct')
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
       return Inertia::render('Products/Show', [
            'product' => $product,
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
        return Inertia::modal('Products/Modals/EditProduct')
            ->with([
                'product' => $product,
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
    public function confirmDelete(Product $product)
    {
        return Inertia::modal('Products/Modals/DeleteProduct')
            ->with([
                'product' => $product,
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

    /**
     * Show create product modal
     * 
     * @return Response
     */
    public function createModal()
    {
        return Inertia::modal('Modals/ProductFormModal')
            ->with([
                'mode' => 'create',
                'categories' => Category::all(['id', 'name']),
                'method' => 'post',
                'action' => route('dashboard.products.store'),
            ]);
    }

    /**
     * Show edit product modal
     * 
     * @param Product $product
     * @return Response
     */
    public function editModal(Product $product)
    {
        return Inertia::modal('Modals/ProductFormModal')
            ->with([
                'mode' => 'edit',
                'product' => $product,
                'categories' => Category::all(['id', 'name']),
                'method' => 'patch',
                'action' => route('dashboard.products.update', $product),
            ]);
    }
}