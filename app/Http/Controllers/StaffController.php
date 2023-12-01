<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Staff;
use App\Services\StaffService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * Class StaffController
 * @package  App\Http\Controllers
 */
class StaffController extends \App\Http\Controllers\Controller
{
    private StaffService $service;

    public function __construct(StaffService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *  path="/api/staff",
     *  operationId="indexStaff",
     *  tags={"Staffs"},
     *  summary="Get list of Staff",
     *  description="Returns list of Staff",
     *  @OA\Response(response=200, description="Successful operation",
     *    @OA\JsonContent(ref="#/components/schemas/Staffs"),
     *  ),
     * )
     *
     * Display a listing of Staff.
     * @return LengthAwarePaginator
     * @throws Throwable
     */
    public function index(): LengthAwarePaginator
    {
        return $this->service->paginatedList();
    }

    /**
     * @OA\Post(
     *  operationId="storeStaff",
     *  summary="Insert a new Staff",
     *  description="Insert a new Staff",
     *  tags={"Staffs"},
     *  path="/api/staff",
     *  @OA\RequestBody(
     *    description="Staff to create",
     *    required=true,
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *      @OA\Property(
     *      title="data",
     *      property="data",
     *      type="object",
     *      ref="#/components/schemas/Staff")
     *     )
     *    )
     *  ),
     *  @OA\Response(response="201",description="Staff created",
     *     @OA\JsonContent(
     *      @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/Staff"
     *      ),
     *    ),
     *  ),
     *  @OA\Response(response=422,description="Validation exception"),
     * )
     *
     * @param StoreStaffRequest $request
     * @return array|Builder|Collection|Staff|Builder[]|Staff[]
     * @throws Throwable
     */
    public function store(StoreStaffRequest $request): array|Builder|Collection|Staff
    {
        return $this->service->createModel($request->validated());

    }

    /**
     * @OA\Get(
     *   path="/api/staff/{staff_id}",
     *   summary="Show a Staff from his Id",
     *   description="Show a Staff from his Id",
     *   operationId="showStaff",
     *   tags={"Staffs"},
     *   @OA\Parameter(ref="#/components/parameters/Staff--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/Staff"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="Staff not found"),
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
     *   operationId="updateStaff",
     *   summary="Update an existing Staff",
     *   description="Update an existing Staff",
     *   tags={"Staffs"},
     *   path="/api/staff/{staff_id}",
     *   @OA\Parameter(ref="#/components/parameters/Staff--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/Staff"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="Staff not found"),
     *   @OA\RequestBody(
     *     description="Staff to update",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *        @OA\Property(
     *        title="data",
     *        property="data",
     *        type="object",
     *        ref="#/components/schemas/Staff")
     *      )
     *     )
     *   )
     *
     * )
     *
     * @param UpdateStaffRequest $request
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|Staff|Staff[]
     * @throws Throwable
     */
    public function update(UpdateStaffRequest $request,int $productId): array|Staff|Collection|Builder
    {
        return $this->service->updateModel($request->validated(),$productId);

    }

    /**
     * @OA\Delete(
     *  path="/api/staff/{staff_id}",
     *  summary="Delete a Staff",
     *  description="Delete a Staff",
     *  operationId="destroyStaff",
     *  tags={"Staffs"},
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/Staff"
     *       ),
     *     ),
     *   ),
     *  @OA\Parameter(ref="#/components/parameters/Staff--id"),
     *  @OA\Response(response=204,description="No content"),
     *  @OA\Response(response=404,description="Staff not found"),
     * )
     *
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|Staff|Staff[]
     * @throws Throwable
     */
    public function destroy(int $productId): array|Builder|Collection|Staff
    {
        return $this->service->deleteModel($productId);
    }
}
