<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Transform\Dashboard\Inertia\V1\CategoryTransform;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'sort', 'direction']);
        
        $categories = Category::when($filters['search'] ?? null, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when(!isset($filters['sort']), function ($query) {
                return $query->latest();
            })
            ->with('menu')
            ->paginate(10)
            ->withQueryString();
            
        $categories = CategoryTransform::paginated($categories);
        
        return Inertia::render('Dashboard/Inertia/V1/Categories/Index', [
            'categories' => $categories,
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
        return Inertia::modal('Dashboard/Inertia/V1/Categories/Create')
            ->baseRoute('dashboard.categories.index');
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param StoreCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     * 
     * @param Category $category
     * @return \Inertia\Response
     */
    public function show(Category $category)
    {
        return Inertia::render('Dashboard/Inertia/V1/Categories/Show', [
            'category' => CategoryTransform::single($category),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param Category $category
     * @return \Inertia\Response
     */
    public function edit(Category $category)
    {
        return Inertia::modal('Dashboard/Inertia/V1/Categories/Edit')
            ->with([
                'category' => CategoryTransform::single($category),
            ])->baseRoute('dashboard.categories.index');
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param StoreCategoryRequest $request
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category updated successfully.');
    }


    /**
     * Show delete confirmation modal
     * 
     * @param Category $category
     * @return \Inertia\Response
     */
    public function delete(Category $category)
    {
        return Inertia::modal('Dashboard/Inertia/V1/Categories/Delete')
            ->with([
                'category' => CategoryTransform::single($category),
            ])->baseRoute('dashboard.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category deleted successfully.');
    }



}

