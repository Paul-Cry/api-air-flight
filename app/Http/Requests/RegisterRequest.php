<?php

namespace App\Http\Requests;

use Facade\FlareClient\Api;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'username' => 'required|unique:users',
            'password' => 'required'
        ];
   }
}
