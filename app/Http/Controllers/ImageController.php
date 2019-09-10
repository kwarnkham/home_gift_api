<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Item;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'image' => 'required',
            'item_id' => 'required|numeric',
            'image_name' => 'required',
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        if($request->item_id == 'null'){
            return ['code' => '1', 'msg' => 'Item ID is empty'];
        }
        // $image= base64_decode($request->image);
        // file_put_contents($request->image_name ,$image); //in public
        // $saveImage = basename(Storage::putFile('public/item_images', new File($request->image_name)));
        // unlink($request->image_name);
        // Image::create(['name'=>$saveImage, 'item_id'=>$request->item_id]);
        $items= Item::all();
        return ['code' => '0', 'msg' => 'ok', 'result' => $items->load('categories', 'images', 'location', 'merchant')];
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
        $image = Image::find($id);
        Storage::delete($image->name);
        $image->delete();
        $items= Item::all();
        return ['code' => '0', 'msg' => 'ok', 'result' => $items->load('categories', 'images', 'location', 'merchant')];
    }
}
