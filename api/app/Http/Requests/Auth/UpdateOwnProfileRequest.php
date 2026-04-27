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

    public function withValidator(\Illuminate\Validation\Validator $validator): void
    {
        $provided = array_intersect_key($this->all(), array_flip(['first_name', 'last_name', 'username', 'email']));

        if (empty($provided)) {
            $validator->after(function ($v) {
                $v->errors()->add('first_name', 'Mindestens ein Feld muss angegeben werden.');
            });
        }
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'first_name' => ['sometimes', 'required_without_all:last_name,username,email', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required_without_all:first_name,username,email', 'string', 'max:255'],
            'username' => ['sometimes', 'required_without_all:first_name,last_name,email', 'string', 'max:255', Rule::unique('internal_users')->ignore($userId)],
            'email' => ['sometimes', 'required_without_all:first_name,last_name,username', 'string', 'email', 'max:255', Rule::unique('internal_users')->ignore($userId)],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required_without_all' => 'Mindestens ein Feld muss angegeben werden.',
            'last_name.required_without_all' => 'Mindestens ein Feld muss angegeben werden.',
            'username.required_without_all' => 'Mindestens ein Feld muss angegeben werden.',
            'email.required_without_all' => 'Mindestens ein Feld muss angegeben werden.',
            'first_name.string' => 'Der Vorname muss eine Zeichenkette sein.',
            'first_name.max' => 'Der Vorname darf maximal 255 Zeichen lang sein.',
            'last_name.string' => 'Der Nachname muss eine Zeichenkette sein.',
            'last_name.max' => 'Der Nachname darf maximal 255 Zeichen lang sein.',
            'username.string' => 'Der Benutzername muss eine Zeichenkette sein.',
            'username.max' => 'Der Benutzername darf maximal 255 Zeichen lang sein.',
            'username.unique' => 'Dieser Benutzername ist bereits vergeben.',
            'email.string' => 'Die E-Mail-Adresse muss eine Zeichenkette sein.',
            'email.email' => 'Bitte eine gültige E-Mail-Adresse eingeben.',
            'email.max' => 'Die E-Mail-Adresse darf maximal 255 Zeichen lang sein.',
            'email.unique' => 'Diese E-Mail-Adresse ist bereits vergeben.',
        ];
    }
}
