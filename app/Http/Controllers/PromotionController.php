<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promotions = Promotion::orderBy('updated_at', 'DESC')->paginate(50);

        return view('promotions.index', compact('promotions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::get();
        return view('promotions.create', compact('products'));
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
            'product_id' => 'required|exists:products,id',
            'amount_per_unit' => 'required|integer',
            'total_quantity' => 'required|integer',
            'status' => 'boolean',
        ]);
        
        try {
            $data['remaining_quantity'] = $data['total_quantity'];
            $promotion = Promotion::create($data);

            return redirect()->route('promotions.index')->with('success', 'Promotion Created Successfully');

        } catch (\Exception $e) {

            Log::error($e);
            return redirect()->route('promotions.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function show(Promotion $promotion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function edit(Promotion $promotion)
    {
        $products = Product::get();

        return view('promotions.edit', compact('promotion', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Promotion $promotion)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'amount_per_unit' => 'required|integer',
            'total_quantity' => 'required|integer',
            'status' => 'boolean',
        ]);

        try {
            $promotion = $promotion->update($data);

            return redirect()->route('promotions.index')->with('success', 'Promotion Updated Successfully');
        } catch (\Exception $e) {

            Log::error($e);
            return redirect()->route('promotions.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promotion $promotion)
    {
        try {
            $promotion->delete();

            return redirect()->route('promotion.index')->with('success', 'Promotion Deleted Successfully');
        } catch (\Exception $e) {

            Log::error($e);
            return redirect()->route('promotion.index')->with('error', 'Something went wrong!');
        }
    }
}
