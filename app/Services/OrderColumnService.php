<?php

namespace App\Services;

use App\Repositories\OrderColumnRepository;

class OrderColumnService extends BaseService
{
    public function __construct(OrderColumnRepository $repository)
    {
        $this->repository = $repository;
    }

}
