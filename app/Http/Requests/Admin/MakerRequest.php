<?php

namespace App\Http\Requests\Admin;

use App\Models\Maker;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MakerRequest extends FormRequest
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
            'name' => [
                'required',
                Rule::unique('maker', 'maker_name')->ignore($this->id),
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'メーカーは入力する必要があります。',
            'name.unique' => 'メーカーはすでに存在します。',
        ];
    }
}
