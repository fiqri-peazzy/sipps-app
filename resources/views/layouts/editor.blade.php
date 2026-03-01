<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Design Workspace - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/LineIcons.2.0.css') }}" />
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-900 overflow-hidden h-screen flex flex-col">
    <!-- Workspace Header -->
    <header class="bg-slate-900 px-6 py-4 flex items-center justify-between text-white shrink-0">
        <div class="flex items-center gap-4">
            <div class="h-10 w-10 rounded-xl bg-primary flex items-center justify-center text-xl">
                <i class="lni lni-brush"></i>
            </div>
            <div>
                <h4 class="text-lg text-white tracking-tight flex items-center gap-2">
                    Design Editor <span class="text-slate-500 font-medium">| SIPPS Interactive</span>
                </h4>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mt-0.5">
                    Workspace kustomisasi desain kaos
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" id="btn-save-design"
                class="px-6 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-xl font-bold text-xs flex items-center gap-2 transition-all shadow-lg shadow-primary/20">
                <i class="lni lni-save"></i> Simpan Desain
            </button>
            <a href="{{ route('customer.order.create') }}"
                class="h-10 w-10 flex items-center justify-center rounded-xl bg-white/10 hover:bg-white/20 transition-colors">
                <i class="lni lni-close text-xl"></i>
            </a>
        </div>
    </header>

    <!-- Main Workspace -->
    <main class="flex-1 overflow-hidden relative">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    @stack('scripts')
</body>

</html>
