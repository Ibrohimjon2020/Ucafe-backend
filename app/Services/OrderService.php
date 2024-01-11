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

    // get Main statistics
    public function getMainStatistics($request, $conditions)
    {
        // get Sum Price from Expense Table
        $expense = $this->expenseRepository->getAllList($request)->sum('price');

        $order = $this->repository->getAllList($request);

        // get Total Number Of Sales 
        $totalNumberOfSales = $this->getTotalNumberOfSales($order);

        // Statistics by payment type   
        $statisticsByPaymentType = $this->getSumPriceStatistics($conditions);

        return array_merge(
            [
                'expense' => array('value' => $expense),
                'in_come_price' => array('value' => $order->sum('price'), 'title' => 'In come order sum price'),
                'total_number' => array('value' => $totalNumberOfSales, 'title' => 'Total number of sales'),
                'by_payment_type' => array('value' => $statisticsByPaymentType)
            ]
        );
    }

    // get Total Number Of Sales 
    public function getTotalNumberOfSales($order)
    {
        $totalNumberOfSales = 0;
        foreach ($order as $item) {
            $orderItems = $this->orderItemRepository->getAllList($item->id);
            if (!$orderItems) continue;
            $totalNumberOfSales += $orderItems->sum('quantity');
        }
        return $totalNumberOfSales;
    }

    // get From date To date Sum Price Statistics
    public function getSumPriceStatistics($conditions)
    {
        $response = DB::table('orders as o')
            ->join('payment_types as pt', "o.payment_type", '=', 'pt.id')
            ->select('pt.title as title', DB::raw('sum(o.price) as total_price'))
            ->whereBetween("o.created_at", $conditions)
            ->groupBy('title')
            ->get();
        return $response;
    }

    // get From date To date Count Order Statistics
    public function getCountOrderStatistics($model)
    {
        // sell order by order_detail
        $groupBy = function ($item) {
            // Extract the value of the 'name' key from the JSON in the 'order_detail' column
            $orderDetail = json_decode($item->order_detail, true);

            // Check if $orderDetail is an array and has the 'name' key
            return is_array($orderDetail) && array_key_exists('name', $orderDetail) ? $orderDetail['name'] : null;
        };
        return $model->groupBy($groupBy)
            ->map(function ($group) {
                return $group->count('id');
            });
    }

    // get Statistics by Menu Type 
    public function getStatisticsByMenuType($conditions)
    {
        $response = DB::table('order_items as oi')
            ->join('menu_items as mi', 'oi.product_id', '=', 'mi.id')
            ->join('menu_types as mt', 'mi.menu_type_id', '=', 'mt.id')
            ->select('mt.title as menu_type', DB::raw('sum(oi.quantity) as total_quantity'), DB::raw('sum(oi.price * oi.quantity) as total_price'))
            ->whereBetween('oi.created_at', $conditions)
            ->groupBy('mt.title')
            ->get();
        return $response;
    }

    // get Statistics by Product
    public function getStatisticsByProduct($conditions)
    {
        $respone = DB::table('order_items as oi')
            ->join('menu_items as mi', 'oi.product_id', '=', 'mi.id')
            ->join('menu_types as mt', 'mi.menu_type_id', '=', 'mt.id')
            ->select('mi.title as name', 'mt.title as category', DB::raw('sum(oi.quantity) as total_quantity'), 'mi.price as price', DB::raw('sum(oi.price * oi.quantity) as total_price'))
            ->whereBetween('oi.created_at', $conditions)
            ->groupBy('mi.id')
            ->get();
        return $respone;
    }

    // get calculate that interval and before interval
    public function getInterval($mainStatistics, $beforeMainStatistics)
    {
        $x = ['expense', 'in_come_price', 'total_number'];
        $respone = [];
        foreach ($x as $key => $value) {
            $before = $beforeMainStatistics["$value"]['value'] / 100;
            if ($before == 0) {
                $beforeNowDividing = 0;
            } else {
                $beforeNowDividing = $mainStatistics["$value"]['value'] / $before;
                $beforeNowDividing == 0 ? $different = 0 : $different = ceil($beforeNowDividing - 100);
            }
            $respone[$value] = [
                "value" => $mainStatistics["$value"]['value'],
                "different" => $different,
            ];
        };
        $result = [];
        for ($x = 0; $x <= 1; $x++) {
            $paymentCash = $beforeMainStatistics['by_payment_type']['value'][$x]->total_price / 100;
            if ($paymentCash == 0) {
                $differentCash = 0;
            } else {
                $beforeNowDividing = $mainStatistics['by_payment_type']['value'][$x]->total_price / $paymentCash;
                $beforeNowDividing == 0 ? $differentCash = 0 : $differentCash = ceil($beforeNowDividing - 100);
            }
            array_push(
                $result,
                [
                    "title" => $beforeMainStatistics['by_payment_type']['value'][$x]->title,
                    "value" => $mainStatistics['by_payment_type']['value'][$x]->total_price,
                    "different" => $differentCash

                ]
            );
        }
        $respone['by_payment_type'] = $result;

        return $respone;
    }

    public function getReport($request)
    {
        // get From date To date from request
        $conditions = [$request->from_date, $request->to_date];

        $order = $this->repository->getAllList($request);

        $mainStatistics = $this->getMainStatistics($request, $conditions);

        // get sell order by order_detail
        $sellOrderByOrderDetail = $this->getCountOrderStatistics($order);

        // find total products and price by menu type
        $Category = $this->getStatisticsByMenuType($conditions);

        // get Statistics by Product
        $productCategory = $this->getStatisticsByProduct($conditions);

        // get Orders with pagination
        $orders = $this->repository->paginatedList($request);

        $from_date = new Carbon($request->from_date);
        $to_date = new Carbon($request->to_date);
        $interval = $to_date->diffInDays($from_date);

        // before interval dates
        $before_to_date = $request->from_date;
        $before_from_date = $from_date->subDay($interval);
        $conditions = [$before_from_date, $before_to_date];

        $request_before_interval = array("from_date" => $before_from_date, "to_date" => $before_to_date);

        $beforeMainStatistics = $this->getMainStatistics($request_before_interval, $conditions);
        //  get main statistics with before interval dates
        $get_main = $this->getInterval($mainStatistics, $beforeMainStatistics);
        return array_merge(
            [
                'main_statistics' => $get_main,
                'charts' =>  [
                    'by_order_detail' => array('value' => $sellOrderByOrderDetail, 'title' => 'Sell order by order_detail count'),
                    'by_category' => array('value' => $Category, 'title' => 'Sell product by category'),
                ],
                'tables' => [
                    'product' => $productCategory,
                    'orders' => $orders,
                ]
            ]
        );
    }
}
