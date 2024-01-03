<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Wildside\Userstamps\Userstamps;

/**
 * @OA\Schema(
 *   description="Order model",
 *   title="Order",
 *   required={},
 *   @OA\Property(type="integer",description="id of Order",title="id",property="id",example="1",readOnly="true"),
 *   @OA\Property(type="string",description="name of Order",title="name",property="name",example="Macbook Pro"),
 *   @OA\Property(type="string",description="sku of Order",title="sku",property="sku",example="MCBPRO2022"),
 *   @OA\Property(type="integer",description="price of Order",title="price",property="price",example="99"),
 *   @OA\Property(type="dateTime",title="created_at",property="created_at",example="2022-07-04T02:41:42.336Z",readOnly="true"),
 *   @OA\Property(type="dateTime",title="updated_at",property="updated_at",example="2022-07-04T02:41:42.336Z",readOnly="true"),
 * )
 *
 *
 *
 *
 *
 * @OA\Schema (
 *   schema="Orders",
 *   title="Orders list",
 *   @OA\Property(title="data",property="data",type="array",
 *     @OA\Items(type="object",ref="#/components/schemas/Order"),
 *   ),
 *   @OA\Property(type="string", title="first_page_url",property="first_page_url",example="http://localhost:8080/api/merchant-customers?page=1"),
 *   @OA\Property(type="string", title="last_page_url",property="last_page_url",example="http://localhost:8080/api/merchant-customers?page=3"),
 *   @OA\Property(type="string", title="prev_page_url",property="prev_page_url",example="null"),
 *   @OA\Property(type="string", title="next_page_url",property="next_page_url",example="http://localhost:8080/api/merchant-customers?page=2"),
 *   @OA\Property(type="integer", title="current_page",property="current_page",example="1"),
 *   @OA\Property(type="integer", title="from",property="from",example="1"),
 *   @OA\Property(type="integer", title="last_page",property="last_page",example="3"),
 *   @OA\Property(type="string", title="path",property="path",example="http://localhost:8080/api/merchant-customers"),
 *   @OA\Property(type="integer", title="per_page",property="per_page",example="1"),
 *   @OA\Property(type="integer", title="total",property="total",example="3"),
 *   @OA\Property(title="links",property="links",type="array",
 *     @OA\Items(type="object",
 *          @OA\Property(type="string",title="url",property="url",example="http://localhost:8080/api/merchant-customers?page=2"),
 *          @OA\Property(type="string",title="label",property="label",example="1"),
 *          @OA\Property(type="bool",title="active",property="active",example="true"),
 *      ),
 *   )
 * )
 *
 * @OA\Parameter(
 *      parameter="Order--id",
 *      in="path",
 *      name="Order_id",
 *      required=true,
 *      description="Id of Order",
 *      @OA\Schema(
 *          type="integer",
 *          example="1",
 *      )
 * ),
 */

class Order extends Model
{
    use HasFactory;
    use Userstamps;

    const CREATED_BY = 'created_by';
    const UPDATED_BY = 'updated_by';
    const DELETED_BY = 'deleted_by';
    protected $fillable = ['order_detail'];
    protected $casts = [];
    protected $with = ['orderStatus', 'paymentType', 'Cashier'];
    public static $rules = [
        "price" => 'numeric',
        "order_status" => 'numeric',
        "payment_status" => 'string',
        "payment_type" => 'numeric',
        "order_detail" => 'json',
        "created_by" => 'numeric',
        "updated_by" => 'numeric',
        "user_id" => 'numeric',
        "order_items" => 'array'
    ];

    public function orderStatus()
    {
        return $this->belongsTo(OrderColumn::class, 'order_status')->select(['id', 'title']);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type')->select(['id', 'title']);;
    }

    public function Cashier()
    {
        return $this->belongsTo(User::class, 'updated_by')->select(['id', 'name']);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id')->select(['id', 'product_id', 'price', 'quantity', 'order_id']);
    }

    public function scopeFilter(Builder $query, $data)
    {
        if ($data['from_date'] && $data['to_date'])  $query->whereBetween('created_at', [$data['from_date'], $data['to_date']]);
        elseif (isset($data['from_date'])) $query->whereBetween('created_at', [$data['from_date'], date("Y-m-d h:i:s")]);
        if (isset($data['payment_type'])) $query->where('payment_type', $data['payment_type']);
        if (isset($data['order_detail'])) $query->whereJsonContains('order_detail', json_decode($data['order_detail'], true));
        return $query;
    }
}
