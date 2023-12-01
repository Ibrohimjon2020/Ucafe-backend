<?php

namespace App\Services;

use App\Repositories\MenuTypeRepository;

class MenuTypeService extends BaseService
{
    public function __construct(MenuTypeRepository $repository)
    {
        $this->repository = $repository;
    }

}
