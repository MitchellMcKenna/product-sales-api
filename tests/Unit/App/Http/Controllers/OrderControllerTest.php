<?php

namespace Tests\Unit\App\Http\Controllers;

use App\Http\Controllers\OrderController;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @var OrderController */
    protected $controller;

    protected function setUp()
    {
        parent::setUp();
        $this->controller = new OrderController();
    }

    public function testIndex()
    {
        factory(Order::class, 2)->create();
        $request = new Request();
        $response = $this->controller->index($request, new Order());
        $this->assertInstanceOf(OrderCollection::class, $response);
    }

    public function testShow()
    {
        /** @var Order $order */
        $order = factory(Order::class)->create();
        $order->wasRecentlyCreated = false;
        $resource = $this->controller->show($order);
        $this->assertInstanceOf(OrderResource::class, $resource);
        $this->assertEquals(Response::HTTP_OK, $resource->response()->getStatusCode());
    }

    public function testStore()
    {
        $order = factory(Order::class)->make();
        $request = OrderCreateRequest::create('/api/orders', 'POST', [], [], [], [], json_encode([
            'data' => [
                'type' => 'order',
                'attributes' => ['order_id' => $order->order_id, 'quantity' => $order->quantity],
                'relationships' => ['product' => ['data' => ['id' => $order->product->id]]]
            ]
        ]));

        $resource = $this->controller->store($request, new Order());
        $this->assertInstanceOf(OrderResource::class, $resource);
        $this->assertEquals(Response::HTTP_CREATED, $resource->response()->getStatusCode());
        $this->assertDatabaseHas('orders', ['order_id' => $order->order_id]);
    }

    public function testUpdate()
    {
        /** @var Order $order */
        $order = factory(Order::class)->create();
        $order->wasRecentlyCreated = false;

        $newOrderId = 12345;
        $request = OrderUpdateRequest::create("/api/orders/{$order->id}", 'PATCH', [], [], [], [], json_encode([
            'data' => ['attributes' => ['order_id' => $newOrderId]]
        ]));
        $resource = $this->controller->update($request, $order);

        $this->assertInstanceOf(OrderResource::class, $resource);
        $this->assertEquals(Response::HTTP_OK, $resource->response()->getStatusCode());
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'order_id' => $newOrderId]);
    }

    public function testDestroy()
    {
        $order = factory(Order::class)->create();
        $response = $this->controller->destroy($order);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
