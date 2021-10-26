<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
{
    protected function failedValidation(Validator $validator) { 
        throw new HttpResponseException(
        response()->json([
                'message' => $validator->errors()->first()
            ], 400)
        ); 
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Product id is required',
            'product_id.exists' => 'Product not found',
            'quantity.required' => 'Quantity is required',
        ];
    }
}
