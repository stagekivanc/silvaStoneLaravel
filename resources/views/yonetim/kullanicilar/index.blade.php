@extends('yonetim.layouts.admin')

@section('title', 'Kullanıcılar')
@section('page_title', 'Kullanıcı Yönetimi')

@section('content')
<div class="max-w-9xl mx-auto">
   
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-600 text-sm font-medium flex items-center">
            <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-600 text-sm font-medium flex items-center">
            <i data-lucide="alert-circle" class="w-5 h-5 mr-3"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Yönetici Hesapları</h2>
            <p class="text-slate-500 text-sm mt-1">Sisteme erişimi olan yönetici hesaplarını buradan yönetebilirsiniz.</p>
        </div>
        <a href="{{ route('yonetim.kullanicilar.create') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">
            <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
            Yeni Kullanıcı
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Ad Soyad</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">E-Posta</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Oluşturulma Tarihi</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg mr-3">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="text-sm font-bold text-slate-900">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-500">{{ $user->created_at->format('d.m.Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('yonetim.kullanicilar.edit', $user->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 hover:border-blue-500 hover:text-blue-500 transition-all shadow-sm">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('yonetim.kullanicilar.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 hover:border-red-500 hover:text-red-500 transition-all shadow-sm">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
                                Kayıtlı kullanıcı bulunamadı.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/30">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
