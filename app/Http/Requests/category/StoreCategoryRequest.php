<?php

namespace App\Http\Requests\category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'from_year'=>['required','numeric'],
            'to_year'=>['required','numeric'],
            'color'=>['required','string'],
            'image' => ['required','mimes:jpeg,png,jpg,gif'],

        ];
    }

    public function messages()
    {
        return [
            
            'from_year.required' => 'يجب تحديد الفئة العمرية المناسبة للكتاب',
            'to_year.required' => 'يجب تحديد الفئة العمرية المناسبة للكتاب',
            
            'from_year.numeric' => 'الفئة العمرية يجب أن تكون أرقام فقط',
            'to_year.numeric' => 'الفئة العمرية يجب أن تكون أرقام فقط',
            
            'color.required' => 'يجب تحديد اللون الخاص بهذه الفئة',
            'image.required' => 'يجب تحديد الصورة الخاصة بهذه الفئة',

        ];
    }
}
