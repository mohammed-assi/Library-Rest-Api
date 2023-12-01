<?php

namespace App\Http\Requests\offer;

use Illuminate\Foundation\Http\FormRequest;

class storeOfferRequest extends FormRequest
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
            'book_id' => ['required', 'exists:books,id'],
            'from_date' => ['required'],
            'to_date' => ['required'],
            'new_price' => ['required', 'numeric']
        ];
    }

    public function messages()
    {
        return [
            'book_id.required' => 'يجب تحديد الكتاب الذي سيطبق عليه العرض',
            'from_date.required' => 'يجب تحديد تاريخ بدء العرض',
            'to_date.required' => 'يجب تحديد تاريخ انتهاء العرض',
            'new_price.required' => 'يجب تحديد السعر الجديد للكتاب اثناء العرض',
            'new_price.numeric' => 'يجب أن يكون السعر الجديد للكتاب أرقام فقط',


        ];
    }
}
