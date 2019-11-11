<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Province;

class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = Province::all();
        return ['code' => '0', 'msg' => 'ok', 'result' => ['provinces' => $provinces]];
    }
}
