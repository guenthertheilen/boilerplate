<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|emailAndTokenMatch:' . $this->request->get('activation_token'),
            'password' => 'required|min:6|confirmed',
            'activation_token' => 'required'
        ];
    }
}
