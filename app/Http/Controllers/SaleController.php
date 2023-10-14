<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $sales = Sale::orderBy('updated_at', 'DESC')
                        ->when((getUserType($user) == 'customer'), function ($query) use ($user) {
                            return $query->where('customer_id', $user->customer->id);
                        })
                        ->paginate(50);

        return view('sale.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'product_cart_ids' => 'array|required',
            'product_cart_ids.*' => 'required|exists:product_carts,id',
            'address' => 'required|string',
            'cart_id' => 'required|exists:carts,id',
            'account_name' => 'required|string',
            'account_number' => 'required|string',
        ]);

        $customer = auth()->user()->customer;
        $productsToOrder = ProductCart::where('cart_id', $data['cart_id'])
                                ->whereIn('id', $data['product_cart_ids'])
                                ->groupBy('product_id')
                                ->selectRaw('product_id, sum(quantity) as quantity')
                                ->get()->toArray();

        $cart = Cart::find($data['cart_id']);

        $totalAmount = 0;
        foreach ($productsToOrder as $key => $product) {
            $prd = Product::find($product['product_id']);
            $totalPrice = $prd->unit_selling_price * $product['quantity'];
            $productsToOrder[$key]['total_price'] = $totalPrice;
            $productsToOrder[$key]['unit_price'] = $prd->unit_selling_price;
            $totalAmount += $totalPrice;
        }

        DB::beginTransaction();
        try {

            $sale = Sale::create([
                'customer_id' => $customer->id,
                'date' => date('Y-m-d H:i:s'),
                'total_amount' => $totalAmount,
                'status' => 'ordered',
                'description' => null,
            ]);
    
            $sale->saleDetails()->createMany(array_map(function ($product) {
                return [
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'total_amount' => $product['total_price'],
                    'unit_price' => $product['unit_price'],
                ];
            }, $productsToOrder));

            $sale->delivery()->create([
                'address' => $request->address,
            ]);

            $payment = Payment::create([
                'total_amount' => $totalAmount,
                'model_type' => 'Sale',
                'reference_id' => $sale->id,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
            ]);

            $cart->status = 'ordered';
            $cart->save();

            DB::commit();
            return redirect()->route('home')->with('success', 'Ordered Your Products Successfully');

        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e);
            return redirect()->route('home')->with('error', 'Something went wrong!');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        return view('sale.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        return view('sale.edit', compact('sale'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $sale)
    {
        $data = $request->validate([
            'order_status' => 'required|in:ordered,approved,delivered',
            'description' => 'nullable|string',
        ]);

        try {
            $sale->status = $data['order_status'];
            $sale->description = $data['description'] ?? null;
            $sale->save();

            return redirect()->back()->with('success', 'Successfully updated sale.');

        } catch (\Exception $e) {

            Log::error($e);
            return redirect()->route('sales.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        //
    }
}