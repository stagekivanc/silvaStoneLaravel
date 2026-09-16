@extends('yonetim.layouts.admin')

@section('title', 'Proje Yönetimi')
@section('page_title', 'Proje Yönetimi')

@section('content')
<div class="max-w-9xl mx-auto">
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
                <p class="text-sm text-emerald-700 font-bold">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="flex justify-between mb-6 gap-3 flex-wrap">
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('yonetim.proje-tipleri.index') }}" class="px-5 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2 text-sm">
                <i data-lucide="tags" class="w-4 h-4"></i>
                Tipler
            </a>
            <a href="{{ route('yonetim.proje-mekanlari.index') }}" class="px-5 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2 text-sm">
                <i data-lucide="map-pin" class="w-4 h-4"></i>
                Mekânlar
            </a>
            <a href="{{ route('yonetim.proje-sehirleri.index') }}" class="px-5 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2 text-sm">
                <i data-lucide="building" class="w-4 h-4"></i>
                Şehirler
            </a>
        </div>
        <a href="{{ route('yonetim.projeler.create') }}" class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Yeni Proje Ekle
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest w-12">#</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Proje</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Tip / Mekân</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Şehir</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Sıra</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Durum</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($projects as $project)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-5 text-slate-400 font-mono text-xs">{{ $project->id }}</td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-12 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 flex-shrink-0">
                                        @if($project->image_url)
                                            <img src="{{ $project->image_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i data-lucide="building-2" class="w-6 h-6 text-slate-300"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $project->title }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $project->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex flex-col gap-1">
                                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-blue-100 w-fit">{{ $project->typeLabel() }}</span>
                                    <span class="text-[11px] text-slate-500">{{ $project->placeLabel() }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-600">{{ $project->city_label ?: '—' }}</td>
                            <td class="px-8 py-5">
                                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded">{{ $project->order }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex flex-col gap-1">
                                    @if($project->status)
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-emerald-100 w-fit">Aktif</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-red-100 w-fit">Pasif</span>
                                    @endif
                                    @if($project->home_status)
                                        <span class="text-[10px] text-slate-400">Anasayfa</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('yonetim.projeler.edit', $project) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Düzenle">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('yonetim.projeler.destroy', $project) }}" method="POST" onsubmit="return confirm('Bu projeyi silmek istediğinize emin misiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Sil">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-8 py-16 text-center text-slate-400 text-sm">Henüz proje eklenmemiş.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
