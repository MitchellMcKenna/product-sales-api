<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class InputValidationException extends \Exception
{
    protected $code = 2;

    protected $message = 'Invalid input data.';

    protected $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public function render()
    {
        $errors = array_map(function ($attribute, $violation) {
            return [
                'code' => $this->code,
                'title' => $this->message,
                'source' => ['pointer' => '/' . str_replace('.', '/', $attribute)],
                'detail' => $violation
            ];
        }, array_keys($this->errors), $this->errors);

        return new Response(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }
}
