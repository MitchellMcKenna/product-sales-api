<?php

namespace Tests\Unit\App\Http\Responses;

use App\Http\Responses\ProductResponse;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductResponseTest extends TestCase
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

    public function testSetsStatusCode()
    {
        $response = new ProductResponse($this->product, Response::HTTP_CREATED);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testSetsContentAndFormatsCorrectly()
    {
        $response = new ProductResponse($this->product);

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
