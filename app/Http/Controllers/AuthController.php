<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class AuthController extends Controller
{
    //

    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'=>$validator->messages(),
            ], 200);
        }

        $users = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($users->save()) {            
            return response()->json(['message' => 'User registered successfully'], 201);
        }else{
            return response()->json(['message' => 'Something went wrong'], 400);
        }       
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();  
            $token = $user->createToken('Token Name')->plainTextToken;
            $token_update = User::where('id',Auth::user()->id)->update(array(
                'remember_token' => $token,
            ));
            return response()->json(['token' => $token,'message' => 'Login successfully'], 200);
        } else {
            return response()->json(['message' => 'Invalid credential email or password'], 401);
        }
    }

}
