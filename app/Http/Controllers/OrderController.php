<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Responses\OrderCollectionResponse;
use App\Http\Responses\OrderResponse;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderController extends Controller
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
        $paginator = (Order::paginate($request->input('limit')))->appends($request->query());
        $orders = $paginator->getCollection();

        return new OrderCollectionResponse($orders, $paginator);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderCreateRequest $request)
    {
        $order = Order::create([
            'order_id' => $request->getOrderId(),
            'quantity' => $request->getQuantity(),
            'product_id' => $request->getProductId()
        ]);
        return new OrderResponse($order, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return new OrderResponse($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OrderUpdateRequest $request
     * @param  \App\Order $order
     * @return Response
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $order->update(array_filter([
            'order_id' => $request->getOrderId(),
            'quantity' => $request->getQuantity(),
            'product_id' => $request->getProductId()
        ]));
        return new OrderResponse($order);
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
