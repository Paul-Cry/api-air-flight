<?php

namespace App\Http\Controllers;

use App\Flight;
use App\Http\Resources\FlightResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlightController extends Controller
{
    public function getFLights($from, $to, $date)
    {
        $flights = Flight::whereHas('airportFrom', function ($q) use ($from) {
            $q->where('iata', $from);
        })->whereHas('airportTo', function ($q) use ($to) {
            $q->where('iata', $to);
        })->get();

        $flights->map(function ($flight) use ($date) {
            $flight->setDate($date);
        });

        return $flights;
    }

    public function search(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'from' => 'required|exists:airports,iata',
            'to' => 'required|exists:airports,iata',
            'date1' => 'required|date_format:Y-m-d',
            'date2' => 'date_format:Y-m-d',
            'passengers' => 'required|max:8|min:1'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validate->errors()
                ]
            ], 422);
        }

        $flights = $this->getFLights($request['from'], $request['to'], $request['date1']);

        if ($request->has('date2')) {
            $flightsBack = $this->getFLights($request['to'], $request['from'], $request['date2']);
        }


        return response()->json([
            'data' => [
                'flights_to' => FlightResource::collection($flights),
                'flights_back' => $request->has('date2') ? FlightResource::collection($flightsBack) : []
            ]
        ]);
    }
}
