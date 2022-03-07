<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Actions\Auth\LogoutUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{

    public function __construct(private LogoutUser $logoutUser)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $userLogged = $request->user();

        $this->logoutUser->execute($userLogged);

        return response()->json(__('auth.logout.success'));
    }
}
