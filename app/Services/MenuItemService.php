<?php

namespace App\Services;

use App\Repositories\MenuItemRepository;

class MenuItemService extends BaseService
{
    public function __construct(MenuItemRepository $repository)
    {
        $this->repository = $repository;
    }

}
