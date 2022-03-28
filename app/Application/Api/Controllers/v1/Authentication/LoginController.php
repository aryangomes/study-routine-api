<?php

namespace App\Application\Api\Controllers\v1\Authentication;

use App\Application\Api\Controllers\Controller;
use App\Application\Api\Requests\Authentication\LoginRequest;
use App\Application\Api\Resources\Authentication\UserLoggedResource;
use Domain\Authentication\Services\LoginService;
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
