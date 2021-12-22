<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'compName' => 'required',
            'zip' => 'required | numeric',
            "prefecture" => 'required',
            'municipality' => 'required',
            'address3'  => 'required',
            "tel"    => 'required | numeric',
            "fax"    => 'nullable | numeric',
        ];
    }

    public function messages()
    {
        return [
            'compName.required' => '入力必須項目です。',
            'zip.required' => '入力必須項目です。',
            'zip.numeric' => '7桁で入力してください。',
            'address3.required' => '入力必須項目です。',
            'prefecture.required' => 'スペースがあってはなりません。',
            'municipality.required' => 'スペースがあってはなりません。',
            'tel.required' => '入力必須項目です。',
            'tel.numeric' => '電話番号が正しくありません。',
            'fax.numeric' => 'FAXが正しくない。',
        ];
    }
}
