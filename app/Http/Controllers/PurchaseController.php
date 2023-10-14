<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $purchases = Purchase::orderBy('updated_at', 'DESC')
                        ->when(!empty($request->id), function ($query) use ($request) {
                            return $query->where('id', $request->id);
                        })
                        ->paginate(50);

        return view('purchase.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::get();

        return view('purchase.create-first', compact('products'));
    }

    public function createSecond(Request $request)
    {
        $product = Product::find($request->product_id);
        $supplier = $product->brand->supplier;

        return view('purchase.create-second', compact('product', 'supplier'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'invoice_number' => 'required|unique:purchases,invoice_number',
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer',
            'unit_selling_price' => 'required|integer',
            'unit_buying_price' => 'required|integer',
            'payment_type' => 'nullable|string',
            'description' => 'nullable|string',
        ];

        $data = Validator::make($request->all(), $rules);

        if ($data->fails()) {
            $product = Product::find($request->product_id);
            $supplier = $product->brand->supplier;

            return view('purchase.create-second', compact('product', 'supplier'))->withErrors($data);
        }

        DB::beginTransaction();
        try {
            $request['staff_id'] = auth()->user()->staff->id;
            $purchase = Purchase::create([
                'invoice_number' => $request->invoice_number,
                'supplier_id' => $request->supplier_id,
                'staff_id' => $request->staff_id,
                'product_id' => $request->product_id,
                'unit_selling_price' => $request->unit_selling_price,
                'unit_buying_price' => $request->unit_buying_price,
                'payment_type' => $request->payment_type,
                'quantity' => $request->quantity,
                'description' => $request->description ?? null,
            ]);
            
            $totalAmount = $purchase->quantity * $purchase->unit_buying_price;

            $product = $purchase->product;
            $product->quantity += $purchase->quantity;
            $product->save();

            $payment = Payment::create([
                'total_amount' => $totalAmount,
                'model_type' => 'Purchase',
                'reference_id' => $purchase->id,
            ]);

            DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Purchase Created Successfully');
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e);
            return redirect()->route('purchases.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
