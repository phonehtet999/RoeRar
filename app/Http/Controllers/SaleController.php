<?php

namespace App\Http\Controllers;

use App\Models\BankAcount;
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

        $bankAccount = BankAcount::where('account_name', $data['account_name'])
                                ->where('account_number', $data['account_number'])
                                ->first();

        if (empty($bankAccount)) {
            return redirect()->back()->with('error', 'Account not found');
        }

        $customer = auth()->user()->customer;
        $productsToOrder = ProductCart::where('cart_id', $data['cart_id'])
                                ->whereIn('id', $data['product_cart_ids'])
                                ->groupBy('product_id')
                                ->selectRaw('product_id, sum(quantity) as quantity')
                                ->get()->toArray();

        $cart = Cart::find($data['cart_id']);

        $today = date('Y-m-d');

        $totalAmount = 0;
        foreach ($productsToOrder as $key => $product) {
            $prd = Product::find($product['product_id']);

            $promotion = $prd->promotions()->where('status', 1)
                            ->where('remaining_quantity', '>', 0)
                            ->where(function ($query) use ($today) {
                                return $query->where('date_from', null)
                                    ->orWhere('date_from', '<=', $today);
                            })
                            ->where(function ($query) use ($today) {
                                return $query->where('date_to', null)
                                    ->orwhere('date_to', '>=', $today);
                            })
                            ->first();

            $promotedAmount = 0;
            $promotedQty = 0;
            if (!empty($promotion)) {
                $promotedQty = min($product['quantity'], $promotion->remaining_quantity);
                $promotedAmount = $promotedQty * $promotion->amount_per_unit;

                $promotion->remaining_quantity -= $promotedQty;
            }

            $totalPrice = ($prd->unit_selling_price * $product['quantity']) - $promotedAmount;
            $productsToOrder[$key]['total_price'] = $totalPrice;
            $productsToOrder[$key]['total_promoted_amount'] = $promotedAmount;
            $productsToOrder[$key]['total_promoted_qty'] = $promotedQty;
            $productsToOrder[$key]['unit_price'] = $prd->unit_selling_price;
            $productsToOrder[$key]['promotion_id'] = $promotion->id ?? null;
            $totalAmount += $totalPrice;
        }

        DB::beginTransaction();
        try {

            if (!empty($promotion)) {
                $promotion->save();
            }

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
                    'total_promoted_amount' => $product['total_promoted_amount'],
                    'total_promoted_qty' => $product['total_promoted_qty'],
                    'unit_price' => $product['unit_price'],
                    'promotion_id' => $product['promotion_id'],
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
