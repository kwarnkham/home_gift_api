<?php

namespace App\Http\Controllers;

use App\Address;
use App\Jobs\ProcessMobileVerificationCode;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Str;
use Validator;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required|numeric|digits_between:7,9',
            'address' => 'required',
            'password' => 'required|min:5|confirmed',
            'location_id' => 'required|numeric',
            'township_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }
        $isExisted = User::where('mobile', $request->mobile)->exists();
        if ($isExisted) {
            return ['code' => '1', 'msg' => 'Phone number already existed'];
        }
        $address = Address::create($request->only(['location_id', 'township_id', 'address']));
        $inputData = $request->only(['name', 'mobile', 'password']);
        $inputData['password'] = bcrypt($inputData['password']);
        $inputData['address_id'] = $address->id;
        $user = User::create(['name' => $inputData['name'], 'mobile' => $inputData['mobile'], 'password' => $inputData['password'], 'address_id' => $inputData['address_id']]);
        $token = Str::random(60);
        $code = rand(1000, 9999);
        $user->forceFill([
            'mobile_verification_code' => bcrypt($code),
            'code_created_at' => now(),
            'api_token' => $token,
        ])->save();
        $client = new Client();
        $response = $client->post('https://boomsms.net/api/sms/json', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.env('BOOM_SMS_TOKEN'),
            ],
            'form_params' => [
                'from' => 'BOOM SMS',
                'text' => $code.' is your code. Welcome to HomeGift',
                'to' => '09'.$user->mobile,
            ],
        ]);
        $user->code_number = 1;
        if ($user->save()) {
            $user->refresh();
            ProcessMobileVerificationCode::dispatch($user)->delay(now()->addMinutes(2));

            return ['code' => '0', 'msg' => 'ok', 'result' => ['user' => $user]];
        }
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
            'password' => 'required',
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

            return ['code' => '0', 'msg' => 'ok', 'result' => ['user' => $user, 'token' => $token]];
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
            'new' => 'required|min:5|confirmed',
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }
        $user = Auth::user();
        if (Hash::check($request->password, $user->password)) {
            $user->password = bcrypt($request->new);
            if ($user->save()) {
                return ['code' => '0', 'msg' => 'ok'];
            }
        } else {
            return ['code' => '1', 'msg' => 'Password is not correct'];
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }
        $user = Auth::user();
        $user->name = $request->name;

        if ($user->save()) {
            return ['code' => '0', 'msg' => 'ok', 'result' => ['user' => $user]];
        }

        return ['code' => '1', 'msg' => 'Update fail'];
    }

    public function updateAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required',
            'location_id' => 'required|numeric',
            'township_id' => 'required|numeric',
            'address_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }
        $address = Address::find($request->address_id);
        $address->address = $request->address;
        $address->location_id = $request->location_id;
        $address->township_id = $request->township_id;

        if ($address->save()) {
            return ['code' => '0', 'msg' => 'ok', 'result' => ['address' => $address]];
        }

        return ['code' => '1', 'msg' => 'Update fail'];
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }
        $user = Auth::user();
        if (Hash::check($request->code, $user->mobile_verification_code)) {
            $user->mobile_verified_at = now();
            $user->mobile_verification_code = null;
            if ($user->save()) {
                return ['code' => '0', 'msg' => 'ok'];
            }
        } else {
            return ['code' => '1', 'msg' => 'Code is incorrect', 'data' => $request->code];
        }
    }

    public function sendCode(Request $request)
    {
        $user = Auth::user();
        if (now()->diffInRealSeconds($user->code_created_at) >= 120 && $user->code_number <= 2) {
            $code = rand(1000, 9999);
            $user->code_created_at = now();
            $user->mobile_verification_code = bcrypt($code);
            $client = new Client();
            $response = $client->post('https://boomsms.net/api/sms/json', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.env('BOOM_SMS_TOKEN'),
                ],
                'form_params' => [
                    'from' => 'BOOM SMS',
                    'text' => $code.' is your code. Welcome to HomeGift',
                    'to' => '09'.$user->mobile,
                ],
            ]);
            ++$user->code_number;
            if ($user->save()) {
                $user->refresh();
                ProcessMobileVerificationCode::dispatch($user)->delay(now()->addMinutes(2));

                return ['code' => '0', 'msg' => 'ok', 'result' => ['user' => $user]];
            }
        }

        return ['code' => '1', 'msg' => 'wait for some time and try again'];
    }

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits_between:7,9',
            'code' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }
        $user = User::where('mobile', $request->mobile)->first();
        if (Hash::check($request->code, $user->password_reset_code)) {
            $user->password_reset_at = now();
            $user->password_reset_code = null;
            $user->mobile_verified_at = now();
            $user->mobile_verification_code = null;
            $user->password = bcrypt($request->password);
            if ($user->save()) {
                $user->refresh();

                return ['code' => '0', 'msg' => 'ok'];
            }
        } else {
            return ['code' => '1', 'msg' => 'Invalid code'];
        }
    }

    public function validateMobile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits_between:7,9',
        ]);
        if ($validator->fails()) {
            return ['code' => '1', 'msg' => $validator->errors()->first()];
        }
        $count = User::where('mobile', $request->mobile)->get();
        if (1 == count($count)) {
            $user = $count[0];
            if (null == $user->password_code_created_at || now()->diffInRealSeconds($user->password_code_created_at) >= 86400) {
                $code = rand(1000, 9999);
                $user->password_code_created_at = now();
                $user->password_reset_code = bcrypt($code);
                $user->save();
                $user->refresh();
                $client = new Client();
                $response = $client->post('https://boomsms.net/api/sms/json', [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer '.env('BOOM_SMS_TOKEN'),
                    ],
                    'form_params' => [
                        'from' => 'BOOM SMS',
                        'text' => $code.' is your code. Welcome to HomeGift',
                        'to' => '09'.$user->mobile,
                    ],
                ]);

                return ['code' => '0', 'msg' => 'ok'];
            }

            return ['code' => '0', 'msg' => 'ok'];
        }

        return ['code' => '1', 'msg' => 'mobile number not found'];
    }

    public function getVersion(Request $request)
    {
        return ['code' => '0', 'msg' => 'ok', 'result' => ['version' => ['android' => '1.0.0', 'ios' => '1.0.0']]];
    }
}
