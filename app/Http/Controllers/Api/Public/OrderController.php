<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\MyOrderResource;
use App\Http\Resources\PublicOrderStatusResource;
use App\Services\Public\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class OrderController extends Controller
{
    /**
     * Cursor-paginated orders for the authenticated customer (with tickets + scan codes).
     */
    /**
     * Order details and payment status for the authenticated customer.
     * {order_id} is the order unique_id returned when booking starts.
     */
    public function orderStatus(Request $request, OrderService $orders, string $order_id): JsonResponse
    {
        try {
            $response = $orders->statusForCustomer($request->user(), $order_id);

            return $this->success(
                __('messages.public_order_status'),
                PublicOrderStatusResource::make($response['order']),
            );
        } catch (Throwable $exception) {
            return $this->failed($exception);
        }
    }

    public function myOrdersList(Request $request, OrderService $orders): JsonResponse
    {
        try {
            $filters = [
                'per_page' => $request->filled('per_page')
                    ? $request->integer('per_page')
                    : null,
                'cursor' => $request->filled('cursor')
                    ? $request->string('cursor')->toString()
                    : null,
            ];

            $response = $orders->listForCustomer($request->user(), $filters);
            $paginator = $response['paginator'];

            return $this->successPaginated(
                __('messages.my_orders_list'),
                MyOrderResource::collection($paginator->items()),
                $paginator,
            );
        } catch (Throwable $exception) {
            return $this->failed($exception);
        }
    }
}
