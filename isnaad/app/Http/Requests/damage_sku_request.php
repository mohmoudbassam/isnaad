<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class damage_sku_request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'discription'=>'required',
            'quantity'=>'required|numeric',
            'price_unit'=>'required|numeric',
            'total'=>'required|numeric',
        ];
    }
}
