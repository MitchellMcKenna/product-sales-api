<?php

namespace Tests\Unit\App\Http\Resources;

use App\Http\Resources\OrderResource;
use App\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @var  Order */
    protected $order;

    protected function setUp()
    {
        parent::setUp();

        $this->order = factory(Order::class)->create();
    }

    public function testSetsContentAndFormatsCorrectly()
    {
        $response = (new OrderResource($this->order))->response();

        $this->assertJsonStringEqualsJsonString($response->getContent(), json_encode([
            'data' => [
                'type' => 'order',
                'id' => (string) $this->order->id,
                'attributes' => [
                    'order_id' => $this->order->order_id,
                    'quantity' => $this->order->quantity,
                    'created_at' => $this->order->created_at->getTimestamp(),
                    'updated_at' => $this->order->updated_at->getTimestamp()
                ],
                'relationships' => [
                    'product' => [
                        'data' => [
                            'type' => 'product',
                            'id' => (string) $this->order->product_id
                        ]
                    ]
                ]
            ],
            'included' => [[
                'type' => 'product',
                'id' => (string) $this->order->product->id,
                'attributes' => [
                    'name' => $this->order->product->name,
                    'created_at' => $this->order->product->created_at->getTimestamp(),
                    'updated_at' => $this->order->product->updated_at->getTimestamp()
                ]
            ]]
        ]));
    }
}
