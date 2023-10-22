<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SaleReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $saleReturns = SaleReturn::orderBy('updated_at', 'DESC')->paginate(50);
        
        return view('sale-return.index', compact('saleReturns'));
    }

    public function createSecond(Request $request)
    {
        $data = $request->validate([
            'sale_id' => 'required|exists:sales,id',
        ]);

        $sale = Sale::find($data['sale_id']);

        return view('sale-return.create-second', compact('sale'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sales = Sale::get();
        
        return view('sale-return.create-first', compact('sales'));
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
            'return_sale_details' => 'array|required',
            'return_sale_details.*' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->return_sale_details as $saleDetailId => $quantity) {
                if ($quantity > 0) {
    
                    $saleDetail = SaleDetail::find($saleDetailId);
                    if (empty($saleDetail)) {
                        return redirect()->back()->with('error', 'Something went wrong!');
                    }
        
                    $totalReturnedAmount = $saleDetail->unit_price * $quantity;
    
                    $sale = Sale::find($saleDetail->sale_id);
                    $sale->total_amount -= $totalReturnedAmount;
                    $sale->save();
    
                    $product = Product::find($saleDetail->product_id);
                    $product->quantity += $quantity;
                    $product->save();
    
                    $saleDetail->total_amount -= $totalReturnedAmount;
                    $saleDetail->quantity -= $quantity;
                    $saleDetail->save();
        
                    $saleReturn = SaleReturn::create([
                        'sale_detail_id' => $saleDetail->id,
                        'product_id' => $saleDetail->product_id,
                        'returned_quantity' => $quantity,
                        'total_returned_amount' => $totalReturnedAmount,
                        'description' => $request->description ?? null,
                    ]);
                }
            }
    
            DB::commit();
            return redirect()->route('sale_returns.index')->with('success', 'Return Created Successfully');

        } catch (\Exception $e) {

            DB::rollback();
            Log::error($e);
            return redirect()->route('sale_returns.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function show(SaleReturn $saleReturn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleReturn $saleReturn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleReturn $saleReturn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaleReturn $saleReturn)
    {
        //
    }
}
