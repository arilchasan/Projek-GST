<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
    public function loginAdminForm()
    {
        return view('admin.loginAdmin');
    }
    public function loginPost(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        if ($username == 'adminGST' && $password == 'adminGST123') {
            $request->session()->put('isAdmin', true);
            return redirect('/dashboard')->with('success', 'Welcome Admin!');
        } else {
            return redirect('/auth/login-admin')->with('error', 'Invalid Username or Password!');
        }
    }
    public function logout()
    {
        session()->forget('isAdmin');
        return redirect('/auth/login-admin')->with('success', 'You have been logged out!');
    }
}
