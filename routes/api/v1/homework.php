<?php

use App\Application\Api\Controllers\v1\HomeworkController;
use Illuminate\Support\Facades\Route;



Route::apiResource('homeworks', HomeworkController::class);
