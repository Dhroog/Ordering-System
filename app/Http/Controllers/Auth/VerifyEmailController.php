<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyEmailRequest;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(VerifyEmailRequest $request)
    {
        if( $request->user()->hasVerifiedEmail() ) return $this->failureResponse('email already verified',403);

        // Code correct
        if ($request->code === auth()->user()->getEmailVerifyCode()) {
            // check if code is still valide
            $secondsOfValidation = (int) config('email.seconds_of_validation');
            if ($secondsOfValidation > 0 &&  $request->user()->email_verify_code_sent_at->diffInSeconds() > $secondsOfValidation) {
                return $this->failureResponse('code is expired');
            }else {
                $request->user()->markEmailAsVerified();
                return $this->SuccessResponse('email verified');
            }
        }
        return $this->failureResponse('code not correct');
    }
}
