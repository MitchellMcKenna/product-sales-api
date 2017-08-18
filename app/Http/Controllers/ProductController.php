<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Responses\ProductCollectionResponse;
use App\Http\Responses\ProductResponse;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var LengthAwarePaginator $paginator */
        $paginator = (Product::paginate($request->input('limit')))->appends($request->query());
        $products = $paginator->getCollection();
        return new ProductCollectionResponse($products, $paginator);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCreateRequest $request)
    {
        return new ProductResponse(Product::create(['name' => $request->getName()]), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResponse($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductUpdateRequest|Request $request
     * @param  \App\Product $product
     * @return Response
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->name = $request->getName();
        $product->save();
        return new ProductResponse($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
