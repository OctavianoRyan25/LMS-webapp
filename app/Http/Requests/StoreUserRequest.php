<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

final class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::min(8)->letters()->numbers()],
            'role_id'  => ['required', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Nama harus diisi.',
            'email.required'    => 'Email harus diisi.',
            'email.unique'      => 'Email sudah digunakan.',
            'password.required' => 'Password harus diisi.',
            'role_id.required'  => 'Role harus dipilih.',
            'role_id.exists'    => 'Role tidak valid.',
        ];
    }
}
