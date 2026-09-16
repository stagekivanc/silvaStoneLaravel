@extends('yonetim.layouts.admin')

@section('title', 'Teklif Detayı: ' . $offer->application_number)
@section('page_title', 'Teklif Talebi Detayı')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6">
        <a href="{{ route('yonetim.teklifler.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Listeye Dön
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <!-- Temel Bilgiler -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </span>
                    Müşteri Bilgileri
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Ad Soyad / Yetkili</label>
                        <div class="text-sm font-medium text-slate-900">{{ $offer->name }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Şirket Ünvanı</label>
                        <div class="text-sm font-medium text-slate-900">{{ $offer->company }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">E-Posta</label>
                        <a href="mailto:{{ $offer->email }}" class="text-sm font-medium text-blue-600 hover:underline">{{ $offer->email }}</a>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Telefon</label>
                        <a href="tel:{{ $offer->phone }}" class="text-sm font-medium text-slate-900 hover:text-blue-600 transition-colors">{{ $offer->phone }}</a>
                    </div>
                </div>
            </div>

            <!-- İlgi Alanları ve Detaylar -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    Talep Detayları
                </h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2">Sektör</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($offer->areas ?? [] as $area)
                                <span class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg text-sm font-medium text-slate-700">
                                    {{ $area }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Ürün Grubu</label>
                            <div class="text-sm font-medium text-slate-900">{{ $offer->budget ?: '-' }}</div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Talep Türü</label>
                            <div class="text-sm font-medium text-slate-900">{{ $offer->employees ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Ürün / Talep Detayı -->
            @if($offer->product_id || $offer->configuration || $offer->items)
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4H7a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>
                        </svg>
                    </span>
                    Ürün / Talep Detayı
                </h3>

                @if(!empty($offer->items) && is_array($offer->items))
                <div class="space-y-3 mb-6">
                    @foreach($offer->items as $item)
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center gap-4">
                        @if(!empty($item['image']))
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] ?? '' }}" class="w-16 h-16 rounded-xl object-cover bg-white">
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-bold text-slate-900 truncate">{{ $item['name'] ?? 'Ürün' }}</div>
                            <div class="text-xs text-slate-500 mt-1">
                                @if(!empty($item['sku'])) SKU: {{ $item['sku'] }} · @endif
                                @if(!empty($item['color'])) Renk: {{ $item['color'] }} · @endif
                                @if(!empty($item['size'])) Beden: {{ $item['size'] }} · @endif
                                Adet: {{ $item['qty'] ?? 1 }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @elseif($offer->product)
                <div class="mb-6 p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center gap-4">
                    @if($offer->product->image_url)
                    <img src="{{ $offer->product->image_url }}" alt="{{ $offer->product->name }}" class="w-20 h-20 rounded-xl object-cover">
                    @endif
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Seçilen Ürün</div>
                        <div class="text-sm font-bold text-slate-900">{{ $offer->product->name }}</div>
                    </div>
                </div>
                @endif

                @if($offer->configuration)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($offer->configuration as $key => $value)
                    @php
                        $configLabel = match ($key) {
                            'sector' => 'Sektör',
                            'material' => 'Ürün Grubu',
                            'request' => 'Talep Türü',
                            'type' => 'Kaynak',
                            'item_count' => 'Kalem Sayısı',
                            'total_qty' => 'Toplam Adet',
                            default => $key,
                        };
                        $configValue = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
                        if ($key === 'type' && $configValue === 'cart') {
                            $configValue = 'Sepet teklifi';
                        }
                    @endphp
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $configLabel }}</div>
                        <div class="text-sm font-bold text-slate-900">{{ $configValue }}</div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif

            <!-- Mesaj -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </span>
                    Müşteri Notu
                </h3>
                <div class="text-sm text-slate-600 leading-relaxed italic bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                    {{ $offer->message ?: 'Mesaj bırakılmamış.' }}
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <!-- Başvuru Bilgileri -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-8">
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Teklif No:</span>
                        <span class="font-bold text-slate-900">{{ $offer->application_number }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Kaynak:</span>
                        <span class="font-medium text-slate-900">{{ $offer->isCartQuote() ? 'Üye sepeti' : 'Form' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Durum:</span>
                        <span class="font-medium text-slate-900">{{ $offer->statusLabel() }}</span>
                    </div>
                    @if($offer->user)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Üye:</span>
                        <a href="{{ route('yonetim.uyeler.show', $offer->user_id) }}" class="font-medium text-blue-600 hover:underline">{{ $offer->user->name }}</a>
                    </div>
                    @endif
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Tarih:</span>
                        <span class="font-medium text-slate-900">{{ $offer->created_at->format('d.m.Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Saat:</span>
                        <span class="font-medium text-slate-900">{{ $offer->created_at->format('H:i') }}</span>
                    </div>

                    @if($offer->ip_address)
                    <div class="flex justify-between items-center text-sm pt-2 border-t border-slate-50">
                        <span class="text-slate-500">IP Adresi:</span>
                        <span class="font-mono text-[11px] font-bold text-slate-400">{{ $offer->ip_address }}</span>
                    </div>
                    @endif
                </div>

                <form action="{{ route('yonetim.teklifler.status', $offer->id) }}" method="POST" class="mt-6 space-y-3 pt-4 border-t border-slate-100">
                    @csrf
                    @method('PATCH')
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest block">Durum Güncelle</label>
                    <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                        <option value="pending" @selected(($offer->status ?: 'pending') === 'pending')>Beklemede</option>
                        <option value="processing" @selected($offer->status === 'processing')>İşleniyor</option>
                        <option value="quoted" @selected($offer->status === 'quoted')>Teklif Verildi</option>
                        <option value="closed" @selected($offer->status === 'closed')>Kapatıldı</option>
                    </select>
                    <textarea name="admin_note" rows="3" placeholder="Yönetici notu..." class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">{{ old('admin_note', $offer->admin_note) }}</textarea>
                    <button type="submit" class="w-full py-3 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-blue-600 transition-colors">Kaydet</button>
                </form>

                <div class="mt-6 space-y-3">
                    <form action="{{ route('yonetim.teklifler.toggle-read', $offer->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-3 rounded-xl {{ $offer->is_read ? 'bg-slate-100 text-slate-600' : 'bg-blue-600 text-white' }} text-sm font-bold hover:opacity-90 transition-all shadow-sm">
                            {{ $offer->is_read ? 'Okunmadı İşaretle' : 'Okundu İşaretle' }}
                        </button>
                    </form>

                    <form action="{{ route('yonetim.teklifler.destroy', $offer->id) }}" method="POST" onsubmit="return confirm('Bu teklif talebini silmek istediğinize emin misiniz?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-3 rounded-xl bg-white border border-red-100 text-red-600 text-sm font-bold hover:bg-red-50 transition-colors">
                            Talebi Sil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
