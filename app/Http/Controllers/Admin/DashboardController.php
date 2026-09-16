<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\OfferRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_messages' => ContactMessage::count(),
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
            'total_offers' => OfferRequest::count(),
            'unread_offers' => OfferRequest::where('is_read', false)->count(),
        ];

        $recentMessages = ContactMessage::latest()->take(5)->get();
        $recentOffers = OfferRequest::latest()->take(5)->get();

        return view('yonetim.dashboard', compact(
            'stats', 
            'recentMessages', 
            'recentOffers'
        ));
    }
}
