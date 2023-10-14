<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staffs = Staff::orderBy('updated_at', 'DESC')->paginate(50);

        return view('staff.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('staff.create');
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
            'position' => 'required|string',
            'phone_number' => 'required|string',
            'salary' => 'required|integer',
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
            
            $user->staff()->create([
                'position' => $data['position'],
                'phone_number' => $data['phone_number'],
                'salary' => $data['salary'],
                'address' => $data['address'],
            ]);

            DB::commit();
            return redirect()->route('staffs.index')->with('success', 'Staff Created Successfully');
        } catch (\Exception $e) {

            DB::rollback();
            Log::error($e);
            return redirect()->route('staffs.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $staff)
    {
        return view('staff.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Staff $staff)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'position' => 'required|string',
            'phone_number' => 'required|string',
            'salary' => 'required|integer',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|string',
            'address' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $user = $staff->user()->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);


            $staff->update([
                'position' => $data['position'],
                'phone_number' => $data['phone_number'],
                'salary' => $data['salary'],
                'address' => $data['address'],
            ]);

            DB::commit();
            return redirect()->route('staffs.index')->with('success', 'Staff Updated Successfully');
        } catch (\Exception $e) {

            DB::rollback();
            Log::error($e);
            return redirect()->route('staffs.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy(Staff $staff)
    {
        DB::beginTransaction();
        try {
            $userId = $staff->user_id;
            $staff->delete();
            $user = User::find($userId);
            $user->delete();

            DB::commit();
            return redirect()->route('staffs.index')->with('success', 'Staff Deleted Successfully');
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e);
            return redirect()->route('staffs.index')->with('error', 'Something went wrong!');
        }
    }
}
