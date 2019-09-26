<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use Str;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required|numeric|digits_between:7,9',
            'address' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }
        $isExisted = User::where('mobile', $request->mobile)->exists();
        if ($isExisted) {
            return ['code' => '1', 'msg' => 'Phone number already existed'];
        }
        $inputData = $request->only(['name', 'mobile', 'address', 'password']);
        $user = User::create($inputData);
        $token = Str::random(60);
        $user->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();
        return ['code' => '0', 'msg' => 'ok', 'result' => ['user' => $user, 'token' => $token]];
    }
}
