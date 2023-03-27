<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Controllers\OrderRequest;
use App\Models\Order;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Resources\Api\V1\OrderResource;

class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/order",
     *     summary="Get all personal orders",
     *     tags={"Orders"},
     *     security={{"api_key": {}}},
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function index()
    {
        $orders = Order::with(['user', 'orderStatus', 'payment'])->get();

        if (empty($orders)) {
            throw new HttpException("Error Processing Request", Response::HTTP_NOT_FOUND);
        }

        return OrderResource::collection($orders);
    }

    /**
     * @OA\Post(
     *     path="/order",
     *     summary="Create a new Order",
     *     security={{"api_key": {}}},
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="order_status_id", type="uuid"),
     *             @OA\Property(property="products", type="object", example="[{'product': 'product_uuid', 'quantity': '1'}]"),
     *             @OA\Property(property="delivery_fee", type="float", example="22.5"),
     *             @OA\Property(property="amount", type="integer", example="2"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Order created successfully"),
     *     @OA\Response(response="201", description="Order created successfully"),
     *     @OA\Response(response="404", description="Not created"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */
    public function store(OrderRequest $request)
    {
        $order = Order::create([
            'user_id' => auth()->user()->uuid,
            'order_status_id' => $request->validated('order_status_id'),
            'products' => json_encode($request->validated('products')),
            'delivery_fee' => $request->validated('delivery_fee'),
            'amount' => $request->validated('amount'),
        ]);

        return OrderResource::make($order->load('user', 'orderStatus', 'payment'));
    }

    /**
     * @OA\Get(
     *     path="/order/{id}",
     *     summary="Get order by ID",
     *     tags={"Orders"},
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the order (UUID)",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="404", description="Not Found")
     * )
     */
    public function show(Order $order)
    {
        return (OrderResource::make($order));
    }

    /**
     * @OA\Put(
     *     path="/order/{id}",
     *     summary="Update an new Order",
     *     security={{"api_key": {}}},
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the order (UUID)",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="order_status_id", type="uuid"),
     *             @OA\Property(property="products", type="object", example="[{'product': 'product_uuid', 'quantity': '1'}]"),
     *             @OA\Property(property="delivery_fee", type="float", example="22.5"),
     *             @OA\Property(property="amount", type="integer", example="2"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Order created successfully"),
     *     @OA\Response(response="202", description="Order created successfully"),
     *     @OA\Response(response="404", description="Not created"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */
    public function update(OrderRequest $orderRequest, Order $order)
    {
        $order->update([
            'user_id' => auth()->user()->uuid,
            'order_status_id' => $orderRequest->validated('order_status_id'),
            'products' => json_encode($orderRequest->validated('products')),
            'delivery_fee' => $orderRequest->validated('delivery_fee'),
            'amount' => $orderRequest->validated('amount'),
        ]);

        return response()->json(OrderResource::make($order), Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(
     *     path="/order/{id}",
     *     summary="Delete an order by ID",
     *     tags={"Orders"},
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the order (UUID)",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(response="204", description="Deleted Success"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="404", description="Not Found")
     * )
     */
    public function destroy(Order $order)
    {
        $status = $order->delete();
        if (!$status) {
            return response()->json([
                'errors' => [
                    'general' => [
                        'Couldn\'t remove the order, please check if this order has an some pending status'
                    ]
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->noContent();
    }
}
