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
        if (DB::table('a_categories')->where('id', $idToMake)->update(['category_id'=>$id, 'updated_at'=>now()])) {
            return ['code' => '0', 'msg' => 'ok'];
        }
    }
    public function unMakeCategoryA($id)
    {
        $found = count(DB::table('a_categories')->where('category_id', $id)->get());
        if ($found == 0) {
            return ['code'=>'1', 'msg'=>"$id not found"];
        }
        DB::table('a_categories')->where('category_id', $id)->update(['category_id'=>null, 'updated_at'=>now()]);
        return ['code' => '0', 'msg' => 'ok'];
    }

    public function indexCategoryA()
    {
        $categories = DB::table('categories')
        ->join('a_categories', 'categories.id', 'a_categories.category_id')
        ->where('a_categories.category_id', '!=', null)
        ->select('categories.*', 'a_categories.id as a_category_id')->get();
        return ['code' => '0', 'msg' => 'ok', 'result'=>['categories'=>$categories]];
    }
    
    public function makeCategoryB($id)
    {
        $success = DB::table('b_categories')->insert(
            ['category_id'=>$id, "created_at"=>now(), "updated_at"=>now()]
        );
        if ($success) {
            return ['code' => '0', 'msg' => 'ok',];
        }
    }

    public function indexCategoryB()
    {
        $categories = DB::table('categories')
        ->join('b_categories', 'categories.id', 'b_categories.category_id')
        ->select('categories.*', 'b_categories.id as b_category_id')->get();
        return ['code' => '0', 'msg' => 'ok', 'result'=>['categories'=>$categories]];
    }

    public function destroyCategoryB($id)
    {
        $success = DB::table('b_categories')->where('category_id', $id)->delete();
        if ($success == 1) {
            return ['code' => '0', 'msg' => 'ok',];
        }
    }

    public function joinAB($aId, $bId)
    {
        $aCategoryId= DB::table('a_categories')->where('category_id', $aId)->select('id')->get()->first()->id;
        $bCategoryId= DB::table('b_categories')->where('category_id', $bId)->select('id')->get()->first()->id;

        $success = DB::table('a_b_categories')->updateOrInsert(
            ['a_category_id' => $aCategoryId, 'b_category_id' => $bCategoryId],
            ['a_category_id'=>$aCategoryId, 'b_category_id'=> $bCategoryId,"created_at"=>now(), "updated_at"=>now()]
        );
        if ($success) {
            return ['code' => '0', 'msg' => 'ok',];
        }
    }

    public function getJoinedA($bId)
    {
        $category= null;
        $found= DB::table('a_b_categories')->select('a_category_id')->where('b_category_id', $bId)->get();
        if (count($found)>0) {
            $aId = $found->first()->a_category_id;
        

            $category = DB::table('categories')->join('a_categories', 'categories.id', 'a_categories.category_id')
                                            ->select('categories.*', 'a_categories.id as a_category_id')
                                            ->where('a_categories.id', $aId)->get()->first();
        }
        return ['code' => '0', 'msg' => 'ok', 'result'=>['category'=>$category]];
    }


    public function getJoinedB($id)
    {
        $category= null;
        $found= DB::table('b_c_categories')->select('b_category_id')->where('category_id', $id)->get();
        if (count($found)>0) {
            $bId = $found->first()->b_category_id;
        

            $category = DB::table('categories')->join('b_categories', 'categories.id', 'b_categories.category_id')
                                            ->select('categories.*', 'b_categories.id as b_category_id')
                                            ->where('b_categories.id', $bId)->get()->first();
        }
        return ['code' => '0', 'msg' => 'ok', 'result'=>['category'=>$category]];
    }

    public function getAB()
    {
        $associations = DB::table('a_b_categories')->select('a_category_id', 'b_category_id')->get();
        return ['code' => '0', 'msg' => 'ok', 'result'=>['associations'=>$associations]];
    }

    public function unJoinAB($bId)
    {
        $success = DB::table('a_b_categories')->where('b_category_id', $bId)->delete();
        if ($success == 1) {
            return ['code' => '0', 'msg' => 'ok'];
        }
        return ['code' => '1', 'msg' => 'fail'];
    }

    public function unJoinBC($id)
    {
        $success = DB::table('b_c_categories')->where('category_id', $id)->delete();
        if ($success == 1) {
            return ['code' => '0', 'msg' => 'ok'];
        }
        return ['code' => '1', 'msg' => 'fail'];
    }

    public function joinBC($bId, $id)
    {
        $success = DB::table('b_c_categories')->updateOrInsert(
            ['category_id' => $id, 'b_category_id' => $bId],
            ['category_id'=>$id, 'b_category_id'=> $bId,"created_at"=>now(), "updated_at"=>now()]
        );

        if ($success) {
            return ['code' => '0', 'msg' => 'ok'];
        }
    }

    public function getBC()
    {
        $associations = DB::table('b_c_categories')->select('id', 'b_category_id')->get();
        return ['code' => '0', 'msg' => 'ok', 'result'=>['associations'=>$associations]];
    }
}
