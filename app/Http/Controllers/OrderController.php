<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Order;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'payment' => 'required',
            'amount' => 'required',
            'items' => 'required',
        ]);

        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $user_id = Auth::user()->id;
        $inputData = $request->only('name', 'mobile', 'address', 'payment', 'amount');
        if ($request->c_note != null) {
            $inputData['c_note'] = $request->c_note;
        }
        $inputData['user_id'] = $user_id;
        $inputData['delivery_fees']= DB::table('delivery_fees')
        ->join('active_delivery_fees', 'delivery_fees.id', 'active_delivery_fees.delivery_fees_id')
        ->where('active_delivery_fees.id', 1)
        ->select('delivery_fees.*')
        ->first()->fees;
        $order = Order::create($inputData);
        $items = json_decode($request->items);
        foreach ($items as $cartItem) {
            $order->items()->attach($cartItem->id, [
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->price,
            ]);
        }
        $order->refresh();
        return ['code' => '0', 'msg' => 'ok', 'result' => ['order' => $order]];
    }

    public function userOrder()
    {
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return ['code' => '0', 'msg' => 'ok', 'result' => ['orders' => $orders]];
    }

    public function index()
    {
        $orders = Order::all();
        return ['code' => '0', 'msg' => 'ok', 'result' => $orders];
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => [
                'required',
                Rule::in(['confirmed', 'on the way', 'delivered', 'canceled']),
            ],
        ]);

        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $order = Order::find($id);
        $invalidAction = false;

        if ($order->status == 'delivered' || $order->status == 'canceled') {
            $invalidAction = true;
        }

        if ($order->status == 'on the way') {
            if ($request->action == 'delivered' || $request->action == 'canceled') {
                $order->status = $request->action;
                $order->save();
            } else {
                $invalidAction = true;
            }
        }

        if ($order->status == 'confirmed') {
            if ($request->action == 'on the way' || $request->action == 'canceled') {
                $order->status = $request->action;
                $order->save();
            } else {
                $invalidAction = true;
            }
        }

        if ($order->status == 'pending') {
            if ($request->action == 'confirmed' || $request->action == 'canceled') {
                $order->status = $request->action;
                $order->save();
            } else {
                $invalidAction = true;
            }
        }

        if ($invalidAction) {
            return ['code' => '1', 'msg' => 'Invalid Action'];
        }

        return ['code' => '0', 'msg' => 'ok',];
        // return [$request, 'id' => $id];
    }
}
