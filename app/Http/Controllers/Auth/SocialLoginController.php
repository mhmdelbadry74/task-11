<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


class SocialLoginController extends Controller
{
    public function redirectToProvider($provider)
    {
       return Socialite::driver($provider)->redirect();


    }
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/login');        }

        $existingUser = User::where('email', $user->getEmail())->first();

        if ($existingUser) {
            auth()->login($existingUser);
        } else {
               //generate code

            $newUser                    = new User();
            $newUser->provider     = $provider;
            $newUser->provider_user_id       = $user->getId();
            $newUser->name              = $user->getName();
            $newUser->email             = $user->getEmail();
            $newUser->save();

            auth()->login($newUser);
        }

        return redirect()->route('dashboard');
    }

}
