<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Order $model
     * @return OrderCollection|JsonResponse
     */
    public function index(Request $request, Order $model)
    {
        /** @var LengthAwarePaginator $paginator */
        $paginator = $model->paginate($request->input('limit'))->appends($request->query());

        return new OrderCollection($paginator);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderCreateRequest $request
     * @param Order $model
     * @return OrderResource|JsonResponse
     */
    public function store(OrderCreateRequest $request, Order $model)
    {
        $order = $model->create([
            'order_id' => $request->getOrderId(),
            'quantity' => $request->getQuantity(),
            'product_id' => $request->getProductId()
        ]);
        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order $order
     * @return OrderResource|JsonResponse
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OrderUpdateRequest $request
     * @param  \App\Order $order
     * @return OrderResource|JsonResponse
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $order->update(array_filter([
            'order_id' => $request->getOrderId(),
            'quantity' => $request->getQuantity(),
            'product_id' => $request->getProductId()
        ]));
        return new OrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
