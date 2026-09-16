@extends('yonetim.layouts.admin')

@section('title', 'Dil Yönetimi')
@section('page_title', 'Dil Yönetimi')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-slate-500 text-sm">Sistemdeki aktif dilleri yönetin.</p>
    <a href="{{ route('yonetim.languages.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Yeni Dil Ekle
    </a>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50/50">
                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Dil Adı</th>
                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Dil Kodu</th>
                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Durum</th>
                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Varsayılan</th>
                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-right">İşlemler</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @foreach($languages as $language)
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4">
                    <span class="text-sm font-semibold text-slate-700">{{ $language->name }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-xs font-bold text-slate-500 uppercase">{{ $language->code }}</span>
                </td>
                <td class="px-6 py-4">
                    @if($language->status)
                        <span class="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase">Aktif</span>
                    @else
                        <span class="px-2 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold uppercase">Pasif</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if($language->is_default)
                        <span class="px-2 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold uppercase">Varsayılan</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('yonetim.languages.edit', $language->id) }}" class="p-2 text-slate-400 hover:text-blue-600 transition-colors">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                        </a>
                        @if(!$language->is_default)
                        <form action="{{ route('yonetim.languages.destroy', $language->id) }}" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
