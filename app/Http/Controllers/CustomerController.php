<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::orderBy('updated_at', 'DESC')->paginate(50);

        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer.create');
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
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|string',
            'address' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            
            $user->customer()->create([
                'phone_number' => $data['phone_number'],
                'address' => $data['address'],
            ]);

            DB::commit();
            return redirect()->route('customers.index')->with('success', 'Customer Created Successfully');
        } catch (\Exception $e) {

            DB::rollback();
            Log::error($e);
            return redirect()->route('customers.index')->with('error', 'Something went wrong!');
        };
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $customer->user->id,
            'phone_number' => 'required|string',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|string',
            'address' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $user = $customer->user()->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            
            $customer->update([
                'phone_number' => $data['phone_number'],
                'address' => $data['address'],
            ]);

            DB::commit();
            return redirect()->route('suppliers.index')->with('success', 'Supplier Updated Successfully');
        } catch (\Exception $e) {

            DB::rollback();
            Log::error($e);
            return redirect()->route('suppliers.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        DB::beginTransaction();
        try {
            $userId = $customer->user_id;
            $customer->delete();
            $user = User::find($userId);
            $user->delete();

            DB::commit();
            return redirect()->route('customers.index')->with('success', 'Customer Deleted Successfully');
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e);
            return redirect()->route('customers.index')->with('error', 'Something went wrong!');
        }
    }
}
