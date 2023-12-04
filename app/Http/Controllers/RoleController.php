<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * Class RoleController
 * @package  App\Http\Controllers
 */
class RoleController extends \App\Http\Controllers\Controller
{
    private RoleService $service;

    public function __construct(RoleService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *  path="/api/roles",
     *  operationId="indexRole",
     *  tags={"Roles"},
     *  summary="Get list of Role",
     *  description="Returns list of Role",
     *  @OA\Response(response=200, description="Successful operation",
     *    @OA\JsonContent(ref="#/components/schemas/Roles"),
     *  ),
     * )
     *
     * Display a listing of Role.
     * @return LengthAwarePaginator
     * @throws Throwable
     */
    public function index(): LengthAwarePaginator
    {
        return $this->service->paginatedList();
    }

    /**
     * @OA\Post(
     *  operationId="storeRole",
     *  summary="Insert a new Role",
     *  description="Insert a new Role",
     *  tags={"Roles"},
     *  path="/api/roles",
     *  @OA\RequestBody(
     *    description="Role to create",
     *    required=true,
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *      @OA\Property(
     *      title="data",
     *      property="data",
     *      type="object",
     *      ref="#/components/schemas/Role")
     *     )
     *    )
     *  ),
     *  @OA\Response(response="201",description="Role created",
     *     @OA\JsonContent(
     *      @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/Role"
     *      ),
     *    ),
     *  ),
     *  @OA\Response(response=422,description="Validation exception"),
     * )
     *
     * @param StoreRoleRequest $request
     * @return array|Builder|Collection|Role|Builder[]|Role[]
     * @throws Throwable
     */
    public function store(StoreRoleRequest $request): array|Builder|Collection|Role
    {
        return $this->service->createModel($request->validated());

    }

    /**
     * @OA\Get(
     *   path="/api/roles/{role_id}",
     *   summary="Show a Role from his Id",
     *   description="Show a Role from his Id",
     *   operationId="showRole",
     *   tags={"Roles"},
     *   @OA\Parameter(ref="#/components/parameters/Role--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/Role"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="Role not found"),
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
     *   operationId="updateRole",
     *   summary="Update an existing Role",
     *   description="Update an existing Role",
     *   tags={"Roles"},
     *   path="/api/roles/{role_id}",
     *   @OA\Parameter(ref="#/components/parameters/Role--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/Role"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="Role not found"),
     *   @OA\RequestBody(
     *     description="Role to update",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *        @OA\Property(
     *        title="data",
     *        property="data",
     *        type="object",
     *        ref="#/components/schemas/Role")
     *      )
     *     )
     *   )
     *
     * )
     *
     * @param UpdateRoleRequest $request
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|Role|Role[]
     * @throws Throwable
     */
    public function update(UpdateRoleRequest $request,int $productId): array|Role|Collection|Builder
    {
        return $this->service->updateModel($request->validated(),$productId);

    }

    /**
     * @OA\Delete(
     *  path="/api/roles/{role_id}",
     *  summary="Delete a Role",
     *  description="Delete a Role",
     *  operationId="destroyRole",
     *  tags={"Roles"},
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/Role"
     *       ),
     *     ),
     *   ),
     *  @OA\Parameter(ref="#/components/parameters/Role--id"),
     *  @OA\Response(response=204,description="No content"),
     *  @OA\Response(response=404,description="Role not found"),
     * )
     *
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|Role|Role[]
     * @throws Throwable
     */
    public function destroy(int $productId): array|Builder|Collection|Role
    {
        return $this->service->deleteModel($productId);
    }
}
