<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Resources\V1\Auth\LoginResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * User Login Function
     *
     * @param LoginRequest $request User Credentials
     * @return LoginResource|JsonResponse Eloquent Resource
     */
    public function login(LoginRequest $request): LoginResource|JsonResponse
    {
        $credentials = $request->safe()->all();

        // Check if credentials match
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid Username or Password.'], 401);
        }

        /** @var User $user*/
        $user = Auth::user();

        return new LoginResource($user);
    }
}
