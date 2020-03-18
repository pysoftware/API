<?php

namespace App\Http\Requests\API;

use App\Http\Requests\BaseAPIRequest;
use App\Models\Car;


class UpdateCarAPIRequest extends BaseAPIRequest
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
        $rules = Car::$rules;

        return $rules;
    }
}
