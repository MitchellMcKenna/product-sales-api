<?php

namespace App\Observers;

use App\Order;
use Spatie\ResponseCache\ResponseCache;

class OrderObserver
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
     * @param Order $order
     * @return void
     */
    public function created(Order $order)
    {
        $this->cache->flush();
    }

    /**
     * Listen to the Order created event.
     *
     * @param Order $order
     * @return void
     */
    public function updated(Order $order)
    {
        $this->cache->flush();
    }

    /**
     * Listen to the Order deleted event.
     *
     * @param Order $order
     * @return void
     */
    public function deleted(Order $order)
    {
        $this->cache->flush();
    }
}
