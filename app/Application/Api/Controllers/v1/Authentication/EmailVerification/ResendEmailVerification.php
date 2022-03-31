<?php

namespace App\Application\Api\Controllers\v1\Authentication\EmailVerification;

use App\Application\Api\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class ResendEmailVerification extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        event(new Registered(auth()->user()));

        return response()->json(['message' => __('email_verification.resend')]);
    }
}
