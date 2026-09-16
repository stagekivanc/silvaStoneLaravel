@extends('yonetim.layouts.app')

@section('content')
<style>
    .btn-premium {
        background: linear-gradient(135deg, #000000ff 0%, #5b5b5bff 100%);
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px -5px rgba(59, 130, 246, 0.5);
    }

    .input-premium:focus {
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 1);
        box-shadow: 
            0 40px 100px -20px rgba(0, 0, 0, 0.08),
            0 20px 40px -10px rgba(0, 0, 0, 0.04);
    }

    .floating-shape {
        position: absolute;
        z-index: 1;
        filter: blur(100px);
        opacity: 0.4;
    }
</style>

<section class="min-h-screen flex items-center justify-center p-6 bg-slate-50 relative overflow-hidden font-sans">
    <!-- Decorative Elements -->
    <div class="floating-shape top-[-10%] left-[-10%] w-[400px] h-[400px] bg-blue-400 rounded-full"></div>
    <div class="floating-shape bottom-[-10%] right-[-10%] w-[400px] h-[400px] bg-emerald-300 rounded-full"></div>
    
    <div class="absolute inset-0 z-0 opacity-[0.03] pointer-events-none" 
         style="background-image: url('{{ asset("assets/img/grid.png") }}'); background-repeat: repeat;"></div>

    <div class="w-full max-w-[460px] glass-card rounded-[40px] p-10 md:p-14 relative z-10 animate-[fadeIn_0.6s_ease-out]">
        <div class="flex flex-col items-center mb-10">
            <div class="mb-8">
                <img src="{{ asset('assets/stage_black_logo.svg') }}" alt="Stage Dijital" 
                     class="h-12 w-auto drop-shadow-sm">
            </div>
            
            <div class="text-center">
                <h1 class="font-display font-medium text-xl text-slate-900 tracking-tight mb-3">
                   Sistem Yönetim Paneli v4.1
                </h1>
                <p class="text-slate-500 text-base leading-relaxed">
                    Güvenli portal için bilgilerinizi kullanın.
                </p>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-bold">Giriş başarısız</p>
                        <p class="text-xs text-red-600 mt-1">Lütfen bilgilerinizi kontrol edip tekrar deneyin.</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('yonetim.login.submit') }}" method="POST" class="space-y-6">
            @csrf
            <!-- Security Honeypot -->
            <div style="display:none">
                <input type="text" name="hp_username" autocomplete="off">
            </div>
            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">E-Posta</label>
                <div class="relative">
                    <input type="email" 
                           name="email"
                           value="{{ old('email') }}"
                           class="w-full bg-slate-100/50 border border-slate-200 text-slate-900 py-4 px-6 rounded-2xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all duration-300 input-premium" 
                           placeholder="admin@lmc.com"
                           required 
                           autofocus>
                </div>
                @error('email')
                    <p class="text-xs text-red-500 ml-1 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="space-y-2">
                <div class="flex justify-between items-center ml-1">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Şifre</label>
                   
                </div>
                <div class="relative">
                    <input type="password" 
                           name="password"
                           class="w-full bg-slate-100/50 border border-slate-200 text-slate-900 py-4 px-6 rounded-2xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all duration-300 input-premium" 
                           placeholder="••••••••" 
                           required>
                </div>
                @error('password')
                    <p class="text-xs text-red-500 ml-1 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center space-x-3 ml-1 mb-2">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <label for="remember" class="text-sm text-slate-500 font-medium cursor-pointer">Beni hatırla</label>
            </div>

            <button type="submit" 
                    class="w-full btn-premium py-5 px-8 rounded-2xl text-white font-extrabold text-base tracking-wide uppercase flex items-center justify-center gap-3">
                Sisteme Giriş Yap
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </form>


    </div>
</section>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

