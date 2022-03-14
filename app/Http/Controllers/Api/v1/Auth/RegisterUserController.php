<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Actions\Auth\CreateTokenToUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\Auth\UserLoggedResource;
use App\Services\Auth\LoginService;
use App\Services\Auth\RegisterUserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RegisterUserController extends Controller
{
    public function __construct(
        private RegisterUserService $registerUserService,
        private LoginService $loginService,
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

        $this->registerUserService->registerUser($validatedData);

        $userLogged = $this->loginRegisteredUser($validatedData);

        return response()->json(new UserLoggedResource($userLogged), Response::HTTP_CREATED);
    }

    /**
     * Login to the registered User
     * @param array $registeredUserData
     * @return \App\Models\User
     */
    private function loginRegisteredUser(array $registeredUserData)
    {
        $dataToLoginRegisteredUser = [
            'email' => $registeredUserData['email'],
            'password' => $registeredUserData['password'],
        ];

        $loginService = $this->loginService;

        return  $loginService($dataToLoginRegisteredUser);
    }
}
