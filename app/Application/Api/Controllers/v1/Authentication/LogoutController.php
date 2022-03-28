<?php

namespace App\Application\Api\Controllers\v1\Authentication;

use App\Application\Api\Controllers\Controller;
use Domain\Authentication\Actions\LogoutUser;
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

        $logoutUser =
            $this->logoutUser;
        $logoutUser($userLogged);

        return response()->json(__('auth.logout_success'));
    }
}
