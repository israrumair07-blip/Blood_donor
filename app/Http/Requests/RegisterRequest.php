<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('email')) {
            $this->merge(['email' => strtolower(trim($this->input('email')))]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::in(config('services.admin.emails', [])),
                'unique:users,email',
            ],
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'email.in' => 'This email address is not authorized to create an administrator account.',
        ];
    }
}