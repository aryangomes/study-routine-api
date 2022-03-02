<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Services\Auth\RegisterUserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RegisterUserController extends Controller
{
    public function __construct(private RegisterUserService $registerUserService)
    {
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

        if (!is_null($userRegistered)) {
            return response(['user' => $userRegistered], Response::HTTP_CREATED);
        }

        return response('error', Response::HTTP_BAD_REQUEST);
    }
}
