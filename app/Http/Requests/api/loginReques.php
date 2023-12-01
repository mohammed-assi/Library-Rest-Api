<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;

class loginReques extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email'=>['required','exists:users,email'],
            'password'=>['required']
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'يرجى إدخال البريد الإلكتروني',
            'email.unique' => 'البريد الإلكتروني غير موجود ',

            'password.required' => 'يرجى إدخال كلمة السر',

        ];
    }
}
