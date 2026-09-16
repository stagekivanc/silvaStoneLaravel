<aside class="fixed inset-y-0 left-0 lg:static bg-white border-r border-slate-200 flex flex-col z-50 transition-all duration-300 h-full -translate-x-full lg:translate-x-0" 
       :class="sidebarOpen ? 'translate-x-0 w-72' : '-translate-x-full w-72 lg:translate-x-0 lg:w-20'">
    <!-- Logo Area -->
    <div class="h-16 lg:h-20 flex items-center border-b border-slate-100 shrink-0 transition-all duration-300 relative"
         :class="sidebarOpen ? 'px-6 lg:px-8' : 'px-6 lg:px-0 lg:justify-center'">
        
        <button @click="sidebarOpen = false" class="absolute right-4 lg:hidden text-slate-400 hover:text-red-500 transition-colors" x-show="sidebarOpen">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <div class="flex items-center gap-3">
            <template x-if="sidebarOpen">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/stage_black_logo.svg') }}" alt="Stage Dijital" class="h-8 w-auto">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1">Version</span>
                        <span class="text-xs font-extrabold text-blue-600 leading-none">v4.1</span>
                    </div>
                </div>
            </template>
            <template x-if="!sidebarOpen">
                <img src="{{ asset('assets/img/favicon-stage.png') }}" alt="Stage" class="h-10 w-auto">
            </template>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto p-4 space-y-1 custom-scrollbar">
        <a href="{{ route('yonetim.dashboard') }}" title="Dashboard"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap"
           :class="[
               sidebarOpen ? '' : 'lg:justify-center',
               {{ request()->routeIs('yonetim.dashboard') ? 'true' : 'false' }} ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium'
           ]">
            <i data-lucide="layout-grid" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm" x-show="sidebarOpen">Dashboard</span>
        </a>

        <!-- Başvurular -->
        <div x-data="{ open: {{ (request()->routeIs('yonetim.mesajlar.*') || request()->routeIs('yonetim.teklifler.*')) ? 'true' : 'false' }} }">
            <button @click="open = !open" 
               class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium"
               :class="sidebarOpen ? '' : 'lg:justify-center'">
                <div class="relative flex items-center gap-3">
                    <i data-lucide="layers" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="text-sm" x-show="sidebarOpen">Başvurular</span>
                    @if($totalUnreadCount > 0)
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white" x-show="!sidebarOpen && window.innerWidth >= 1024"></span>
                        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full" x-show="sidebarOpen">{{ $totalUnreadCount }}</span>
                    @endif
                </div>
                <i data-lucide="chevron-down" x-show="sidebarOpen" class="w-4 h-4 ml-auto transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="open && sidebarOpen" x-collapse x-cloak class="mt-1 space-y-1 ml-4 border-l border-slate-100 pl-2">
                <a href="{{ route('yonetim.mesajlar.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all whitespace-nowrap {{ request()->routeIs('yonetim.mesajlar.*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 text-sm' }}">
                     <i data-lucide="mail" class="w-4 h-4 flex-shrink-0"></i>
                     <span>İletişim Mesajları</span>
                     @if($unreadMessagesCount > 0)
                         <span class="ml-auto bg-blue-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $unreadMessagesCount }}</span>
                     @endif
                </a>

                <a href="{{ route('yonetim.teklifler.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all whitespace-nowrap {{ request()->routeIs('yonetim.teklifler.*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 text-sm' }}">
                    <i data-lucide="file-text" class="w-4 h-4 flex-shrink-0"></i>
                    <span>Teklif Talepleri</span>
                    @if($unreadOffersCount > 0)
                        <span class="ml-auto bg-blue-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $unreadOffersCount }}</span>
                    @endif
                </a>
            </div>
        </div>

        <a href="{{ route('yonetim.uyeler.index') }}" title="Üyeler"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap"
           :class="[
               sidebarOpen ? '' : 'lg:justify-center',
               {{ request()->routeIs('yonetim.uyeler.*') ? 'true' : 'false' }} ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium'
           ]">
            <i data-lucide="user-round" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm" x-show="sidebarOpen">Üyeler</span>
        </a>

        <a href="{{ route('yonetim.sayfalar.index') }}" title="Sayfalar"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap"
           :class="[
               sidebarOpen ? '' : 'lg:justify-center',
               {{ request()->routeIs('yonetim.sayfalar.*') ? 'true' : 'false' }} ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium'
           ]">
            <i data-lucide="layout" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm" x-show="sidebarOpen">Sayfalar</span>
        </a>

        <div class="px-3 pt-6 pb-2 transition-opacity duration-200" x-show="sidebarOpen">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ürün Yönetimi</span>
        </div>
        <div class="px-3 pt-6 pb-2 flex justify-center" x-show="!sidebarOpen">
            <div class="h-[1px] w-8 bg-slate-200"></div>
        </div>

        <a href="{{ route('yonetim.urunler.index') }}" title="Ürünler"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap"
           :class="[
               sidebarOpen ? '' : 'lg:justify-center',
               {{ request()->routeIs('yonetim.urunler.*') ? 'true' : 'false' }} ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium'
           ]">
            <i data-lucide="shirt" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm" x-show="sidebarOpen">Ürünler</span>
        </a>

        <a href="{{ route('yonetim.urun-kategorileri.index') }}" title="Ürün Kategorileri"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap"
           :class="[
               sidebarOpen ? '' : 'lg:justify-center',
               {{ request()->routeIs('yonetim.urun-kategorileri.*') ? 'true' : 'false' }} ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium'
           ]">
            <i data-lucide="folder" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm" x-show="sidebarOpen">Kategoriler</span>
        </a>

        <a href="{{ route('yonetim.urun-ozellikleri.index') }}" title="Özellikler"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap"
           :class="[
               sidebarOpen ? '' : 'lg:justify-center',
               {{ request()->routeIs('yonetim.urun-ozellikleri.*') ? 'true' : 'false' }} ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium'
           ]">
            <i data-lucide="list-checks" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm" x-show="sidebarOpen">Özellikler</span>
        </a>

        <a href="{{ route('yonetim.urun-renkleri.index') }}" title="Renkler"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap"
           :class="[
               sidebarOpen ? '' : 'lg:justify-center',
               {{ request()->routeIs('yonetim.urun-renkleri.*') ? 'true' : 'false' }} ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium'
           ]">
            <i data-lucide="palette" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm" x-show="sidebarOpen">Renkler</span>
        </a>

        <a href="{{ route('yonetim.urun-rozetleri.index') }}" title="Rozetler"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap"
           :class="[
               sidebarOpen ? '' : 'lg:justify-center',
               {{ request()->routeIs('yonetim.urun-rozetleri.*') ? 'true' : 'false' }} ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium'
           ]">
            <i data-lucide="award" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm" x-show="sidebarOpen">Rozetler</span>
        </a>

        <div class="px-3 pt-6 pb-2 transition-opacity duration-200" x-show="sidebarOpen">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Proje Yönetimi</span>
        </div>
        <div class="px-3 pt-6 pb-2 flex justify-center" x-show="!sidebarOpen">
            <div class="h-[1px] w-8 bg-slate-200"></div>
        </div>

        <a href="{{ route('yonetim.projeler.index') }}" title="Projeler"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap"
           :class="[
               sidebarOpen ? '' : 'lg:justify-center',
               {{ request()->routeIs('yonetim.projeler.*') ? 'true' : 'false' }} ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium'
           ]">
            <i data-lucide="building-2" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm" x-show="sidebarOpen">Projeler</span>
        </a>

        <a href="{{ route('yonetim.proje-tipleri.index') }}" title="Proje Tipleri"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap"
           :class="[
               sidebarOpen ? '' : 'lg:justify-center',
               {{ request()->routeIs('yonetim.proje-tipleri.*') ? 'true' : 'false' }} ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium'
           ]">
            <i data-lucide="tags" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm" x-show="sidebarOpen">Proje Tipleri</span>
        </a>

        <a href="{{ route('yonetim.proje-mekanlari.index') }}" title="Mekânlar"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap"
           :class="[
               sidebarOpen ? '' : 'lg:justify-center',
               {{ request()->routeIs('yonetim.proje-mekanlari.*') ? 'true' : 'false' }} ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium'
           ]">
            <i data-lucide="map-pin" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm" x-show="sidebarOpen">Mekânlar</span>
        </a>

        <a href="{{ route('yonetim.proje-sehirleri.index') }}" title="Şehirler"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap"
           :class="[
               sidebarOpen ? '' : 'lg:justify-center',
               {{ request()->routeIs('yonetim.proje-sehirleri.*') ? 'true' : 'false' }} ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium'
           ]">
            <i data-lucide="building" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm" x-show="sidebarOpen">Şehirler</span>
        </a>

        <div class="px-3 pt-6 pb-2 transition-opacity duration-200" x-show="sidebarOpen">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Dil & Çeviri</span>
        </div>
        <div class="px-3 pt-6 pb-2 flex justify-center" x-show="!sidebarOpen">
            <div class="h-[1px] w-8 bg-slate-200"></div>
        </div>

        <a href="{{ route('yonetim.languages.index') }}" title="Dil Yönetimi"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap"
           :class="[
               sidebarOpen ? '' : 'lg:justify-center',
               {{ request()->routeIs('yonetim.languages.*') ? 'true' : 'false' }} ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium'
           ]">
            <i data-lucide="languages" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm" x-show="sidebarOpen">Dil Yönetimi</span>
        </a>

        <a href="{{ route('yonetim.translations.index') }}" title="Sabit Çeviriler"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap"
           :class="[
               sidebarOpen ? '' : 'lg:justify-center',
               {{ request()->routeIs('yonetim.translations.*') ? 'true' : 'false' }} ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium'
           ]">
            <i data-lucide="globe" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm" x-show="sidebarOpen">Sabit Çeviriler</span>
        </a>

        <div class="px-3 pt-6 pb-2 transition-opacity duration-200" x-show="sidebarOpen">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sistem</span>
        </div>
        <div class="px-3 pt-6 pb-2 flex justify-center" x-show="!sidebarOpen">
            <div class="h-[1px] w-8 bg-slate-200"></div>
        </div>

        <a href="{{ route('yonetim.ayarlar') }}" title="Genel Ayarlar"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap"
           :class="[
               sidebarOpen ? '' : 'lg:justify-center',
               {{ request()->routeIs('yonetim.ayarlar') ? 'true' : 'false' }} ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium'
           ]">
            <i data-lucide="settings" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm" x-show="sidebarOpen">Genel Ayarlar</span>
        </a>

        <a href="{{ route('yonetim.kullanicilar.index') }}" title="Kullanıcılar"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group whitespace-nowrap"
           :class="[
               sidebarOpen ? '' : 'lg:justify-center',
               {{ request()->routeIs('yonetim.kullanicilar.*') ? 'true' : 'false' }} ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium'
           ]">
            <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm" x-show="sidebarOpen">Kullanıcılar</span>
        </a>
    </nav>

    <div class="p-4 border-t border-slate-200 shrink-0">
        <a href="https://wa.me/905397885555?text=T%C3%BCrksan%20Web%20Sitesinde%20Yard%C4%B1ma%20%C4%B0htiyac%C4%B1m%20var"
           target="_blank"
           title="Stage Dijital Destek"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-green-600 bg-green-50 hover:bg-green-100 transition-all group whitespace-nowrap"
           :class="sidebarOpen ? '' : 'lg:justify-center'">
            <i data-lucide="life-buoy" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm font-bold" x-show="sidebarOpen">Stage Dijital Destek</span>
        </a>
    </div>
</aside>
