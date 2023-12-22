<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Services\ExpenseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * Class ExpenseController
 * @package  App\Http\Controllers
 */
class ExpenseController extends \App\Http\Controllers\Controller
{
    private ExpenseService $service;

    public function __construct(ExpenseService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *  path="/api/expenses",
     *  operationId="indexExpense",
     *  tags={"Expenses"},
     *  summary="Get list of Expense",
     *  description="Returns list of Expense",
     *  @OA\Response(response=200, description="Successful operation",
     *    @OA\JsonContent(ref="#/components/schemas/Expenses"),
     *  ),
     * )
     *
     * Display a listing of Expense.
     * @return LengthAwarePaginator
     * @throws Throwable
     */
    public function index(): LengthAwarePaginator
    {
        return $this->service->paginatedList(request()->all());
    }

    /**
     * @OA\Post(
     *  operationId="storeExpense",
     *  summary="Insert a new Expense",
     *  description="Insert a new Expense",
     *  tags={"Expenses"},
     *  path="/api/expenses",
     *  @OA\RequestBody(
     *    description="Expense to create",
     *    required=true,
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *      @OA\Property(
     *      title="data",
     *      property="data",
     *      type="object",
     *      ref="#/components/schemas/Expense")
     *     )
     *    )
     *  ),
     *  @OA\Response(response="201",description="Expense created",
     *     @OA\JsonContent(
     *      @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/Expense"
     *      ),
     *    ),
     *  ),
     *  @OA\Response(response=422,description="Validation exception"),
     * )
     *
     * @param StoreExpenseRequest $request
     * @return array|Builder|Collection|Expense|Builder[]|Expense[]
     * @throws Throwable
     */
    public function store(StoreExpenseRequest $request): array|Builder|Collection|Expense
    {
        return $this->service->createModel($request->validated());
    }

    /**
     * @OA\Get(
     *   path="/api/expenses/{expense_id}",
     *   summary="Show a Expense from his Id",
     *   description="Show a Expense from his Id",
     *   operationId="showExpense",
     *   tags={"Expenses"},
     *   @OA\Parameter(ref="#/components/parameters/Expense--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/Expense"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="Expense not found"),
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
     *   operationId="updateExpense",
     *   summary="Update an existing Expense",
     *   description="Update an existing Expense",
     *   tags={"Expenses"},
     *   path="/api/expenses/{expense_id}",
     *   @OA\Parameter(ref="#/components/parameters/Expense--id"),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/Expense"
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response="404",description="Expense not found"),
     *   @OA\RequestBody(
     *     description="Expense to update",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *        @OA\Property(
     *        title="data",
     *        property="data",
     *        type="object",
     *        ref="#/components/schemas/Expense")
     *      )
     *     )
     *   )
     *
     * )
     *
     * @param UpdateExpenseRequest $request
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|Expense|Expense[]
     * @throws Throwable
     */
    public function update(UpdateExpenseRequest $request, int $productId): array|Expense|Collection|Builder
    {
        return $this->service->updateModel($request->validated(), $productId);
    }

    /**
     * @OA\Delete(
     *  path="/api/expenses/{expense_id}",
     *  summary="Delete a Expense",
     *  description="Delete a Expense",
     *  operationId="destroyExpense",
     *  tags={"Expenses"},
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *       @OA\JsonContent(
     *       @OA\Property(
     *       title="data",
     *       property="data",
     *       type="object",
     *       ref="#/components/schemas/Expense"
     *       ),
     *     ),
     *   ),
     *  @OA\Parameter(ref="#/components/parameters/Expense--id"),
     *  @OA\Response(response=204,description="No content"),
     *  @OA\Response(response=404,description="Expense not found"),
     * )
     *
     * @param int $productId
     * @return array|Builder|Builder[]|Collection|Expense|Expense[]
     * @throws Throwable
     */
    public function destroy(int $productId): array|Builder|Collection|Expense
    {
        return $this->service->deleteModel($productId);
    }
}
