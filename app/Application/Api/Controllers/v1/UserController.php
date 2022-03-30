<?php

namespace App\Application\Api\Controllers\v1;

use App\Application\Api\Controllers\Controller;
use App\Application\Api\Requests\User\UpdateUserRequest;
use App\Application\Api\Resources\User\UserResource;
use Domain\User\Models\User;
use Domain\User\Services\UserService;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
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
     * @param  \Domain\User\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $userLogged = $this->getUserFromAuthUser();

        return response()->json(new UserResource($userLogged));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Application\Api\Requests\User\UpdateUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request)
    {

        $validatedData = $request->validated();

        $validatedDataExceptUserAvatar = collect($validatedData)->except('user_avatar')->toArray();

        $userAvatarImage = $request->file('user_avatar');

        $userLogged = $this->getUserFromAuthUser();

        $this->userService->update($userLogged, $validatedDataExceptUserAvatar);

        $this->userService->uploadUserAvatar($userLogged, $userAvatarImage);

        return response()->json(new UserResource($userLogged));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {

        $userLogged = $this->getUserFromAuthUser();

        $this->userService->delete($userLogged);

        return response(status: Response::HTTP_NO_CONTENT);
    }
    /**
     * Get User from Auth User
     * @return User
     */
    private function getUserFromAuthUser(): User
    {
        $userLogged = User::find(auth()->user()->id);

        return $userLogged;
    }
}
