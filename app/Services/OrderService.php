<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\MenuType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderService extends BaseService
{
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createModel($data): array|Collection|Builder|Model|null|JsonResponse
    {
        $orderItems = $data['order_items'];
        // remove OrderItems from request
        unset($data['order_items']);

        // Start a database transaction
        DB::beginTransaction();
        try {
            $order  = parent::createModel($data);
            // Create OrderItems
            foreach ($orderItems as $item => $value) {
                $orderItem = new OrderItem();
                $orderItem['order_id'] = $order->id;
                $orderItem['product_id'] = $value['product_id'];
                // find Product price
                $menuItem = MenuItem::find($value['product_id']);
                if ($menuItem->quantity < $value['quantity']) {
                    DB::rollBack();
                    return response()->json(['message' => "not enough"], 400);
                }
                $orderItem['price'] = $menuItem->price;
                $orderItem['quantity'] = $value['quantity'];
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
            return response()->json(['message' => 'Error creating order'], 500);
        }
    }

    public function updateModel($data, $id): array |Collection|Builder|Model|null
    {
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
            $menuItem = MenuItem::find($value['product_id']);

            // if product doesn't have in OrderItem Table. Than create new Item
            if (!$orderItem) {
                $orderItem = new OrderItem();
                $orderItem['order_id'] = $id;
                $orderItem['product_id'] = $value['product_id'];
                $orderItem['price'] = $menuItem->price;
                $orderItem['quantity'] = $value['quantity'];
                $orderItem->save();
                // update menuItem quantity
                $menuItem->quantity -= $value['quantity'];
            }
            // if quantity in request different quantity in Database
            $quantityDiffrent = $orderItem->quantity - $value['quantity'];

            // update menuItem quantity
            if ($quantityDiffrent > 0)
                $menuItem->quantity += $quantityDiffrent;
            elseif ($quantityDiffrent < 0)
                $menuItem->quantity += $quantityDiffrent;

            if ($value['quantity'] == 0) {
                $menuItem->quantity += $orderItem->quantity;
                $orderItem->delete();
            }
            $orderItem['quantity'] = $value['quantity'];
            $orderItem->save();
            $menuItem->save();
        }
        $order = parent::updateModel($data, $id);
        return $order;
    }

    public function deleteModel($id): array|Builder|Collection|Model
    {
        // If OrderItem has a that order_id, MenuItem should update quantity.
        $orderItems = OrderItem::where('order_id', $id)->get();
        if ($orderItems) {
            foreach ($orderItems as $item) {
                $menuItem = MenuItem::find($item->product_id);
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
}
