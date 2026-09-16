<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class CustomerAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'E-posta adresi zorunludur.',
            'email.email' => 'Geçerli bir e-posta giriniz.',
            'password.required' => 'Şifre zorunludur.',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && ! $user->is_active) {
            return back()->withErrors([
                'email' => 'Hesabınız pasif durumda. Lütfen bizimle iletişime geçin.',
            ])->withInput($request->only('email'));
        }

        if (! Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'E-posta veya şifre hatalı.',
            ])->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        $lang = app()->getLocale() ?: 'tr';

        return redirect()->intended(route('customer.panel', ['lang' => $lang]))
            ->with('success', 'Giriş başarılı. Hoş geldiniz!');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'ad' => ['required', 'string', 'max:100'],
            'soyad' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'telefon' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
        ], [
            'ad.required' => 'Ad zorunludur.',
            'soyad.required' => 'Soyad zorunludur.',
            'email.required' => 'E-posta zorunludur.',
            'email.unique' => 'Bu e-posta ile kayıtlı bir hesap var.',
            'telefon.required' => 'Telefon zorunludur.',
            'password.required' => 'Şifre zorunludur.',
            'password.confirmed' => 'Şifreler eşleşmiyor.',
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
        ]);

        $user = User::create([
            'name' => trim($validated['ad'] . ' ' . $validated['soyad']),
            'email' => $validated['email'],
            'phone' => $validated['telefon'],
            'password' => $validated['password'],
            'is_active' => true,
        ]);

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        $lang = app()->getLocale() ?: 'tr';

        return redirect()->route('customer.panel', ['lang' => $lang])
            ->with('success', 'Hesabınız oluşturuldu. Hoş geldiniz!');
    }

    public function logout(Request $request)
    {
        $wasImpersonating = $request->session()->has('impersonator_admin_id');

        Auth::guard('web')->logout();

        if ($wasImpersonating) {
            $request->session()->forget([
                'impersonator_admin_id',
                'impersonating_member_id',
                'impersonating_member_name',
            ]);
            $request->session()->regenerateToken();

            return redirect()->route('yonetim.uyeler.index')
                ->with('success', 'Üye hesabından çıkıldı. Panele döndünüz.');
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $lang = app()->getLocale() ?: 'tr';

        return redirect()->route('home', ['lang' => $lang])
            ->with('success', 'Çıkış yapıldı.');
    }
}
