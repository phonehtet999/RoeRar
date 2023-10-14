<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Staff;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\Pure;

class DashboardController extends Controller
{
    public function home(Request $request)
    {
        $userType = getUserType(auth()->user());
        if ($userType == 'customer') {
            $user = auth()->user();
            $products = Product::when(!empty($request->category_id), function ($query) use ($request) {
                                return $query->where('category_id', $request->category_id);
                            })
                            ->orderBy('updated_at', 'desc')
                            ->paginate(8);

            $cart = Cart::firstOrCreate(
                ['status' => 'pending', 'customer_id' => $user->customer->id],
                ['total_amount' => 0]
            );
            
            $count = $cart->productCarts->sum('quantity');

            return view('customer-dashboard', compact('products', 'count', 'cart'));
        } else if ($userType == 'staff') {

            $customerCount = Customer::count();
            $staffCount = Staff::count();
            $supplierCount = Supplier::count();
            $brandCount = Brand::count();
            $categoryCount = Category::count();
            
            $thisMonthSale = Sale::whereMonth('date', date('m'))
                                    ->whereYear('date', date('Y'))
                                    ->where('status', '<>', 'ordered')
                                    ->sum('total_amount');

            $thisMonthPendingOrder = Sale::whereMonth('date', date('m'))
                                    ->whereYear('date', date('Y'))
                                    ->where('status', 'ordered')
                                    ->sum('total_amount');

            $thisMonthPurchase = Purchase::whereMonth('created_at', date('m'))
                                            ->whereYear('created_at', date('Y'))
                                            ->select(DB::raw('sum(unit_buying_price * quantity) as total_purchase'))
                                            ->first();

            $saleOverViews = Sale::where('status', '<>', 'ordered')
                                    ->selectRaw('year(date) as year, monthname(date) as month, sum(total_amount) as total_sale')
                                    ->groupBy('year', 'month')
                                    ->pluck('total_sale', 'month')
                                    ->toArray();

            $purchaseOverViews = Purchase::selectRaw('year(created_at) as year, monthname(created_at) as month, sum(unit_buying_price * quantity) as total_purchase')
                                        ->groupBy('year', 'month')
                                        ->pluck('total_purchase', 'month')
                                        ->toArray();

            // dd($saleOverViews);
            return view('staff-dashboard', compact(
                'customerCount',
                'staffCount',
                'supplierCount',
                'brandCount',
                'categoryCount',
                'thisMonthSale',
                'thisMonthPurchase',
                'thisMonthPendingOrder',
                'saleOverViews',
                'purchaseOverViews',
            ));
        } else {

            return view('customer-dashboard');
        }
    }
}
