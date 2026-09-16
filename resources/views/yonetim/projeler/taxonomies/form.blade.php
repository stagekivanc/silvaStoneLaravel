@php
  $isEdit = isset($item) && $item;
  $translations = $isEdit ? $item->translations->keyBy('lang_key') : collect();
@endphp

@extends('yonetim.layouts.admin')

@section('title', $pageTitle)
@section('page_title', $pageTitle)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route($routePrefix . '.index') }}" class="flex items-center gap-2 text-slate-400 hover:text-slate-900 transition-colors group">
            <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Listeye Dön</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-8 p-6 bg-red-50 border border-red-100 rounded-2xl">
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-xs text-red-500 font-medium">• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('yonetim.partials.language-tabs')

    <form action="{{ $storeUrl }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-6">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        @foreach(\App\Models\Language::active() as $lang)
            @php $tr = $translations->get($lang->code); @endphp
            <div data-lang-area="{{ $lang->code }}" class="{{ $lang->is_default ? '' : 'hidden' }} space-y-2">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Ad ({{ strtoupper($lang->code) }})</label>
                <input type="text" name="translations[{{ $lang->code }}][name]"
                       value="{{ old("translations.{$lang->code}.name", $tr->name ?? '') }}"
                       class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all font-bold"
                       placeholder="Örn: Otel" {{ $lang->is_default ? 'required' : '' }}>
            </div>
        @endforeach

        <div class="space-y-2">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Slug (URL / filtre kodu)</label>
            <input type="text" name="slug" value="{{ old('slug', $item->slug ?? '') }}"
                   class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl font-mono text-sm"
                   placeholder="otel">
            <p class="text-[11px] text-slate-400">Boş bırakılırsa addan üretilir. Proje filtrelerinde bu kod kullanılır.</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Sıra</label>
                <input type="number" name="order" value="{{ old('order', $item->order ?? 0) }}" min="0"
                       class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl">
            </div>
            <div class="flex items-end pb-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="status" value="1" class="w-4 h-4 rounded border-slate-300 text-blue-600"
                           @checked(old('status', $item->status ?? true))>
                    <span class="text-sm font-medium text-slate-700">Aktif</span>
                </label>
            </div>
        </div>

        <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20">
            Kaydet
        </button>
    </form>
</div>
@endsection
