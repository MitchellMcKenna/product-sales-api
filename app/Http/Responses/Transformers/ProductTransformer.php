<?php
namespace App\Http\Responses\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    public function transform(Product $product)
    {
        $meta = isset($product->quantity) ? ['meta' => ['quantity' => $product->quantity]] : [];

        return [
            'id' => (string) $product->id,
            'name' => $product->name,
            'created_at' => $product->created_at ? $product->created_at->getTimestamp() : null,
            'updated_at' => $product->updated_at ? $product->updated_at->getTimestamp() : null,
        ] + $meta;
    }
}
