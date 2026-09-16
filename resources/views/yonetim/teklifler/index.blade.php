@extends('yonetim.layouts.admin')

@section('title', 'Teklif Talepleri')
@section('page_title', 'Teklif Talepleri')

@section('content')
<div class="max-w-9xl mx-auto">
    <form method="GET" class="mb-6 flex flex-wrap gap-3">
        <select name="source" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm">
            <option value="">Tüm kaynaklar</option>
            <option value="cart" @selected(request('source') === 'cart')>Sepet teklifi</option>
            <option value="form" @selected(request('source') === 'form')>Form</option>
        </select>
        <select name="status" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm">
            <option value="">Tüm durumlar</option>
            <option value="pending" @selected(request('status') === 'pending')>Beklemede</option>
            <option value="processing" @selected(request('status') === 'processing')>İşleniyor</option>
            <option value="quoted" @selected(request('status') === 'quoted')>Teklif Verildi</option>
            <option value="closed" @selected(request('status') === 'closed')>Kapatıldı</option>
        </select>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-semibold">Filtrele</button>
    </form>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Okuma</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Başvuru No</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Ad Soyad / Ünvan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Şirket</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Kaynak / Durum</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Tarih</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($requests as $request)
                        <tr class="hover:bg-slate-50/50 transition-colors {{ !$request->is_read ? 'bg-blue-50/30' : '' }}">
                            <td class="px-6 py-4">
                                @if(!$request->is_read)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Yeni</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">Okundu</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-slate-900">{{ $request->application_number }}</span>
                                @if($request->isCartQuote())
                                    <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-emerald-100 text-emerald-700 uppercase tracking-tighter">Sepet</span>
                                @elseif($request->configuration)
                                    <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-amber-100 text-amber-700 uppercase tracking-tighter">Konfig</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-900">{{ $request->name }}</span>
                                    <span class="text-xs text-slate-500">{{ $request->email }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600 font-medium">{{ $request->company }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-700">{{ $request->statusLabel() }}</div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-wider mt-0.5">{{ $request->isCartQuote() ? 'Üye sepeti' : 'Form' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-700">{{ $request->created_at->format('d.m.Y') }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $request->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <form action="{{ route('yonetim.teklifler.toggle-read', $request->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 hover:border-blue-500 hover:text-blue-500 transition-all shadow-sm" title="{{ $request->is_read ? 'Okunmadı İşaretle' : 'Okundu İşaretle' }}">
                                        @if($request->is_read)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"></path>
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                                <a href="{{ route('yonetim.teklifler.show', $request->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 hover:border-blue-500 hover:text-blue-500 transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                Henüz teklif talebi bulunmamaktadır.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
        <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/50">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
