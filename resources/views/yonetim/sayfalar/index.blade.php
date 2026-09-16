@extends('yonetim.layouts.admin')

@section('title', 'Sayfa Yönetimi')
@section('page_title', 'Sayfa Yönetimi')

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

    <div class="bg-white rounded-xl  border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">SAYFA ADI</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">SLUG</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">İŞLEMLER</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($pages->sortBy('name') as $page)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex flex-col gap-1">
                                    <span class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $page->name }}</span>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $page->type }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <a href="{{ url(app()->getLocale() . '/' . $page->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-xs font-bold hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                    /{{ $page->slug }}
                                </a>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <a href="{{ route('yonetim.sayfalar.edit', $page->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:border-blue-500 hover:text-blue-500 transition-all shadow-sm">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    Düzenle
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
