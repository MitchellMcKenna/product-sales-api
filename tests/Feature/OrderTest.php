<?php

namespace Tests\Feature;

use App\Order;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @var Order */
    protected $order;

    protected function setUp()
    {
        parent::setUp();

        $this->order = factory(Order::class)->create();
    }

    public function testIndexPagination()
    {
        // Add 9 more for a total of 10
        factory(Order::class, 9)->create();
        $this->get('/api/orders?page=2&limit=5')
            ->assertJsonStructure(['data', 'links' => ['first', 'last', 'prev', 'next'], 'meta'])
            ->assertJsonFragment([
                'current_page' => 2,
                'from' => 6,
                'last_page' => 2,
                'path' => route('orders.index'),
                'per_page' => "5",
                'to' => 10,
                'total' => 10
            ]);
    }

    public function testShow()
    {
        $this->get("/api/orders/{$this->order->id}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['type' => 'order', 'id' => (string) $this->order->id])
            ->assertJsonStructure(
                ['data' => ['type', 'id', 'attributes' => ['order_id', 'quantity', 'created_at', 'updated_at']]]
            );
    }

    public function testStore()
    {
        $order = factory(Order::class)->make();
        $this->postJson("/api/orders", ['data' => [
            'type' => 'order',
            'attributes' => ['order_id' => $order->order_id, 'quantity' => $order->quantity],
            'relationships' => ['product' => ['data' => ['id' => $order->product->id]]]
        ]])->assertStatus(Response::HTTP_CREATED);
    }

    public function testUpdate()
    {
        $newOrder = factory(Order::class)->make();
        $this->patchJson(
            "/api/orders/{$this->order->id}",
            ['data' => ['attributes' => ['quantity' => $newOrder->quantity]]]
        )
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['quantity' => $newOrder->quantity]);

        $this->assertDatabaseHas('orders', ['id' => $this->order->id, 'quantity' => $newOrder->quantity]);
    }

    public function testDestroy()
    {
        $this->delete("/api/orders/{$this->order->id}")
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('orders', ['id' => $this->order->id]);
    }
}
