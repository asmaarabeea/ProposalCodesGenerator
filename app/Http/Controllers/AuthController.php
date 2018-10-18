<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create user and create token
     *
     * @param  [string] first_name
     * @param  [string] last_name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     *
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $requested_data = $request->only('first_name', 'last_name', 'email', 'role_id', 'password', 'password_confirmation');
        $validator      = Validator::make($requested_data, [
            'first_name' => 'required|string|min:3|max:255',
            'last_name'  => 'required|string|min:3|max:255',
            'email'      => 'required|email|max:255|unique:users,email',
            'role_id'    => 'required|exists:roles,id',
            'password'   => 'required|min:6|string|confirmed'
        ]);
        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $user = User::create($requested_data);
        if (!$user)
            return response()->json([
                'message' => 'user could not be created',
            ], 401);

        $tokenResult = $user->createToken('Personal Access Token');
        $token       = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'message'      => 'user created successfully',
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ], 201);
    }

    /**
     * Login user and create token
     * @param Request $request
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $requested_data = $request->only('email', 'password', 'remember_me');
        $validator      = Validator::make($requested_data, [
            'email'       => 'required|email|max:255',
            'password'    => 'required|min:6|string',
            'remember_me' => 'boolean'
        ]);
        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user        = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token       = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }


    /**
     * @param Request $request
     * Logout user (Revoke the token)
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return response()->json(auth()->user());
    }
}
