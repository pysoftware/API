<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseAPIRequest;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email|unique:users',
            'name' => 'string|min:1',
            'last_name' => 'string|min:3',
            'patronymic' => 'string|min:3',
            'password' => 'required|string|confirmed|min:6'
        ];
    }
}
