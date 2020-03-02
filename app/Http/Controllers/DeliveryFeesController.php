<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class DeliveryFeesController extends Controller
{
    public function find()
    {
        $fees= DB::table('delivery_fees')
        ->join('active_delivery_fees', 'delivery_fees.id', 'active_delivery_fees.delivery_fees_id')
        ->where('active_delivery_fees.id', 1)
        ->select('delivery_fees.*')
        ->first();
        return response()->json($fees);
    }
}
