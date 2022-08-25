<?php

use App\Application\Api\Controllers\v1\Examables\Test\AddNewTopicController;
use App\Application\Api\Controllers\v1\Examables\Test\GetTopicsController;
use App\Application\Api\Controllers\v1\Examables\Test\TestController;
use Illuminate\Support\Facades\Route;

Route::apiResource('tests', TestController::class);
