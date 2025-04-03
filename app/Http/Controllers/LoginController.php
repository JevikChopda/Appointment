<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'doctor') {
                return redirect()->route('doctor.dashboard');
            } else {
                return redirect()->route('patient.dashboard');
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }
}
