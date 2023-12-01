<?php

namespace App\Http\Requests\book;

use Illuminate\Foundation\Http\FormRequest;

class UpdatebooksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['nullable','string'],
            'price' => ['nullable','numeric'],
            'description' =>  ['nullable','string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'image' => ['nullable','mimes:jpeg,png,jpg,gif'],
            'file' => ['nullable','file','max:2048','mimes:csv,txt,xlx,xls,pdf']

        ];
    }

    public function messages()
    {
        return [
            
            'price.numeric' => 'يجب أن يكون السعر أرقام فقط',
                       
            

        ];
    }
}   
