<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller
{
    public function register(Request $request) {
        try {
            $validatedData = $request->validate([
                'email' => 'required|string|email|unique:users',
                'name' => 'required|string',
                'password' => 'required|string|min:8',
            ], [
                'email.required' => 'Email wajib untuk diisi.',
                'name.required' => 'Nama wajib untuk diisi',
                'password.required' => 'Password wajib diisi.',
            ]);
        } catch(ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()]);
        }
        $validatedData["password"] = Hash::make($validatedData["password"]);

        $User = User::create($validatedData);
        $token = $User->createToken("auth_token")->plainTextToken;
        return response()->json([
            "data" => [
                "token" => $token
            ]
            ]);
    }
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [
                    "errors" => $validator->invalid()
                ]
                ], 422);
        }
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'email' => 'The provided credentials are incorrect.'
            ]);
        }
        $token = $user->createToken("auth_token")->plainTextToken;
        return response()->json([
            "data" => [
                "token" => $token
            ]
            ]);
    }
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
