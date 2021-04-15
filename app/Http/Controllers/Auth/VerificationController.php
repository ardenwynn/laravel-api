<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only('resend');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:3,1')->only('verify', 'resend');
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response(['message' => 'Already verified']);
        }
        $request->user()->sendEmailVerificationNotification();

        return response(['message' => 'Email resent']);
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));
        if ($user->hasVerifiedEmail()) {
            return response(['message' => 'Already verified']);
        }
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response(['message' => 'Successfully verified']);
    }
}
