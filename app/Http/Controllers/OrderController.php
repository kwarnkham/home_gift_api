<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Order;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'payment' => 'required',
            'delivery_fees' => 'required',
            'amount' => 'required',
            'items' => 'required',
        ]);

        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $user_id = Auth::user()->id;
        $inputData = $request->only('name', 'mobile', 'address', 'payment', 'delivery_fees', 'amount');
        if ($request->c_note != null) {
            $inputData['c_note'] = $request->c_note;
        }
        $inputData['user_id'] = $user_id;
        $order = Order::create($inputData);
        $items = json_decode($request->items);
        foreach ($items as $cartItem) {
            // $order->items()->attach($cartItem->item->id, [
            //     'name' => $cartItem->item->name,
            //     'quantity' => $cartItem->quantity,
            //     'price' => $cartItem->item->price,
            //     'description' => $cartItem->item->description,
            //     'notice' => $cartItem->item->notice,
            //     'weight' => $cartItem->item->weight,
            //     'location' => $cartItem->item->location,
            //     'merchant' => $cartItem->item->merchant
            // ]);
            $order->items()->attach($cartItem->item->id, [
                'name' => $cartItem->item->name,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->item->price,
                'description' => $cartItem->item->description,
                'notice' => $cartItem->item->notice,
                'weight' => $cartItem->item->weight,
                'location' => $cartItem->item->location->name,
                'merchant' => $cartItem->item->merchant->name
            ]);
        }
        $order = Order::find($order->id);
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
