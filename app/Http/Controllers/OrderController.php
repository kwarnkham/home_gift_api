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
            'items' => 'required'
        ]);

        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $user_id = Auth::user()->id;

        $inputData = $request->only('name', 'mobile', 'address', 'payment', 'delivery_fees', 'amount');
        $inputData['user_id']=$user_id;
        // $order = Order::create($inputData);

        // return $order;
        return $request->items;
    }

    public function index(Request $request){
        $orders=Order::where('user_id',$request->userId)->get();
        return $orders;
    }
}
