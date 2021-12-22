<?php

namespace App\Http\Requests\Admin;

use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Customer;

class UpdateCustomerRequest extends FormRequest
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
        // $customer = Customer::find($this->get('id'));

        return [
            'compName' => 'required',
            'compNameKana' => 'required',
            'email1' => [
                'required', 
                'email',
            ],
            'email2' => [
                'nullable', 
                'email',
            ],
            'email3' => [
                'nullable', 
                'email',
            ],
            'email4' => [
                'nullable', 
                'email',
            ],
            'sales'  => 'required',
            "tel"    => 'required',
            "fax"    => 'required',
            "rank"    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'compName.required' => '空白にはできません。',
            'compNameKana.required' => '空白にはできません。',
            'address.required' => '会社のかな名を入力する必要があります。',
            'email1.required' => '入力必須項目です。',
            'email1.unique' => 'eメールはすでに存在します。',
            'email2.unique' => 'eメールはすでに存在します。',
            'email3.unique' => 'eメールはすでに存在します。',
            'email4.unique' => 'eメールはすでに存在します。',
            'email1.email' => 'Eメールの形式が正しくありません。',
            'email2.email' => 'Eメールの形式が正しくありません。',
            'email3.email' => 'Eメールの形式が正しくありません。',
            'email4.email' => 'Eメールの形式が正しくありません。',
            'sales.required' => '営業担当者は必須アイテムです。',
            'tel.required' => '電話番号が違います。',
            'fax.required' => 'FAXが違います。',
            'rank.required' => 'ランクが違います。',
        ];
    }
}
