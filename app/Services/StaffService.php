<?php

namespace App\Services;

use App\Repositories\StaffRepository;

class StaffService extends BaseService
{
    public function __construct(StaffRepository $repository)
    {
        $this->repository = $repository;
    }

}
