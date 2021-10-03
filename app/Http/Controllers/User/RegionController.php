<?php

namespace App\Http\Controllers\User;

use Response;
use App\Models\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{
    public function getAll(Request $request)
    {
        $countryId = $request->get('country_id');
        $regions = Region::where('country_id', $countryId)->get(['id', 'name'])->toArray();

        return Response::json($regions, 200);
    }
}
