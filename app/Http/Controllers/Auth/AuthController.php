<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(Request $request) {
        return redirect()->route('google.login');
    }

    public function logout(Request $request) {
        $request->session()->flush();
        return redirect('/');
    }

    public function register(Request $request) {
        return view('auth.register');
    }

    public function google(Request $request) {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(Request $request) {
//        dd(Socialite::driver('google'));
        $googleUser = Socialite::driver('google')->stateless()->user();


//        dd($googleUser);
        // Проверяем пользователя в базе
        $user = User::updateOrCreate([
            'email' => $googleUser->getEmail(),
        ], [
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
        ]);

        Auth::login($user);
        $user = Auth::user();
        $is_have_folder = Folder::where('id_user', $user->id)->exists();
        if (!$is_have_folder) {
            Folder::create([
                'name' => 'Жалпы коноктор',
                'info' => $request->info ?? '',
                'id_parent' => null,
                'id_user' => $user->id,
            ]);

        }

        return redirect('/');
    }
}
