<?php

namespace App\Http\Resources;

class ProductCollection extends \Illuminate\Http\Resources\Json\ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($product) use ($request) {
                return (new ProductResource($product))->toArray($request);
            })
        ];
    }
}
