<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        
        $this->middleware('guest')->except(['logout', 'verifyOtp', 'otpView']);
        $this->middleware('auth')->only('logout');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))->user();
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate([
                'email' => $googleUser->email,
            ], [
                'name' => $googleUser->name,
                'id_google' => $googleUser->id,
                'password' => bcrypt(Str::random(16))
            ]);

            Auth::login($user);

            
            $otp = strtoupper(Str::random(6)); 
            $user->update(['otp' => $otp]);

            
            Mail::raw("Halo {$user->name}, kode OTP verifikasi login Anda adalah: {$otp}", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Kode OTP Perpustakaan Digital');
            });

            return redirect()->route('otp.view');

        } catch (\Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Gagal login melalui Google.');
        }
    }

    public function otpView()
    {
        
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|string|max:6']);
        $user = Auth::user();

        
        if ($user && $request->otp === $user->otp) {
            $user->update(['otp' => null]);
            return redirect($this->redirectTo); 
        }

        return back()->with('error', 'Kode OTP salah atau sudah kadaluwarsa!');


    } 
}