<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Township;
use App\Location;

class TownshipController extends Controller
{
    public function index()
    {
        return ['code' => '0', 'msg' => 'ok', 'result' => ['townships' => Township::all()->load('location')]];
    }

    public function findByLocation(Request $request)
    {
        $location = Location::find($request->locationId);
        if ($location) {
            $townships = $location->townships;
            return ['code' => '0', 'msg' => 'ok', 'result' => ['townships' => $townships]];
        }
        return ['code' => '1', 'msg' => 'location does not exist'];
    }
}
