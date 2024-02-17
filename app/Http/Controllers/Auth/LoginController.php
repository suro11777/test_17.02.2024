<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->input('remember'))) {
            throw ValidationException::withMessages([array_key_first($credentials) => 'Bad credentials']);
        }
        $user = $request->user();
        $token = $user->createToken("personal_access_token");
        $success['access_token'] =  $token->accessToken;
        $success['token_type'] = 'Bearer';
        $success['expires_at'] = Carbon::parse($token->token->expires_at)->toDateTimeString();
        $success['token'] = $token->token->id;
        Auth::loginUsingId($user->id);

        return response()->json($success);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        $token = request()->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

}
