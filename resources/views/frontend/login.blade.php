@extends('frontend.layouts.app')

@section('title', data_get($page, 'seo_title') ?: 'Giriş Yap | Silva Stone')
@section('body_class', 'loginPage')

@section('content')
@php
    $lang = app()->getLocale();
    $homeUrl = route('home', ['lang' => $lang]);
    $privacyUrl = route('module.dispatcher', ['lang' => $lang, 'module' => 'gizlilik-politikasi']);
    $aktifTab = (request()->query('tab') === 'kayit' || old('ad') || old('soyad') || old('telefon'))
        ? 'kayit'
        : 'giris';
    $hero = $content['hero'] ?? [];
    $labels = $content['labels'] ?? [];
@endphp

<main class="w-full bg-brand-gray/30 py-8 md:py-10 px-4 sm:px-6">
    <div class="w-full max-w-lg mx-auto">
        <div class="text-center mb-6">
            <nav class="text-[10px] text-gray-400 uppercase tracking-[0.2em] mb-4">
                <a href="{{ $homeUrl }}" class="hover:text-brand-red transition-colors">Ana Sayfa</a>
                <span class="mx-2 opacity-40">/</span>
                <span class="text-gray-600">{{ $page->title ?? 'Hesabım' }}</span>
            </nav>
            <h1 id="authHeading" class="text-2xl md:text-3xl font-light text-gray-900 tracking-tight mb-2">
                {{ $aktifTab === 'kayit' ? ($hero['register_title'] ?? 'Hesap Oluşturun') : ($hero['login_title'] ?? 'Tekrar Hoş Geldiniz') }}
            </h1>
            <p id="authSubheading" class="text-sm text-gray-500 font-light">
                {{ $aktifTab === 'kayit' ? ($hero['register_subtitle'] ?? '') : ($hero['login_subtitle'] ?? '') }}
            </p>
        </div>

        <div class="bg-white border border-gray-100 p-6 sm:p-8 md:p-10 shadow-[0_20px_60px_-20px_rgba(0,0,0,0.08)]">
            @guest('web')
            <div class="flex w-full p-1 bg-brand-gray border border-gray-100 mb-8">
                <button type="button" id="tabGiris" class="auth-tab flex-1 py-3 text-[10px] font-bold tracking-[0.2em] uppercase transition-all duration-300 {{ $aktifTab === 'giris' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-400 hover:text-gray-700' }}">
                    {{ $labels['login_tab'] ?? 'Giriş Yap' }}
                </button>
                <button type="button" id="tabKayit" class="auth-tab flex-1 py-3 text-[10px] font-bold tracking-[0.2em] uppercase transition-all duration-300 {{ $aktifTab === 'kayit' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-400 hover:text-gray-700' }}">
                    {{ $labels['register_tab'] ?? 'Kayıt Ol' }}
                </button>
            </div>
            @endguest

            @if(session('success'))
                <div class="mb-6 p-3 bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-6 p-3 bg-red-50 border border-red-100 text-red-600 text-xs space-y-1">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @auth('web')
            <div class="text-center space-y-4 py-4">
                <p class="text-sm text-gray-700">Merhaba, <strong>{{ auth('web')->user()->name }}</strong></p>
                <p class="text-xs text-gray-500">Hesap panelinizden teklif geçmişinizi ve bilgilerinizi yönetebilirsiniz.</p>
                <a href="{{ route('customer.panel', ['lang' => $lang]) }}" class="block w-full py-4 bg-brand-dark text-white text-[10px] font-bold tracking-[0.25em] uppercase hover:bg-brand-red transition-colors">
                    Hesabıma Git
                </a>
                <form action="{{ route('customer.logout', ['lang' => $lang]) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 text-[10px] font-bold tracking-[0.2em] uppercase text-gray-400 hover:text-brand-red transition-colors">Çıkış Yap</button>
                </form>
            </div>
            @else
            <form id="formGiris" action="{{ route('customer.login', ['lang' => $lang]) }}" method="POST" class="space-y-7 {{ $aktifTab === 'kayit' ? 'hidden' : '' }}">
                @csrf
                <div class="group">
                    <label for="loginEmail" class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3 group-focus-within:text-brand-red transition-colors">{{ $labels['email'] ?? 'E-posta Adresi' }}</label>
                    <input type="email" id="loginEmail" name="email" value="{{ old('email') }}" required placeholder="ornek@sirket.com" autocomplete="email" class="auth-input w-full bg-transparent border-0 border-b border-gray-200 px-3 py-3 text-sm text-gray-900 placeholder-gray-300 outline-none focus:border-brand-red transition-colors">
                </div>
                <div class="group">
                    <label for="loginPassword" class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3 group-focus-within:text-brand-red transition-colors">{{ $labels['password'] ?? 'Şifre' }}</label>
                    <div class="relative">
                        <input type="password" id="loginPassword" name="password" required placeholder="••••••••" autocomplete="current-password" class="auth-input w-full bg-transparent border-0 border-b border-gray-200 px-3 py-3 pr-11 text-sm text-gray-900 placeholder-gray-300 outline-none focus:border-brand-red transition-colors">
                        <button type="button" class="toggle-password absolute right-2 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-900 transition-colors" data-target="loginPassword" aria-label="Şifreyi göster">
                            <i class="bx bx-show text-xl"></i>
                        </button>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-1">
                    <label class="flex items-center gap-2.5 cursor-pointer group/check">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 accent-brand-red">
                        <span class="text-xs text-gray-500 group-hover/check:text-gray-700 transition-colors">{{ $labels['remember'] ?? 'Beni hatırla' }}</span>
                    </label>
                    <a href="#" class="text-[10px] text-gray-400 hover:text-brand-red transition-colors uppercase tracking-[0.15em] font-bold">{{ $labels['forgot'] ?? 'Şifremi Unuttum' }}</a>
                </div>
                <button type="submit" class="w-full mt-2 py-4 bg-brand-dark text-white text-[10px] font-bold tracking-[0.25em] uppercase hover:bg-brand-red transition-colors duration-300">
                    {{ $labels['login_submit'] ?? 'Giriş Yap' }}
                </button>
            </form>

            <form id="formKayit" action="{{ route('customer.register', ['lang' => $lang]) }}" method="POST" class="space-y-6 {{ $aktifTab === 'giris' ? 'hidden' : '' }}">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="group">
                        <label for="regAd" class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3 group-focus-within:text-brand-red transition-colors">{{ $labels['first_name'] ?? 'Ad' }}</label>
                        <input type="text" id="regAd" name="ad" value="{{ old('ad') }}" required placeholder="Adınız" autocomplete="given-name" class="auth-input w-full bg-transparent border-0 border-b border-gray-200 px-3 py-3 text-sm text-gray-900 placeholder-gray-300 outline-none focus:border-brand-red transition-colors">
                    </div>
                    <div class="group">
                        <label for="regSoyad" class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3 group-focus-within:text-brand-red transition-colors">{{ $labels['last_name'] ?? 'Soyad' }}</label>
                        <input type="text" id="regSoyad" name="soyad" value="{{ old('soyad') }}" required placeholder="Soyadınız" autocomplete="family-name" class="auth-input w-full bg-transparent border-0 border-b border-gray-200 px-3 py-3 text-sm text-gray-900 placeholder-gray-300 outline-none focus:border-brand-red transition-colors">
                    </div>
                </div>
                <div class="group">
                    <label for="regEmail" class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3 group-focus-within:text-brand-red transition-colors">{{ $labels['email'] ?? 'E-posta' }}</label>
                    <input type="email" id="regEmail" name="email" value="{{ old('email') }}" required placeholder="ornek@sirket.com" autocomplete="email" class="auth-input w-full bg-transparent border-0 border-b border-gray-200 px-3 py-3 text-sm text-gray-900 placeholder-gray-300 outline-none focus:border-brand-red transition-colors">
                </div>
                <div class="group">
                    <label for="regTelefon" class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3 group-focus-within:text-brand-red transition-colors">{{ $labels['phone'] ?? 'Telefon' }}</label>
                    <input type="tel" id="regTelefon" name="telefon" value="{{ old('telefon') }}" required placeholder="0(5XX) XXX XX XX" autocomplete="tel" class="auth-input w-full bg-transparent border-0 border-b border-gray-200 px-3 py-3 text-sm text-gray-900 placeholder-gray-300 outline-none focus:border-brand-red transition-colors">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="group">
                        <label for="regPassword" class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3 group-focus-within:text-brand-red transition-colors">{{ $labels['password'] ?? 'Şifre' }}</label>
                        <div class="relative">
                            <input type="password" id="regPassword" name="password" required placeholder="Min. 8 karakter" autocomplete="new-password" minlength="8" class="auth-input w-full bg-transparent border-0 border-b border-gray-200 px-3 py-3 pr-11 text-sm text-gray-900 placeholder-gray-300 outline-none focus:border-brand-red transition-colors">
                            <button type="button" class="toggle-password absolute right-2 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-900 transition-colors" data-target="regPassword" aria-label="Şifreyi göster">
                                <i class="bx bx-show text-xl"></i>
                            </button>
                        </div>
                    </div>
                    <div class="group">
                        <label for="regPasswordConfirm" class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3 group-focus-within:text-brand-red transition-colors">{{ $labels['password_confirm'] ?? 'Tekrar' }}</label>
                        <div class="relative">
                            <input type="password" id="regPasswordConfirm" name="password_confirmation" required placeholder="Tekrar girin" autocomplete="new-password" minlength="8" class="auth-input w-full bg-transparent border-0 border-b border-gray-200 px-3 py-3 pr-11 text-sm text-gray-900 placeholder-gray-300 outline-none focus:border-brand-red transition-colors">
                            <button type="button" class="toggle-password absolute right-2 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-900 transition-colors" data-target="regPasswordConfirm" aria-label="Şifreyi göster">
                                <i class="bx bx-show text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <p class="text-[11px] text-gray-400 leading-relaxed">
                    {{ $labels['privacy_note'] ?? 'Kayıt olarak gizlilik politikasını kabul etmiş olursunuz.' }}
                    <a href="{{ $privacyUrl }}" class="text-brand-red hover:underline">Gizlilik Politikası</a>
                </p>
                <button type="submit" class="w-full mt-2 py-4 bg-brand-dark text-white text-[10px] font-bold tracking-[0.25em] uppercase hover:bg-brand-red transition-colors duration-300">
                    {{ $labels['register_submit'] ?? 'Hesap Oluştur' }}
                </button>
            </form>
            @endauth
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var tabGiris = document.getElementById('tabGiris');
    var tabKayit = document.getElementById('tabKayit');
    var formGiris = document.getElementById('formGiris');
    var formKayit = document.getElementById('formKayit');
    var heading = document.getElementById('authHeading');
    var subheading = document.getElementById('authSubheading');
    var loginTitle = @json($hero['login_title'] ?? 'Tekrar Hoş Geldiniz');
    var loginSubtitle = @json($hero['login_subtitle'] ?? '');
    var registerTitle = @json($hero['register_title'] ?? 'Hesap Oluşturun');
    var registerSubtitle = @json($hero['register_subtitle'] ?? '');

    function setTab(mode) {
        var isRegister = mode === 'kayit';
        formGiris.classList.toggle('hidden', isRegister);
        formKayit.classList.toggle('hidden', !isRegister);
        tabGiris.classList.toggle('bg-white', !isRegister);
        tabGiris.classList.toggle('text-gray-900', !isRegister);
        tabGiris.classList.toggle('shadow-sm', !isRegister);
        tabGiris.classList.toggle('text-gray-400', isRegister);
        tabKayit.classList.toggle('bg-white', isRegister);
        tabKayit.classList.toggle('text-gray-900', isRegister);
        tabKayit.classList.toggle('shadow-sm', isRegister);
        tabKayit.classList.toggle('text-gray-400', !isRegister);
        heading.textContent = isRegister ? registerTitle : loginTitle;
        subheading.textContent = isRegister ? registerSubtitle : loginSubtitle;
        var url = new URL(window.location.href);
        if (isRegister) url.searchParams.set('tab', 'kayit'); else url.searchParams.delete('tab');
        window.history.replaceState({}, '', url);
    }

    tabGiris?.addEventListener('click', function () { setTab('giris'); });
    tabKayit?.addEventListener('click', function () { setTab('kayit'); });

    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.getAttribute('data-target'));
            if (!input) return;
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.querySelector('i')?.classList.toggle('bx-show', !show);
            btn.querySelector('i')?.classList.toggle('bx-hide', show);
        });
    });
});
</script>
@endsection
