<?php

namespace App\Http\Controllers;

use App\Image;
use App\Item;
use Illuminate\Http\Request;
use Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();
        return ['code' => '0', 'msg' => 'ok', 'result' => $items->load('categories', 'images', 'location', 'merchant')];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'notice' => 'required',
            'merchant_id' => 'required',
            'location_id' => 'required',
            'category_id' => 'required',
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $item = Item::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'notice' => $request->notice,
            'merchant_id' => $request->merchant_id,
            'location_id' => $request->location_id,
        ]);

        $item->categories()->attach($request->category_id); //array of categories

        Image::create([
            'name' => $request->image,
            'item_id' => $item->id,
        ]);

        return ['code' => '0', 'msg' => 'ok', 'result' => $item->load('categories', 'images', 'location', 'merchant')];
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
    public function update(Request $request, $id)
    {
        //
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
