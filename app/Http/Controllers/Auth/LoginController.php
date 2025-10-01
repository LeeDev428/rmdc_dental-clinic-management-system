<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request...
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
            return redirect()->route('verification.notice')->withErrors(['email' => 'Please verify your email address before logging in.']);
        }
    }
}

