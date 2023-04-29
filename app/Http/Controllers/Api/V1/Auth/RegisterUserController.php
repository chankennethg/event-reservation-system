<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\RegisterUserRequest;
use App\Http\Resources\V1\Auth\RegisterUserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterUserController extends Controller
{
    /**
     * User Registration Function
     *
     * @param RegisterUserRequest $request payload
     * @return RegisterUserResource Eloquent Resources Response
     */
    public function store(RegisterUserRequest $request): RegisterUserResource
    {
        $data = $request->safe()->all();

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return new RegisterUserResource($user);
    }
}
