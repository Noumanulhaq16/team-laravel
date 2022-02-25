<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => $validator->errors(),
                ]
            );
        }

        $user = new User();
        $user->name = $request->name;
        // $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->role = '3';
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(
            [
                'status' => 'success',
                'message' => 'You Has Been Successfully Registered',
            ]
        );
    }
    protected function registerAgent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => $validator->errors(),
                ]
            );
        }

        $user = new User();
        $user->name = $request->name;
        // $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->role = '2';
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(
            [
                'status' => 'success',
                'message' => 'You Has Been Successfully Registered',
            ]
        );
    }
    public function login(Request $request)
    {
        $controlls = $request->all();
        $rules =  [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'rememberme' => 'boolean'
        ];
        $validator = Validator::make($controlls, $rules);
        if ($validator->fails()) {
            return response()->json([
                'error' =>  $validator->errors(),
            ], 200);
        }
        $credentials = ['email' => $request->email, 'password' => $request->password];

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('auth_token');
        $token = $tokenResult->token;
        if ($request->rememberme)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            'user' => $user,
        ]);
    }
}
