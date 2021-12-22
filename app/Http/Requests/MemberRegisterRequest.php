<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberRegisterRequest extends FormRequest
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
            'company_name'=>'required',
            'name'=> 'required',
            'email' => 'required|email|min:5|unique:users|confirmed',
            'email_confirmation' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
            'zip' => 'required|regex:/^[0-9]{3}(\-?)[0-9]{4}$/',
        ];
    }

    public function messages()
    {
        return [
            'company_name.required' => '入力必須項目です。',
            'name.required' => '入力必須項目です。',
            'email.required' => '入力必須項目です。',
            'email.unique' => 'このメールアドレスはすでに登録済みです。',
            'email_confirmation.required' => '同じ値をもう一度入力してください。',
            'email_confirmation.email' => '有効なEメールアドレスを入力してください。',
            'password.required' => '入力必須項目です。',
            'password_confirmation.required' => '同じ値をもう一度入力してください。',
            'zip.required' => '入力必須項目です。',
            'email.min' => '5 文字以上で入力してください。',
            'email.confirmed' => '同じ値をもう一度入力してください。',
            'email.email' => '有効なEメールアドレスを入力してください。',
            'password.min' => 'パスワードは8文字以上で設定してください。',
            'password.confirmed' => '同じ値をもう一度入力してください。',
            'zip.regex' => '7桁の数字を入力してください。'
        ];
    }
}
