<?php

namespace Tests\Unit\App\Http\Controllers;

use App\Http\Controllers\TopSellersController;
use App\Http\Requests\TopSellersRequest;
use App\Order;
use App\Product;
use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopSellersControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @var TopSellersController */
    protected $controller;

    /** @var DatabaseManager */
    protected $db;

    protected function setUp()
    {
        parent::setUp();
        $this->controller = new TopSellersController();
        $this->db = $this->app->make(DatabaseManager::class);
    }

    public function testIndexDefaultsToOnlyIncludeOrdersInLast24Hours()
    {
        $topSeller = factory(Product::class)->create();

        // Create 1 before
        factory(Order::class)->create(['product_id' => $topSeller->id, 'created_at' => Carbon::now()->subHours(25)]);
        // Create 2 during
        factory(Order::class, 2)->create(
            ['product_id' => $topSeller->id, 'quantity' => 4]
        );

        $response = $this->controller->index(new TopSellersRequest(), $this->db);
        $this->assertEquals(8, $response->getOriginalContent()['data'][0]['meta']['quantity']);
    }

    public function testIndexOnlyIncludesOrdersBetweenBeginAndEndQueryParams()
    {
        $begin = Carbon::create(2010);
        $end = Carbon::create(2012);
        $topSeller = factory(Product::class)->create();

        // Create 1 before
        factory(Order::class)->create(['product_id' => $topSeller->id, 'created_at' => Carbon::create(2009)]);
        // Create 2 during
        factory(Order::class, 2)->create(
            ['product_id' => $topSeller->id, 'quantity' => 5, 'created_at' => Carbon::create(2011)]
        );
        // Create 1 after
        factory(Order::class)->create(['product_id' => $topSeller->id, 'created_at' => Carbon::create(2013)]);

        $request = new TopSellersRequest(['begin' => $begin->timestamp, 'end' => $end->timestamp]);
        $response = $this->controller->index($request, $this->db);
        $this->assertEquals(10, $response->getOriginalContent()['data'][0]['meta']['quantity']);
    }

    public function testIndexPagination()
    {
        factory(Order::class)->create(['quantity' => 100]);
        $secondPlace = factory(Order::class)->create(['quantity' => 50]);

        $request = new TopSellersRequest(['page' => 2, 'limit' => 1]);
        $response = $this->controller->index($request, $this->db);
        $this->assertEquals($secondPlace->product->id, $response->getOriginalContent()['data'][0]['id']);
    }
}
