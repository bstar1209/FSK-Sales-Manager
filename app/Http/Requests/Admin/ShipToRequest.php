<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ShipToRequest extends FormRequest
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
            'registeredName' => 'required',
            'companyName' => 'required',
            'tel' => 'required',
            'country' => 'required',
            'city' => 'required',
            'address1' => 'required',
            'personInCharge' => 'required',
        ];
    }
}
