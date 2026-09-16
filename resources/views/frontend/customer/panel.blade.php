@extends('frontend.layouts.app')

@section('title', __t('account_title', 'Hesabım', 'frontend') . ' | Silva Stone')
@section('body_class', 'customerPanelPage')

@section('content')
@php
    $lang = app()->getLocale();
    $homeUrl = route('home', ['lang' => $lang]);
@endphp

<main class="w-full bg-brand-gray/30 py-8 md:py-12 px-4 sm:px-6">
    <div class="w-full max-w-6xl mx-auto">
        <nav class="text-[10px] text-gray-400 uppercase tracking-[0.2em] mb-6">
            <a href="{{ $homeUrl }}" class="hover:text-brand-red transition-colors">{{ __t('account_home', 'Ana Sayfa', 'frontend') }}</a>
            <span class="mx-2 opacity-40">/</span>
            <span class="text-gray-600">{{ __t('account_title', 'Hesabım', 'frontend') }}</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-light text-gray-900 tracking-tight">{{ form_t('account_hello', 'Merhaba, :name', ['name' => $user->name]) }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ __t('account_subtitle', 'Hesap bilgilerinizi ve teklif geçmişinizi buradan yönetebilirsiniz.', 'frontend') }}</p>
            </div>
            <form action="{{ route('customer.logout', ['lang' => $lang]) }}" method="POST">
                @csrf
                <button type="submit" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-brand-red transition-colors">{{ __t('header_logout', 'Çıkış Yap', 'frontend') }}</button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <aside class="lg:col-span-3">
                <div class="bg-white border border-gray-100 p-2 space-y-1">
                    <a href="{{ route('customer.panel', ['lang' => $lang, 'tab' => 'ozet']) }}"
                       class="block px-4 py-3 text-xs font-bold uppercase tracking-[0.15em] transition-colors {{ $tab === 'ozet' ? 'bg-brand-dark text-white' : 'text-gray-500 hover:text-brand-red' }}">
                        {{ __t('account_tab_overview', 'Özet', 'frontend') }}
                    </a>
                    <a href="{{ route('customer.panel', ['lang' => $lang, 'tab' => 'teklifler']) }}"
                       class="block px-4 py-3 text-xs font-bold uppercase tracking-[0.15em] transition-colors {{ $tab === 'teklifler' ? 'bg-brand-dark text-white' : 'text-gray-500 hover:text-brand-red' }}">
                        {{ __t('account_tab_quotes', 'Teklif Geçmişi', 'frontend') }}
                    </a>
                    <a href="{{ route('customer.panel', ['lang' => $lang, 'tab' => 'profil']) }}"
                       class="block px-4 py-3 text-xs font-bold uppercase tracking-[0.15em] transition-colors {{ $tab === 'profil' ? 'bg-brand-dark text-white' : 'text-gray-500 hover:text-brand-red' }}">
                        {{ __t('account_tab_profile', 'Profil Bilgileri', 'frontend') }}
                    </a>
                    <a href="{{ route('module.dispatcher', ['lang' => $lang, 'module' => 'urunler']) }}"
                       class="block px-4 py-3 text-xs font-bold uppercase tracking-[0.15em] text-gray-500 hover:text-brand-red transition-colors">
                        {{ __t('account_tab_products', 'Ürünlere Git', 'frontend') }}
                    </a>
                </div>
            </aside>

            <div class="lg:col-span-9 space-y-6">
                @if($tab === 'ozet')
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-white border border-gray-100 p-6">
                            <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-2">{{ __t('account_stat_total', 'Toplam Teklif', 'frontend') }}</div>
                            <div class="text-3xl font-light text-gray-900">{{ $stats['total'] }}</div>
                        </div>
                        <div class="bg-white border border-gray-100 p-6">
                            <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-2">{{ __t('account_stat_pending', 'Bekleyen', 'frontend') }}</div>
                            <div class="text-3xl font-light text-gray-900">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="bg-white border border-gray-100 p-6">
                            <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-2">{{ __t('account_stat_quoted', 'Teklif Verildi', 'frontend') }}</div>
                            <div class="text-3xl font-light text-gray-900">{{ $stats['quoted'] }}</div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-100 p-6 md:p-8">
                        <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-4">{{ __t('account_summary', 'Hesap Özeti', 'frontend') }}</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">{{ __t('account_email', 'E-posta', 'frontend') }}</div>
                                <div class="text-gray-800">{{ $user->email }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">{{ __t('account_phone', 'Telefon', 'frontend') }}</div>
                                <div class="text-gray-800">{{ $user->phone ?: '—' }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">{{ __t('account_company', 'Firma', 'frontend') }}</div>
                                <div class="text-gray-800">{{ $user->company ?: '—' }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">{{ __t('account_member_since', 'Üyelik', 'frontend') }}</div>
                                <div class="text-gray-800">{{ $user->created_at?->format('d.m.Y') }}</div>
                            </div>
                        </div>
                        <p class="mt-6 text-xs text-gray-500 leading-relaxed">
                            {{ __t('account_no_payment_note', 'Online ödeme yoktur. Sepetinize ürün ekleyip Teklif Al ile talebinizi iletebilirsiniz.', 'frontend') }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">{{ __t('account_recent_quotes', 'Son Teklifler', 'frontend') }}</h2>
                            <a href="{{ route('customer.panel', ['lang' => $lang, 'tab' => 'teklifler']) }}" class="text-[10px] font-bold uppercase tracking-widest text-brand-red">{{ __t('account_view_all', 'Tümü', 'frontend') }}</a>
                        </div>
                        @include('frontend.customer.partials.offers-table', ['offers' => $offers->take(5), 'compact' => true])
                    </div>

                @elseif($tab === 'teklifler')
                    <div class="bg-white border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">{{ __t('account_quotes_title', 'Teklif Geçmişi', 'frontend') }}</h2>
                        </div>
                        @include('frontend.customer.partials.offers-table', ['offers' => $offers, 'compact' => false])
                        @if($offers->hasPages())
                            <div class="px-6 py-4 border-t border-gray-100">{{ $offers->links() }}</div>
                        @endif
                    </div>

                @else
                    <div class="bg-white border border-gray-100 p-6 md:p-8">
                        <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-6">{{ __t('account_profile_title', 'Profil Bilgileri', 'frontend') }}</h2>
                        <form action="{{ route('customer.panel.update', ['lang' => $lang]) }}" method="POST" class="space-y-6 max-w-xl">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">{{ __t('account_name', 'Ad Soyad', 'frontend') }}</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full border-0 border-b border-gray-200 px-0 py-3 text-sm outline-none focus:border-brand-red">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">{{ __t('account_email', 'E-posta', 'frontend') }}</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full border-0 border-b border-gray-200 px-0 py-3 text-sm outline-none focus:border-brand-red">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">{{ __t('account_phone', 'Telefon', 'frontend') }}</label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required
                                       class="w-full border-0 border-b border-gray-200 px-0 py-3 text-sm outline-none focus:border-brand-red">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">{{ __t('account_company', 'Firma', 'frontend') }}</label>
                                <input type="text" name="company" value="{{ old('company', $user->company) }}"
                                       class="w-full border-0 border-b border-gray-200 px-0 py-3 text-sm outline-none focus:border-brand-red">
                            </div>
                            <div class="pt-4 border-t border-gray-100">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4">{{ __t('account_password_section', 'Şifre Değiştir (opsiyonel)', 'frontend') }}</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">{{ __t('account_new_password', 'Yeni Şifre', 'frontend') }}</label>
                                        <input type="password" name="password" minlength="8" autocomplete="new-password"
                                               class="w-full border-0 border-b border-gray-200 px-0 py-3 text-sm outline-none focus:border-brand-red">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">{{ __t('account_password_confirm', 'Şifre Tekrar', 'frontend') }}</label>
                                        <input type="password" name="password_confirmation" minlength="8" autocomplete="new-password"
                                               class="w-full border-0 border-b border-gray-200 px-0 py-3 text-sm outline-none focus:border-brand-red">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-brand-dark text-white text-[10px] font-bold tracking-[0.25em] uppercase hover:bg-brand-red transition-colors">
                                {{ __t('account_save', 'Kaydet', 'frontend') }}
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
