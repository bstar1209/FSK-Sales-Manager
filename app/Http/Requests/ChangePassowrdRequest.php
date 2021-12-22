<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePassowrdRequest extends FormRequest
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
            'old' => 'required|password',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'old.required' => '以下の各フィールドは入力必須です。',
            'old.password' => '現在のパスワードが正しくないです。',
            'password.required' => '以下の各フィールドは入力必須です。',
            'password.min' => 'パスワードは8文字以上で設定してください。',
            'password.confirmed' => '新しいパスワードが一致していません。',
            'password_confirmation.required' => '以下の各フィールドは入力必須です。',
        ];
    }
}
