<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::orderBy('updated_at', 'DESC')->paginate(50);

        return view('supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('supplier.create');
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
            'company_name' => 'required|string',
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
            
            $user->supplier()->create([
                'company_name' => $data['company_name'],
                'phone_number' => $data['phone_number'],
                'address' => $data['address'],
            ]);

            DB::commit();
            return redirect()->route('suppliers.index')->with('success', 'Supplier Created Successfully');
        } catch (\Exception $e) {

            DB::rollback();
            Log::error($e);
            return redirect()->route('suppliers.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $supplier->user->id,
            'company_name' => 'required|string',
            'phone_number' => 'required|string',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|string',
            'address' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $user = $supplier->user()->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            
            $supplier->update([
                'company_name' => $data['company_name'],
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
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        DB::beginTransaction();
        try {
            $userId = $supplier->user_id;
            $supplier->delete();
            $user = User::find($userId);
            $user->delete();

            DB::commit();
            return redirect()->route('suppliers.index')->with('success', 'Supplier Deleted Successfully');
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e);
            return redirect()->route('suppliers.index')->with('error', 'Something went wrong!');
        }
    }
}
