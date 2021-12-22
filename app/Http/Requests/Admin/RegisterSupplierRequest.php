<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RegisterSupplierRequest extends FormRequest
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
            'compNameKana' => 'required',
            'email1' => 'required |email',
            'email2' => 'nullable |email',
            'email3' => 'nullable |email',
            'email4' => 'nullable |email',
            'country' => 'required',
            'address' => 'required',
            'payTerm' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'compName.required' => '会社名は入力必須です。',
            'compNameKana.required' => '会社のかな名を入力する必要があります。',
            'country.required' => '国名が必要です。',
            'address.required' => '会社のかな名を入力する必要があります。',
            'payTerm.required' => '支払い条件は入力必須です。',
            'email1.required' => 'メールアドレス1が必要です。',
            'email1.email' => 'メールアドレスが正しい形式ではありません。',
            'email2.email' => 'メールアドレスが正しい形式ではありません。',
            'email3.email' => 'メールアドレスが正しい形式ではありません。',
            'email4.email' => 'メールアドレスが正しい形式ではありません。',
        ];
    }
}
