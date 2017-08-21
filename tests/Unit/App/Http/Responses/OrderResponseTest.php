<?php

namespace Tests\Unit\App\Http\Responses;

use App\Http\Responses\OrderResponse;
use App\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class OrderResponseTest extends TestCase
{
    use RefreshDatabase;

    /** @var OrderResponse */
    protected $response;

    /** @var  Order */
    protected $order;

    protected function setUp()
    {
        parent::setUp();

        $this->order = factory(Order::class)->create();
    }

    public function testSetsStatusCode()
    {
        $response = new OrderResponse($this->order, Response::HTTP_CREATED);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testSetsContentAndFormatsCorrectly()
    {
        $response = new OrderResponse($this->order);

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
