<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Order;

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
        foreach ($items as $item) {
            $order->items()->attach($item->item->id, [
                'name' => $item->item->name,
                'quantity' => $item->quantity,
                'price' => $item->item->price,
                'description' => $item->item->description,
                'notice' => $item->item->notice,
                'weight' => $item->item->weight,
                'location_id' => $item->item->location->id,
                'merchant_id' => $item->item->merchant->id
            ]);
        }
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'asc')->get();
        return ['code' => '0', 'msg' => 'ok', 'result' => $orders];
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return ['code' => '0', 'msg' => 'ok', 'result' => $orders];
    }
}
