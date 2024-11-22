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

        // If no user exists, create a new one
        if (!$user) {
            $new_user = User::create([
                'name' => $google_user->getName(),
                'email' => $google_user->getEmail(),
                'google_id' => $google_user->getId()
            ]);

            Auth::login($new_user);
            return redirect()->intended('dashboard');
        } else {
            // If the user exists, log them in
            Auth::login($user);
            return redirect()->intended('dashboard');
        }
    } catch (\Throwable $th) {
        dd('Something went wrong! ' . $th->getMessage());
    }
}

}
