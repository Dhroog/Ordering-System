<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __invoke()
    {
        auth()->user()->tokens()->delete();
        return $this->SuccessResponse('Successfully logged out');
    }
}
