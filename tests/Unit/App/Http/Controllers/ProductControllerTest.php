<?php

namespace Tests\Unit\App\Http\Controllers;

use App\Http\Controllers\ProductController;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Responses\ProductCollectionResponse;
use App\Http\Responses\ProductResponse;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @var ProductController */
    protected $controller;

    /** @var Product */
    protected $product;

    protected function setUp()
    {
        parent::setUp();
        $this->controller = new ProductController();
    }

    public function testIndex()
    {
        factory(Product::class, 2)->create();
        $request = new Request();
        $response = $this->controller->index($request);
        $this->assertInstanceOf(ProductCollectionResponse::class, $response);
    }

    public function testShow()
    {
        $product = factory(Product::class)->create();
        $response = $this->controller->show($product);
        $this->assertInstanceOf(ProductResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testStore()
    {
        $product = factory(Product::class)->make();
        $request = ProductCreateRequest::create('/api/products', 'POST', [], [], [], [], json_encode([
            'data' => ['attributes' => ['name' => $product->name]]
        ]));

        $response = $this->controller->store($request);
        $this->assertInstanceOf(ProductResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertDatabaseHas('products', ['name' => $product->name]);
    }

    public function testUpdate()
    {
        $product = factory(Product::class)->create();

        $newName = "Updated Name";
        $request = ProductUpdateRequest::create("/api/products/{$product->id}", 'PATCH', [], [], [], [], json_encode([
            'data' => ['attributes' => ['name' => $newName]]
        ]));
        $response = $this->controller->update($request, $product);

        $this->assertInstanceOf(ProductResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => $newName]);
    }

    public function testDestroy()
    {
        $product = factory(Product::class)->create();
        $response = $this->controller->destroy($product);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
