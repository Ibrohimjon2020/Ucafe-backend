<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreMenuTypeRequest;
use App\Http\Requests\UpdateMenuTypeRequest;
use App\Models\MenuType;
use App\Services\MenuTypeService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * Class MenuTypeController
 * @package  App\Http\Controllers
 */
class MenuTypeController extends \App\Http\Controllers\Controller
{
    private MenuTypeService $service;

    public function __construct(MenuTypeService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *  path="/api/menu-type",
     *  operationId="indexMenuType",
     *  tags={"MenuTypes"},
     *  summary="Get list of MenuType",
     *  description="Returns list of MenuType",
     *  @OA\Response(response=200, description="Successful operation",
     *    @OA\JsonContent(ref="#/components/schemas/MenuTypes"),
     *  ),
     * )
     *
     * Display a listing of MenuType.
     * @return LengthAwarePaginator
     * @throws Throwable
     */
    public function index(): LengthAwarePaginator
    {
        return $this->service->paginatedList();
    }

    /**
     * @OA\Post(
     *  operationId="storeMenuType",
     *  summary="Insert a new MenuType",
     *  description="Insert a new MenuType",
     *  tags={"MenuTypes"},
     *  path="/api/menu-type",
     *  @OA\RequestBody(
     *    description="MenuType to create",
     *    required=true,
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *      @OA\Property(
     *      title="data",
     *      property="data",
     *      type="object",
     *      ref="#/components/schemas/MenuType")
     *     )
     *    )
     *  ),
     *  @OA\Response(response="201",description="MenuType created",
     *     @OA\JsonContent(
     *      @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/MenuType"
     *      ),
     *    ),
     *  ),
     *  @OA\Response(response=422,description="Validation exception"),
     * )
     *
     * @param StoreMenuTypeRequest $request
     * @return array|Builder|Collection|MenuType|Builder[]|MenuType[]
     * @throws Throwable
     */
    public function store(StoreMenuTypeRequest $request): array|Builder|Collection|MenuType
    {
        return $this->service->createModel($request->validated());

    }

    /**
     * @OA\Get(
     *   path="/api/menu-type/{menutype_id}",
     *   summary="Show a MenuType from his Id",
     *   description="Show a MenuType from his Id",
     *   operationId="showMenuType",
     *   tags={"MenuTypes"},
     *   @OA\Parameter(ref="#/components/parameters/MenuType--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/MenuType"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="MenuType not found"),
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
     *   operationId="updateMenuType",
     *   summary="Update an existing MenuType",
     *   description="Update an existing MenuType",
     *   tags={"MenuTypes"},
     *   path="/api/menu-type/{menutype_id}",
     *   @OA\Parameter(ref="#/components/parameters/MenuType--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/MenuType"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="MenuType not found"),
     *   @OA\RequestBody(
     *     description="MenuType to update",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *        @OA\Property(
     *        title="data",
     *        property="data",
     *        type="object",
     *        ref="#/components/schemas/MenuType")
     *      )
     *     )
     *   )
     *
     * )
     *
     * @param UpdateMenuTypeRequest $request
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|MenuType|MenuType[]
     * @throws Throwable
     */
    public function update(UpdateMenuTypeRequest $request,int $productId): array|MenuType|Collection|Builder
    {
        return $this->service->updateModel($request->validated(),$productId);

    }

    /**
     * @OA\Delete(
     *  path="/api/menu-type/{menutype_id}",
     *  summary="Delete a MenuType",
     *  description="Delete a MenuType",
     *  operationId="destroyMenuType",
     *  tags={"MenuTypes"},
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/MenuType"
     *       ),
     *     ),
     *   ),
     *  @OA\Parameter(ref="#/components/parameters/MenuType--id"),
     *  @OA\Response(response=204,description="No content"),
     *  @OA\Response(response=404,description="MenuType not found"),
     * )
     *
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|MenuType|MenuType[]
     * @throws Throwable
     */
    public function destroy(int $productId): array|Builder|Collection|MenuType
    {
        return $this->service->deleteModel($productId);
    }
}
