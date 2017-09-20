<?php

namespace Tests\Unit\App\Http\Resources;

use App\Http\Resources\ProductResource;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @var ProductResponse */
    protected $response;

    /** @var  Product */
    protected $product;

    protected function setUp()
    {
        parent::setUp();

        $this->product = factory(Product::class)->create();
    }

    public function testSetsContentAndFormatsCorrectly()
    {
        $response = (new ProductResource($this->product))->response();

        $this->assertJsonStringEqualsJsonString($response->getContent(), json_encode([
            'data' => [
                'type' => 'product',
                'id' => (string) $this->product->id,
                'attributes' => [
                    'name' => $this->product->name,
                    'created_at' => $this->product->created_at->getTimestamp(),
                    'updated_at' => $this->product->updated_at->getTimestamp()
                ]
            ]
        ]));
    }
}
