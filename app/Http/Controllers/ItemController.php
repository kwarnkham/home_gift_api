<?php

namespace App\Http\Controllers;

use App\Image;
use App\Item;
use App\Category;
use App\Location;
use Illuminate\Http\Request;
use Validator;

class ItemController extends Controller
{
    public function findByCategory(Request $request)
    {
        $category = Category::where('id', $request->categoryId)->first();
        if ($category) {
            return ['code' => '0', 'msg' => 'ok', 'result' => ['items' => $category->items->paginate(1)]];
        } else {
            return ['code' => '1', 'msg' => 'category does not exist'];
        }
    }

    public function findByLocation(Request $request)
    {
        $location = Location::where('id', $request->locationId)->first();
        if ($location) {
            return ['code' => '0', 'msg' => 'ok', 'result' => ['items' => $location->items]];
        } else {
            return ['code' => '1', 'msg' => 'location does not exist'];
        }
    }
    
    public function index(Request $request)
    {
        $items = Item::paginate($request->per_page);
        if ($request->withTrash == 'true') {
            $items = Item::withTrashed()->paginate($request->per_page);
        }
        return ['code' => '0', 'msg' => 'ok', 'result' => ['items' => $items]];
    }

    public function indexTrashed(Request $request)
    {
        $items = Item::onlyTrashed()->paginate($request->per_page);
        return ['code' => '0', 'msg' => 'ok', 'result' => ['items' => $items]];
    }

    public function unDestroy($id)
    {
        $item = Item::withTrashed()->where('id', $id)->first();
        if ($item->restore()) {
            return ['code' => '0', 'msg' => 'ok', 'result' => ['item' => $item]];
        }
        return ['code' => '1', 'msg' => 'restore failed'];
    }

    public function find(Request $request)
    {
        $item = Item::where('id', $request->id)->withTrashed()->first();
        if ($item) {
            return ['code' => '0', 'msg' => 'ok', 'result' => ['item' => $item]];
        } else {
            return ['code' => '1', 'msg' => 'item does not exist'];
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'ch_name'=>"required",
            'price' => 'required|numeric',
            'description' => 'required',
            'ch_description'=>"required",
            'weight' => 'required',
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

        $alreadyExisted = Item::where('ch_name', $request->ch_name)->exists();

        if ($alreadyExisted) {
            return ['code' => '1', 'msg' => "Item name: $request->name already existed"];
        }

        $item = Item::create([
            'name' => $request->name,
            'ch_name' => $request->ch_name,
            'price' => $request->price,
            'description' => $request->description,
            'ch_description' => $request->ch_description,
            'notice' => $request->notice,
            'ch_notice' => $request->ch_notice,
            'weight' => $request->weight,
            'merchant_id' => $request->merchant_id,
            'location_id' => $request->location_id,
        ]);

        return ['code' => '0', 'msg' => 'ok', 'result' => ['item' => $item]];
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'ch_name'=>"required",
            'price' => 'required|numeric',
            'description' => 'required',
            'ch_description'=> "required",
            'notice' => 'required',
            'ch_notice'=> "required",
            'weight' => 'required',
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
        $item->ch_name= $request->ch_name;
        $item->price = $request->price;
        $item->description = $request->description;
        $item->ch_description= $request->ch_description;
        $item->notice = $request->notice;
        $item->ch_notice = $request->ch_notice;
        $item->weight = $request->weight;
        $item->merchant_id = $request->merchant_id;
        $item->location_id = $request->location_id;
        $item->save();
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

    public function addCategory($id, $categoryId)
    {
        $item = Item::find($id);
        $item->categories()->attach($categoryId);
        $item->refresh();
        return ['code' => '0', 'msg' => 'ok', 'result' => ['item' => $item]];
    }

    public function checkName($name)
    {
        $alreadyExisted = Item::where('name', $name)->exists();

        if ($alreadyExisted) {
            return ['code' => '1', 'msg' => "Item name: $name already existed"];
        }

        $alreadyExisted = Item::where('ch_name', $name)->exists();

        if ($alreadyExisted) {
            return ['code' => '1', 'msg' => "Item name: $name already existed"];
        }
        return ['code' => '0', 'msg' => 'ok'];
    }

    public function destroy($id)
    {
        $item = Item::where('id', $id);
        if ($item->exists()) {
            $item->get()[0]->delete();
            return ['code' => '0', 'msg' => 'ok'];
        }
        return ['code' => '1', 'msg' => 'not found'];
    }

    public function findByName(Request $request)
    {
        $items = Item::where('name', 'like', '%'.$request->name.'%')->orWhere('ch_name', 'like', '%'.$request->name.'%');
        if ($request->withTrash == 'true') {
            $items = Item::where('name', 'like', '%'.$request->name.'%')->orWhere('ch_name', 'like', '%'.$request->name.'%')->withTrashed();
        }
        if ($items->exists()) {
            return ['code' => '0', 'msg' => 'ok', 'result'=>['items' => $items->get()]];
        } else {
            return ['code' => '1', 'msg' => "Item \"$request->name\" is not found"];
        }
    }

    
    // public function updateName(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'itemId' => 'required'
    //     ]);

    //     if ($request->itemId == 'null') {
    //         return ['code' => '1', 'msg' => 'Item ID is missing'];
    //     }

    //     if ($validator->fails()) {
    //         return ['code' => '1', 'msg' => $validator->errors()->first()];
    //     }

    //     $item = Item::find($request->itemId);

    //     $item->name = $request->name;

    //     if (!$item->save()) {
    //         ['code' => '1', 'msg' => 'Cannot update item name'];
    //     }

    //     return ['code' => '0', 'msg' => 'ok', 'result' => ['item' => $item]];
    // }
}





    // public function removeCategory($id, $categoryId)
    // {
    //     $item = Item::find($id);
    //     $item->categories()->detach($categoryId);
    //     $item->refresh();
    //     return ['code' => '0', 'msg' => 'ok', 'result' => ['item' => $item]];
    // }
