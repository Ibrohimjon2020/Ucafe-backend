<?php

namespace App\Repositories;

use App\Models\Staff;

class StaffRepository extends BaseRepository
{
    public function __construct(Staff $model)
    {
        parent::__construct($model);
    }
}
