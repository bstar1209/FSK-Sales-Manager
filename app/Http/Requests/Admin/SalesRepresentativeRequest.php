<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SalesRepresentativeRequest extends FormRequest
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
            'english' => 'required',
            'password' => 'required',
            'email' => 'required | email',
            "tel"    => 'nullable',
            "fax"    => 'nullable | numeric',
        ];
    }

    public function messages()
    {
        return [
            'english.required' => 'この項目は入力必須です。',
            'password.required' => 'この項目は入力必須です。',
            'email.required' => 'この項目は入力必須です。',
            'email.email' => 'メールアドレスが正しい形式ではないです。',
            'tel.numeric' => '電話番号が正しくないです。',
            'fax.numeric' => 'FAX番号が正しくないです。',
        ];
    }
}
