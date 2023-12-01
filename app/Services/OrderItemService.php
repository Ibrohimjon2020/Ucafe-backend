<?php

namespace App\Services;

use App\Repositories\OrderItemRepository;

class OrderItemService extends BaseService
{
    public function __construct(OrderItemRepository $repository)
    {
        $this->repository = $repository;
    }

}
