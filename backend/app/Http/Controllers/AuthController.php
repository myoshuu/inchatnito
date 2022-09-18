<?php

namespace App\Http\Controllers;

use App\Models\ActiveUser;
use App\Models\AnonymousName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function getName()
    {
        $takenName = ActiveUser::all("name");
        $allName = AnonymousName::all("name")->diffAssoc($takenName);

        return implode("", $allName->toArray()[mt_rand(0, count($allName) - 1)]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
        
        $anonymousName = AuthController::getName();
        if (count(ActiveUser::where('user_id', Auth::id())->get()) < 1) {
            ActiveUser::create([
                'user_id' => Auth::id(),
                'name' => $anonymousName
            ]);
        } else {
            ActiveUser::where('user_id', Auth::id())->update([
                'name' => $anonymousName
            ]);
        }

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'anonymousName' => $anonymousName,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);

    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);

        $anonymousName = AuthController::getName();
        ActiveUser::create([
            'user_id' => Auth::id(),
            'name' => $anonymousName
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'anonymousName' => $anonymousName,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        ActiveUser::where('user_id', Auth::id())->delete();
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        $anonymousName = AuthController::getName();
        ActiveUser::where('user_id', Auth::id())->update([
            'name' => $anonymousName
        ]);
        
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'anonymousName' => $anonymousName,
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

}