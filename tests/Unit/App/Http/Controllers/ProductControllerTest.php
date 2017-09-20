<?php

namespace Tests\Unit\App\Http\Controllers;

use App\Http\Controllers\ProductController;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
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
        $response = $this->controller->index($request, new Product());
        $this->assertInstanceOf(ProductCollection::class, $response);
    }

    public function testShow()
    {
        /** @var Product $product */
        $product = factory(Product::class)->create();
        $product->wasRecentlyCreated = false;
        $resource = $this->controller->show($product);
        $this->assertInstanceOf(ProductResource::class, $resource);
        $this->assertEquals(Response::HTTP_OK, $resource->response()->getStatusCode());
    }

    public function testStore()
    {
        $product = factory(Product::class)->make();
        $request = ProductCreateRequest::create('/api/products', 'POST', [], [], [], [], json_encode([
            'data' => ['attributes' => ['name' => $product->name]]
        ]));

        $resource = $this->controller->store($request, new Product());
        $this->assertInstanceOf(ProductResource::class, $resource);
        $this->assertEquals(Response::HTTP_CREATED, $resource->response()->getStatusCode());
        $this->assertDatabaseHas('products', ['name' => $product->name]);
    }

    public function testUpdate()
    {
        /** @var Product $product */
        $product = factory(Product::class)->create();
        $product->wasRecentlyCreated = false;

        $newName = "Updated Name";
        $request = ProductUpdateRequest::create("/api/products/{$product->id}", 'PATCH', [], [], [], [], json_encode([
            'data' => ['attributes' => ['name' => $newName]]
        ]));
        $resource = $this->controller->update($request, $product);

        $this->assertInstanceOf(ProductResource::class, $resource);
        $this->assertEquals(Response::HTTP_OK, $resource->response()->getStatusCode());
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
