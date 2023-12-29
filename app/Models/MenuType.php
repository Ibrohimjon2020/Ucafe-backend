<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   description="MenuType model",
 *   title="MenuType",
 *   required={},
 *   @OA\Property(type="integer",description="id of MenuType",title="id",property="id",example="1",readOnly="true"),
 *   @OA\Property(type="string",description="name of MenuType",title="name",property="name",example="Macbook Pro"),
 *   @OA\Property(type="string",description="sku of MenuType",title="sku",property="sku",example="MCBPRO2022"),
 *   @OA\Property(type="integer",description="price of MenuType",title="price",property="price",example="99"),
 *   @OA\Property(type="dateTime",title="created_at",property="created_at",example="2022-07-04T02:41:42.336Z",readOnly="true"),
 *   @OA\Property(type="dateTime",title="updated_at",property="updated_at",example="2022-07-04T02:41:42.336Z",readOnly="true"),
 * )
 *
 *
 *
 *
 *
 * @OA\Schema (
 *   schema="MenuTypes",
 *   title="MenuTypes list",
 *   @OA\Property(title="data",property="data",type="array",
 *     @OA\Items(type="object",ref="#/components/schemas/MenuType"),
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
 *      parameter="MenuType--id",
 *      in="path",
 *      name="MenuType_id",
 *      required=true,
 *      description="Id of MenuType",
 *      @OA\Schema(
 *          type="integer",
 *          example="1",
 *      )
 * ),
 */

class MenuType extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'logo', 'order_number'];

    protected $casts = [
        'title' => 'array'
    ];
    public static $rules = [
        "title" => 'array|required',
        "logo" => 'string|nullable',
    ];
}
