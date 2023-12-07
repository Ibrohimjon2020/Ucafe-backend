<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = ['day','price','decription'];

    protected $casts = [
        'day'=>'date'
    ];
    // public static $rules = [
    //     "day"=>'array|required',
    //     "price"=>'numeric|required',
    //     "text"=>'string|nullable',
    // ];

}
