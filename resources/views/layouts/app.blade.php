<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIM SWDKLLJ - Jasa Raharja Jawa Tengah</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }

        .bg-main {
            background:
                radial-gradient(circle at top right, rgba(0,160,227,.08), transparent 25%),
                radial-gradient(circle at bottom left, rgba(0,91,172,.08), transparent 25%),
                #F5F8FC;
        }

        .page-container {
            max-width: 97%;
            margin: auto;
        }

        .card-ui {
            background: rgba(255,255,255,.85);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(226,232,240,.7);
            box-shadow:
                0 10px 30px rgba(15,23,42,.04),
                0 1px 3px rgba(15,23,42,.06);
        }

        .fade-up {
            animation: fadeUp .5s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="font-sans antialiased bg-main text-slate-800">

    <div class="min-h-screen">

        {{-- NAVIGATION --}}
        @include('layouts.navigation')

        {{-- HEADER --}}
        @isset($header)

            <header class="relative z-10 fade-up">

                <div class="page-container pt-8 pb-2">

                    <div class="card-ui rounded-[32px] px-8 py-7">

                        {{ $header }}

                    </div>

                </div>

            </header>

        @endisset

        {{-- CONTENT --}}
        <main class="relative z-10 pb-10 fade-up">

            {{ $slot }}

        </main>

    </div>

    <script>
        lucide.createIcons();
    </script>

</body>

</html>