<?php

namespace App\Queries;

use App\Product;
use DateTime;
use Illuminate\Database\DatabaseManager;

class TopSellersQuery
{
    /** @var DatabaseManager */
    private $db;

    /**
     * @param DatabaseManager $db
     */
    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    /**
     * Get Products ordered by the highest quantity of orders in a given time span.
     *
     * @param DateTime $begin
     * @param DateTime $end
     * @param int $limit
     * @param int $page
     * @return Product[]|\Illuminate\Database\Eloquent\Collection
     */
    public function get(DateTime $begin, DateTime $end, $page = 1, $limit = 15)
    {
        $topSellers = $this->db->table('orders')
            ->leftJoin('products', 'orders.product_id', '=', 'products.id')
            ->selectRaw('products.*, SUM(orders.quantity) as quantity')
            ->orderBy('quantity', 'desc')
            ->groupBy('orders.product_id')
            ->whereBetween('orders.created_at', [$begin, $end])
            ->limit($limit)
            ->offset($limit * ($page - 1))
            ->get();

        return (new Product())->hydrate($topSellers->toArray());
    }

    /**
     * Get total number of products that have sales in a given time span.
     *
     * @param DateTime $begin
     * @param DateTime $end
     * @return int
     */
    public function total(DateTime $begin, DateTime $end)
    {
        return $this->db->table('orders')
            ->selectRaw('count(DISTINCT orders.product_id) as total')
            ->whereBetween('orders.created_at', [$begin, $end])
            ->value('total');
    }
}
