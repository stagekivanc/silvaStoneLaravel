@extends('yonetim.layouts.admin')

@section('title', 'Mesaj Detayı')
@section('page_title', 'Mesaj Detayı')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <!-- Action Bar -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('yonetim.mesajlar.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-blue-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Geri Dön
        </a>
        
        <form action="{{ route('yonetim.mesajlar.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Bu mesajı silmek istediğinize emin misiniz?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 transition-colors tracking-widest uppercase">
                Mesajı Sil
            </button>
        </form>
    </div>

    <!-- Message Content -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <!-- Sender Header -->
        <div class="p-8 border-b border-slate-50 bg-slate-50/30">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                        {{ substr($message->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">{{ $message->name }} {{ $message->surname }}</h3>
                        <p class="text-sm text-slate-500">{{ $message->email }} • {{ $message->phone }}</p>
                    </div>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tarih</p>
                    <p class="text-sm font-medium text-slate-700">{{ $message->created_at->translatedFormat('d F Y - H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="p-8 space-y-8">
            <!-- Subject line -->
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Konu</p>
                <h2 class="text-xl font-bold text-slate-900">{{ $message->subject ?? 'Konu Belirtilmemiş' }}</h2>
            </div>

            <!-- Message Body -->
            <div class="text-slate-700 leading-relaxed text-lg whitespace-pre-wrap">
                {{ $message->message }}
            </div>

            @if($message->company)
            <!-- Meta Info -->
            <div class="pt-8 border-t border-slate-50">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Şirket / Firma</p>
                <p class="text-sm font-medium text-slate-900">{{ $message->company }}</p>
            </div>
            @endif

            <div class="pt-8 border-t border-slate-50 grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($message->ip_address)
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">IP ADRESİ</p>
                    <p class="text-sm font-mono font-bold text-slate-600">{{ $message->ip_address }}</p>
                </div>
                @endif
                @if($message->user_agent)
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">TARAYICI BİLGİSİ</p>
                    <p class="text-[11px] font-medium text-slate-500 italic leading-snug">{{ $message->user_agent }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="p-8 bg-slate-50/50 border-t border-slate-50 flex flex-wrap gap-4">
            <a href="mailto:{{ $message->email }}" class="inline-flex items-center px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-sm">
                E-posta Gönder
            </a>
            <a href="tel:{{ $message->phone }}" class="inline-flex items-center px-6 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all shadow-sm">
                Ara
            </a>
        </div>
    </div>
</div>
@endsection
