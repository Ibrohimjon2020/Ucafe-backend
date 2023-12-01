<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderColumnRequest;
use App\Http\Requests\UpdateOrderColumnRequest;
use App\Models\OrderColumn;
use App\Services\OrderColumnService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * Class OrderColumnController
 * @package  App\Http\Controllers
 */
class OrderColumnController extends \App\Http\Controllers\Controller
{
    private OrderColumnService $service;

    public function __construct(OrderColumnService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *  path="/api/order-column",
     *  operationId="indexOrderColumn",
     *  tags={"OrderColumns"},
     *  summary="Get list of OrderColumn",
     *  description="Returns list of OrderColumn",
     *  @OA\Response(response=200, description="Successful operation",
     *    @OA\JsonContent(ref="#/components/schemas/OrderColumns"),
     *  ),
     * )
     *
     * Display a listing of OrderColumn.
     * @return LengthAwarePaginator
     * @throws Throwable
     */
    public function index(): LengthAwarePaginator
    {
        return $this->service->paginatedList();
    }

    /**
     * @OA\Post(
     *  operationId="storeOrderColumn",
     *  summary="Insert a new OrderColumn",
     *  description="Insert a new OrderColumn",
     *  tags={"OrderColumns"},
     *  path="/api/order-column",
     *  @OA\RequestBody(
     *    description="OrderColumn to create",
     *    required=true,
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *      @OA\Property(
     *      title="data",
     *      property="data",
     *      type="object",
     *      ref="#/components/schemas/OrderColumn")
     *     )
     *    )
     *  ),
     *  @OA\Response(response="201",description="OrderColumn created",
     *     @OA\JsonContent(
     *      @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/OrderColumn"
     *      ),
     *    ),
     *  ),
     *  @OA\Response(response=422,description="Validation exception"),
     * )
     *
     * @param StoreOrderColumnRequest $request
     * @return array|Builder|Collection|OrderColumn|Builder[]|OrderColumn[]
     * @throws Throwable
     */
    public function store(StoreOrderColumnRequest $request): array|Builder|Collection|OrderColumn
    {
        return $this->service->createModel($request->validated());

    }

    /**
     * @OA\Get(
     *   path="/api/order-column/{ordercolumn_id}",
     *   summary="Show a OrderColumn from his Id",
     *   description="Show a OrderColumn from his Id",
     *   operationId="showOrderColumn",
     *   tags={"OrderColumns"},
     *   @OA\Parameter(ref="#/components/parameters/OrderColumn--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/OrderColumn"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="OrderColumn not found"),
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
     *   operationId="updateOrderColumn",
     *   summary="Update an existing OrderColumn",
     *   description="Update an existing OrderColumn",
     *   tags={"OrderColumns"},
     *   path="/api/order-column/{ordercolumn_id}",
     *   @OA\Parameter(ref="#/components/parameters/OrderColumn--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/OrderColumn"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="OrderColumn not found"),
     *   @OA\RequestBody(
     *     description="OrderColumn to update",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *        @OA\Property(
     *        title="data",
     *        property="data",
     *        type="object",
     *        ref="#/components/schemas/OrderColumn")
     *      )
     *     )
     *   )
     *
     * )
     *
     * @param UpdateOrderColumnRequest $request
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|OrderColumn|OrderColumn[]
     * @throws Throwable
     */
    public function update(UpdateOrderColumnRequest $request,int $productId): array|OrderColumn|Collection|Builder
    {
        return $this->service->updateModel($request->validated(),$productId);

    }

    /**
     * @OA\Delete(
     *  path="/api/order-column/{ordercolumn_id}",
     *  summary="Delete a OrderColumn",
     *  description="Delete a OrderColumn",
     *  operationId="destroyOrderColumn",
     *  tags={"OrderColumns"},
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/OrderColumn"
     *       ),
     *     ),
     *   ),
     *  @OA\Parameter(ref="#/components/parameters/OrderColumn--id"),
     *  @OA\Response(response=204,description="No content"),
     *  @OA\Response(response=404,description="OrderColumn not found"),
     * )
     *
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|OrderColumn|OrderColumn[]
     * @throws Throwable
     */
    public function destroy(int $productId): array|Builder|Collection|OrderColumn
    {
        return $this->service->deleteModel($productId);
    }
}
