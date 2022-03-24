<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class BaseApiController extends Controller
{
    protected function routeNotImplemented()
    {
        return response(status: Response::HTTP_NOT_IMPLEMENTED);
    }
}
