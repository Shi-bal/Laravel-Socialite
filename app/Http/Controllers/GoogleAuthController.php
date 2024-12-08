<?php
namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleAuthController extends Controller
{

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle()
    {
        try {
            $google_user = Socialite::driver('google')->user();

            // Retrieve the user by their Google ID
            $user = User::where('google_id', $google_user->getId())->first();

            // If no user exists, check if the email is already in use
            if (!$user) {
                $user = User::where('email', $google_user->getEmail())->first();

                // If the email already exists, link the Google account
                if ($user) {
                    $user->google_id = $google_user->getId();
                    $user->avatar = $google_user->getAvatar();
                    $user->save();
                } else {
                    // Otherwise, create a new user
                    $user = User::create([
                        'name' => $google_user->getName(),
                        'email' => $google_user->getEmail(),
                        'google_id' => $google_user->getId(),
                        'avatar' => $google_user->getAvatar(),
                    ]);
                }
            } else {
                // Update the avatar if the user already exists
                $user->avatar = $google_user->getAvatar();
                $user->save();
            }

            // Log the user in
            Auth::login($user);
            return redirect()->intended('dashboard');
        } catch (\Throwable $th) {
            return redirect()->route('login')->with('error', 'Something went wrong! ' . $th->getMessage());
        }
    }
}
