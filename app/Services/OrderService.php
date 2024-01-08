<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Repositories\ExpenseRepository;
use App\Repositories\MenuItemRepository;
use App\Repositories\OrderItemRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderService extends BaseService
{
    protected MenuItemRepository $menuItemRepository;
    protected ExpenseRepository $expenseRepository;
    protected OrderItemRepository $orderItemRepository;

    public function __construct(
        OrderRepository $repository,
        MenuItemRepository $menuItemRepository,
        ExpenseRepository $expenseRepository,
        OrderItemRepository $orderItemRepository
    ) {
        $this->repository = $repository;
        $this->menuItemRepository = $menuItemRepository;
        $this->expenseRepository = $expenseRepository;
        $this->orderItemRepository = $orderItemRepository;
    }

    public function createModel($data): array|Collection|Builder|Model|null|JsonResponse
    {
        // Start a database transaction
        DB::beginTransaction();
        try {
            $orderItems = $data['order_items'];

            // remove OrderItems from request
            unset($data['order_items']);

            $order  = parent::createModel($data);
            if (empty($order['id'])) {
                return response()->json($order, 400);
            }
            // Create OrderItems
            foreach ($orderItems as $item => $value) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $value['product_id'];
                // find Product price
                $menuItem = $this->menuItemRepository->findById($value['product_id']);

                if (!$menuItem || $menuItem->quantity < $value['quantity'] || $value['quantity'] <= 0) {
                    DB::rollBack();
                    return response()->json(['message' => "Not enough"], 400);
                }
                $orderItem->price = $menuItem->price;
                $orderItem->quantity = $value['quantity'];
                $orderItem->save();
                // update menuItem quantity
                $menuItem->quantity -= $value['quantity'];
                $menuItem->save();
            }
            // Commit the transaction
            DB::commit();
            // Return the order
            return response()->json(['message' => 'Order created successfully', 'order' => $order]);
        } catch (\Exception $e) {
            // Handle any exception that may occur during the transaction
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateModel($data, $id): array |Collection|Builder|Model|null|JsonResponse
    {
        // Start a database transaction
        DB::beginTransaction();
        try {
            if (isset($data['order_items'])) {
                $orderItems = $data['order_items'];
                // remove OrderItems from request
                unset($data['order_items']);

                // Update OrderItems
                foreach ($orderItems as $item => $value) {
                    $conditions = [
                        ['order_id', $id],
                        ['product_id', $value['product_id']],
                    ];
                    $orderItem = OrderItem::where($conditions)->first();

                    // find Product price
                    // $menuItem = MenuItem::find($value['product_id']);
                    $menuItem = $this->menuItemRepository->findById($value['product_id']);
                    if (!$menuItem || $value['quantity'] > $menuItem->quantity || $value['quantity'] < 0) {
                        DB::rollBack();
                        return response()->json(['message' => "Not enough"], 400);
                    }

                    if ($value['quantity'] == 0 && $orderItem) {
                        $menuItem->quantity += $orderItem->quantity;
                        $orderItem->delete();
                    }

                    // if product doesn't have in OrderItem Table. Create new Item
                    if (!$orderItem) {
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $id;
                        $orderItem->product_id = $value['product_id'];
                        $orderItem->price = $menuItem->price;
                        $orderItem->quantity = $value['quantity'];
                        $orderItem->save();
                        // update menuItem quantity
                        $menuItem->quantity -= $value['quantity'];
                    }
                    // Diffrent quantity in OrderItem table and request.
                    $quantityDiffrent = $orderItem->quantity - $value['quantity'];

                    // update menuItem quantity
                    $menuItem->quantity += $quantityDiffrent;
                    $menuItem->save();
                    $orderItem->quantity = $value['quantity'];
                    $orderItem->save();
                }
            }
            // Commit the transaction
            DB::commit();
            $order = parent::updateModel($data, $id);
            // Return the order
            return response()->json(['message' => 'Order updated successfully', 'order' => $order]);
        } catch (\Exception $e) {
            // Handle any exception that may occur during the transaction
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteModel($id): array|Builder|Collection|Model
    {
        // If OrderItem has a that order_id, MenuItem should update quantity.
        $orderItems = OrderItem::where('order_id', $id)->get();
        if ($orderItems) {
            foreach ($orderItems as $item) {
                // $menuItem = MenuItem::find($item->product_id);
                $menuItem = $this->menuItemRepository->findById($item->product_id);
                $menuItem->quantity += $item->quantity;
                $menuItem->save();
                $item->delete();
            }
        }
        $order = parent::deleteModel($id);
        return $order;
    }

    public function getModelById($id): Model|array |Collection|Builder|null
    {
        return $this->getRepository()->findById($id, ['orderItems']);
    }
    // get From date To date Sum Price Statistics

    public function getSumPriceStatistics($model, $groupBy)
    {
        return $model->groupBy($groupBy)
            ->map(function ($group) {
                return $group->sum('price');
            });
    }

    // get From date To date Count Order Statistics

    public function getCountOrderStatistics($model, $groupBy)
    {
        return $model->groupBy($groupBy)
            ->map(function ($group) {
                return $group->count('id');
            });
    }

    // get Statistics by Menu Type 

    public function getStatisticsByMenuType($request)
    {
        $conditions = [$request->from_date, $request->to_date];
        $response = DB::table('order_items as oi')
            ->join('menu_items as mi', 'oi.product_id', '=', 'mi.id')
            ->join('menu_types as mt', 'mi.menu_type_id', '=', 'mt.id')
            ->select('mt.title as menu_type', DB::raw('sum(oi.quantity) as total_quantity'), DB::raw('sum(oi.price * oi.quantity) as total_price'))
            ->whereBetween('oi.created_at', $conditions)
            ->groupBy('mt.title')
            ->get();
        return $response;
    }

    public function getReport($request)
    {
        $response = [];
        $expense = $this->expenseRepository->getAllList($request)->sum('price');
        $order = $this->repository->getAllList($request);
        $totalNumberOfSales = 0;
        foreach ($order as $item) {
            $orderItems = $this->orderItemRepository->getAllList($item->id);
            if (!$orderItems) continue;
            $totalNumberOfSales += $orderItems->sum('quantity');
        }
        // Statistics by payment type   
        $statisticsByPaymentType = $this->getSumPriceStatistics($order, 'payment_type');

        // sell order by order_detail
        $orderDetailTake = function ($item) {
            // Extract the value of the 'name' key from the JSON in the 'order_detail' column
            $orderDetail = json_decode($item->order_detail, true);

            // Check if $orderDetail is an array and has the 'name' key
            return is_array($orderDetail) && array_key_exists('name', $orderDetail) ? $orderDetail['name'] : null;
        };
        $sellOrderByOrderDetail = $this->getCountOrderStatistics($order, $orderDetailTake);

        // find total products and price by menu type
        $productCategory = $this->getStatisticsByMenuType($request);

        array_push(
            $response,
            ['Expense' => $expense],
            ['In come order sum price' => $order->sum('price')],
            ['Total number of sale product' => $totalNumberOfSales],
            ['Statistics by payment type sum price' => $statisticsByPaymentType],
            ['Sell order by order_detail count' => $sellOrderByOrderDetail],
            ['productCategory' => $productCategory],
        );
        return $response;
    }
}
