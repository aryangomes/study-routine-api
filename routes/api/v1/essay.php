<?php

use App\Application\Api\Controllers\v1\Examables\EssayController;
use Illuminate\Support\Facades\Route;



Route::apiResource('essays', EssayController::class);
