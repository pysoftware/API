<?php

namespace App\Http\Requests\API;

use App\Models\Sale;
use InfyOm\Generator\Request\APIRequest;

class UpdateSaleAPIRequest extends APIRequest
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
            'employee_id' => 'exists:employees,id',
            'car_id' => 'exists:cars,id',
            'customer_id' => 'exists:customers,id',
        ];
    }
}
