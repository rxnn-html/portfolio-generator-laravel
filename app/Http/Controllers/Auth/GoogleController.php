<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        try {
            $g = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            Log::warning('Google sign-in failed: '.$e->getMessage());

            return redirect()->route('home')->with('error', 'Google sign-in failed. Please try again.');
        }

        $user = User::updateOrCreate(
            ['email' => $g->getEmail()],
            [
                'name' => $g->getName() ?? $g->getNickname() ?? $g->getEmail(),
                'google_id' => $g->getId(),
                'avatar' => $g->getAvatar(),
            ]
        );

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('manage');
    }
}
