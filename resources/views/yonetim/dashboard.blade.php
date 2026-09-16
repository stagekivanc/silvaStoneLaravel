@extends('yonetim.layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Yönetim Paneli Özet')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-8">
    <!-- Stat Card 2: Mesajlar -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-500 flex items-center justify-center group-hover:bg-purple-500 group-hover:text-white transition-all duration-300">
                <i data-lucide="mail" class="w-6 h-6"></i>
            </div>
        </div>
        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">İletişim Mesajları</p>
        <div class="flex items-baseline gap-2">
            <h3 class="text-2xl font-display font-bold text-slate-800">{{ number_format($stats['total_messages']) }}</h3>
            <span class="text-slate-400 text-xs font-medium uppercase">Toplam</span>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between">
            <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-tight">Okunmamış</span>
            <span class="text-xs font-bold {{ $stats['unread_messages'] > 0 ? 'text-orange-600 bg-orange-50' : 'text-slate-400 bg-slate-50' }} px-2 py-0.5 rounded-full uppercase">
                {{ $stats['unread_messages'] }} YENİ
            </span>
        </div>
    </div>

    <!-- Stat Card 3: Teklif -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                <i data-lucide="file-plus" class="w-6 h-6"></i>
            </div>
        </div>
        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">Teklif Talepleri</p>
        <div class="flex items-baseline gap-2">
            <h3 class="text-2xl font-display font-bold text-slate-800">{{ number_format($stats['total_offers']) }}</h3>
            <span class="text-slate-400 text-xs font-medium uppercase">Toplam</span>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between">
            <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-tight">Yeni Talep</span>
            <span class="text-xs font-bold {{ $stats['unread_offers'] > 0 ? 'text-emerald-600 bg-emerald-50' : 'text-slate-400 bg-slate-50' }} px-2 py-0.5 rounded-full uppercase">
                {{ $stats['unread_offers'] }} TALEP
            </span>
        </div>
    </div>
</div>
 
<div>
    <!-- Son Gelen Başvurular (Combined) -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden font-display h-fit">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center">
            <h3 class="text-lg font-medium text-slate-800">Son Gelen Başvurular</h3>
            <i data-lucide="clock" class="w-5 h-5 text-slate-400"></i>
        </div>
        <div class="p-0">
            @php
                $allRecent = collect();
                
                foreach($recentMessages as $item) {
                    $allRecent->push([
                        'type' => 'İletişim',
                        'title' => $item->name . ' ' . $item->surname,
                        'subtitle' => $item->email,
                        'date' => $item->created_at,
                        'status' => $item->is_read,
                        'route' => route('yonetim.mesajlar.show', $item->id),
                        'color' => 'purple'
                    ]);
                }
                
                foreach($recentOffers as $item) {
                    $allRecent->push([
                        'type' => 'Teklif',
                        'title' => ($item->company ?? 'Şirket Yok') . ' - ' . ($item->name ?? 'İsimsiz'),
                        'subtitle' => $item->email,
                        'date' => $item->created_at,
                        'status' => $item->is_read,
                        'route' => route('yonetim.teklifler.show', $item->id),
                        'color' => 'emerald'
                    ]);
                }

                $sortedRecent = $allRecent->sortByDesc('date')->take(7);
            @endphp

            <div class="divide-y divide-slate-50">
                @forelse($sortedRecent as $record)
                <a href="{{ $record['route'] }}" class="flex items-center p-6 hover:bg-slate-50/80 transition-all group">
                    <div class="w-10 h-10 rounded-xl bg-{{ $record['color'] }}-50 text-{{ $record['color'] }}-500 flex items-center justify-center flex-shrink-0 mr-4">
                        @if($record['type'] == 'İletişim') <i data-lucide="mail" class="w-5 h-5"></i>
                        @else <i data-lucide="file-plus" class="w-5 h-5"></i> @endif
                    </div>
                    <div class="flex-grow min-w-0">
                        <div class="flex items-center mb-0.5">
                            <span class="text-xs font-bold text-{{ $record['color'] }}-600 uppercase tracking-tight mr-2">{{ $record['type'] }}</span>
                            <span class="text-[10px] text-slate-400">{{ $record['date']->diffForHumans() }}</span>
                        </div>
                        <h4 class="text-sm font-medium text-slate-800 line-clamp-1">{{ $record['title'] }}</h4>
                        <p class="text-xs text-slate-500 line-clamp-1">{{ $record['subtitle'] }}</p>
                    </div>
                    @if(!$record['status'])
                    <div class="w-2 h-2 rounded-full bg-blue-500 ml-4 ring-4 ring-blue-50 shrink-0"></div>
                    @endif
                    <div class="ml-4 shrink-0">
                        <i data-lucide="eye" class="w-5 h-5 text-slate-300"></i>
                    </div>
                </a>
                @empty
                <div class="p-12 text-center">
                    <p class="text-slate-400 text-sm">Son zamanlarda herhangi bir başvuru alınmadı.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
