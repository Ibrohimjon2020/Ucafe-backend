<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="u-cafe",
 *      description="Documentation for u-cafe admin dashboard and main site",
 *      @OA\Contact(
 *          email="s.murodjonov@uzinfocom.uz"
 *      ),
 *
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
