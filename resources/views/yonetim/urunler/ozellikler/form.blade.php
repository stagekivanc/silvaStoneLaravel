@php
  $isEdit = isset($item) && $item;
  $translations = $isEdit ? $item->translations->keyBy('lang_key') : collect();
@endphp

@extends('yonetim.layouts.admin')

@section('title', $isEdit ? 'Özellik Düzenle' : 'Yeni Özellik')
@section('page_title', $isEdit ? 'Özellik Düzenle' : 'Yeni Özellik')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('yonetim.urun-ozellikleri.index') }}" class="flex items-center gap-2 text-slate-400 hover:text-slate-900 text-xs font-bold uppercase tracking-widest">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Listeye Dön
        </a>
    </div>

    @include('yonetim.partials.language-tabs')

    <form action="{{ $isEdit ? route('yonetim.urun-ozellikleri.update', $item->id) : route('yonetim.urun-ozellikleri.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-6">
        @csrf
        @if($isEdit) @method('PUT') @endif

        @foreach(\App\Models\Language::active() as $lang)
            @php $tr = $translations->get($lang->code); @endphp
            <div data-lang-area="{{ $lang->code }}" class="{{ $lang->is_default ? '' : 'hidden' }} space-y-2">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Görünen ad ({{ strtoupper($lang->code) }})</label>
                <input type="text" name="translations[{{ $lang->code }}][name]" value="{{ old("translations.{$lang->code}.name", $tr->name ?? '') }}"
                       class="w-full bg-slate-50 border border-slate-200 py-3.5 px-5 rounded-xl font-bold" {{ $lang->is_default ? 'required' : '' }}
                       placeholder="Örn: İç mekana uygun">
            </div>
        @endforeach

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Filtre tipi</label>
                <select name="filter_key" required class="w-full bg-slate-50 border border-slate-200 py-3.5 px-5 rounded-xl font-bold">
                    @foreach(\App\Models\ProductFeature::FILTER_KEYS as $key => $label)
                        <option value="{{ $key }}" @selected(old('filter_key', $item->filter_key ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Filtre değeri</label>
                <input type="text" name="filter_value" value="{{ old('filter_value', $item->filter_value ?? '') }}" required
                       class="w-full bg-slate-50 border border-slate-200 py-3.5 px-5 rounded-xl font-mono text-sm"
                       placeholder="600x1200 / 3-4 / 1">
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $item->slug ?? '') }}" class="w-full bg-slate-50 border border-slate-200 py-3.5 px-5 rounded-xl font-mono text-sm" placeholder="indoor">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Sıra</label>
                <input type="number" name="order" value="{{ old('order', $item->order ?? 0) }}" min="0" class="w-full bg-slate-50 border border-slate-200 py-3.5 px-5 rounded-xl">
            </div>
            <div class="flex items-end pb-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="status" value="1" class="w-4 h-4" @checked(old('status', $item->status ?? true))>
                    <span class="text-sm font-medium text-slate-700">Aktif</span>
                </label>
            </div>
        </div>

        <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700">Kaydet</button>
    </form>
</div>
@endsection
