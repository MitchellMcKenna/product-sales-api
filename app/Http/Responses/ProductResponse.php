<?php

namespace App\Http\Responses;

use App\Http\Responses\Transformers\ProductTransformer;
use App\Product;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;

class ProductResponse extends Response
{
    public function __construct(Product $product, $status = Response::HTTP_OK)
    {
        $fractal = (new Manager())->setSerializer(new JsonApiSerializer());

        return parent::__construct(
            $fractal->createData(
                new Item($product, new ProductTransformer(), 'product')
            )->toArray(),
            $status
        );
    }
}
