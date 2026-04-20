<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOwnProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'first_name' => ['sometimes', 'required', 'string', 'min:3', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'min:3', 'max:255'],
            'username' => ['sometimes', 'required', 'string', 'min:3', 'max:255', Rule::unique('internal_users', 'username')->ignore($userId)],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Der Vorname ist erforderlich.',
            'first_name.string' => 'Der Vorname muss ein String sein.',
            'first_name.min' => 'Der Vorname muss mindestens 3 Zeichen lang sein.',
            'first_name.max' => 'Der Vorname darf maximal 255 Zeichen lang sein.',
            'last_name.required' => 'Der Nachname ist erforderlich.',
            'last_name.string' => 'Der Nachname muss ein String sein.',
            'last_name.min' => 'Der Nachname muss mindestens 3 Zeichen lang sein.',
            'last_name.max' => 'Der Nachname darf maximal 255 Zeichen lang sein.',
            'username.required' => 'Der Benutzername ist erforderlich.',
            'username.string' => 'Der Benutzername muss ein String sein.',
            'username.min' => 'Der Benutzername muss mindestens 3 Zeichen lang sein.',
            'username.max' => 'Der Benutzername darf maximal 255 Zeichen lang sein.',
            'username.unique' => 'Der Benutzername ist bereits vergeben.',
        ];
    }
}
