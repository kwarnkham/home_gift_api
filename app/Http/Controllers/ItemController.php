<?php

namespace App\Http\Controllers;

use App\Image;
use App\Item;
use App\Category;
use App\Location;
use App\Merchant;
use App\Province;
use Illuminate\Http\Request;
use Validator;

class ItemController extends Controller
{
    private $perPage = 10;
    public function findByProvince(Request $request)
    {
        $province = Province::where('id', $request->provinceId)->first();
        if ($province) {
            $locations =  $province->locations;
            $items = collect();
            foreach ($locations as $location) {
                $items =  $items->merge($location->items);
            }
            return ['code' => '0', 'msg' => 'ok', 'result' => ['items' => $items->paginate($request->per_page ?? $this->perPage)]];
        } else {
            return ['code' => '1', 'msg' => 'province does not exist'];
        }
    }

    public function findByCategory(Request $request)
    {
        $category = Category::where('id', $request->categoryId)->first();
        if ($category) {
            return ['code' => '0', 'msg' => 'ok', 'result' => ['items' => $category->items->paginate($request->per_page ?? $this->perPage)]];
        } else {
            return ['code' => '1', 'msg' => 'category does not exist'];
        }
    }

    public function findByLocation(Request $request)
    {
        $location = Location::where('id', $request->locationId)->first();
        if ($location) {
            return ['code' => '0', 'msg' => 'ok', 'result' => ['items' => $location->items->paginate($request->per_page ?? $this->perPage)]];
        } else {
            return ['code' => '1', 'msg' => 'location does not exist'];
        }
    }

    public function findByMerchant(Request $request)
    {
        $merchant = Merchant::where('id', $request->merchantId)->first();
        if ($merchant) {
            return ['code' => '0', 'msg' => 'ok', 'result' => ['items' => $merchant->items->paginate($request->per_page ?? $this->perPage)]];
        } else {
            return ['code' => '1', 'msg' => 'merchant does not exist'];
        }
    }

    public function findByName(Request $request)
    {
        $items = Item::where('name', 'like', '%' . $request->name . '%')
            ->orWhere('ch_name', 'like', '%' . $request->name . '%')
            ->orWhere('mm_name', 'like', '%' . $request->name . '%');
        if ($request->withTrash == 'true') {
            $items = Item::onlyTrashed()->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%')
                    ->orWhere('ch_name', 'like', '%' . $request->name . '%')
                    ->orWhere('mm_name', 'like', '%' . $request->name . '%');
            });
        }
        if ($items->exists()) {
            return ['code' => '0', 'msg' => 'ok', 'result' => ['items' => $items->paginate($request->per_page ?? $this->perPage)]];
        } else {
            return ['code' => '1', 'msg' => "Item \"$request->name\" is not found"];
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
            'chName' => "required",
            'mmName' => "required",
            'price' => 'required|numeric',
            'description' => 'required',
            'chDescription' => "required",
            'mmDescription' => "required",
            'weight' => 'required',
            'merchantId' => 'required',
            'locationId' => 'required',
        ]);

        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $item = Item::create([
            'name' => $request->name,
            'ch_name' => $request->chName,
            'mm_name' => $request->mmName,

            'price' => $request->price,

            'description' => $request->description,
            'ch_description' => $request->chDescription,
            'mm_description' => $request->mmDescription,

            'notice' => $request->notice,
            'ch_notice' => $request->chNotice,
            'mm_notice' => $request->mmNotice,

            'weight' => $request->weight,
            'merchant_id' => $request->merchantId,
            'location_id' => $request->locationId,
        ]);

        return ['code' => '0', 'msg' => 'ok', 'result' => ['item' => $item]];
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'ch_name' => "required",
            'mm_name' => "required",

            'price' => 'required|numeric',

            'description' => 'required',
            'ch_description' => "required",
            'mm_description' => "required",


            'weight' => 'required',
            'merchant_id' => 'required',
            'location_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $item = Item::withTrashed()->find($id);
        $item->name = $request->name;
        $item->ch_name = $request->ch_name;
        $item->mm_name = $request->mm_name;

        $item->price = $request->price;

        $item->description = $request->description;
        $item->ch_description = $request->ch_description;
        $item->mm_description = $request->mm_description;

        $item->notice = $request->notice;
        $item->ch_notice = $request->ch_notice;
        $item->mm_notice = $request->mm_notice;

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

    public function destroy($id)
    {
        $item = Item::where('id', $id);
        if ($item->exists()) {
            $item->get()[0]->delete();
            return ['code' => '0', 'msg' => 'ok'];
        }
        return ['code' => '1', 'msg' => 'not found'];
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
