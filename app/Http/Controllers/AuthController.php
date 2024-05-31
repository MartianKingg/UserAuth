<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{

    // Registration
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $user = new User;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->save();

        auth::login($user);

        return redirect()->route('dashboard');
    }

    //login

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentails = $request->only('email', 'password');

        if (auth::attempt($credentails)) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('login')->withErrors('Invalid Credentials');
        }
    }

    

    // logout
    public function logout(Request $request){
        auth::logout();

        return redirect()->route('login');
    }
}
