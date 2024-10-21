<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{
     // Register a new user
     public function register(Request $request)
     {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
 
         $user = User::create([
             'name' => $request->name,
             'email' => $request->email,
             'password' => bcrypt($request->password),
         ]);
 
 
        //  return response()->json(compact('user', 'token'), 201);

         return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
     }
 
     // Log in an existing user
     public function login(Request $request)
     {
         $credentials = $request->only('email', 'password');
 
         if (!$token = JWTAuth::attempt($credentials)) {
             return response()->json(['error' => 'Invalid credentials'], 401);
         }
 
         return response()->json(compact('token'));
     }
 
     // Get authenticated user
    //  public function me()
    //  {
    //      return response()->json(Auth::user());
    //  }
    public function me(Request $request)
    {
        $token = $request->bearerToken();
    //    dd($token);
        if ($token) {
            return response()->json(auth()->user());
           
        }                                    
    
        return response()->json(['error' => 'You are not logged in'], 401);
    }
    
     // Log out user
     public function logout()
     {
         Auth::logout();
         return response()->json(['message' => 'Successfully logged out']);
     }
}
