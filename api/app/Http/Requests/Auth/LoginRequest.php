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
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Die E-Mail-Adresse ist erforderlich.',
            'email.email' => 'Die E-Mail-Adresse muss eine gültige E-Mail-Adresse sein.',
            'email.max' => 'Die E-Mail-Adresse darf maximal 255 Zeichen lang sein.',
            'password.required' => 'Das Passwort ist erforderlich.',
            'password.min' => 'Das Passwort muss mindestens 8 Zeichen lang sein.',
            'password.max' => 'Das Passwort darf maximal 255 Zeichen lang sein.',
            'password.regex' => 'Das Passwort muss mindestens einen Kleinbuchstaben, einen Großbuchstaben, eine Zahl und ein Sonderzeichen enthalten.',
        ];
    }
}
