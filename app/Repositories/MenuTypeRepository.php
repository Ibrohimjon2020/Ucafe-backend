<?php

namespace App\Repositories;

use App\Models\MenuType;

class MenuTypeRepository extends BaseRepository
{
    public function __construct(MenuType $model)
    {
        parent::__construct($model);
    }
}
