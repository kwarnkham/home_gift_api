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
            'city' => "required",
            'township'=>"required",
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
        $inputData['address'] = $inputData['address']." / $request->city / $request->township";
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

    public function getOrderRule(Request $request)
    {
        $rule = "";
        if ($request->lang =='en_US') {
            $rule= "Order made between 9am and 3pm will be delivered in same day within 3pm to 6pm. Order made after 3pm will be delivered next day 9am to 12 noon.";
        } elseif ($request->lang == 'my') {
            $rule= "နေ့စဉ်ပစ္စည်းအော်တာလက်ခံချိန်နံနက်(၉)နာရီမှညနေ့(၃)နာရီ။ နေ့စဉ်ပစ္စည်းပို့ဆောင်ချိန်ညနေ့(၃)နာရီမှ(၆)နာရီ။ (၃)နာရီနောက်ပိုင်းအော်တာများကိုနောက်တစ်နေ့နံနက်(၉)နာရီမှနေ့လည်(၁၂)နာရီအတွင်းပို့ဆောင်ပေးပါမည်။";
        } else {
            $rule = "每天接订单时间是早上9:00至下午3:00、送货时间是下午3:00至6:00.下午3:00后下的单，我们会在第二天早上9:00至中午12:00送达.";
        }
        return $rule;
    }
}
