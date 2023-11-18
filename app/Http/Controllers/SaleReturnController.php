<?php

namespace App\Http\Controllers;

use App\Models\Payment;
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
        // $products = Product::get();

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
        // dd($request->all());
        $data = $request->validate([
            'return_sale_details' => 'array|required',
            'return_sale_details.*' => 'required|integer',
            'product' => 'array|nullable',
            'product.*' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->return_sale_details as $saleDetailId => $quantity) {
                if ($quantity > 0) {
    
                    $saleDetail = SaleDetail::find($saleDetailId);
                    $unitPrice = $saleDetail->total_amount / $saleDetail->quantity;
                    if (empty($saleDetail)) {
                        return redirect()->back()->with('error', 'Something went wrong!');
                    }

                    $exchangePrdId = null;
        
                    if (empty($data['product'][$saleDetailId])) {
                        $totalReturnedAmount = $unitPrice * $quantity;

                        $sale = Sale::find($saleDetail->sale_id);
                        $sale->total_amount -= $totalReturnedAmount;
                        $sale->save();

                        $product = Product::find($saleDetail->product_id);
                        $product->quantity += $quantity;
                        $product->save();

                        $saleDetail->total_amount -= $totalReturnedAmount;
                        $saleDetail->quantity -= $quantity;
                        $saleDetail->save();

                        $payment = Payment::where('model_type', 'Sale')
                                    ->where('reference_id', $sale->id)
                                    ->first();

                        $payment->total_amount -= $totalReturnedAmount;
                        $payment->save();

                    } else {
                        $exchangePrd = Product::find($data['product'][$saleDetailId]);
                        $exchangePrdId = $exchangePrd->id;

                        if ($exchangePrdId != $saleDetail->product_id) {
                            $exchangeAmount = $unitPrice * $quantity;

                            $newSaleDetail = SaleDetail::where('sale_id', $saleDetail->sale_id)
                                                        ->where('product_id', $data['product'][$saleDetailId])
                                                        ->first();

                            $product = Product::find($saleDetail->product_id);
                            $product->quantity += $quantity;
                            $product->save();

                            $exchangePrd->quantity -= $quantity;
                            $exchangePrd->save();

                            $saleDetail->total_amount -= $exchangeAmount;
                            $saleDetail->quantity -= $quantity;
                            $saleDetail->save();

                            if ($newSaleDetail) {
                                $newSaleDetail->total_amount += $exchangeAmount;
                                $newSaleDetail->quantity += $quantity;
                                $newSaleDetail->save();
                            } else {
                                $newSaleDetail = SaleDetail::create([
                                    'sale_id' => $saleDetail->sale_id,
                                    'product_id' => $exchangePrdId,
                                    'quantity' => $quantity,
                                    'unit_price' => $unitPrice,
                                    'total_amount' => $exchangeAmount,
                                    'total_promoted_qty' => 0,
                                    'total_promoted_amount' => 0,
                                ]);
                            }
                        }
                    }
        
                    $saleReturn = SaleReturn::create([
                        'sale_detail_id' => $saleDetail->id,
                        'product_id' => $saleDetail->product_id,
                        'returned_quantity' => $quantity,
                        'total_returned_amount' => $totalReturnedAmount ?? 0,
                        'description' => $request->description ?? null,
                        'exchange_prd_id' => $exchangePrdId,
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
