<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Role;
use App\Models\Staff;
use App\Models\User;
use App\Services\BaseService;
use App\Services\StaffService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Throwable;

/**
 * Class StaffController
 * @package  App\Http\Controllers
 */
class StaffController extends \App\Http\Controllers\Controller
{


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
    public function index()
    {
        if (auth()->user()->hasRoles('Administrator'))
            return User::whereHas('roles')->with('roles')->paginate();
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
     */
    public function store(StoreStaffRequest $request)
    {
        if (auth()->user()->hasRoles('Administrator')) {
            $staff = new User();
            $staff->name = $request->name;
            $staff->login = $request->login;
            $staff->staff_status = $request->staff_status ?? false;
            $staff->password = Hash::make($request->login);
            $staff->save();
            $role = Role::whereId($request->role)->firstOrFail();
            $staff->giveRole($role->title['en']);
            return response()->json($staff, 201);
        } else return BaseService::permissionDenied();
        // return $this->service->createModel($request->validated());
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
        // return $this->service->getModelById($productId);
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
    //  * @return array|Builder|Builder[]|Collection|Staff|Staff[]
     * @throws Throwable
     */
    public function update(UpdateStaffRequest $request, int $productId)
    {
        if (auth()->user()->hasRoles('Administrator')) {
            $staff = User::whereId($productId)->firstOrfail();
            $staff->name = $request->name;
            $staff->login = $request->login;
            $staff->staff_status = $request->staff_status ?? $staff->staff_status;
            $staff->password = Hash::make($request->login);
            $staff->save();
            $staff->roles()->detach();
            $role = Role::whereId($request->role)->firstOrFail();
            $staff->giveRole($role->title['en']);
            return response()->json($staff, 200);
        } else return BaseService::permissionDenied();
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
        // return $this->service->deleteModel($productId);
    }
}
