<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SystemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'logo'        => 'nullable|image|mimes:png,jpg,jpeg,ico|max:2048',
            'mini_logo'   => 'nullable|image|mimes:png,jpg,jpeg,ico|max:1024',
            'icon'        => 'nullable|image|mimes:png,jpg,jpeg,ico|max:512',
            'site_title'  => 'required|string|max:255',
            'app_name'    => 'required|string|max:255',
            'admin_name'  => 'required|string|max:255',
            'copyright'   => 'nullable|string|max:255',
            'contact'     => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:255',
            'about'       => 'nullable|string',
        ];
    }
}

