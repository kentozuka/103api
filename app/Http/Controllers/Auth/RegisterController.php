<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);
        $user = new User();
        $user->fill($validatedData);
        $user->password = bcrypt($request->password);
        $user->save();
        $name = $request->name;
        if (!$token = auth()->attempt($request->only('email', 'password'))) {
            return response(null, 401);
        }
        return response()->json(compact('token', 'name'));
    }
}