<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  @php($layoutPage = $page ?? null)
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

  <title>@hasSection('title')@yield('title')@else{{ data_get($layoutPage, 'seo_title') ?: data_get($layoutPage, 'title') ?: \App\Models\Setting::get('site_name', 'Silva Stone') }}@endif</title>
  @hasSection('meta_description')
    <meta name="description" content="@yield('meta_description')">
  @elseif(data_get($layoutPage, 'seo_description'))
    <meta name="description" content="{{ data_get($layoutPage, 'seo_description') }}">
  @endif
  @hasSection('meta_keywords')
    <meta name="keywords" content="@yield('meta_keywords')">
  @elseif(data_get($layoutPage, 'seo_keywords'))
    <meta name="keywords" content="{{ data_get($layoutPage, 'seo_keywords') }}">
  @endif
  <meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')" />
  <meta name="theme-color" content="#0c0c0c" />
  <meta name="color-scheme" content="light" />
  <meta name="author" content="{{ \App\Models\Setting::get('company_name', 'Acarkon Orman Ürünleri') }}" />

  @hasSection('canonical')
    <link rel="canonical" href="@yield('canonical')">
  @else
    <link rel="canonical" href="{{ seo_canonical_url() }}">
  @endif
  @foreach (seo_hreflang_alternates() as $alternate)
    <link rel="alternate" hreflang="{{ $alternate['hreflang'] }}" href="{{ $alternate['href'] }}">
  @endforeach

  @php($social = seo_social_defaults($layoutPage))
  <link rel="icon" href="{{ site_favicon_url(silva_asset('assets/silvalogo.svg')) }}" type="image/svg+xml" />
  <link rel="apple-touch-icon" href="{{ site_favicon_url(silva_asset('assets/silvalogo.svg')) }}" />

  <meta property="og:type" content="website" />
  <meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}" />
  <meta property="og:site_name" content="{{ \App\Models\Setting::get('site_name', 'Silva Stone') }}" />
  <meta property="og:title" content="@hasSection('title')@yield('title')@else{{ $social['title'] }}@endif" />
  <meta property="og:description" content="@hasSection('meta_description')@yield('meta_description')@else{{ $social['description'] }}@endif" />
  <meta property="og:url" content="@hasSection('canonical')@yield('canonical')@else{{ $social['url'] }}@endif" />
  <meta property="og:image" content="@hasSection('og_image')@yield('og_image')@else{{ $social['image'] }}@endif" />

  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="@hasSection('title')@yield('title')@else{{ $social['title'] }}@endif" />
  <meta name="twitter:description" content="@hasSection('meta_description')@yield('meta_description')@else{{ $social['description'] }}@endif" />
  <meta name="twitter:image" content="@hasSection('og_image')@yield('og_image')@else{{ $social['image'] }}@endif" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            void: '#0c0c0c',
            ink: '#171717',
            soft: '#f7f7f5',
            mist: '#efeeeb',
            stone: '#8a8680',
            line: '#e4e2dd',
            accent: '#c45c3e',
          },
          fontFamily: {
            sans: ['Outfit', 'system-ui', 'sans-serif'],
            display: ['Instrument Serif', 'Georgia', 'serif'],
          },
          maxWidth: {
            site: '1280px',
          },
        },
      },
    };
  </script>
  <link rel="stylesheet" href="{{ silva_asset('css/styles.css') }}" />
  @php($silvaBase = rtrim(silva_asset(), '/'))
  <script>window.SILVA_BASE = @json($silvaBase);</script>
  @stack('head')
</head>
<body class="text-ink font-sans antialiased overflow-x-hidden @yield('body_class', 'bg-white')" @yield('body_attrs')>
  @include('frontend.layouts.header')

  <main id="top">
    @yield('content')
  </main>

  @include('frontend.layouts.footer')

  @php($wa = preg_replace('/\D+/', '', \App\Models\Setting::get('whatsapp', '908503460226')))
  <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="fixed bottom-5 right-5 z-40 flex h-12 w-12 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg transition hover:scale-105" aria-label="WhatsApp">
    <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.485 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
  </a>

  @stack('scripts')
</body>
</html>
