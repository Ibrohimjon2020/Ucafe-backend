<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Models\OrderItem;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

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
        $order  = parent::createModel($data);
                // Create OrderItems
        foreach ($orderItems as $item => $value){
            $orderItem = new OrderItem();
            $orderItem['order_id'] = $order->id;
            $orderItem['product_id'] = $value['product_id'];
            // find Product price
            $menuItem = MenuItem::find($value['product_id']);
            $orderItem['price'] = $menuItem->price;
            $orderItem['quantity'] = $value['quantity'];
            $orderItem->save();
            // update menuItem quantity
            $menuItem->quantity -= $value['quantity'];
            $menuItem->save();

        }
        return $order;
    }

    public function updateModel($data, $id): array |Collection|Builder|Model|null
    {
        $orderItems = $data['order_items'];

        // remove OrderItems from request
        unset($data['order_items']);

        // Update OrderItems
        foreach ($orderItems as $item => $value){
            $conditions = [
                ['order_id', $id],
                ['product_id', $value['product_id']],
            ];
            $orderItem = OrderItem::where($conditions)->first();

            // find Product price
            $menuItem = MenuItem::find($value['product_id']);

            // if product doesn't have in OrderItem Table. Than create new Item
            if(!$orderItem){
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
            if($quantityDiffrent > 0)
                $menuItem->quantity += $quantityDiffrent;
            elseif($quantityDiffrent < 0)
                $menuItem->quantity += $quantityDiffrent;
            
            if($value['quantity'] == 0){
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

}
