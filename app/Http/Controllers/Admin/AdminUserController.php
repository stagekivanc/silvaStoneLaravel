<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function index()
    {
        // ID 1 (superadmin) hariç listele
        $users = Admin::where('id', '!=', 1)->latest()->paginate(15);
        return view('yonetim.kullanicilar.index', compact('users'));
    }

    public function create()
    {
        return view('yonetim.kullanicilar.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'Ad Soyad alanı zorunludur.',
            'email.required' => 'E-posta alanı zorunludur.',
            'email.unique' => 'Bu e-posta adresi zaten kullanımda.',
            'password.required' => 'Şifre alanı zorunludur.',
            'password.confirmed' => 'Şifreler eşleşmiyor.',
        ]);

        Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('yonetim.kullanicilar.index')->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    public function edit($id)
    {
        $user = Admin::findOrFail($id);
        return view('yonetim.kullanicilar.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = Admin::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'Ad Soyad alanı zorunludur.',
            'email.required' => 'E-posta alanı zorunludur.',
            'email.unique' => 'Bu e-posta adresi zaten kullanımda.',
            'password.confirmed' => 'Şifreler eşleşmiyor.',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('yonetim.kullanicilar.index')->with('success', 'Kullanıcı başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $user = Admin::findOrFail($id);
        
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Kendi hesabınızı silemezsiniz.');
        }

        $user->delete();

        return redirect()->route('yonetim.kullanicilar.index')->with('success', 'Kullanıcı başarıyla silindi.');
    }
}
