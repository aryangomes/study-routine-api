<?php

namespace App\Application\Api\Controllers\v1\Authentication\EmailVerification;

use App\Application\Api\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerify extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return response()->json(['message' => __('email_verification.verified')]);
    }
}
