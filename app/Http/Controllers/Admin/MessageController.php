<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(20);
        return view('yonetim.mesajlar.index', compact('messages'));
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        
        // Okundu olarak işaretle
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }
        
        return view('yonetim.mesajlar.show', compact('message'));
    }

    public function toggleRead($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['is_read' => !$message->is_read]);
        
        return back()->with('success', 'Okunma durumu güncellendi.');
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();
        
        return redirect()->route('yonetim.mesajlar.index')->with('success', 'Mesaj başarıyla silindi.');
    }
}
