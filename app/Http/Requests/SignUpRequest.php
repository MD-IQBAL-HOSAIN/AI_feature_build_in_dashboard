<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name"=> "required|string|max:255",
            "email"=> "required|email|unique:users,email",
            "password"=> ['required', 'string', 'min:6', 'max:8',
            // 'regex:/[0-9]/', 'regex:/[A-Z]/', 'regex:/[a-z]/', 
             'confirmed'],
        ];
    }

    public function messages(){

        return array_merge(parent::messages(),[
            // 'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
            'password.confirmed' => 'Password confirmation does not match.',
        ] );
    }
}

