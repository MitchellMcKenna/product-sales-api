<?php

namespace App\Http\Requests;

use App\Exceptions\InputValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class JsonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // By default allow all requests to be authorized.
        return true;
    }

    /**
     * The data to be validated should be processed as JSON even if Content-Type is not set to application/json
     * @return mixed
     */
    protected function validationData()
    {
        return $this->json()->all();
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  Validator $validator
     *
     * @return void
     * @throws InputValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new InputValidationException($validator->errors()->getMessages());
    }
}
