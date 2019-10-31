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
        return ['code' => '0', 'msg' => 'ok', 'result' => ['items' => $items]];
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
            'weight' => 'required|numeric',
            'merchant_id' => 'required',
            'location_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $alreadyExisted = Item::where('name', $request->name)->exists();

        if ($alreadyExisted) {
            return ['code' => '1', 'msg' => "Item name: $request->name already existed"];
        }

        $item = Item::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'notice' => $request->notice,
            'weight' => $request->weight,
            'merchant_id' => $request->merchant_id,
            'location_id' => $request->location_id,
        ]);

        return ['code' => '0', 'msg' => 'ok', 'result' => ['item' => $item]];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { }

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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'notice' => 'required',
            'weight' => 'required|numeric',
            'merchant_id' => 'required',
            'location_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $existed = Item::where('name', $request->name)->where('id', '!=', $id)->exists();

        if ($existed) {
            return ['code' => '1', 'msg' => 'Item already exist'];
        }
        $item = Item::find($id);
        $item->name = $request->name;
        $item->price = $request->price;
        $item->description = $request->description;
        $item->notice = $request->notice;
        $item->weight = $request->weight;
        $item->merchant_id = $request->merchant_id;
        $item->location_id = $request->location_id;
        $item->save();
        $item->refresh();
        return ['code' => '0', 'msg' => 'ok', 'result' => ['item' => $item]];
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

    public function addCategory($id, $category_id)
    {
        $item = Item::find($id);
        $item->categories()->attach($category_id);
        $item->refresh();
        return ['code' => '0', 'msg' => 'ok', 'result' => ['item' => $item]];
    }

    public function removeCategory($id, $category_id)
    {
        $item = Item::find($id);
        $item->categories()->detach($category_id);
        $item->refresh();
        return ['code' => '0', 'msg' => 'ok', 'result' => ['item' => $item]];
    }

    public function updateCategory($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categories' => 'required|array',
        ]);

        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }
        $item = Item::find($id);
        $item->categories()->sync($request->categories);
        $item->refresh();
        return ['code' => '0', 'msg' => 'ok', 'result' => ['item' => $item]];
    }

    public function updateName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'itemId' => 'required'
        ]);

        if ($request->itemId == 'null') {
            return ['code' => '1', 'msg' => 'Item ID is missing'];
        }

        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $item = Item::find($request->itemId);

        $item->name = $request->name;

        if (!$item->save()) {
            ['code' => '1', 'msg' => 'Cannot update item name'];
        }

        return ['code' => '0', 'msg' => 'ok', 'result' => ['item' => $item]];
    }
}
