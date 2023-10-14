<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveries = Delivery::orderBy('updated_at', 'DESC')->paginate(50);

        return view('delivery.index', compact('deliveries'));
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
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function show(Delivery $delivery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function edit(Delivery $delivery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Delivery $delivery)
    {
        $data = $request->validate([
            'delivery_status' => 'required|in:pending,delivered',
            'delivery_cost' => 'required|integer',
            'deli_description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $delivery->status = $data['delivery_status'];
            $delivery->description = $data['description'] ?? null;
            $delivery->save();

            $payment = Payment::updateOrCreate([
                'model_type' => 'Delivery',
                'reference_id' => $delivery->id,
            ], [
                'total_amount' => $data['delivery_cost'],
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Successfully updated delivery.');

        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e);
            return redirect()->route('sales.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Delivery $delivery)
    {
        //
    }
}
