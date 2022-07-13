<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addInternationalOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
   // public $attributes=['gr'=>'box'];
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
      return  [
            'gr'=>'required_without_all:length,width,height|array',
           'gr.*.1'=>'integer',
          'length'=>'required_with:width',
          'width'=>'required_with:length',
          'height'=>'required_with:width',

        ];
    }
    public function messages()
    {
        return [
             'gr.required_without_all'=>'pleas  choise a box ',
             //'*.integer'=>'pleas enter a valid box dont put empty box',
             'gr.0.1.integer'=>'pleas enter a valid box dont put empty box',
             'gr.1.1.integer'=>'pleas enter a valid box dont put empty box',
             'gr.2.1.integer'=>'pleas enter a valid box dont put empty box',
             'gr.3.1.integer'=>'pleas enter a valid box dont put empty box',
             'gr.4.1.integer'=>'pleas enter a valid box dont put empty box',
             'gr.5.1.integer'=>'pleas enter a valid box dont put empty box',
             'length.required_with'=>'pleas enter a length',
             'width.required_with'=>'pleas enter a width',
             'height.required_with'=>'pleas enter a height',
             'height.numeric'=>'pleas enter a length as number',
             'length.numeric'=>'pleas enter a length as number',
             'width.numeric'=>'pleas enter a length as number',
            // 'height.required_with'=>'pleas enter a length',
          //  'gr.*.'
        ];
    }
    public function attributes(){
        return ['gr'=>'box'];
    }

}
