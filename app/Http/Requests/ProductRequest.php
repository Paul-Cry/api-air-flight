<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends ApiAdminRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'=>'required|unique:products',
            'price'=>'required|integer|min:1',
            'photo'=>'required|image|mimes:jpg,jpeg,png',
        ];
    }
}
