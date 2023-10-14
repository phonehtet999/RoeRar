<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::orderBy('updated_at', 'DESC')->paginate(50);

        return view('brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::get();

        return view('brand.create', compact('suppliers'));
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
            'supplier_id' => 'required|exists:suppliers,id',
            'description' => 'nullable|string',
        ]);

        try {
            $brand = Brand::create($data);

            return redirect()->route('brands.index')->with('success', 'Brand Created Successfully');
        } catch (\Exception $e) {

            Log::error($e);
            return redirect()->route('brands.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        $suppliers = Supplier::get();

        return view('brand.edit', compact('brand', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'supplier_id' => 'required|exists:suppliers,id',
            'description' => 'nullable|string',
        ]);

        try {
            $brand = $brand->update($data);

            return redirect()->route('brands.index')->with('success', 'Brand Updated Successfully');
        } catch (\Exception $e) {

            Log::error($e);
            return redirect()->route('brands.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();

            return redirect()->route('brands.index')->with('success', 'Brand Deleted Successfully');
        } catch (\Exception $e) {

            Log::error($e);
            return redirect()->route('brands.index')->with('error', 'Something went wrong!');
        }
    }
}
