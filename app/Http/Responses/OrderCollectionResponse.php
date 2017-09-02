<?php

namespace App\Http\Responses;

use App\Http\Responses\Transformers\OrderTransformer;
use App\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\JsonApiSerializer;

class OrderCollectionResponse extends Response
{
    /**
     * @param Order[]|\Illuminate\Support\Collection $orders
     * @param LengthAwarePaginator $paginator
     */
    public function __construct($orders, $paginator)
    {
        $fractal = (new Manager())->setSerializer(new JsonApiSerializer());
        $resource = new Collection($orders, new OrderTransformer(), 'order');
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return parent::__construct($fractal->createData($resource)->toArray());
    }
}
