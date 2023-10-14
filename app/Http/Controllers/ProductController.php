<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('updated_at', 'DESC')->paginate(50);

        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::get();
        $brands = Brand::get();

        return view('product.create', compact('categories', 'brands'));
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
            'code' => 'required|string',
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'unit_selling_price' => 'required|integer',
            'unit_buying_price' => 'required|integer',
            'minimum_required_quantity' => 'required|integer',
            'status' => 'required|in:in_stock,out_of_stock',
            'color' => 'required|string',
            'image' => 'file|mimes:png,jpg,gif|max:35840',
        ]);

        DB::beginTransaction();
        try {
            if (!empty($data['image']) and $request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images/products'), $imageName);
                $data['image'] = $imageName;
            }

            $data['quantity'] = 0;
            $product = Product::create($data);

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product Created Successfully');
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e);
            return redirect()->route('products.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::get();
        $brands = Brand::get();

        return view('product.edit', compact('product', 'brands', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'unit_selling_price' => 'required|integer',
            'unit_buying_price' => 'required|integer',
            'minimum_required_quantity' => 'required|integer',
            'status' => 'required|in:in_stock,out_of_stock',
            'color' => 'required|string',
            'image' => 'nullable|file|mimes:png,jpg,gif|max:35840',
        ]);

        DB::beginTransaction();
        try {
            if (!empty($data['image']) and $request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images/products'), $imageName);
                $data['image'] = $imageName;
            }

            $data['quantity'] = 0;
            $product = $product->update($data);

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product Updated Successfully');
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e);
            return redirect()->route('products.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();

            return redirect()->route('products.index')->with('success', 'Products Deleted Successfully');
        } catch (\Exception $e) {

            Log::error($e);
            return redirect()->route('products.index')->with('error', 'Something went wrong!');
        }
    }

    public function addToCart(Request $request)
    {
        DB::beginTransaction();
        try {

            $user = auth()->user();

            if (empty($user->customer)) {
                return response()->json([
                    'message' => 'Customer not found!',
                    'status' => 'fail',
                ], 200);
            }

            $product = Product::find($request->product_id);
            $product->quantity -= $request->qty;
            $product->save();

            if (!$product) {
                return response()->json([
                    'message' => 'Product not found!',
                    'status' => 'fail',
                ], 200);
            }

            $cart = Cart::where('customer_id', $user->customer->id)->where('status', 'pending')->first();
            if (!empty($cart)) {
                $cart->total_amount += ($product->unit_selling_price * $request->qty);
                $cart->save();
            } else {
                $cart = Cart::create([
                    'customer_id' => $user->customer->id,
                    'total_amount' => $product->unit_selling_price * $request->qty,
                    'status' => 'pending',
                ]);
            }

            $cart->products()->attach($request->product_id, ['quantity' => $request->qty]);

            $count = $cart->productCarts->sum('quantity');

            DB::commit();

            return response()->json([
                'product_id' => $request->product_id,
                'count' => $count,
                'qty' => $product->quantity,
                'message' => 'Added To Cart',
                'status' => 'success',
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e);
            return response()->json([
                'message' => 'Something went wrong!',
                'status' => 'fail',
            ], 200);
        }
    }

    public function removeFromCart(Request $request) {

        $productCart = ProductCart::find($request->product_cart_id);
        if (!$productCart) {
            return response()->json([
                'message' => 'Product not found!',
                'status' => 'fail',
            ], 200);
        }

        DB::beginTransaction();
        try {
            $cart = $productCart->cart;
            $product = $productCart->product;
            
            $cart->total_amount -= ($product->unit_selling_price * $productCart->quantity);
            $cart->save();
    
            $product->quantity += $productCart->quantity;
            $product->save();
            
            $productCart->delete();

            DB::commit();
    
            return response()->json([
                'product_cart_id' => $request->product_cart_id,
                'total_amount' => $cart->total_amount,
                'total_quantity' => $cart->productCarts->sum('quantity'),
                'message' => 'Removed From Cart',
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e);
            return response()->json([
                'message' => 'Something went wrong!',
                'status' => 'fail',
            ], 200);
        }
    }
}
