<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Helper\Operator;
use Exception;
use DB;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Offer::all();
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
        $data = $request->all();
        
        try {
            if (array_key_exists('data', $data)) {
                $data = $data['data'];
            }

            DB::beginTransaction();
            
            $offer = Offer::create($data);

            $offer->orderTaskList()->createMany($data['orderTaskList']);

            DB::commit();

            return response()->json([ 'data' => $offer, 
                                      'status' => 201]);
        }
        catch(Exception $e) {
            DB::rollBack();

            return response()->json([ 'message' => $e->getMessage(), 
                                      'status' => 400 ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        return $offer;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function edit(Offer $offer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Offer $offer)
    {
        $data = $request->all();

        try {
            DB::beginTransaction();

            if (array_key_exists('data', $data)) {
                $data = $data['data'];
            }
            if (array_key_exists('member_id', $data)) {
                $offer->member_id = $data['member_id'];
            }
            if (array_key_exists('work_time_id', $data)) {
                $offer->work_time_id = $data['work_time_id'];
            }
            if (array_key_exists('start_date', $data)) {
                $offer->start_date = $data['start_date'];
            }
            if (array_key_exists('end_date', $data)) {
                $offer->end_date = $data['end_date'];
            }
            if (array_key_exists('province', $data)) {
                $offer->province = $data['province'];
            }
            if (array_key_exists('city', $data)) {
                $offer->city = $data['city'];
            }
            if (array_key_exists('address', $data)) {
                $offer->address = $data['address'];
            }
            if (array_key_exists('location', $data)) {
                $offer->location = $data['location'];
            }
            if (array_key_exists('remark', $data)) {
                $offer->remark = $data['remark'];
            }
            if (array_key_exists('status', $data)) {
                $offer->status = $data['status'];
            }

            $offer->save();

            $offer->orderTaskList()->delete();
            $offer->orderTaskList()->createMany($data['orderTaskList']);

            DB::commit();

            return response()->json([ 'data' => $offer, 
                                      'status' => 200]);
        }
        catch(Exception $e) {
            DB::rollBack();

            return response()->json([ 'message' => $e->getMessage(), 
                                      'status' => 400 ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();

        return response()->json([ 'message' => 'Deleted', 
                                  'status' => 200]);
    }

    /**
     * Search the specified resource from storage by parameter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Offer  $offer
     * @param  Parameter  $param
     * @param  Text  $text
     * @return \Illuminate\Http\Response
     */
    public function searchByParam(Request $request, Offer $offer, $param = 'info', $text)
    {
        return $offer
            ->where($param,
                Operator::LIKE,
                '%'.$text.'%')
            ->get();
    }
}