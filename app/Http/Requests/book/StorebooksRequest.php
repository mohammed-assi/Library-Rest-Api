<?php

namespace App\Http\Requests\book;

use Illuminate\Foundation\Http\FormRequest;

class StorebooksRequest extends FormRequest
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
            'name' => ['required','string'],
            'price' => ['required','numeric'],
            'description' =>  ['required','string'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['required','mimes:jpeg,png,jpg,gif'],
            'file' => ['required','file','max:2048','mimes:csv,txt,xlx,xls,pdf']

        ];
    }

    public function messages()
    {
        return [
            
            'name.required' => 'يرجى إدخال أسم الكتاب',
            'price.required' => 'يرجى إدخال الأسم',
            'price.numeric' => 'يجب أن يكون السعر أرقام فقط',
            
            'description.required' => 'يرجى إدخال لمحة عن الكتاب',
            'category_id.required' => 'يرجى تحديد الفئة التي ينتمي لها الكتاب',
           
            'image.required' => 'يرجى تحديد صورة الكتاب',
            'file.required' => 'يرجى تحديد ملف الكتاب',

            

        ];
    }
}
