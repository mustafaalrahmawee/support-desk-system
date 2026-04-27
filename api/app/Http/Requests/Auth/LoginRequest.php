<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Bitte eine E-Mail-Adresse eingeben.',
            'email.string' => 'Die E-Mail-Adresse muss eine Zeichenkette sein.',
            'email.email' => 'Bitte eine gültige E-Mail-Adresse eingeben.',
            'email.max' => 'Die E-Mail-Adresse darf maximal 255 Zeichen lang sein.',
            'password.required' => 'Bitte ein Passwort eingeben.',
            'password.string' => 'Das Passwort muss eine Zeichenkette sein.',
            'password.max' => 'Das Passwort darf maximal 255 Zeichen lang sein.',
        ];
    }
}
