<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return ['code' => '0', 'msg' => 'ok', 'result' => ['categories' => $categories]];
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'chName' => 'required',
            'mmName' => 'required'
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $is_existed_category = Category::where('name', $request->name)->exists();

        if ($is_existed_category) {
            return ['code' => '1', 'msg' => $request->name . ' already exist'];
        }

        $category = Category::create([
            'name' => $request->name,
            'ch_name' => $request->chName,
            'mm_name' => $request->mmName
        ]);


        return ['code' => '0', 'msg' => 'ok', 'result' => ['category' => $category]];
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
            'chName' => 'required',
            'mmName' => 'required'
        ]);

        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }

        $is_existed_category = Category::where('name', $request->name)->exists();

        if ($is_existed_category) {
            return ['code' => '1', 'msg' => $request->name . ' already exist'];
        }

        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->ch_name = $request->chName;
        $category->mm_name = $request->mmName;
        $category->save();


        return ['code' => '0', 'msg' => 'ok', 'result' => ['category' => $category]];
    }
}
