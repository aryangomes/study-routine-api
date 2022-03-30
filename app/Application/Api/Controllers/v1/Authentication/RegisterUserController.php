<?php

namespace App\Application\Api\Controllers\v1\Authentication;

use App\Application\Api\Controllers\Controller;
use App\Application\Api\Requests\Authentication\RegisterUserRequest;
use App\Application\Api\Resources\Authentication\UserLoggedResource;
use Domain\Authentication\Services\LoginService;
use Domain\Authentication\Services\RegisterUserService;
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

        $userAvatarImage = $request->file('user_avatar');

        $this->registerUserService->registerNewUser($validatedData, $userAvatarImage);

        $userLogged = $this->loginRegisteredUser($validatedData);

        return response()->json(new UserLoggedResource($userLogged), Response::HTTP_CREATED);
    }

    /**
     * Login to the registered User
     * @param array $registeredUserData
     * @return \Domain\User\Models\User
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
