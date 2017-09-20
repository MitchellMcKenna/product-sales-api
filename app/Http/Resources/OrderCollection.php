<?php

namespace App\Http\Resources;

class OrderCollection extends \Illuminate\Http\Resources\Json\ResourceCollection
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
            'data' => $this->collection->map(function ($order) use ($request) {
                return (new OrderResource($order))->toArray($request);
            })
        ];
    }

    public function with($request)
    {
        return [
            'included' => $this->collection->pluck('product')->unique()->values()->map(function ($product) {
                return new ProductResource($product);
            })
        ];
    }
}
