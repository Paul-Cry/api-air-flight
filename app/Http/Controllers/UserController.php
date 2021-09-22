<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Http\Requests\ApiRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\UserResource;
use App\Passenger;
use App\User;
use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function foo\func;

class UserController extends Controller
{
    public function info(Request $request)
    {
        $token = $request->bearerToken();

        if ($user = User::where('api_token', $token)->first()) {
            return response()->json(new UserResource($user));
        }

        return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized',
                ]
        ],401);
    }

    public function bookings(Request $request)
    {
        $token = $request->bearerToken();

        if ($user = User::where('api_token', $token)->first()) {
            $bookings = Booking::all()->filter(function ($booking) use ($user) {
                $passengers = $booking->passengers->filter(function ($item) use ($user) {
                    return $item->document_number === $user->document_number;
                });
                return count($passengers) > 0;
            });

            $bookings->map(function ($booking){

                $booking->flightFrom->date = $booking->date_from;
                if ($booking->flightBack) {
                    $booking->flightBack->date = $booking->date_back;
                }
                return $booking;
            });

            return response()->json([
                'data' => [
                    'items' => BookingResource::collection($bookings)
                ]
            ]);
        }

        return response()->json([
            'error' => [
                'code' => 401,
                'message' => 'Unauthorized',
            ]
        ],401);
    }

}
