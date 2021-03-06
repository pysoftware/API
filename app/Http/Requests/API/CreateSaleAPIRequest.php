<?php

namespace App\Http\Requests\API;

use App\Http\Requests\BaseAPIRequest;
use App\Models\Sale;

class CreateSaleAPIRequest extends BaseAPIRequest
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
            'employee_id' => 'required|integer',
            'car_id' => 'required|integer',
            'customer_id' => 'required|integer',
        ];
    }
}
