<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $google = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('login')->withErrors(['login' => 'Google sign-in did not complete. Please try again.']);
        }

        if (! ($google->user['email_verified'] ?? true) || ! $google->getEmail()) {
            return redirect()->route('login')->withErrors(['login' => 'Your Google email is not verified.']);
        }

        $user = User::where('google_id', $google->getId())->first()
            ?? User::where('email', $google->getEmail())->first()
            ?? new User();

        $user->fill([
            'google_id' => $google->getId(),
            'name'      => $google->getName() ?: $google->getEmail(),
            'email'     => $google->getEmail(),
            'avatar'    => $google->getAvatar(),
        ])->save();

        Auth::login($user, true);
        $request->session()->regenerate(); // protects against session fixation

        return redirect()->intended('/manage');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}