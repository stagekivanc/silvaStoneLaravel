<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\OfferRequest;

class NotificationController extends Controller
{
    /**
     * Tüm bildirimleri okundu olarak işaretler.
     */
    public function markAllAsRead()
    {
        ContactMessage::where('is_read', false)->update(['is_read' => true]);
        OfferRequest::where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'Tüm bildirimler okundu olarak işaretlendi.');
    }
}
