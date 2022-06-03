<?php

use App\Application\Api\Controllers\v1\GroupWork\GroupWorkController;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use Illuminate\Support\Facades\Route;



Route::apiResource('groupsWork', GroupWorkController::class)
    ->parameter('groupsWork', 'groupWork');
