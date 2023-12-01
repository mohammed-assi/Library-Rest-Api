<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;

class registerReques extends FormRequest
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
            'name'=>['required','string'],
            'email'=>['required','email','unique:users,email'],
            'password'=>['required','min:6']
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'يرجى إدخال البريد الإلكتروني',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني موجود مسبقاً',

            'name.required' => 'يرجى إدخال الأسم',

            'password.required' => 'يرجى إدخال كلمة السر',
            'password.min' => 'كلمة السر يجب أن تكون أكبر من خمس خانات',

        ];
    }
}
