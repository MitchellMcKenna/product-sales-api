<?php

namespace App\Http\Requests;

class OrderCreateRequest extends OrderRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data.attributes.order_id' => 'required|numeric',
            'data.attributes.quantity' => 'required|numeric|min:1',
            'data.relationships.product.data.id' => 'required|exists:products,id',
        ];
    }
}
