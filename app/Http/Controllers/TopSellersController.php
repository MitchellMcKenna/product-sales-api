<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopSellersRequest;
use App\Http\Responses\ProductCollectionResponse;
use App\Product;
use Illuminate\Database\DatabaseManager;

class TopSellersController extends Controller
{
    public function index(TopSellersRequest $request, DatabaseManager $db)
    {
        $topSellers = $db->table('orders')
            ->leftJoin('products', 'orders.product_id', '=', 'products.id')
            ->selectRaw('products.*, SUM(orders.quantity) as quantity')
            ->orderBy('quantity', 'desc')
            ->groupBy('orders.product_id')
            ->whereBetween('orders.created_at', [$request->getBegin(), $request->getEnd()])
            ->limit($request->getLimit())
            ->offset($request->getLimit() * ($request->getPage() - 1))
            ->get();

        $products = (new Product())->hydrate($topSellers->toArray());

        return new ProductCollectionResponse($products);
    }
}
