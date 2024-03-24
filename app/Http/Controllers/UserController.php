<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tax;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required|string',
            'telp' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'telp' => $request->telp,
            'password' => Hash::make($request->password),
            'status' => 'nonactive',
            'expired_at' => Carbon::now('Asia/Jakarta')->addDays(365)->toDateTimeString(),
        ]);
        if ($request->wantsJson()) {
            return response()->json(['message' => 'User registered successfully', 'user' => $user]);
        }

        return redirect('/auth/login')->with('success', 'Register Success! Please login.');
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');


        Auth::attempt($credentials);
        $user = User::where('email', $request->email)->first();



        if (!$user) {
            return redirect()->back()->with('error', 'Email not registered.');
        }

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', 'Wrong password.');
        }

        if ($user->status == 'nonactive') {
            return redirect()->back()->with('error', 'Account not active. Please contact admin.');
        }


        if ($user->expired_at && $user->expired_at < Carbon::now('Asia/Jakarta')) {
            $user->update(['status' => 'nonactive']);
            Auth::logout();
            return redirect()->back()->with('error', 'Account expired. Please contact admin.');
        }


        //$token = $user->createToken('authToken')->plainTextToken;
        return redirect('/home')->with('success', 'Login Success!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/auth/login')->with('success', 'Logout Success!');
    }

    public function userFile()
    {
        $tax = Tax::where('user_id', Auth::user()->id)->get();
        return view('userFile', compact('tax'));
    }
}
