<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginController extends Controller
{

    public function __construct(private LoginService $loginService)
    {
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

        $userLogin = $this->loginService->execute($requestValidated);

        $userWasLoginSuccessfully = (!is_null($userLogin));

        if (!$userWasLoginSuccessfully) {
            return response('error', Response::HTTP_BAD_REQUEST);
        }
        return response(['user' => $userLogin], Response::HTTP_OK);
    }
}
