<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Location;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::all();
        return ['code' => '0', 'msg' => 'ok', 'result' => ['locations' => $locations]];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $is_existed_location = Location::where('name', $request->name)->exists();

        if ($is_existed_location) {
            return ['code' => '1', 'msg' => $request->name . ' already exists'];
        }

        $location = Location::create([
            'name' => $request->name
        ]);

        return ['code' => '0', 'msg' => 'ok', 'result' => ['location' => $location]];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $is_existed_location = Location::where('name', $request->name)->exists();

        if ($is_existed_location) {
            return ['code' => '1', 'msg' => $request->name . ' already exists'];
        }
        $location = Location::find($request->id);
        $location->name = $request->name;
        $location->save();

        return ['code' => '0', 'msg' => 'ok', 'result' => ['location' => $location]];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
