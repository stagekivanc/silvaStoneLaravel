<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfferRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OfferRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = OfferRequest::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('source')) {
            $query->where('source', $request->get('source'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $requests = $query->paginate(20)->withQueryString();

        return view('yonetim.teklifler.index', compact('requests'));
    }

    public function show($id)
    {
        $offer = OfferRequest::with(['product', 'user'])->findOrFail($id);

        if (! $offer->is_read) {
            $offer->update(['is_read' => true]);
        }

        return view('yonetim.teklifler.show', compact('offer'));
    }

    public function updateStatus(Request $request, $id)
    {
        $offer = OfferRequest::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'processing', 'quoted', 'closed'])],
            'admin_note' => ['nullable', 'string', 'max:5000'],
        ]);

        $offer->update($validated);

        return back()->with('success', 'Teklif durumu güncellendi.');
    }

    public function toggleRead($id)
    {
        $offer = OfferRequest::findOrFail($id);
        $offer->update(['is_read' => ! $offer->is_read]);

        return back()->with('success', 'Okunma durumu güncellendi.');
    }

    public function destroy($id)
    {
        $offer = OfferRequest::findOrFail($id);
        $offer->delete();

        return redirect()->route('yonetim.teklifler.index')->with('success', 'Teklif talebi başarıyla silindi.');
    }
}
