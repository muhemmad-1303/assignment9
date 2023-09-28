<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {

        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'alpha_num', 'unique:users'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required'],
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ], 201);

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'token' => $user->createToken("API_TOKEN")->plainTextToken
        ], 200);

        
    }

    public function login(Request $request){
        $validateUser = Validator::make($request->all(),
            [
                'name' => 'required','alpha_num',
                'password' => 'required'
            ]);

        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        if(!auth()->attempt($request->only(['name', 'password']))){
            return response()->json([
                'status' => false,
                'message' => 'username & Password does not exist.',
            ], 401);
        }

        $user = User::where('name', $request->name)->first();

        return response()->json([
            'status' => true,
            'message' => 'Logged In Successfully',
            'token' => $user->createToken("API_TOKEN")->plainTextToken
        ], 200);
    }
}