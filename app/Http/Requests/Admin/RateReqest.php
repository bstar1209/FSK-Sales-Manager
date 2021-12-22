<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RateReqest extends FormRequest
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
        return (request()->action === 'create' ? [
            'type_money' => 'required|string|unique:rate|size:3'
        ] : []) + [
            'buy_rate' => 'required|numeric',
            'sale_rate' => 'required|numeric',
        ];
    }
}
