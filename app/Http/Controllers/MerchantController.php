<?php

namespace App\Http\Controllers;

use App\Merchant;
use Illuminate\Http\Request;
use Validator;

class MerchantController extends Controller
{
    public function index(Request $request)
    {
        $temp = Merchant::all();
        $merchants = $temp;
        if ('1' != $request->all) {
            $merchants = [];
            foreach ($temp as $key => $merchant) {
                if (count($merchant->items) > 0) {
                    array_push($merchants, $merchant);
                }
            }
        }

        return ['code' => '0', 'msg' => 'ok', 'result' => ['merchants' => $merchants]];
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'chName' => 'required',
            'mmName' => 'required',
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $is_existed_merchant = Merchant::where('name', $request->name)->exists();

        if ($is_existed_merchant) {
            return ['code' => '1', 'msg' => $request->name.' already exists'];
        }
        $merchant = Merchant::create([
            'name' => $request->name,
            'ch_name' => $request->chName,
            'mm_name' => $request->mmName,
        ]);

        return ['code' => '0', 'msg' => 'ok', 'result' => ['merchant' => $merchant]];
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
            'chName' => 'required',
            'mmName' => 'required',
        ]);

        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $is_existed_merchant = Merchant::where('name', $request->name)->exists();

        if ($is_existed_merchant) {
            return ['code' => '1', 'msg' => $request->name.' already exist'];
        }

        $merchant = Merchant::find($request->id);
        $merchant->name = $request->name;
        $merchant->ch_name = $request->chName;
        $merchant->mm_name = $request->mmName;
        $merchant->save();

        return ['code' => '0', 'msg' => 'ok', 'result' => ['merchant' => $merchant]];
    }
}
