<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class DeliveryFeesController extends Controller
{
    public function find()
    {
        $fees = DB::table('delivery_fees')
            ->join('active_delivery_fees', 'delivery_fees.id', 'active_delivery_fees.delivery_fees_id')
            ->where('active_delivery_fees.id', 1)
            ->select('delivery_fees.*')
            ->first()
        ;

        return ['code' => '0', 'msg' => 'ok', 'result' => ['deliveryFees' => $fees]];
    }

    public function index()
    {
        $fees = DB::table('delivery_fees')->get();

        return ['code' => '0', 'msg' => 'ok', 'result' => ['deliveryFees' => $fees]];
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        DB::table('active_delivery_fees')->where('id', 1)->update(['delivery_fees_id' => $request->id]);
        $fees = DB::table('delivery_fees')
            ->join('active_delivery_fees', 'delivery_fees.id', 'active_delivery_fees.delivery_fees_id')
            ->where('active_delivery_fees.id', 1)
            ->select('delivery_fees.*')
            ->first()
        ;

        return ['code' => '0', 'msg' => 'ok', 'result' => ['deliveryFees' => $fees]];
    }
}
