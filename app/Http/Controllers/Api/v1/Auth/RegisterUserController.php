<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Actions\Auth\CreateTokenToUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\Auth\UserLoggedResource;
use App\Services\Auth\RegisterUserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RegisterUserController extends Controller
{
    public function __construct(
        private RegisterUserService $registerUserService,
        private CreateTokenToUser $createTokenToUser
    ) {
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RegisterUserRequest $request)
    {
        $validatedData = $request->validated();

        $userRegistered = $this->registerUserService->execute($validatedData);

        $this->createTokenToUser->execute($userRegistered);

        return response()->json(new UserLoggedResource($userRegistered), Response::HTTP_CREATED);
    }
}
