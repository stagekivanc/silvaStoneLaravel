@extends('yonetim.layouts.admin')

@section('title', 'Ürün Yönetimi')
@section('page_title', 'Ürün Yönetimi')

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

    <div class="flex justify-between mb-6">
        <div class="flex items-center gap-2">
            <a href="{{ route('yonetim.urun-kategorileri.index') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
                <i data-lucide="folder" class="w-4 h-4"></i>
                Kategorileri Yönet
            </a>
        </div>
        <a href="{{ route('yonetim.urunler.create') }}" class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Yeni Ürün Ekle
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest w-12">#</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Ürün Bilgisi</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Kategori</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Sıra</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Durum</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-5 text-slate-400 font-mono text-xs">{{ $product->id }}</td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-12 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 flex-shrink-0">
                                        @if($product->image_url)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i data-lucide="package" class="w-6 h-6 text-slate-300"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $product->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $product->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-blue-100">
                                    {{ $product->category->name ?? 'Kategorisiz' }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded">{{ $product->order }}</span>
                            </td>
                            <td class="px-8 py-5">
                                @if($product->status)
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-emerald-100">Aktif</span>
                                @else
                                    <span class="px-3 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-red-100">Pasif</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('yonetim.urunler.edit', $product->id) }}" class="inline-flex items-center justify-center w-9 h-9 bg-white border border-slate-200 text-slate-400 rounded-xl hover:border-blue-500 hover:text-blue-500 hover:bg-blue-50 transition-all shadow-sm">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('yonetim.urunler.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-9 h-9 bg-white border border-slate-200 text-slate-400 rounded-xl hover:border-red-500 hover:text-red-500 hover:bg-red-50 transition-all shadow-sm">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center gap-3">
                                    <i data-lucide="package" class="w-10 h-10 opacity-20"></i>
                                    <p class="text-sm font-medium">Henüz hiç ürün eklenmemiş.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
