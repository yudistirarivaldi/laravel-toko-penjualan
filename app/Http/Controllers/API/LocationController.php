<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Models\Regency;

class LocationController extends Controller
{
    public function provinces(Request $request)
    {
        return Province::all();

    }

    public function regencies(Request $request, $province_id)
    {
        return Regency::where('province_id', $province_id)->get();
    }

}
