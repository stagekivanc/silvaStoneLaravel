@extends('yonetim.layouts.admin')

@section('title', 'Dili Düzenle')
@section('page_title', 'Dili Düzenle: ' . $language->name)

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('yonetim.languages.index') }}" class="flex items-center gap-2 text-slate-400 hover:text-slate-900 transition-colors group">
            <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Listeye Dön</span>
        </a>
    </div>

    <form action="{{ route('yonetim.languages.update', $language->id) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        @method('PUT')
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                            <i data-lucide="type" class="w-3 h-3 text-slate-300"></i>
                            Dil Adı
                        </label>
                        <input type="text" name="name" required
                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all font-bold" 
                               value="{{ old('name', $language->name) }}" placeholder="Örn: İngilizce">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                            <i data-lucide="code" class="w-3 h-3 text-slate-300"></i>
                            Dil Kodu
                        </label>
                        <input type="text" name="code" required
                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all font-mono text-sm" 
                               value="{{ old('code', $language->code) }}" placeholder="Örn: en">
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 space-y-6 sticky top-8">
                <div class="space-y-4">
                    <div class="flex items-center justify-between pt-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest text-[10px]">Durum</label>
                        <select name="status" class="bg-slate-50 border border-slate-200 text-slate-900 py-2 px-4 rounded-xl text-sm font-bold">
                            <option value="1" {{ $language->status ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$language->status ? 'selected' : '' }}>Pasif</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest text-[10px]">Sıralama</label>
                        <input type="number" name="order" value="{{ $language->order }}" class="w-24 bg-slate-50 border border-slate-200 text-slate-900 py-2 px-4 rounded-xl text-sm text-center font-bold">
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-50 mt-4">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest text-[10px]">Varsayılan Dil</label>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_default" value="1" {{ $language->is_default ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-blue-600 text-white font-extrabold rounded-xl shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all flex items-center justify-center gap-3">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        Güncelle
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
