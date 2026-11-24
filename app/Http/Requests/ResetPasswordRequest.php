<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    public function messages(): array
    {
        return [
            'token.required' => 'El token de recuperación es obligatorio.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debes ingresar un correo electrónico válido.',
            'email.exists' => 'No encontramos una cuenta con ese correo electrónico.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
        ];
    }

    public function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required','email','exists:users,email'],
            'password' => ['required','confirmed',
                Password::min(8)
                    // ->letters()
                    // ->symbols()
                    // ->numbers()
            ]
        ];
    }
}
