<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('updated_at', 'DESC')->paginate(50);

        return view('category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'design' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        try {
            $category = Category::create($data);

            return redirect()->route('categories.index')->with('success', 'Category Created Successfully');
        } catch (\Exception $e) {

            Log::error($e);
            return redirect()->route('categories.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'design' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        try {
            $category = $category->update($data);

            return redirect()->route('categories.index')->with('success', 'Category Updated Successfully');
        } catch (\Exception $e) {

            Log::error($e);
            return redirect()->route('categories.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return redirect()->route('categories.index')->with('success', 'Category Deleted Successfully');
        } catch (\Exception $e) {

            Log::error($e);
            return redirect()->route('categories.index')->with('error', 'Something went wrong!');
        }
    }
}
