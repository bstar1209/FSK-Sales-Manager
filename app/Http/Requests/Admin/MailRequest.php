<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MailRequest extends FormRequest
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
            'title' => 'required|email:rfc'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '不具合が発生しておりますのでご確認ください。',
            'title.email' => '不具合が発生しておりますのでご確認ください。',
        ];
    }   
}
