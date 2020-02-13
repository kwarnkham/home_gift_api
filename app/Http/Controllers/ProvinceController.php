<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Province;
use Validator;

class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = Province::all();
        return ['code' => '0', 'msg' => 'ok', 'result' => ['provinces' => $provinces->load('locations')]];
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

        $is_existed_location = Province::where('name', $request->name)->exists();

        if ($is_existed_location) {
            return ['code' => '1', 'msg' => $request->name . ' already exists'];
        }

        $location = Province::create([
            'name' => $request->name,
            'ch_name' => $request->chName,
            'mm_name' => $request->mmName,
        ]);

        return ['code' => '0', 'msg' => 'ok', 'result' => ['location' => $location]];
    }
}
