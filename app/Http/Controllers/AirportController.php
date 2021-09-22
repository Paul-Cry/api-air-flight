<?php

namespace App\Http\Controllers;

use App\Airport;
use App\Category;
use App\Http\Requests\ApiAdminRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\AirportResource;
use App\Product;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function search(Request $request)
    {
        return response()->json([
            'data' => [
                'items' => AirportResource::collection(Airport::where('iata', 'like', '%' . $request['query'] . '%')->
                orWhere('city', 'like', '%' . $request['query'] . '%')->
                orWhere('name', 'like', '%' . $request['query'] . '%')->get())
            ]
        ]);
    }
}
