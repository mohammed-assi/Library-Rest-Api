<?php

namespace App\Http\Requests\category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            'from_year'=>['nullable','numeric'],
            'to_year'=>['nullable','numeric'],
            'color'=>['nullable','string'],
            'image' => ['nullable','mimes:jpeg,png,jpg,gif'],
        ];
    }

    public function messages()
    {
        return [
             
            'from_year.numeric' => 'الفئة العمرية يجب أن تكون أرقام فقط',
            'to_year.numeric' => 'الفئة العمرية يجب أن تكون أرقام فقط',
            

        ];
    }
}
