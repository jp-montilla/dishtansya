<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
            'email' => 'required|unique:users|email',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'Invalid email address',
            'email.unique' => 'Email already taken',
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
        ];
    }
}
