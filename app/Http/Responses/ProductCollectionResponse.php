<?php

namespace App\Http\Responses;

use App\Http\Responses\Transformers\ProductTransformer;
use App\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\JsonApiSerializer;

class ProductCollectionResponse extends Response
{
    /**
     * @param Product[]|\Illuminate\Support\Collection $products
     * @param LengthAwarePaginator $paginator
     */
    public function __construct($products, $paginator)
    {
        $fractal = (new Manager())->setSerializer(new JsonApiSerializer());
        $resource = new Collection($products, new ProductTransformer(), 'product');
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return parent::__construct($fractal->createData($resource)->toArray());
    }
}
