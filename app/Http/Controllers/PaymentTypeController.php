<?php
namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentTypeRequest;
use App\Http\Requests\UpdatePaymentTypeRequest;
use App\Models\PaymentType;
use App\Services\PaymentTypeService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * Class PaymentTypeController
 * @package  App\Http\Controllers
 */
class PaymentTypeController extends \App\Http\Controllers\Controller
{
    private PaymentTypeService $service;

    public function __construct(PaymentTypeService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *  path="/api/payment_types",
     *  operationId="indexPaymentType",
     *  tags={"PaymentTypes"},
     *  summary="Get list of PaymentType",
     *  description="Returns list of PaymentType",
     *  @OA\Response(response=200, description="Successful operation",
     *    @OA\JsonContent(ref="#/components/schemas/PaymentTypes"),
     *  ),
     * )
     *
     * Display a listing of PaymentType.
     * @return LengthAwarePaginator
     * @throws Throwable
     */
    public function index(): LengthAwarePaginator
    {
        return $this->service->paginatedList();
    }

    /**
     * @OA\Post(
     *  operationId="storePaymentType",
     *  summary="Insert a new PaymentType",
     *  description="Insert a new PaymentType",
     *  tags={"PaymentTypes"},
     *  path="/api/payment_types",
     *  @OA\RequestBody(
     *    description="PaymentType to create",
     *    required=true,
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *      @OA\Property(
     *      title="data",
     *      property="data",
     *      type="object",
     *      ref="#/components/schemas/PaymentType")
     *     )
     *    )
     *  ),
     *  @OA\Response(response="201",description="PaymentType created",
     *     @OA\JsonContent(
     *      @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/PaymentType"
     *      ),
     *    ),
     *  ),
     *  @OA\Response(response=422,description="Validation exception"),
     * )
     *
     * @param StorePaymentTypeRequest $request
     * @return array|Builder|Collection|PaymentType|Builder[]|PaymentType[]
     * @throws Throwable
     */
    public function store(StorePaymentTypeRequest $request): array|Builder|Collection|PaymentType
    {
        return $this->service->createModel($request->validated());

    }

    /**
     * @OA\Get(
     *   path="/api/payment_types/{paymenttype_id}",
     *   summary="Show a PaymentType from his Id",
     *   description="Show a PaymentType from his Id",
     *   operationId="showPaymentType",
     *   tags={"PaymentTypes"},
     *   @OA\Parameter(ref="#/components/parameters/PaymentType--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/PaymentType"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="PaymentType not found"),
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
     *   operationId="updatePaymentType",
     *   summary="Update an existing PaymentType",
     *   description="Update an existing PaymentType",
     *   tags={"PaymentTypes"},
     *   path="/api/payment_types/{paymenttype_id}",
     *   @OA\Parameter(ref="#/components/parameters/PaymentType--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/PaymentType"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="PaymentType not found"),
     *   @OA\RequestBody(
     *     description="PaymentType to update",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *        @OA\Property(
     *        title="data",
     *        property="data",
     *        type="object",
     *        ref="#/components/schemas/PaymentType")
     *      )
     *     )
     *   )
     *
     * )
     *
     * @param UpdatePaymentTypeRequest $request
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|PaymentType|PaymentType[]
     * @throws Throwable
     */
    public function update(UpdatePaymentTypeRequest $request,int $productId): array|PaymentType|Collection|Builder
    {
        return $this->service->updateModel($request->validated(),$productId);

    }

    /**
     * @OA\Delete(
     *  path="/api/payment_types/{paymenttype_id}",
     *  summary="Delete a PaymentType",
     *  description="Delete a PaymentType",
     *  operationId="destroyPaymentType",
     *  tags={"PaymentTypes"},
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/PaymentType"
     *       ),
     *     ),
     *   ),
     *  @OA\Parameter(ref="#/components/parameters/PaymentType--id"),
     *  @OA\Response(response=204,description="No content"),
     *  @OA\Response(response=404,description="PaymentType not found"),
     * )
     *
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|PaymentType|PaymentType[]
     * @throws Throwable
     */
    public function destroy(int $productId): array|Builder|Collection|PaymentType
    {
        return $this->service->deleteModel($productId);
    }
}
