<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(): RedirectResponse
    {
        return Socialite::driver('slack')->redirect();
    }

    public function logout(): RedirectResponse
    {
        auth()->logout();

        return redirect()->route('login');
    }

    public function callback(): RedirectResponse
    {
        $slackUser = Socialite::driver('slack')->user();

        $user = User::updateOrCreate([
            'slack_id' => $slackUser->getId(),
        ], [
            'name' => $slackUser->getName(),
            'nickname' => $slackUser->getNickname(),
            'avatar' => $slackUser->getAvatar(),
        ]);

        auth()->login($user);

        return redirect()->intended(route('poll.index'));
    }
}
