@extends('yonetim.layouts.admin')

@section('title', 'Üye: ' . $member->name)
@section('page_title', 'Üye Detayı')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('yonetim.uyeler.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Listeye Dön
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-600 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">{{ $member->name }}</h2>
                    <p class="text-sm text-slate-500 mt-1">{{ $member->email }}</p>
                </div>
                @if($member->is_active)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">Pasif</span>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Telefon</div>
                    <div class="text-sm font-medium text-slate-900">{{ $member->phone ?: '—' }}</div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Firma</div>
                    <div class="text-sm font-medium text-slate-900">{{ $member->company ?: '—' }}</div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Kayıt Tarihi</div>
                    <div class="text-sm font-medium text-slate-900">{{ $member->created_at?->format('d.m.Y H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-3">
            <form action="{{ route('yonetim.uyeler.impersonate', $member->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition-colors">
                    Hesabına Gir
                </button>
            </form>
            <form action="{{ route('yonetim.uyeler.toggle-active', $member->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="w-full py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 hover:border-amber-500 hover:text-amber-600 transition-colors">
                    {{ $member->is_active ? 'Pasife Al' : 'Aktifleştir' }}
                </button>
            </form>
            <form action="{{ route('yonetim.uyeler.destroy', $member->id) }}" method="POST"
                  onsubmit="return confirm('Bu üyeyi silmek istediğinize emin misiniz?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 rounded-xl border border-red-100 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
                    Üyeyi Sil
                </button>
            </form>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Teklif Talepleri</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-3 text-xs font-bold text-slate-400 uppercase">No</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-400 uppercase">Kaynak</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-400 uppercase">Durum</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-400 uppercase">Tarih</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-400 uppercase text-right">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($offers as $offer)
                        <tr>
                            <td class="px-6 py-3 text-sm font-semibold text-slate-900">{{ $offer->application_number }}</td>
                            <td class="px-6 py-3 text-sm text-slate-600">{{ $offer->isCartQuote() ? 'Sepet' : 'Form' }}</td>
                            <td class="px-6 py-3 text-sm text-slate-600">{{ $offer->statusLabel() }}</td>
                            <td class="px-6 py-3 text-sm text-slate-500">{{ $offer->created_at?->format('d.m.Y H:i') }}</td>
                            <td class="px-6 py-3 text-right">
                                <a href="{{ route('yonetim.teklifler.show', $offer->id) }}" class="text-sm font-medium text-blue-600 hover:underline">Görüntüle</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">Bu üyeye ait teklif yok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($offers->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $offers->links() }}</div>
        @endif
    </div>
</div>
@endsection
