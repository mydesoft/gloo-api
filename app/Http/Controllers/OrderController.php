<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Actions\CreateOrderAction;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function getOrders(Request $request)
    {
        $orders = Order::query()
                   ->latest()
                   ->paginate($request->per_page ?? 2);

         return response()->json([
            'status' => 'success',
            'data' => [$orders],
        ], Response::HTTP_OK);                  
    }

    public function storeOrder(OrderRequest $request, CreateOrderAction $createOrderAction): JsonResponse
    {
        $data = $request->validated();

         try {
                DB::beginTransaction();

                $order = $createOrderAction->executeOrderCreation($data);

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'msg' => 'Order created successfully',
                    'data' => [ new OrderResource($order)],
                ], Response::HTTP_CREATED);

        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return response()->json([
                'status' => 'fail',
                'msg' => 'Something went wrong, please try again',
                'data' => [],
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    public function singleOrder($id): JsonResponse
    {
        $order = Order::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [ new OrderResource($order)],
        ], Response::HTTP_OK);
    }
}
