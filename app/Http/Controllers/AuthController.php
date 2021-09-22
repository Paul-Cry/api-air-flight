<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\ApiAdminRequest;
use App\Http\Requests\CategoryRequest;
use App\User;
use http\Client\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'first_name' => "required",
            'last_name' => "required",
            'phone' => 'required|unique:users',
            'password' => 'required',
            'document_number' => 'required|max:10|min:10'
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

        User::create($request->all());
        return response()->json()->setStatusCode(204);
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'phone' => 'required|exists:users,phone',
            'password' => 'required',
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

        if ($user = User::where('phone', $request['phone'])->first()) {
            if ($user->password === $request['password']) {
                $token = $user->setToken();

                return response()->json([
                    'data' => [
                        'token' => $token,
                    ]
                ],200);
            }
        }

        return response()->json([
            'error' => [
                'code' => 401,
                'message' => 'Unauthorized',
                'errors' => [
                    'phone' => ['phone or password incorrect']
                ]
            ]
        ],401);
    }
}
