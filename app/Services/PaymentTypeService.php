<?php

namespace App\Services;

use App\Repositories\PaymentTypeRepository;

class PaymentTypeService extends BaseService
{
    public function __construct(PaymentTypeRepository $repository)
    {
        $this->repository = $repository;
    }

}
