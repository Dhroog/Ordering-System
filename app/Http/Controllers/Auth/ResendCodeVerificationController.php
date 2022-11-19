<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResendCodeVerificationRequest;
use Illuminate\Http\Request;

class ResendCodeVerificationController extends Controller
{
    public function __invoke(ResendCodeVerificationRequest $request)
    {
        if( $request->user()->hasVerifiedEmail() ) return $this->failureResponse('email already verified',403);
        $request->user()->sendEmailVerificationNotification(true);
        return $this->SuccessResponse();
    }
}
