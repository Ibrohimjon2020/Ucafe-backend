<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;  
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderColumn;
use App\Services\OrderService;
use App\Services\OrderItemService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Throwable;

/**
 * Class OrderController
 * @package  App\Http\Controllers
 */
class OrderController extends \App\Http\Controllers\Controller
{
    private OrderService $service;
    private OrderItemService $serviceItem;


    public function __construct(OrderService $service, OrderItemService $serviceItem)
    {
        $this->service = $service;
        $this->serviceItem = $serviceItem;
    }

    /**
     * @OA\Get(
     *  path="/api/orders",
     *  operationId="indexOrder",
     *  tags={"Orders"},
     *  summary="Get list of Order",
     *  description="Returns list of Order",
     *  @OA\Response(response=200, description="Successful operation",
     *    @OA\JsonContent(ref="#/components/schemas/Orders"),
     *  ),
     * )
     *
     * Display a listing of Order.
     * @return LengthAwarePaginator
     * @throws Throwable
     */
    public function index(Request $request)
    {
        return $this->service->paginatedList($request);
    }

    /**
     * @OA\Post(
     *  operationId="storeOrder",
     *  summary="Insert a new Order",
     *  description="Insert a new Order",
     *  tags={"Orders"},
     *  path="/api/orders",
     *  @OA\RequestBody(
     *    description="Order to create",
     *    required=true,
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *      @OA\Property(
     *      title="data",
     *      property="data",
     *      type="object",
     *      ref="#/components/schemas/Order")
     *     )
     *    )
     *  ),
     *  @OA\Response(response="201",description="Order created",
     *     @OA\JsonContent(
     *      @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/Order"
     *      ),
     *    ),
     *  ),
     *  @OA\Response(response=422,description="Validation exception"),
     * )
     *
     * @param StoreOrderRequest $request
     * @return array|Builder|Collection|Order|Builder[]|Order[]
     * @throws Throwable
     */
    public function store(StoreOrderRequest $request): JsonResponse|array|Builder|Collection|Order
    {
        return $this->service->createModel($request->validated());

    }

    /**
     * @OA\Get(
     *   path="/api/orders/{order_id}",
     *   summary="Show a Order from his Id",
     *   description="Show a Order from his Id",
     *   operationId="showOrder",
     *   tags={"Orders"},
     *   @OA\Parameter(ref="#/components/parameters/Order--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/Order"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="Order not found"),
     * )
     *
     * @param $productId
     * @return JsonResponse
     * @throws Throwable
     */
    public function show($productId): Model
    {
        return $this->service->getModelById($productId);
    }

    /**
     * @OA\Patch(
     *   operationId="updateOrder",
     *   summary="Update an existing Order",
     *   description="Update an existing Order",
     *   tags={"Orders"},
     *   path="/api/orders/{order_id}",
     *   @OA\Parameter(ref="#/components/parameters/Order--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/Order"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="Order not found"),
     *   @OA\RequestBody(
     *     description="Order to update",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *        @OA\Property(
     *        title="data",
     *        property="data",
     *        type="object",
     *        ref="#/components/schemas/Order")
     *      )
     *     )
     *   )
     *
     * )
     *
     * @param UpdateOrderRequest $request
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|Order|Order[]
     * @throws Throwable
     */
    public function update(UpdateOrderRequest $request,int $productId): array|Order|Collection|Builder
    {
        return $this->service->updateModel($request->validated(),$productId);

    }

    /**
     * @OA\Delete(
     *  path="/api/orders/{order_id}",
     *  summary="Delete a Order",
     *  description="Delete a Order",
     *  operationId="destroyOrder",
     *  tags={"Orders"},
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/Order"
     *       ),
     *     ),
     *   ),
     *  @OA\Parameter(ref="#/components/parameters/Order--id"),
     *  @OA\Response(response=204,description="No content"),
     *  @OA\Response(response=404,description="Order not found"),
     * )
     *
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|Order|Order[]
     * @throws Throwable
     */
    public function destroy(int $productId): array|Builder|Collection|Order
    {
        return $this->service->deleteModel($productId);
    }
    
    // That is change order status
    public function changeStatus(Request $request){
        $orderColumn = OrderColumn::find($request->status);
        $order = Order::find($request->id);
        if ($orderColumn && $order){
            $order->order_status = $request->status;
            $order->save();
        }else{
            $order = response()->json(['message' => 'Bad Request'], 400);;
        }
        return $order;

    }

    
}
