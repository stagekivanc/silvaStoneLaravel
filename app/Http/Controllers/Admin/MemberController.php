<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfferRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->latest();

        if ($search = trim((string) $request->get('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'active');
        }

        $members = $query->paginate(20)->withQueryString();

        return view('yonetim.uyeler.index', compact('members'));
    }

    public function show($id)
    {
        $member = User::findOrFail($id);
        $offers = OfferRequest::where('user_id', $member->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('yonetim.uyeler.show', compact('member', 'offers'));
    }

    public function impersonate(Request $request, $id)
    {
        $member = User::findOrFail($id);
        $admin = Auth::guard('admin')->user();

        if (! $admin) {
            abort(403);
        }

        $request->session()->put('impersonator_admin_id', $admin->id);
        $request->session()->put('impersonating_member_id', $member->id);
        $request->session()->put('impersonating_member_name', $member->name);

        Auth::guard('web')->login($member);

        $lang = app()->getLocale() ?: 'tr';

        return redirect()->route('home', ['lang' => $lang])
            ->with('success', $member->name . ' hesabına giriş yapıldı.');
    }

    public function leaveImpersonation(Request $request)
    {
        if (! $request->session()->has('impersonator_admin_id')) {
            return redirect()->route('yonetim.uyeler.index');
        }

        Auth::guard('web')->logout();

        $request->session()->forget([
            'impersonator_admin_id',
            'impersonating_member_id',
            'impersonating_member_name',
        ]);

        return redirect()->route('yonetim.uyeler.index')
            ->with('success', 'Üye hesabından çıkıldı. Panele döndünüz.');
    }

    public function toggleActive($id)
    {
        $member = User::findOrFail($id);
        $member->update(['is_active' => ! $member->is_active]);

        $label = $member->is_active ? 'aktif' : 'pasif';

        return back()->with('success', "Üye durumu {$label} olarak güncellendi.");
    }

    public function destroy($id)
    {
        $member = User::findOrFail($id);
        $member->delete();

        return redirect()->route('yonetim.uyeler.index')
            ->with('success', 'Üye silindi.');
    }
}
