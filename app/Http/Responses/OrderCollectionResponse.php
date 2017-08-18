<?php

namespace App\Http\Responses;

use App\Http\Responses\Transformers\OrderTransformer;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\JsonApiSerializer;

class OrderCollectionResponse extends Response
{
    public function __construct($orders, $paginator = null)
    {
        $fractal = (new Manager())->setSerializer(new JsonApiSerializer());
        $resource = new Collection($orders, new OrderTransformer(), 'order');

        if ($paginator) {
            $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        }

        return parent::__construct($fractal->createData($resource)->toArray());
    }
}
