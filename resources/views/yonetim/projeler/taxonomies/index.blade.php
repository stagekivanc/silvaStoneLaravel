@extends('yonetim.layouts.admin')

@section('title', $pageTitle)
@section('page_title', $pageTitle)

@section('content')
<div class="max-w-5xl mx-auto">
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl">
            <p class="text-sm text-emerald-700 font-bold">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
            <p class="text-sm text-red-700 font-bold">{{ session('error') }}</p>
        </div>
    @endif

    <div class="flex justify-end mb-6">
        <a href="{{ route($routePrefix . '.create') }}" class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Yeni {{ $singularLabel }}
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest w-12">#</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Ad</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Slug</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Sıra</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Durum</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($items as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-5 text-slate-400 font-mono text-xs">{{ $item->id }}</td>
                        <td class="px-8 py-5 text-sm font-bold text-slate-900">{{ $item->name }}</td>
                        <td class="px-8 py-5 text-[11px] font-mono text-slate-400">{{ $item->slug }}</td>
                        <td class="px-8 py-5"><span class="text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded">{{ $item->order }}</span></td>
                        <td class="px-8 py-5">
                            @if($item->status)
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-emerald-100">Aktif</span>
                            @else
                                <span class="px-3 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-red-100">Pasif</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route($routePrefix . '.edit', $item->id) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route($routePrefix . '.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-16 text-center text-slate-400 text-sm">Henüz kayıt yok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
