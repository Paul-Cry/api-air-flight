<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends ApiAdminRequest
{


    public function rules()
    {
        return [
            'title'=> 'unique:products',
            'price'=>'integer|min:1',
            'photo'=>'image|mimes:jpg,jpeg,png',
        ];
    }
}
