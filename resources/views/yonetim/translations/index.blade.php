@extends('yonetim.layouts.admin')

@section('title', 'Sabit Çeviriler')
@section('page_title', 'Sabit Çeviriler')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4" x-data="{ search: '' }">
    <div class="flex items-center gap-4">
        <p class="text-slate-500 text-sm">Arayüzdeki sabit metinleri tüm diller için buradan güncelleyebilirsiniz.</p>
        <div class="flex items-center bg-slate-100 p-1 rounded-xl">
            <a href="{{ route('yonetim.translations.index', ['group' => 'frontend']) }}" 
               class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $group == 'frontend' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500 hover:text-slate-700' }}">
               Arayüz
            </a>
            <a href="{{ route('yonetim.translations.index', ['group' => 'routes']) }}" 
               class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $group == 'routes' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500 hover:text-slate-700' }}">
               URL / Rotalar
            </a>
        </div>
    </div>
    
    <div class="relative group max-w-md w-full">
        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
            <i data-lucide="search" class="w-4 h-4 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
        </div>
        <input type="text" 
               x-model="search"
               @input="$dispatch('search-updated', search)"
               placeholder="Anahtar kelime veya çeviri ara..." 
               class="w-full bg-white border border-slate-200 text-slate-900 py-3 pl-12 pr-4 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 transition-all font-medium text-sm shadow-sm">
    </div>
</div>

<form action="{{ route('yonetim.translations.update') }}" method="POST" x-data="{ 
    search: '',
    rows: []
}" @search-updated.window="search = $event.detail">
    @csrf
    <input type="hidden" name="group" value="{{ $group }}">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest w-1/4">Key / Anahtar</th>
                        @foreach($languages as $lang)
                        <th class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $lang->is_default ? 'bg-blue-500' : 'bg-slate-300' }}"></span>
                                {{ strtoupper($lang->code) }} - {{ $lang->name }}
                            </div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @php
                        $allTranslations = \App\Models\StaticTranslation::where('group', $group)->get()->groupBy('key');
                    @endphp
                    @foreach($translations as $trans)
                    @php
                        $rowTranslations = $allTranslations->get($trans->key);
                        $searchContent = strtolower($trans->key . ' ' . $trans->group);
                        foreach($languages as $lang) {
                            $val = $rowTranslations ? $rowTranslations->where('lang_key', $lang->code)->first() : null;
                            if($val) $searchContent .= ' ' . strtolower($val->value);
                        }
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors" 
                        x-show="search === '' || '{{ addslashes($searchContent) }}'.includes(search.toLowerCase())">
                        <td class="px-6 py-5 align-top">
                            <div class="space-y-1">
                                <span class="text-xs font-bold text-slate-700 block uppercase tracking-tighter">{{ $trans->key }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500 uppercase">{{ $trans->group }}</span>
                            </div>
                        </td>
                        @foreach($languages as $lang)
                        @php
                            $value = $rowTranslations ? ($rowTranslations->where('lang_key', $lang->code)->first()->value ?? '') : '';
                        @endphp
                        <td class="px-6 py-5">
                            <textarea name="translations[{{ $lang->code }}][{{ $trans->key }}]" 
                                      class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 transition-all text-sm min-h-[44px] h-[44px] bg-slate-50/50 focus:bg-white resize-y"
                            >{{ $value }}</textarea>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6 bg-slate-50/80 border-t border-slate-100 flex justify-end sticky bottom-0 z-10">
            <button type="submit" class="px-10 py-4 rounded-2xl bg-blue-600 text-white text-sm font-extrabold hover:bg-blue-700 hover:-translate-y-0.5 active:translate-y-0 transition-all shadow-xl shadow-blue-600/20 flex items-center gap-3">
                <i data-lucide="save" class="w-5 h-5 text-blue-200"></i>
                Tümünü Kaydet
            </button>
        </div>
    </div>
</form>
@endsection
