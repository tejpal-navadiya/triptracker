<?php

namespace App\Http\Requests\Auth\Masteradmin;


use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_email' => ['required', 'email'],
            'user_password' => ['required', 'string'],
            'user_id' => ['required', 'string'],
        ];
    }

        public function messages(): array
    {
        return [
            'user_email.required' => 'The email field is required.',
            'user_email.email' => 'The email must be a valid email address.',
            'user_password.required' => 'The password field is required.',
            'user_id.required' => 'The user ID field is required.',
        ];
    }
}
