<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateRfqRequest extends FormRequest
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
            'customer_id' => 'required',
            'katashiki' => 'required',
            'countAspiration' => 'required',
            'maker' => 'required | exists:maker,maker_name',
            'compName' => 'required | exists:user_info,company_name'
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'compName.required' => 'メーカーは入力する必要があります。',
    //         'katashiki.required' => 'メーカーは入力する必要があります。',
    //         'countAspiration.required' => 'メーカーは入力する必要があります。',
    //         'maker.required' => 'メーカーは入力する必要があります。',
    //         'maker.exists' => 'メーカーはまだ登録されていません。',
    //     ];
    // }
}
