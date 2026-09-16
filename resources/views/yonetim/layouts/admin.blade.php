<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Yönetim Paneli') - Stage Dijital</title>
    <meta name="description" content="Stage Dijital Özel Yönetim Paneli ile Web Sitenizi Düzenleyebilir ve Güncelleşitirebilirsiniz.">
    <meta name="keywords" content="Yönetim Paneli, Stage Dijital">
    <meta name="author" content="Stage Dijital">
    <link rel="icon" href="{{ asset('assets/img/favicon-stage.png') }}">
    <meta property="og:title" content="Yönetim Paneli - Stage Dijital">
    <meta property="og:description" content="Stage Dijital Özel Yönetim Paneli ile Web Sitenizi Düzenleyebilir ve Güncelleşitirebilirsiniz.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://stagedijital.com">
    <meta property="og:image" content="{{ asset('assets/img/stage-logo.png') }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Rubik:wght@300..900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#10b981',
                        dark: '#0f172a',
                    },
                    fontFamily: {
                        sans: ['Manrope', 'sans-serif'],
                        display: ['Rubik', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Manrope', sans-serif; -webkit-font-smoothing: antialiased; }
        .font-display { font-family: 'Rubik', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 antialiased" x-data="{ 
    sidebarOpen: window.innerWidth >= 1024 ? (localStorage.getItem('sidebarOpen') === null ? true : localStorage.getItem('sidebarOpen') === 'true') : false,
    toggleSidebar() { 
        this.sidebarOpen = !this.sidebarOpen; 
        if (window.innerWidth >= 1024) {
            localStorage.setItem('sidebarOpen', this.sidebarOpen);
        }
    } 
}" x-init="$watch('sidebarOpen', val => { if(window.innerWidth >= 1024) localStorage.setItem('sidebarOpen', val) })">
    
    <!-- Mobile Backdrop -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-30 lg:hidden"
         x-cloak>
    </div>

    <div class="flex h-screen bg-slate-50 overflow-hidden">
        <!-- Sidebar -->
        @include('yonetim.partials.sidebar')

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header -->
            @include('yonetim.partials.header')

            <!-- Page Content -->
            <div class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
                @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl text-sm font-bold flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl text-sm font-bold flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    {{ session('error') }}
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
