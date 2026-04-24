<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInternalUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('internal_user')?->id ?? $this->route('id');

        return [
            'first_name'            => ['sometimes', 'required', 'string', 'max:255'],
            'last_name'             => ['sometimes', 'required', 'string', 'max:255'],
            'username'              => ['sometimes', 'required', 'string', 'max:255', Rule::unique('internal_users', 'username')->ignore($id)],
            'email'                 => ['sometimes', 'required', 'email', 'max:255', Rule::unique('internal_users', 'email')->ignore($id)],
            'password'              => ['sometimes', 'required', 'string', 'min:8', 'confirmed'],
            'roles'                 => ['sometimes', 'required', 'array', 'min:1'],
            'roles.*'               => ['string', 'exists:roles,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'   => 'Vorname ist erforderlich.',
            'first_name.max'        => 'Vorname darf maximal 255 Zeichen lang sein.',
            'last_name.required'    => 'Nachname ist erforderlich.',
            'last_name.max'         => 'Nachname darf maximal 255 Zeichen lang sein.',
            'username.required'     => 'Benutzername ist erforderlich.',
            'username.max'          => 'Benutzername darf maximal 255 Zeichen lang sein.',
            'username.unique'       => 'Dieser Benutzername ist bereits vergeben.',
            'email.required'        => 'E-Mail-Adresse ist erforderlich.',
            'email.email'           => 'Bitte eine gültige E-Mail-Adresse eingeben.',
            'email.unique'          => 'Diese E-Mail-Adresse ist bereits vergeben.',
            'password.required'     => 'Passwort ist erforderlich.',
            'password.min'          => 'Das Passwort muss mindestens 8 Zeichen lang sein.',
            'password.confirmed'    => 'Die Passwörter stimmen nicht überein.',
            'roles.required'        => 'Mindestens eine Rolle muss ausgewählt werden.',
            'roles.min'             => 'Mindestens eine Rolle muss ausgewählt werden.',
            'roles.*.exists'        => 'Eine oder mehrere gewählte Rollen sind ungültig.',
        ];
    }
}
