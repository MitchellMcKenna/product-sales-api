<?php

namespace App\Http\Requests;

abstract class ProductRequest extends JsonRequest
{
    public function getName()
    {
        return $this->json('data.attributes.name');
    }
}
