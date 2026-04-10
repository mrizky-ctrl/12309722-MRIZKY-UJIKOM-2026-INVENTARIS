<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   public function login(Request $request)
{
    $creds = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);
    if (Auth::attempt($creds)) {
        $request->session()->regenerate();
        /** @var \App\Models\User $user **/
        $user = Auth::user();

        // arah redirect admin ke dashboard admin, staff ke dashboard staff
        return $user->role == 'admin'
            ? redirect('/admin/dashboard')
            : redirect('/staff/dashboard');
    }
    return back()->withErrors(['loginError' => 'Email atau Password salah!']);
}
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
