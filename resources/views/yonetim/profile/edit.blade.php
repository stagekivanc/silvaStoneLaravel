@extends('yonetim.layouts.admin')

@section('title', 'Profil Bilgilerim')
@section('page_title', 'Profil Düzenle')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('yonetim.profil.update') }}" method="POST" class="space-y-8">
        @csrf
        
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="bg-slate-50 border-b border-slate-100 px-8 py-5 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 flex items-center justify-center">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Kişisel Bilgiler</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Hesap bilgilerinizi buradan güncelleyebilirsiniz</p>
                </div>
            </div>

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                            <i data-lucide="user" class="w-3 h-3 text-slate-300"></i>
                            Ad Soyad
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all font-bold" 
                               placeholder="Adınız ve soyadınız">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                            <i data-lucide="mail" class="w-3 h-3 text-slate-300"></i>
                            E-Posta Adresi
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all font-bold" 
                               placeholder="admin@example.com">
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="bg-slate-50 border-b border-slate-100 px-8 py-5 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Güvenlik Ayarları</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Şifrenizi değiştirmek için aşağıdaki alanları doldurun (Opsiyonel)</p>
                </div>
            </div>

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                            <i data-lucide="key" class="w-3 h-3 text-slate-300"></i>
                            Yeni Şifre
                        </label>
                        <input type="password" name="password"
                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all font-bold" 
                               placeholder="********">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-3 h-3 text-slate-300"></i>
                            Şifre Tekrar
                        </label>
                        <input type="password" name="password_confirmation"
                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all font-bold" 
                               placeholder="********">
                    </div>
                </div>
                
                <div class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-100 rounded-xl">
                    <i data-lucide="info" class="w-5 h-5 text-amber-500 shrink-0"></i>
                    <p class="text-xs text-amber-700 font-medium leading-relaxed">
                        Şifrenizi değiştirmek istemiyorsanız bu alanları boş bırakabilirsiniz. Yeni şifreniz en az 8 karakterden oluşmalıdır.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <button type="submit" class="px-8 py-4 bg-blue-600 text-white font-extrabold rounded-xl shadow-lg shadow-blue-600/20 hover:bg-blue-700 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center gap-3">
                <i data-lucide="save" class="w-5 h-5"></i>
                Bilgileri Güncelle
            </button>
        </div>
    </form>
</div>
@endsection
