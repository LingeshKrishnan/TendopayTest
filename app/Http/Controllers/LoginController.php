<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        return view('login');
    }

    public function register(Request $request)
    {
        return view('register');
    }

    public function registerPost(Request $request)
    {
        $user = new User();

        $request->validate(
            [
                'name' => ['required', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users' . $user->email],
                'password' => ['required', 'max:20']
            ]
        );
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return back()->with('success', 'User Registered Successfully!');
    }

    public function loginPost(Request $request)
    {
        $credetials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credetials)) {
            return redirect('/home')->with('success', 'Login Success');
        }

        return back()->with('error', 'Invalid Credentials! Kindly Check the Credentials!');
    }
    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
