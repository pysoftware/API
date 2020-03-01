<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseAPIRequest;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed|min:6',
        ];
    }
}
