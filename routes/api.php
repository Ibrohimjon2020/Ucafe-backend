<?php

use App\Http\Controllers\{
    MenuItemController,
    MenuTypeController,
    StaffController,
    OrderColumnController,
    RoleController,
    ExpenseController,
    OrderItemController,
    OrderController,
//imports
};
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login-staff', [LoginController::class, 'login']);

// Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('menu-type', MenuTypeController::class);
    Route::apiResource('menu-item', MenuItemController::class);
    Route::apiResource('staff', StaffController::class);
    Route::apiResource('order-column', OrderColumnController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('expenses', ExpenseController::class);
    Route::apiResource('order_items', OrderItemController::class);
    Route::apiResource('orders', OrderController::class);
//routes

// });
