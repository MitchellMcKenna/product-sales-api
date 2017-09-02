<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopSellersRequest;
use App\Http\Responses\ProductCollectionResponse;
use App\Queries\TopSellersQuery;
use Illuminate\Pagination\LengthAwarePaginator;

class TopSellersController extends Controller
{
    public function index(TopSellersRequest $request, TopSellersQuery $query)
    {
        $products = $query->get($request->getBegin(), $request->getEnd(), $request->getPage(), $request->getLimit());

        $paginator = (new LengthAwarePaginator(
            $products,
            $query->total($request->getBegin(), $request->getEnd()),
            $request->getLimit(),
            $request->getPage(),
            ['path' => $request->url()]
        ))->appends($request->query());

        return new ProductCollectionResponse($products, $paginator);
    }
}
