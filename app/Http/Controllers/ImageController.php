<?php

namespace App\Http\Controllers;

use App\Image;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required|array',
            'files.*' => 'image',
            'item_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        if ('null' == $request->item_id) {
            return ['code' => '1', 'msg' => 'Item ID is empty'];
        }

        foreach ($request->file('files') as $image) {
            // $saveImage = basename(Storage::putFile('public/item_images', $image));
            $saveImage = basename(Storage::disk('spaces')->putFile('item_images', $image));
            Image::create(['name' => $saveImage, 'item_id' => $request->item_id]);
        }
        $items = Item::all();

        return ['code' => '0', 'msg' => 'ok', 'result' => $items];
    }

    public function destroy($id)
    {
        $image = Image::find($id);
        // Storage::delete($image->name);
        // $image->delete();
        if (Storage::disk('spaces')->delete('item_images/'.$image->name)) {
            $image->delete();
        }
        $items = Item::all();

        return ['code' => '0', 'msg' => 'ok', 'result' => $items];
    }
}
