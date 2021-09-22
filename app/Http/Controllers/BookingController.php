<?php

namespace App\Http\Controllers;

use App\Airport;
use App\Booking;
use App\Flight;
use App\Http\Resources\BookingResource;
use App\Http\Resources\PassengerResource;
use App\Passenger;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function  booking(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'flight_from.id' => 'required|exists:flights,id',
            'flight_back.id' => 'exists:flights,id',
            'flight_back.date' => 'date_format:Y-m-d',
            'flight_from.date' => 'required|date_format:Y-m-d',
            'passengers' => 'required',
            'passengers.*.first_name' => 'required',
            'passengers.*.last_name' => 'required',
            'passengers.*.birth_date' => 'required|date_format:Y-m-d',
            'passengers.*.document_number' => 'required|max:10|min:10',
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

        $bookingData = [
            'flight_from' => $request['flight_from.id'],
            'date_from' => $request['flight_from.date'],
            'code' => Str::upper(Str::random(5))
        ];
        if ($request->has('flight_back')) {
            $bookingData['flight_back'] = $request['flight_back.id'];
            $bookingData['date_back'] = $request['flight_back.date'];
        }

        $booking = Booking::create($bookingData);
        $booking->passengers()->createMany($request['passengers']);

        return response()->json([
          'data' => [
              'code' => $booking->code
          ]
        ]);
    }

    public function  bookingInfo($code)
    {
        $booking = Booking::where('code', $code)->first();

        $booking->flightFrom->date = $booking->date_from;
        if ($booking->flightBack) {
            $booking->flightBack->date = $booking->date_back;
        }

        return new BookingResource($booking);
    }

    public function seatInfo($code)
    {
        $bookig = Booking::where('code', $code)->first();

        $seatFrom = $bookig->passengers->map(function ($passenger) {
            return [
                'passenger_id' => $passenger->id,
                'place' => $passenger->place_from
            ];
        })->filter(function ($passenger) {
            return $passenger['place'] !== null;
        });

        $seatBack = [];
        if ($bookig->flightBack) {
            $seatBack = $bookig->passengers->map(function ($passenger) {
                return [
                    'passenger_id' => $passenger->id,
                    'place' => $passenger->place_back
                ];
            })->filter(function ($passenger) {
                return $passenger['place'] !== null;
            });
        }

        return response()->json([
            'data' => [
                'occupied_from' => $seatFrom,
                'occupied_back' => $seatBack
            ]
        ]);
    }

    public function changePlace(Request $request, $code)
    {
        $validate = Validator::make($request->all(), [
            'passenger' => 'required|exists:passengers,id',
            'seat' => 'required|max:2|min:2',
            'type' => 'required|min:4|max:4'
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

        $bookig = Booking::where('code', $code)->first();

        if ($currPassenger = $bookig->passengers->find($request['passenger'])) {
            if ($request['type'] === 'from') {
                if ($passenger = Passenger::where('place_from', $request['seat'])->first()) {
                    return response()->json([
                        'error' => [
                            'code' => 422,
                            'message' => 'Seat is occupied',
                        ]
                    ], 422);
                }
                $currPassenger->place_from = $request['seat'];

            }
            if ($request['type'] === 'back') {
                if ($passenger = Passenger::where('place_back', $request['seat'])->first()) {
                    return response()->json([
                        'error' => [
                            'code' => 422,
                            'message' => 'Seat is occupied',
                        ]
                    ], 422);
                }
                $currPassenger->place_back = $request['seat'];
            }

            $currPassenger->save();

            return response()->json([
               'data' =>  new PassengerResource($currPassenger)
            ]);
        }
        return response()->json([
            'error' => [
                'code' => 403,
                'message' => 'Passenger does not apply to booking',
            ]
        ], 403);
    }
}
