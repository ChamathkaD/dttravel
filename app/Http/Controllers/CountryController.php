<?php

namespace App\Http\Controllers;

use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'countries',
            'data' => Country::pluck('name'),
        ], 200);
    }
}
