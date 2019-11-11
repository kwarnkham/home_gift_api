<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Location;

class LocationController extends Controller
{

    public function index()
    {
        $locations = Location::all();
        return ['code' => '0', 'msg' => 'ok', 'result' => ['locations' => $locations]];
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'province_id' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $is_existed_location = Location::where('name', $request->name)->exists();

        if ($is_existed_location) {
            return ['code' => '1', 'msg' => $request->name . ' already exists'];
        }

        $location = Location::create([
            'name' => $request->name,
            'province_id' => $request->province_id
        ]);

        return ['code' => '0', 'msg' => 'ok', 'result' => ['location' => $location]];
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
            'province_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $is_existed_location = Location::where('name', $request->name)->where('id', '!=', $request->id)->exists();

        if ($is_existed_location) {
            return ['code' => '1', 'msg' => $request->name . ' already exists'];
        }
        $location = Location::find($request->id);
        $location->name = $request->name;
        $location->province_id = $request->province_id;
        $location->save();

        return ['code' => '0', 'msg' => 'ok', 'result' => ['location' => $location]];
    }
}
