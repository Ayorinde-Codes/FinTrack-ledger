<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;

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
                'company_id' => $request->company_id,
            ]);

            $user->roles()->attach(UserRole::USER->value);

            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
            $success['username'] = $user->username;
            $success['avatar'] = $user->avatar;

            return $this->createdResponse("User account created successfully", $success);
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
                return $this->errorResponse("Invalid Credentials");
            }
        } catch (\Exception $e) {
            return $this->serverErrorResponse("Unable to create account", $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        $request->user()->currentAccessToken()->delete();

        return $this->okResponse('You have been successfully logged out.');
    }
}
