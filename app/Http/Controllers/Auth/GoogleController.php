<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
class GoogleController extends Controller
{
    public function redirectToGoogle(Request $request) {
        return Socialite::driver('google')->redirect();

    }

    public function handleGoogleCallback(Request $request){
        $user = Socialite::driver('google')->user();
        dd($user);
    }
}
