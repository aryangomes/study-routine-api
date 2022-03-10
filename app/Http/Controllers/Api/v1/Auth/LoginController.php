<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Actions\Auth\CreateTokenToUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\UserLoggedResource;
use App\Services\Auth\LoginService;
use Illuminate\Http\Response;

class LoginController extends Controller
{

    public function __construct(
        private LoginService $loginService

    ) {
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(LoginRequest $request)
    {
        $requestValidated = $request->validated();

        $loginService = $this->loginService;

        $userLogged = $loginService($requestValidated);

        return response()->json(
            new UserLoggedResource($userLogged),
            Response::HTTP_OK
        );
    }
}
