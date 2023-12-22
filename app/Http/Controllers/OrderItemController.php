<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderItemRequest;
use App\Http\Requests\UpdateOrderItemRequest;
use App\Models\OrderItem;
use App\Services\OrderItemService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * Class OrderItemController
 * @package  App\Http\Controllers
 */
class OrderItemController extends \App\Http\Controllers\Controller
{
    private OrderItemService $service;

    public function __construct(OrderItemService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *  path="/api/order_items",
     *  operationId="indexOrderItem",
     *  tags={"OrderItems"},
     *  summary="Get list of OrderItem",
     *  description="Returns list of OrderItem",
     *  @OA\Response(response=200, description="Successful operation",
     *    @OA\JsonContent(ref="#/components/schemas/OrderItems"),
     *  ),
     * )
     *
     * Display a listing of OrderItem.
     * @return LengthAwarePaginator
     * @throws Throwable
     */
    public function index(): LengthAwarePaginator
    {
        return $this->service->paginatedList();
    }

    /**
     * @OA\Post(
     *  operationId="storeOrderItem",
     *  summary="Insert a new OrderItem",
     *  description="Insert a new OrderItem",
     *  tags={"OrderItems"},
     *  path="/api/order_items",
     *  @OA\RequestBody(
     *    description="OrderItem to create",
     *    required=true,
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *      @OA\Property(
     *      title="data",
     *      property="data",
     *      type="object",
     *      ref="#/components/schemas/OrderItem")
     *     )
     *    )
     *  ),
     *  @OA\Response(response="201",description="OrderItem created",
     *     @OA\JsonContent(
     *      @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/OrderItem"
     *      ),
     *    ),
     *  ),
     *  @OA\Response(response=422,description="Validation exception"),
     * )
     *
     * @param StoreOrderItemRequest $request
     * @return array|Builder|Collection|OrderItem|Builder[]|OrderItem[]
     * @throws Throwable
     */
    public function store(StoreOrderItemRequest $request): array|Builder|Collection|OrderItem
    {
        return $this->service->createModel($request->validated());

    }

    /**
     * @OA\Get(
     *   path="/api/order_items/{orderitem_id}",
     *   summary="Show a OrderItem from his Id",
     *   description="Show a OrderItem from his Id",
     *   operationId="showOrderItem",
     *   tags={"OrderItems"},
     *   @OA\Parameter(ref="#/components/parameters/OrderItem--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/OrderItem"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="OrderItem not found"),
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
     *   operationId="updateOrderItem",
     *   summary="Update an existing OrderItem",
     *   description="Update an existing OrderItem",
     *   tags={"OrderItems"},
     *   path="/api/order_items/{orderitem_id}",
     *   @OA\Parameter(ref="#/components/parameters/OrderItem--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/OrderItem"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="OrderItem not found"),
     *   @OA\RequestBody(
     *     description="OrderItem to update",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *        @OA\Property(
     *        title="data",
     *        property="data",
     *        type="object",
     *        ref="#/components/schemas/OrderItem")
     *      )
     *     )
     *   )
     *
     * )
     *
     * @param UpdateOrderItemRequest $request
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|OrderItem|OrderItem[]
     * @throws Throwable
     */
    public function update(UpdateOrderItemRequest $request,int $productId): array|OrderItem|Collection|Builder
    {
        return $this->service->updateModel($request->validated(),$productId);

    }

    /**
     * @OA\Delete(
     *  path="/api/order_items/{orderitem_id}",
     *  summary="Delete a OrderItem",
     *  description="Delete a OrderItem",
     *  operationId="destroyOrderItem",
     *  tags={"OrderItems"},
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/OrderItem"
     *       ),
     *     ),
     *   ),
     *  @OA\Parameter(ref="#/components/parameters/OrderItem--id"),
     *  @OA\Response(response=204,description="No content"),
     *  @OA\Response(response=404,description="OrderItem not found"),
     * )
     *
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|OrderItem|OrderItem[]
     * @throws Throwable
     */
    public function destroy(int $productId): array|Builder|Collection|OrderItem
    {
        return $this->service->deleteModel($productId);
    }
}
