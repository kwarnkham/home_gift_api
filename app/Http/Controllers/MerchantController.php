<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Merchant;
use Validator;

class MerchantController extends Controller
{
    
    public function index()
    {
        $merchants = Merchant::all();
        return ['code' => '0', 'msg' => 'ok', 'result' => ["merchants" => $merchants]];
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $is_existed_merchant = Merchant::where('name', $request->name)->exists();

        if ($is_existed_merchant) {
            return ['code' => '1', 'msg' => $request->name . ' already exists'];
        }
        $merchant = Merchant::create([
            'name' => $request->name
        ]);


        return ['code' => '0', 'msg' => 'ok', 'result' => ["merchant" => $merchant]];
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $is_existed_merchant = Merchant::where('name', $request->name)->exists();

        if ($is_existed_merchant) {
            return ['code' => '1', 'msg' => $request->name . ' already exist'];
        }

        $merchant = Merchant::find($request->id);
        $merchant->name = $request->name;
        $merchant->save();

        return ['code' => '0', 'msg' => 'ok', 'result' => ["merchant" => $merchant]];
    }
}
