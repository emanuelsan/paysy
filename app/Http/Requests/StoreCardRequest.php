<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreCardRequest extends Request
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
            'creditcardtype' => 'required|in:visa,mastercard,discover,amex',
            'creditcardnumber' => 'required|integer',
            'cvv2' => 'required|digits_between:3,4',
            'creditcardholder' => 'required',
            'expiremonth' => 'required|integer|in:1,2,3,4,5,6,7,8,9,10,11,12',
            'expireyear' => 'required|integer|min:'.date('Y').'|max:'.(date('Y')+10)
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'expiremonth.in' => 'Please select a month using the month dropdown',
            'expiremonth.min'  => 'Please select a year using the year dropdown',
            'expiremonth.max'  => 'Please select a year using the year dropdown',
        ];
    }
}
