<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Helper\Operators;
use Exception;
use DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Order::with([
            'member',
            'art',
            'work_time',
            'review_order',
            'contact'
        ])->get();
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
            
            $order = Order::create($data);

            $order->contact()->createMany($data['contact']);

            $order->orderTaskList()->createMany($data['orderTaskList']);

            DB::commit();

            return response()->json([ 'data' => $order, 
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return $order->load([
            'member',
            'art',
            'work_time',
            'review_order',
            'contact'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $data = $request->all();

        try {
            DB::beginTransaction();
            
            if (array_key_exists('data', $data)) {
                $data = $data['data'];
            }
            if (array_key_exists('member_id', $data)) {
                $requests->member_id = $data['member_id'];
            }
            if (array_key_exists('art_id', $data)) {
                $requests->art_id = $data['art_id'];
            }
            if (array_key_exists('work_time_id', $data)) {
                $requests->work_time_id = $data['work_time_id'];
            }
            if (array_key_exists('start_date', $data)) {
                $requests->start_date = $data['start_date'];
            }
            if (array_key_exists('end_date', $data)) {
                $requests->end_date = $data['end_date'];
            }
            if (array_key_exists('province', $data)) {
                $requests->province = $data['province'];
            }
            if (array_key_exists('city', $data)) {
                $requests->city = $data['city'];
            }
            if (array_key_exists('address', $data)) {
                $requests->address = $data['address'];
            }
            if (array_key_exists('location', $data)) {
                $requests->location = $data['location'];
            }
            if (array_key_exists('remark', $data)) {
                $requests->remark = $data['remark'];
            }
            if (array_key_exists('status', $data)) {
                $requests->status = $data['status'];
            }

            if (array_key_exists('contact', $data)) {
                $order->contact()->delete();
                $order->contact()->createMany($data['contact']);
            }

            if (array_key_exists('orderTaskList', $data)) {
                $order->orderTaskList()->delete();
                $order->orderTaskList()->createMany($data['orderTaskList']);
            }

            $order->save();

            DB::commit();

            return response()->json([ 'data' => $order, 
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json([ 'message' => 'Deleted Success', 
                                  'status' => 200]);
    }

    /**
     * Search the specified resource from storage by parameter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @param  Parameter  $param
     * @param  Text  $text
     * @return \Illuminate\Http\Response
     */
    public function searchByParam(Request $request, Order $order, $param = 'info', $text)
    {
        return $order
            ->where($param,
                Operators::LIKE,
                '%'.$text.'%')
            ->get();
    }

    /**
     * Search the specified resource from storage by member.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @param  Parameter  $member
     * @return \Illuminate\Http\Response
     */
    public function getByMember(Request $request, Order $order, $member)
    {
        return $order
            ->where('member_id', $member)
            ->load([
                'member',
                'art',
                'work_time',
                'review_order',
                'contact'
            ])
            ->get();
    }

    /**
     * Search the specified resource from storage by ART.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @param  Parameter  $art
     * @return \Illuminate\Http\Response
     */
    public function getByArt(Request $request, Order $order, $art)
    {
        return $order
            ->where('art_id', $art)
            ->load([
                'member',
                'art',
                'work_time',
                'review_order',
                'contact'
            ])
            ->get();
    }
}
