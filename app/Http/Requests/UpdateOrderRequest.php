<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return Auth::check();
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
        "price" => 'numeric',
        "order_status" => 'numeric',
        "payment_status" => 'string',
        "payment_type" => 'numeric|required',
        "order_detail" => 'json|required',
        "created_by" => 'numeric',
        "updated_by" => 'numeric',
        "user_id" => 'numeric',
        ];
    }
}
