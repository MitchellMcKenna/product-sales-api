<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Product $model
     * @return ProductCollection|JsonResponse
     */
    public function index(Request $request, Product $model)
    {
        /** @var LengthAwarePaginator $paginator */
        $paginator = $model->paginate($request->input('limit'))->appends($request->query());
        return new ProductCollection($paginator);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductCreateRequest $request
     * @param Product $model
     * @return ProductResource|JsonResponse
     */
    public function store(ProductCreateRequest $request, Product $model)
    {
        $product = $model->create(['name' => $request->getName()]);
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return ProductResource|JsonResponse
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductUpdateRequest|Request $request
     * @param  \App\Product $product
     * @return ProductResource|JsonResponse
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->name = $request->getName();
        $product->save();
        return new ProductResource($product);
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
