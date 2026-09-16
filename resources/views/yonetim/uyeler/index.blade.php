@extends('yonetim.layouts.admin')

@section('title', 'Üyeler')
@section('page_title', 'Üyeler')

@section('content')
<div class="max-w-9xl mx-auto">
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-600 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('yonetim.uyeler.index') }}" class="mb-6 flex flex-col sm:flex-row gap-3">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Ad, e-posta, telefon..."
               class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
        <select name="status" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm">
            <option value="">Tüm durumlar</option>
            <option value="active" @selected(request('status') === 'active')>Aktif</option>
            <option value="passive" @selected(request('status') === 'passive')>Pasif</option>
        </select>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-blue-600 transition-colors">
            Filtrele
        </button>
    </form>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Durum</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Üye</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Telefon</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Firma</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Kayıt</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($members as $member)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                @if($member->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">Pasif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-900">{{ $member->name }}</div>
                                <div class="text-xs text-slate-500">{{ $member->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $member->phone ?: '—' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $member->company ?: '—' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $member->created_at?->format('d.m.Y H:i') }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <form action="{{ route('yonetim.uyeler.impersonate', $member->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 hover:border-emerald-500 hover:text-emerald-600 transition-all shadow-sm"
                                            title="Hesabına Gir">
                                        <i data-lucide="log-in" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                <a href="{{ route('yonetim.uyeler.show', $member->id) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 hover:border-blue-500 hover:text-blue-500 transition-all shadow-sm"
                                   title="Detay">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('yonetim.uyeler.toggle-active', $member->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 hover:border-amber-500 hover:text-amber-600 transition-all shadow-sm"
                                            title="{{ $member->is_active ? 'Pasife al' : 'Aktifleştir' }}">
                                        <i data-lucide="{{ $member->is_active ? 'user-x' : 'user-check' }}" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                <form action="{{ route('yonetim.uyeler.destroy', $member->id) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Bu üyeyi silmek istediğinize emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 hover:border-red-500 hover:text-red-500 transition-all shadow-sm"
                                            title="Sil">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">Henüz üye kaydı yok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($members->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $members->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
