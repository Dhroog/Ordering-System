<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        if(Auth::attempt($request->except('login')))
        {
            return $this->DataResponse($request->user()->createToken("auth_token")->plainTextToken);
        }
        return $this->failureResponse('These credentials do not match our records.');

    }
}
