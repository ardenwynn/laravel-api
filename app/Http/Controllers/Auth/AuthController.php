<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return Application|ResponseFactory|Response
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create(array_merge(
            $request->validated(),
            ['password' => bcrypt($request->password)],
        ));
        event(new Registered($user));

        return response([
            'message' => 'User successfully registered, email verification sent',
            'user' => new UserResource($user),
        ], 201);
    }

    /**
     * @param LoginRequest $request
     * @return Application|ResponseFactory|Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (!$token = auth()->attempt($credentials)) {
            return response(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function profile()
    {
        return response(new UserResource(auth()->user()));
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function logout()
    {
        auth()->logout();

        return response(['message' => 'Successfully logged out']);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * @param $token
     * @return Application|ResponseFactory|Response
     */
    protected function respondWithToken($token)
    {
        return response([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }
}
