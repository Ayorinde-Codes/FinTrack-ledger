<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'avatar' => $request->avatar,
                'client_id' => $request->client_id,
            ]);

            $userRoleId = Role::where('name', UserRole::USER->value)->value('id');

            $user->roles()->attach($request->role_id ?? $userRoleId);

            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
            $success['username'] = $user->username;
            $success['avatar'] = $user->avatar;

            return $this->createdResponse('User account created successfully', $success);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $data['token'] = $user->createToken('MyApp')->plainTextToken;
                $data['name'] = $user->name;
                $data['username'] = $user->username;

                return $this->okResponse('User login successfully.', $data);
            } else {
                return $this->errorResponse('Invalid Credentials');
            }
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Unable to create account', $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        $request->user()->currentAccessToken()->delete();

        return $this->okResponse('You have been successfully logged out.');
    }
}
