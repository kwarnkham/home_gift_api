<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Validator;
use Illuminate\Support\Facades\DB;

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


    public function makeCategoryA($id)
    {
        $availableCount= count(DB::table('a_categories')->where('category_id', null)->get());
        if ($availableCount < 1) {
            return ['code' => '1', 'msg' => 'Cannot add more'];
        }
        $idToMake = DB::table('a_categories')->where('category_id', null)->first()->id;
        if (DB::table('a_categories')->where('id', $idToMake)->update(['category_id'=>$id])) {
            return ['code' => '0', 'msg' => 'ok'];
        }
    }
    public function unMakeCategoryA($id)
    {
        $found = count(DB::table('a_categories')->where('category_id', $id)->get());
        if ($found == 0) {
            return ['code'=>'1', 'msg'=>"$id not found"];
        }
        DB::table('a_categories')->where('category_id', $id)->update(['category_id'=>null]);
        return ['code' => '0', 'msg' => 'ok'];
    }

    public function indexCategoryA()
    {
        $categories = DB::table('categories')
        ->join('a_categories', 'categories.id', 'a_categories.category_id')
        ->where('a_categories.category_id', '!=', null)
        ->select('categories.*')->get();
        return ['code' => '0', 'msg' => 'ok', 'result'=>['categories'=>$categories]];
    }
}
