<?php

namespace App\Observers;

use App\Product;
use Spatie\ResponseCache\ResponseCache;

class ProductObserver
{
    protected $cache;

    /**
     * @param ResponseCache $cache
     */
    public function __construct(ResponseCache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Listen to the Order created event.
     *
     * @param Product $product
     * @return void
     */
    public function created(Product $product)
    {
        $this->cache->flush();
    }

    /**
     * Listen to the Order created event.
     *
     * @param Product $product
     * @return void
     */
    public function updated(Product $product)
    {
        $this->cache->flush();
    }

    /**
     * Listen to the Order deleted event.
     *
     * @param Product $product
     * @return void
     */
    public function deleted(Product $product)
    {
        $this->cache->flush();
    }
}
