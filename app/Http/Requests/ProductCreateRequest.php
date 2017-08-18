<?php

namespace App\Http\Requests;

class ProductCreateRequest extends ProductRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data.attributes.name' => 'required|string|between:3,191|unique:products,name'
        ];
    }
}
