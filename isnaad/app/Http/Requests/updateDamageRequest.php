<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DamageRequest extends FormRequest
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
          //  'image'=>'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'store'=>'required',
            'shipping_number'=>'required',
            'cost'=>'required|numeric',
            'Transaction_Cost'=>'required_if:paid,==,paid',
            'Transaction_ID'=>'required_if:paid,==,paid'
        ];
    }

    public function messages()
    {
        return [
            'Transaction_Cost.required_if'=>'The transaction cost field is required when damage is paid',
            'Transaction_ID.required_if'=>'The Transaction ID field is required when damage is paid',
        ];
    }
}
