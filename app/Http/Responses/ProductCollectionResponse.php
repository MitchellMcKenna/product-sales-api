<?php

namespace App\Http\Responses;

use App\Http\Responses\Transformers\ProductTransformer;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\JsonApiSerializer;

class ProductCollectionResponse extends Response
{
    public function __construct($products, $paginator = null)
    {
        $fractal = (new Manager())->setSerializer(new JsonApiSerializer());
        $resource = new Collection($products, new ProductTransformer(), 'product');

        if ($paginator) {
            $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        }

        return parent::__construct($fractal->createData($resource)->toArray());
    }
}
