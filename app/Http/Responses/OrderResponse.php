<?php

namespace App\Http\Responses;

use App\Http\Responses\Transformers\OrderTransformer;
use App\Order;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;

class OrderResponse extends Response
{
    public function __construct(Order $order, $status = Response::HTTP_OK)
    {
        $fractal = (new Manager())->setSerializer(new JsonApiSerializer());

        return parent::__construct(
            $fractal->createData(new Item($order, new OrderTransformer(), 'order'))->toArray(),
            $status
        );
    }
}
