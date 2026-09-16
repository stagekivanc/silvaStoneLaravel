@extends('yonetim.layouts.admin')

@section('title', 'Kullanıcı Düzenle')
@section('page_title', 'Kullanıcı Düzenle')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-8">
        <a href="{{ route('yonetim.kullanicilar.index') }}" class="inline-flex items-center text-slate-500 hover:text-blue-600 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Kullanıcı Listesine Dön
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100">
            <div class="flex items-center mb-2">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mr-2"></i>
                <h3 class="text-sm font-bold text-red-800">Lütfen aşağıdaki hataları düzeltin:</h3>
            </div>
            <ul class="list-disc list-inside text-sm text-red-600 ml-7 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 max-w-3xl">
        <form action="{{ route('yonetim.kullanicilar.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Ad Soyad -->
                <div class="col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Ad Soyad <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                        class="w-full h-12 px-4 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 transition-all font-medium text-slate-900 placeholder-slate-400"
                        placeholder="Örn: Ahmet Yılmaz" required>
                </div>

                <!-- E-Posta -->
                <div class="col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">E-Posta Adresi <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                        class="w-full h-12 px-4 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 transition-all font-medium text-slate-900 placeholder-slate-400"
                        placeholder="admin@example.com" required>
                </div>

                <!-- Şifre -->
                <div class="col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Yeni Şifre (İsteğe Bağlı)</label>
                    <input type="password" name="password" 
                        class="w-full h-12 px-4 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 transition-all font-medium text-slate-900 placeholder-slate-400">
                    <p class="text-xs text-slate-400 mt-1">Şifreyi değiştirmek istemiyorsanız boş bırakın.</p>
                </div>

                <!-- Şifre Tekrarı -->
                <div class="col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Yeni Şifre (Tekrar)</label>
                    <input type="password" name="password_confirmation" 
                        class="w-full h-12 px-4 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 transition-all font-medium text-slate-900 placeholder-slate-400">
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" class="inline-flex items-center px-8 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 transform hover:-translate-y-0.5">
                    <i data-lucide="save" class="w-5 h-5 mr-2"></i>
                    Değişiklikleri Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
