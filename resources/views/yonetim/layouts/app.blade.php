<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Yönetim Paneli - Stage Dijital</title>
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
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Rubik:wght@300..900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Using CDN for preview, but should be handled via Vite in production) -->
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
        
        body {
            font-family: 'Manrope', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .font-display {
            font-family: 'Rubik', sans-serif;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    
    <div id="app">
        @yield('content')
    </div>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
