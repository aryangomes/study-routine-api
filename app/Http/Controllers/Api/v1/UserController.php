<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\User\UpdateUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request)
    {

        $validatedData = $request->validated();

        $userLogged = auth()->user();

        $this->userService = new UserService($userLogged);

        $this->userService->updateUser($validatedData);

        return response()->json(new UserResource($userLogged));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {

        $userLogged = auth()->user();

        $this->userService = new UserService($userLogged);

        $this->userService->deleteUser();

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
