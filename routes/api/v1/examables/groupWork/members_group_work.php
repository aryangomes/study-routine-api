<?php

use App\Application\Api\Controllers\v1\Examables\GroupWork\Member\AddNewMemberToWorkGroupController;
use App\Application\Api\Controllers\v1\Examables\GroupWork\Member\GetMembersOfGroupWorkController;
use App\Application\Api\Controllers\v1\Examables\GroupWork\Member\RemoveMemberFromWorkGroupController;

use Illuminate\Support\Facades\Route;


Route::prefix('members')->name('members.')->group(function () {
    Route::post('/', AddNewMemberToWorkGroupController::class)
        ->name('add_new_member');

    Route::get('/', GetMembersOfGroupWorkController::class)
        ->name('get_members');

    Route::delete('/{member}', RemoveMemberFromWorkGroupController::class)
        ->name('remove_member');
});
