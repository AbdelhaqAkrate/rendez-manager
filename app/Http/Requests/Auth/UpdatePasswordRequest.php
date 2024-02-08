<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'min:8', 'max:16', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[-+_!@#$%^&*.,?])[A-Za-z\d\-+_!@#$%^&*.,?]+$/'],
        ];
    }
}
