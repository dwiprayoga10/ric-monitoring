<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>RIC MONITORING - Jasa Raharja Cabang Semarang</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            overflow: hidden;
        }

        .bg-main {
            background:
                radial-gradient(circle at top right, rgba(0,160,227,.18), transparent 30%),
                radial-gradient(circle at bottom left, rgba(0,91,172,.20), transparent 30%),
                linear-gradient(135deg, #003B70 0%, #005BAC 50%, #00A0E3 100%);
        }

        .glass {
            backdrop-filter: blur(22px);
            background: rgba(255,255,255,.10);
            border: 1px solid rgba(255,255,255,.12);
            box-shadow: 0 15px 40px rgba(0,0,0,.20);
        }

        .floating {
            animation: floating 5s ease-in-out infinite;
        }

        @keyframes floating {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .animate-fade {
            animation: fade .7s ease;
        }

        @keyframes fade {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .input-modern {
            width: 100%;
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,.14);
            background: rgba(255,255,255,.08);
            color: white;
            padding: 14px 18px;
            transition: .3s ease;
        }

        .input-modern::placeholder {
            color: rgba(255,255,255,.45);
        }

        .input-modern:focus {
            outline: none;
            border-color: rgba(0,160,227,.65);
            background: rgba(255,255,255,.12);
            box-shadow: 0 0 0 4px rgba(0,160,227,.12);
        }

        .btn-primary {
            background: linear-gradient(135deg, #005BAC, #00A0E3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,.20);
        }

        @media(max-width:1024px) {
            body {
                overflow-y: auto;
            }
        }
    </style>
</head>

<body class="bg-main text-white min-h-screen">

<div class="relative min-h-screen overflow-hidden">

    <!-- Blur Background -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-cyan-400 opacity-20 blur-[120px] rounded-full"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-900 opacity-20 blur-[120px] rounded-full"></div>

    <div class="relative z-10 h-screen grid lg:grid-cols-2">

        <!-- LEFT SIDE -->
        <div class="hidden lg:flex flex-col justify-center px-16 animate-fade">

            <div class="flex items-center gap-4 mb-8">
                <img
                    src="{{ asset('images/Desain tanpa judul.png') }}"
                    class="h-16 floating"
                    alt="Logo Jasa Raharja"
                >

                <div>
                    <h1 class="font-bold text-2xl">
                        RIC MONITORING
                    </h1>

                    <p class="text-blue-100">
                        Jasa Raharja Cabang Semarang
                    </p>
                </div>
            </div>

            <h2 class="text-5xl font-extrabold leading-tight max-w-2xl mb-5">
                RIC
                <span class="text-cyan-300">
                    MONITORING
                </span>
                SWDKLLJ
            </h2>

            <p class="text-blue-100 text-lg max-w-xl leading-relaxed">
                Platform pengelolaan data SWDKLLJ dan
                surat pemberitahuan pajak kendaraan
                berbasis import data yang terstruktur,
                akurat, aman, dan terintegrasi untuk
                mendukung proses monitoring data di
                Jasa Raharja Cabang Semarang.
            </p>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mt-8 max-w-lg">

                <div class="glass rounded-3xl p-4 text-center">
                    <h3 class="text-cyan-300 text-2xl font-bold">
                        Import
                    </h3>
                    <p class="text-sm text-blue-100">
                        Data Terstruktur
                    </p>
                </div>

                <div class="glass rounded-3xl p-4 text-center">
                    <h3 class="text-cyan-300 text-2xl font-bold">
                        Akurat
                    </h3>
                    <p class="text-sm text-blue-100">
                        Validasi Data
                    </p>
                </div>

                <div class="glass rounded-3xl p-4 text-center">
                    <h3 class="text-cyan-300 text-2xl font-bold">
                        Aman
                    </h3>
                    <p class="text-sm text-blue-100">
                        Sistem Terintegrasi
                    </p>
                </div>

            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="flex items-center justify-center px-6">

            <!-- CARD -->
            <div class="glass rounded-[34px] w-full max-w-[420px] p-6 animate-fade">

                <!-- Header -->
                <div class="text-center mb-7">

                    <img
                        src="{{ asset('images/Desain tanpa judul.png') }}"
                        class="h-20 mx-auto floating mb-4"
                        alt="Logo Jasa Raharja"
                    >

                    <h2 class="text-2xl font-bold mb-2">
                        RIC MONITORING
                    </h2>

                    <p class="text-blue-100 text-sm leading-relaxed">
                        Sistem pengelolaan data SWDKLLJ
                        berbasis import data
                    </p>

                </div>

                <!-- Slot -->
                {{ $slot }}

            </div>

        </div>

    </div>

</div>

</body>
</html>