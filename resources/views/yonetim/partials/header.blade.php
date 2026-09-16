<header class="h-16 lg:h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-4 lg:px-8 z-20 shrink-0 sticky top-0">
    <div class="flex items-center gap-3 lg:gap-4">
        <button @click="toggleSidebar()" class="p-2 -ml-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">
            <i data-lucide="menu" class="w-6 h-6 lg:hidden"></i>
            <i data-lucide="panel-left" class="w-5 h-5 hidden lg:block"></i>
        </button>
        <h2 class="text-lg font-bold text-slate-800 hidden lg:block">@yield('page_title', 'Dashboard')</h2>
        <h2 class="text-lg font-bold text-slate-800 lg:hidden truncate max-w-[150px]">@yield('title', 'Dashboard')</h2>
    </div>
    
    <div class="flex items-center gap-4">
        <!-- Siteyi Görüntüle -->
        <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-2 p-3 lg:px-4 lg:py-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-900 hover:text-white transition-all group shadow-sm">
            <i data-lucide="globe" class="w-4 h-4 transition-transform group-hover:rotate-12"></i>
            <span class="text-xs font-bold uppercase tracking-tight hidden lg:block">Siteyi Görüntüle</span>
        </a>

        <!-- Notifications Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" class="relative w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-200 transition-all group">
                <i data-lucide="bell" class="w-5 h-5 group-hover:rotate-12 transition-transform"></i>
                @if($totalUnreadCount > 0)
                    <span class="absolute -top-1 -right-1 flex h-5 w-5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-5 w-5 bg-red-500 text-[10px] font-bold text-white items-center justify-center">
                            {{ $totalUnreadCount }}
                        </span>
                    </span>
                @endif
            </button>

            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translateY-2"
                 x-transition:enter-end="opacity-100 translateY-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translateY-0"
                 x-transition:leave-end="opacity-0 translateY-2"
                 class="absolute right-0 mt-4 w-80 bg-white rounded-2xl border border-slate-200 shadow-xl z-50 overflow-hidden"
                 x-cloak
                 style="display: none;">
                
                <div class="px-5 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Bildirimler</h3>
                    <div class="flex items-center gap-2">
                        @if($totalUnreadCount > 0)
                            <form action="{{ route('yonetim.notifications.markAllRead') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 hover:underline cursor-pointer">
                                    Tümünü Oku
                                </button>
                            </form>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-700 text-[10px] font-bold">{{ $totalUnreadCount }} Yeni</span>
                        @endif
                    </div>
                </div>

                <div class="max-h-[300px] overflow-y-auto custom-scrollbar">
                    @forelse($latestNotifications as $notif)
                        <a href="{{ $notif->notif_route }}" class="flex items-start gap-3 px-5 py-4 hover:bg-slate-50 transition-all border-b border-slate-50 last:border-0 group">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 shadow-sm transition-transform group-hover:scale-110 {{ 
                                $notif->notif_type == 'message' ? 'bg-amber-50 text-amber-600' : (
                                $notif->notif_type == 'analysis' ? 'bg-blue-50 text-blue-600' : (
                                $notif->notif_type == 'offer' ? 'bg-emerald-50 text-emerald-600' : 'bg-purple-50 text-purple-600'
                                ) ) }}">
                                <i data-lucide="{{ $notif->notif_icon }}" class="w-4 h-4"></i>
                            </div>
                            <div class="flex flex-col gap-0.5 min-w-0">
                                <span class="text-xs font-bold text-slate-800 truncate">{{ $notif->notif_label }}</span>
                                <span class="text-[10px] font-medium text-slate-400">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="px-5 py-8 text-center text-slate-400 text-xs">
                            <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3 mx-auto">
                                <i data-lucide="bell-off" class="w-6 h-6 text-slate-300"></i>
                            </div>
                            Henüz yeni bildirim yok.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="h-8 w-[1px] bg-slate-200 mx-1"></div>
        
        
        <!-- User Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 p-1.5 pr-3 rounded-xl hover:bg-slate-100 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-md group-hover:scale-105 transition-transform">
                    <span class="font-bold text-sm">{{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 2)) }}</span>
                </div>
                <div class="flex flex-col items-start hidden sm:flex">
                    <span class="text-xs font-bold text-slate-900 leading-none">{{ Auth::guard('admin')->user()->name }}</span>
                    <span class="text-[10px] font-medium text-slate-400 mt-1 uppercase tracking-tight">Yönetici</span>
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 bg-white rounded-2xl border border-slate-200 shadow-xl py-2 z-50"
                 x-cloak>
                
                <div class="px-4 py-3 border-b border-slate-50 mb-1">
                    <p class="text-xs font-bold text-slate-900">{{ Auth::guard('admin')->user()->name }}</p>
                    <p class="text-[10px] font-medium text-slate-400 truncate">{{ Auth::guard('admin')->user()->email }}</p>
                </div>

                <a href="{{ route('yonetim.profil') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all font-medium">
                    <i data-lucide="user-cog" class="w-4 h-4"></i>
                    Profil Düzenle
                </a>

                <form action="{{ route('yonetim.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-all font-medium">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Oturumu Kapat
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

