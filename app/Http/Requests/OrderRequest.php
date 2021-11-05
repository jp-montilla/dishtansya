<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    { 
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
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id|numeric',
            'products.*.quantity' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'products.*.product_id.required' => 'Product id is required',
            'products.*.product_id.exists' => 'Product not found',
            'products.*.product_id.numeric' => 'Product id is not a number',
            'products.*.quantity.required' => 'Quantity is required',
            'products.*.quantity.numeric' => 'Quantity is not a number',
        ];
    }
}
