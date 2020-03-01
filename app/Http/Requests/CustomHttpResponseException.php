<?php


namespace App\Http\Requests;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\MessageBag;

class CustomHttpResponseException extends Exception
{
    /**
     * @var Validator
     */
    private $validator;

    protected $code = 422;

    /**
     * CustomHttpResponseException constructor.
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
        parent::__construct();
    }

    public function render()
    {
        $errors = [];
        foreach ($this->validator->errors()->toArray() as $field => $error) {
            array_push($errors, [
                'field' => $field,
                'message' => $error
            ]);
        }

        return response()->json([
            'success' => $this->validator->fails() ? false : true,
            'errors' => $errors,
        ], $this->code);
    }
}
