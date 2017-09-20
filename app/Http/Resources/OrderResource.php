<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class OrderResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'type' => 'order',
            'id' => (string) $this->id,
            'attributes' => [
                'order_id' => $this->order_id,
                'quantity' => $this->quantity,
                'created_at' => $this->created_at ? $this->created_at->getTimestamp() : null,
                'updated_at' => $this->updated_at ? $this->updated_at->getTimestamp() : null,
            ],
            'relationships' => [
                'product' => [
                    'data' => [
                        'type' => 'product',
                        'id' => (string) $this->product_id
                    ]
                ]
            ]
        ];
    }

    public function with($request)
    {
        if ($this->whenLoaded('product')) {
            return ['included' => [new ProductResource($this->product)]];
        }
    }
}
