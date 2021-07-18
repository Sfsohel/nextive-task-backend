<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    
    public function loginUser(Request $request)
    {
        $credential = $request->only(['email','password']);
        if (!$token = auth()->attempt($credential)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response(["token",$token]);
    }

    public function registerUser(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:4'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        // dd($data);
        User::create($data);
        return "user added";
    }
    public function logoutUser()
    {
        try {
            $user = auth()->logout();
            return response(["success" => "Logged out successfully"]);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    
}
