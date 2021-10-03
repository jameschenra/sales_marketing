<?php

namespace App\Http\Controllers\User;

use Response;
use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
    public function getAll(Request $request)
    {
        $regionId = $request->get('region_id');
        $cities = City::where('region_id', $regionId)->get(['id', 'name'])->toArray();

        return Response::json($cities, 200);
    }
}
