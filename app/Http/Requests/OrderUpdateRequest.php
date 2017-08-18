<?php

namespace App\Http\Requests;

class OrderUpdateRequest extends OrderRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data.attributes.order_id' => 'numeric',
            'data.attributes.quantity' => 'numeric|min:1',
            'data.relationships.product.data.id' => 'exists:products,id',
        ];
    }
}
