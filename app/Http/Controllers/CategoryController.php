<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return Response
     */
    public function index(Request $request)
    {
        return Inertia::render('Dashboarde/Inertia/V3/Categories/Index', [
            'categories' => Category::latest()->paginate(10),
        ]);
    }

    /**
     * 
     * Show the form for creating a new resource.
     * 
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Dashboarde/Inertia/V3/Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param Request
     * 
     */
    public function store(Request $request)
    {
        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * 
     * Display the specified resource.
     * 
     * @param Category $category
     * @return Response
     * 
     */
    public function show(Category $category)
    {
        return Inertia::render('Dashboarde/Inertia/V3/Categories/Show', [
            'category' => $category,
        ]);
    }

    /**
     * Edit the specified resource in storage.
     * 
     * @param Category $category
     * @return Response
     * 
     * 
     */
    public function edit(Category $category)
    {
        return Inertia::render('Dashboarde/Inertia/V3/Categories/Edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param Category $category
     * @param Request $request
     * @return Response
     * 
     * 
     */
    public function update(Request $request, Category $category)
    {
        $category->update($request->validated());

    }


    /**
     * Destroy the specified resource from storage.
     * 
     * @param Category $category
     * @return Response
     * 
     * 
     */
    public function destroy(Category $category)
    {
        $category->delete();    
    }



}

