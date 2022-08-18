<?php

use App\Application\Api\Controllers\v1\SubjectController;
use Illuminate\Support\Facades\Route;

Route::apiResource('subjects', SubjectController::class);
