<?php

namespace App\Application\Api\Controllers\v1;

use App\Application\Api\Controllers\Controller;
use Illuminate\Http\Response;

abstract class BaseApiController extends Controller
{
    protected function routeNotImplemented()
    {
        return response(status: Response::HTTP_NOT_IMPLEMENTED);
    }
}
