<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
class OrderService extends BaseService
{
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getModelById($id): Model|array |Collection|Builder|null
    {
        return $this->getRepository()->findById($id);
    }

}
