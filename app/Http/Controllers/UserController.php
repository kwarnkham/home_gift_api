<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required|numeric|digits_between:7,9',
            'address' => 'required',
            'password' => 'required|min:5|confirmed'
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }
        $isExisted = User::where('mobile', $request->mobile)->exists();
        if ($isExisted) {
            return ['code' => '1', 'msg' => 'Phone number already existed'];
        }
        $inputData = $request->only(['name', 'mobile', 'address', 'password']);
        $inputData['password'] = bcrypt($inputData['password']);
        $user = User::create($inputData);
        $token = Str::random(60);
        $user->forceFill([
            'api_token' => $token,
        ])->save();
        return ['code' => '0', 'msg' => 'ok', 'result' => ['user' => $user, 'token' => $token]];
    }

    public function show()
    {
        $user = Auth::user();
        return ['code' => '0', 'msg' => 'ok', 'result' => ['user' => $user]];
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }
        $credentials = $request->only('mobile', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = Str::random(60);
            $user->forceFill([
                'api_token' => $token,
            ])->save();
            return ['code' => '0', 'msg' => 'ok', 'result' => ['user' => $user]];
        }
        return ['code' => '1', 'msg' => 'Invalid Info. Can\'t login.'];
    }

    public function logout()
    {
        $user = Auth::user();
        $user->forceFill([
            'api_token' => null,
        ])->save();
        return ['code' => '0', 'msg' => 'ok'];
    }

    public function reponseToInvalidToken()
    {
        return ['code' => '1', 'msg' => 'Login again'];
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:5',
            'new' => "required|min:5|confirmed"
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }
        $user = Auth::user();
        if (Hash::check($request->password, $user->password)) {
            $user->password = bcrypt($request->new);
            if ($user->save()) {
                return ['code'=>'0', 'msg'=>'ok'];
            }
        } else {
            return ['code'=>'1', 'msg'=>'Password is not correct'];
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required|numeric|digits_between:7,9',
            'address' => 'required',
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }
        $user = Auth::user();
        $isExisted = User::where([
            ['mobile', $request->mobile],
            ['id', '!=', $user->id],
        ])->exists();
        if ($isExisted) {
            return ['code' => '1', 'msg' => 'Phone number already existed'];
        }
        
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->address = $request->address;
        if ($user->save()) {
            return ['code' => '0', 'msg' => 'ok', 'result'=>['user'=>$user]];
        } else {
            return ['code' => '1', 'msg' => 'Update fail'];
        }
    }
}
