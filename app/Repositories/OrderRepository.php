<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
class OrderRepository extends BaseRepository
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function create($data): array|Collection|Builder|Model|null
    {
        $model = $this->getModel();
        foreach ($data as $item => $value) {
            $model->{$item} = $value;
            if ($item === "order_detail"){
                $orderDetailData = json_decode($value, true);
                $orderDetailCondition = ['with myself', 'in place', 'delivery'];
                if (!(isset($orderDetailData['name']) && in_array($orderDetailData['name'], $orderDetailCondition))) {
                    return ['message' => "Order_detail is wrong"];
                } 
            }
        }
        $model->payment_status = 'unpaid';
        $model->save();
        return $model;
    }

    public function update($data, $id): Model|array|Collection|Builder|null
    {
        $model = $this->query()->whereId($id)->first();
        foreach ($data as $item => $value) {
            $model->{$item} = $value;
        }
        // If request has a payent_status, Update price column in Order table.
        if (!empty($data['payment_status'])) {
            $order_price_sum = OrderItem::where('order_id', $id)
                ->selectRaw('SUM(price * quantity) as total_amount')
                ->groupBy('order_id')
                ->first();
            if ($order_price_sum) {
                $model->price = $order_price_sum->total_amount;
                $model->payment_status = 'paid';
            }
        }
        $model->save();
        return $model;
    }
}
