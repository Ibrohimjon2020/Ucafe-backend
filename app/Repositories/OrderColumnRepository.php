<?php

namespace App\Repositories;

use App\Models\OrderColumn;

class OrderColumnRepository extends BaseRepository
{
    public function __construct(OrderColumn $model)
    {
        parent::__construct($model);
    }
}
