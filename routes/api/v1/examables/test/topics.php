<?php

use App\Application\Api\Controllers\v1\Examables\Test\AddNewTopicController;
use App\Application\Api\Controllers\v1\Examables\Test\GetTopicsController;
use App\Application\Api\Controllers\v1\Examables\Test\Topic\TopicController;
use Illuminate\Support\Facades\Route;

Route::apiResource('topics', TopicController::class);

Route::post('{test}/addNewTopic', AddNewTopicController::class)
    ->name('tests.add_new_topic');

Route::get('{test}/topics', GetTopicsController::class)
    ->name('tests.get_topics');
