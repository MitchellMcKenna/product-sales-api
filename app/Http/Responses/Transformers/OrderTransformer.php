<?php
namespace App\Http\Responses\Transformers;

use App\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'product'
    ];

    public function transform(Order $order)
    {
        return [
            'id' => (string) $order->id,
            'order_id' => $order->order_id,
            'quantity' => $order->quantity,
            'created_at' => $order->created_at->getTimestamp(),
            'updated_at' => $order->updated_at->getTimestamp(),
            'links' => [
                'self' => '/orders/' . $order->id,
            ]
        ];
    }

    /**
     * Include Product
     *
     * @param Order $order
     * @return \League\Fractal\Resource\Item
     */
    public function includeProduct(Order $order)
    {
        return $this->item($order->product, new ProductTransformer(), 'product');
    }
}
