<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'image' => 'nullable|file|mimes:png,jpg,gif|max:35840',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|string',
            'position' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $user = auth()->user();
        DB::beginTransaction();

        try {
            if (!empty($data['image']) and $request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images/profiles'), $imageName);
                $data['image'] = $imageName;
            }

            $user->email = $data['email'];
            $user->name = $data['name'];
            $user->image = $data['image'];
            $user->save();

            if  (getUserType($user) === 'staff') {
                
                $staff = $user->staff;
                $staff->position = $data['position'];
                $staff->address = $data['address'];
                $staff->phone_number = $data['phone_number'];
                $staff->save();
            } elseif (getUserType($user) === 'customer') {

                $customer = $user->customer;
                $customer->phone_number = $data['phone_number'];
                $customer->address = $data['address'];
                $customer->save();
            }
            
            DB::commit();
            return redirect()->route('home')->with('success', 'Successfully updated your profile.');
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e);
            return redirect()->route('home')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function profile()
    {
        $user = auth()->user();
        $userType = getUserType($user);

        return view('user.profile', compact('user', 'userType'));
    }
}
