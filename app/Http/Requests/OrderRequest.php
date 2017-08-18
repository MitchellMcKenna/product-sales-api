<?php

namespace App\Http\Requests;

abstract class OrderRequest extends JsonRequest
{
    public function getOrderId()
    {
        return $this->json('data.attributes.order_id');
    }

    public function getQuantity()
    {
        return $this->json('data.attributes.quantity');
    }

    public function getProductId()
    {
        return $this->json('data.relationships.product.data.id');
    }
}
