<?php

namespace App\Http\Controllers\Auth;

use App\Facades\Sms;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Repositories\PhoneVerificationCodeRepository;
use App\Rules\PasswordRule;
use App\Rules\PhoneRule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $user = new User([
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'password' => Hash::make($request->input('password')),
        ]);
        $token = $user->createToken(__("property.personal_access_token"));
        $success['access_token'] =  $token->accessToken;
        $success['token'] = $token->token->id;
        $success['token_type'] = 'Bearer';
        $success['expires_at'] = Carbon::parse($token->token->expires_at)->toDateTimeString();
        Auth::login($user, true);

        return response($success, 201);
    }
}
