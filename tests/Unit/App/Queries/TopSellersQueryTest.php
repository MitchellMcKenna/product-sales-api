<?php

namespace Tests\Unit\App\Queries;

use App\Http\Requests\TopSellersRequest;
use App\Order;
use App\Product;
use App\Queries\TopSellersQuery;
use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopSellersQueryTest extends TestCase
{
    use RefreshDatabase;

    /** @var TopSellersQuery */
    protected $query;

    protected function setUp()
    {
        parent::setUp();
        $this->query = new TopSellersQuery($this->app->make(DatabaseManager::class));
    }

    public function testGetOnlyIncludesOrdersBetweenBeginAndEndQueryParams()
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

        $topSellers = $this->query->get($begin, $end);
        $this->assertEquals(10, $topSellers->first()->quantity);
    }

    public function testGetLimitAndPageParams()
    {
        $begin = Carbon::create(2010);
        $end = Carbon::create(2012);

        // Factory will create 3 orders; 1 for each new product.
        factory(Order::class, 3)->create(['created_at' => Carbon::create(2011)]);

        $this->assertEquals(2, count($this->query->get($begin, $end, 1, 2)));
        $this->assertEquals(1, count($this->query->get($begin, $end, 2, 2)));
    }

    public function testTotal()
    {
        $begin = Carbon::create(2010);
        $end = Carbon::create(2012);
        $nonTopSeller = factory(Product::class)->create();
        $topSeller = factory(Product::class)->create();
        $topSeller2 = factory(Product::class)->create();

        // Create 1 before and 1 after for $nonTopSeller which shouldn't be included
        factory(Order::class)->create(['product_id' => $nonTopSeller->id, 'created_at' => Carbon::create(2009)]);
        factory(Order::class)->create(['product_id' => $nonTopSeller->id, 'created_at' => Carbon::create(2013)]);

        // Create 1 during for $topSeller and 1 for $topSeller2 which should be included
        factory(Order::class)->create(['product_id' => $topSeller->id, 'created_at' => Carbon::create(2011)]);
        factory(Order::class)->create(['product_id' => $topSeller2->id, 'created_at' => Carbon::create(2011)]);

        $this->assertEquals(2, $this->query->total($begin, $end));
    }
}
