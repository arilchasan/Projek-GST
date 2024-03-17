<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
            'status' => 'active',
            'expired_at' => Carbon::now('Asia/Jakarta')->addDays(365)->toDateTimeString(),
        ]);
        if ($request->wantsJson()) {
            return response()->json(['message' => 'User registered successfully', 'user' => $user]);
        }

        return redirect('/auth/login')->with('success', 'Berhasil mendaftar!');
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


        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', 'Password salah!');
        }

        if (!$user) {
            return redirect()->back()->with('error', 'Email tidak terdaftar.');
        }

        if ($user->status == 'nonactive') {
            return redirect()->back()->with('error', 'Akun Anda tidak aktif.');
        }


        if ($user->expired_at && $user->expired_at < Carbon::now('Asia/Jakarta')) {
            Auth::logout();
            return redirect()->back()->with('error', 'Akun Anda telah kedaluwarsa.');
        }


        //$token = $user->createToken('authToken')->plainTextToken;

        return redirect('/home')->with('success', 'Berhasil login!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/auth/login')->with('success', 'Berhasil logout!');
    }
}
