<?php

namespace App\Http\Controllers;

use App\Models\OfferRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class CustomerPanelController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('web')->user();
        $tab = $request->query('tab', 'ozet');
        if (! in_array($tab, ['ozet', 'teklifler', 'profil'], true)) {
            $tab = 'ozet';
        }

        $offersQuery = OfferRequest::query()
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('email', $user->email);
            })
            ->orderByDesc('created_at');

        $offers = $offersQuery->paginate(10)->withQueryString();

        $stats = [
            'total' => OfferRequest::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('email', $user->email);
            })->count(),
            'pending' => OfferRequest::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('email', $user->email);
            })->where(function ($q) {
                $q->whereNull('status')
                    ->orWhere('status', '')
                    ->orWhereIn('status', ['pending', 'processing']);
            })->count(),
            'quoted' => OfferRequest::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('email', $user->email);
            })->where('status', 'quoted')->count(),
        ];

        return view('frontend.customer.panel', compact('user', 'tab', 'offers', 'stats'));
    }

    public function showOffer($lang, $id)
    {
        $user = Auth::guard('web')->user();

        $offer = OfferRequest::where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('email', $user->email);
            })
            ->firstOrFail();

        return view('frontend.customer.offer-show', compact('user', 'offer'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('web')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'company' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)],
        ], [
            'name.required' => 'Ad soyad zorunludur.',
            'phone.required' => 'Telefon zorunludur.',
            'email.required' => 'E-posta zorunludur.',
            'email.unique' => 'Bu e-posta başka bir hesapta kayıtlı.',
            'password.confirmed' => 'Şifreler eşleşmiyor.',
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        $user->company = $validated['company'] ?? null;
        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        $lang = app()->getLocale() ?: 'tr';

        return redirect()
            ->route('customer.panel', ['lang' => $lang, 'tab' => 'profil'])
            ->with('success', __t('account_profile_updated', 'Profil bilgileriniz güncellendi.', 'frontend'));
    }
}
