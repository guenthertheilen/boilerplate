<?php

namespace App\Http\Requests;

use App\Rules\EmailAndTokenMatch;
use Illuminate\Foundation\Http\FormRequest;

class CreatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                new EmailAndTokenMatch($this->request->get('activation_token'))
            ],
            'password' => 'required|min:6|confirmed',
            'activation_token' => 'required'
        ];
    }
}
