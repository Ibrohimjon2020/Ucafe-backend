<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Models\MenuItem;
use App\Services\MenuItemService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * Class MenuItemController
 * @package  App\Http\Controllers
 */
class MenuItemController extends \App\Http\Controllers\Controller
{
    private MenuItemService $service;

    public function __construct(MenuItemService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *  path="/api/menu-item",
     *  operationId="indexMenuItem",
     *  tags={"MenuItems"},
     *  summary="Get list of MenuItem",
     *  description="Returns list of MenuItem",
     *  @OA\Response(response=200, description="Successful operation",
     *    @OA\JsonContent(ref="#/components/schemas/MenuItems"),
     *  ),
     * )
     *
     * Display a listing of MenuItem.
     * @return LengthAwarePaginator
     * @throws Throwable
     */
    public function index(): LengthAwarePaginator
    {
        return $this->service->paginatedList();
    }

    /**
     * @OA\Post(
     *  operationId="storeMenuItem",
     *  summary="Insert a new MenuItem",
     *  description="Insert a new MenuItem",
     *  tags={"MenuItems"},
     *  path="/api/menu-item",
     *  @OA\RequestBody(
     *    description="MenuItem to create",
     *    required=true,
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *      @OA\Property(
     *      title="data",
     *      property="data",
     *      type="object",
     *      ref="#/components/schemas/MenuItem")
     *     )
     *    )
     *  ),
     *  @OA\Response(response="201",description="MenuItem created",
     *     @OA\JsonContent(
     *      @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/MenuItem"
     *      ),
     *    ),
     *  ),
     *  @OA\Response(response=422,description="Validation exception"),
     * )
     *
     * @param StoreMenuItemRequest $request
     * @return array|Builder|Collection|MenuItem|Builder[]|MenuItem[]
     * @throws Throwable
     */
    public function store(StoreMenuItemRequest $request): array|Builder|Collection|MenuItem
    {
        return $this->service->createModel($request->validated());

    }

    /**
     * @OA\Get(
     *   path="/api/menu-item/{menuitem_id}",
     *   summary="Show a MenuItem from his Id",
     *   description="Show a MenuItem from his Id",
     *   operationId="showMenuItem",
     *   tags={"MenuItems"},
     *   @OA\Parameter(ref="#/components/parameters/MenuItem--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/MenuItem"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="MenuItem not found"),
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
     *   operationId="updateMenuItem",
     *   summary="Update an existing MenuItem",
     *   description="Update an existing MenuItem",
     *   tags={"MenuItems"},
     *   path="/api/menu-item/{menuitem_id}",
     *   @OA\Parameter(ref="#/components/parameters/MenuItem--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/MenuItem"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="MenuItem not found"),
     *   @OA\RequestBody(
     *     description="MenuItem to update",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *        @OA\Property(
     *        title="data",
     *        property="data",
     *        type="object",
     *        ref="#/components/schemas/MenuItem")
     *      )
     *     )
     *   )
     *
     * )
     *
     * @param UpdateMenuItemRequest $request
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|MenuItem|MenuItem[]
     * @throws Throwable
     */
    public function update(UpdateMenuItemRequest $request,int $productId): array|MenuItem|Collection|Builder
    {
        return $this->service->updateModel($request->validated(),$productId);

    }

    /**
     * @OA\Delete(
     *  path="/api/menu-item/{menuitem_id}",
     *  summary="Delete a MenuItem",
     *  description="Delete a MenuItem",
     *  operationId="destroyMenuItem",
     *  tags={"MenuItems"},
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/MenuItem"
     *       ),
     *     ),
     *   ),
     *  @OA\Parameter(ref="#/components/parameters/MenuItem--id"),
     *  @OA\Response(response=204,description="No content"),
     *  @OA\Response(response=404,description="MenuItem not found"),
     * )
     *
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|MenuItem|MenuItem[]
     * @throws Throwable
     */
    public function destroy(int $productId): array|Builder|Collection|MenuItem
    {
        return $this->service->deleteModel($productId);
    }
}
