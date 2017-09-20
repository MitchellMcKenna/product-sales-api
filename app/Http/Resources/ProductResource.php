<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ProductResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $meta = isset($this->quantity) ? ['meta' => ['quantity' => $this->quantity]] : [];
        return [
            'type' => 'product',
            'id' => (string) $this->id,
            'attributes' => [
                'name' => $this->name,
                'created_at' => $this->created_at ? $this->created_at->getTimestamp() : null,
                'updated_at' => $this->updated_at ? $this->updated_at->getTimestamp() : null,
            ],
        ] + $meta;
    }
}
