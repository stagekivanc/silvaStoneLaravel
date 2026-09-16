<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    /**
     * Admin login sayfasını gösterir.
     */
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('yonetim.dashboard');
        }
        return view('yonetim.login');
    }

    /**
     * Login işlemini gerçekleştirir.
     */
    public function login(Request $request)
    {
        // 1. Rate Limiting Check
        $throttleKey = 'admin_login|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', 'Çok fazla başarısız giriş denemesi. Lütfen ' . $seconds . ' saniye sonra tekrar deneyin.');
        }

        // 2. Honeypot Check
        if ($request->filled('hp_username')) {
            // Fake success or just return back silently to waste bot's time
            return back()->with('error', 'Geçersiz işlem.');
        }

        // 3. Validation and Attempt
        $credentials = $request->validate([
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'E-posta adresi gereklidir.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'password.required' => 'Şifre gereklidir.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            RateLimiter::clear($throttleKey);
            return redirect()->intended(route('yonetim.dashboard'));
        }

        // Increment attempts on failure
        RateLimiter::hit($throttleKey, 60); // 5 attempts per 60 seconds

        return back()->withErrors([
            'email' => 'Girdiğiniz bilgiler kayıtlarımızla eşleşmiyor.',
        ])->withInput($request->only('email'));
    }

    /**
     * Oturumu kapatır.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('yonetim.login');
    }
}
