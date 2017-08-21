<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @var Product */
    protected $product;

    protected function setUp()
    {
        parent::setUp();

        $this->product = factory(Product::class)->create();
    }

    public function testIndexPagination()
    {
        // Add 9 more for a total of 10
        factory(Product::class, 9)->create();
        $this->get('/api/products?page=2&limit=5')
            ->assertJsonFragment(['meta' => ['pagination' => [
                'total' => 10, 'count' => 5, 'per_page' => 5, 'current_page' => 2, 'total_pages' => 2
            ]]]);
    }

    public function testShow()
    {
        $this->get("/api/products/{$this->product->id}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['type' => 'product', 'id' => (string) $this->product->id])
            ->assertJsonFragment(['name' => $this->product->name])
            ->assertJsonStructure(['data' => ['type', 'id', 'attributes' => ['name', 'created_at', 'updated_at']]]);
    }

    public function testStore()
    {
        $product = factory(Product::class)->make();
        $this->postJson(
            "/api/products",
            ['data' => ['type' => 'product', 'attributes' => ['name' => $product->name]]]
        )->assertStatus(Response::HTTP_CREATED);
    }

    public function testUpdate()
    {
        $newProduct = factory(Product::class)->make();
        $this->patchJson(
            "/api/products/{$this->product->id}",
            ['data' => ['attributes' => ['name' => $newProduct->name]]]
        )
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['name' => $newProduct->name]);

        $this->assertDatabaseHas('products', ['id' => $this->product->id, 'name' => $newProduct->name]);
    }

    public function testDestroy()
    {
        $this->delete("/api/products/{$this->product->id}")
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('products', ['id' => $this->product->id]);
    }
}
