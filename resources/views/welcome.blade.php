<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SIM SWDKLLJ - Jasa Raharja Cabang Semarang</title>

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
                radial-gradient(circle at top right, rgba(0,160,227,.18), transparent 28%),
                radial-gradient(circle at bottom left, rgba(0,91,172,.2), transparent 28%),
                linear-gradient(135deg, #003B70 0%, #005BAC 50%, #00A0E3 100%);
        }

        .glass {
            backdrop-filter: blur(18px);
            background: rgba(255,255,255,.10);
            border: 1px solid rgba(255,255,255,.14);
            box-shadow: 0 8px 30px rgba(0,0,0,.14);
        }

        .animate-fade-up {
            animation: fadeUp .8s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .floating {
            animation: floating 5s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .btn-primary {
            background: linear-gradient(135deg, #005BAC, #00A0E3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(0,0,0,.2);
        }

        .hero-title {
            line-height: 1.05;
        }

        @media (max-width: 1024px) {
            body {
                overflow-y: auto;
            }

            .mobile-scroll {
                min-height: 100vh;
                overflow-y: auto;
            }
        }
    </style>
</head>

<body class="bg-main text-white h-screen overflow-hidden">

<div class="relative h-screen overflow-hidden flex flex-col mobile-scroll">

    <!-- Background Blur -->
    <div class="absolute top-0 left-0 w-80 h-80 bg-cyan-400 opacity-20 blur-[120px] rounded-full"></div>
    <div class="absolute bottom-0 right-0 w-80 h-80 bg-blue-900 opacity-20 blur-[120px] rounded-full"></div>

    <!-- Navbar -->
    <header class="relative z-20 px-6 lg:px-14 py-5 flex-shrink-0">
        <div class="flex items-center justify-between">

            <div class="flex items-center gap-4 animate-fade-up">
                <img
                    src="{{ asset('images/Desain tanpa judul.png') }}"
                    alt="Logo Jasa Raharja"
                    class="h-12 w-auto object-contain floating"
                />

                <div>
                    <h1 class="font-bold text-lg lg:text-xl">
                        SIM SWDKLLJ
                    </h1>
                    <p class="text-sm text-blue-100">
                        Jasa Raharja Cabang Semarang
                    </p>
                </div>
            </div>

            @if (Route::has('login'))
                <nav class="flex gap-3 animate-fade-up">

                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="glass px-5 py-2 rounded-xl hover:bg-white/20 transition">
                            Dashboard
                        </a>
                    @else

                        <a href="{{ route('login') }}"
                           class="px-5 py-2 rounded-xl border border-white/30 hover:bg-white/10 transition">
                            Login
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="btn-primary px-5 py-2 rounded-xl font-semibold transition duration-300">
                                Register
                            </a>
                        @endif

                    @endauth
                </nav>
            @endif

        </div>
    </header>

    <!-- Main Content -->
    <main class="relative z-10 flex-1 px-6 lg:px-14 pb-6 flex items-center">

        <div class="grid lg:grid-cols-2 gap-10 items-center w-full">

            <!-- LEFT -->
            <div class="animate-fade-up">

                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass mb-5">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-sm">
                        Sistem Monitoring Terintegrasi
                    </span>
                </div>

                <h2 class="hero-title text-4xl lg:text-6xl font-extrabold mb-5 max-w-3xl">
                    Sistem Monitoring
                    <span class="text-cyan-300">
                        Pembayaran
                    </span>
                    & Surat Pemberitahuan Pajak
                </h2>

                <p class="text-blue-100 text-base lg:text-lg leading-relaxed max-w-2xl mb-7">
                    Platform digital monitoring pembayaran SWDKLLJ dan
                    pengelolaan surat pemberitahuan pajak kendaraan
                    secara cepat, transparan, dan terintegrasi
                    untuk wilayah Cabang Semarang.
                </p>


                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 mt-8 max-w-xl">

                    <div class="glass rounded-2xl p-4 text-center">
                        <h3 class="font-bold text-2xl text-cyan-300">
                            24/7
                        </h3>
                        <p class="text-xs text-blue-100">
                            Monitoring
                        </p>
                    </div>

                    <div class="glass rounded-2xl p-4 text-center">
                        <h3 class="font-bold text-2xl text-cyan-300">
                            Real-Time
                        </h3>
                        <p class="text-xs text-blue-100">
                            Tracking
                        </p>
                    </div>

                    <div class="glass rounded-2xl p-4 text-center">
                        <h3 class="font-bold text-2xl text-cyan-300">
                            Secure
                        </h3>
                        <p class="text-xs text-blue-100">
                            System
                        </p>
                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="animate-fade-up flex justify-center lg:justify-end">

                <div class="glass rounded-[36px] p-8 w-full max-w-md">

                    <div class="flex justify-center mb-5">
                        <img
                            src="{{ asset('images/Desain tanpa judul.png') }}"
                            class="h-24 object-contain floating"
                            alt="Logo Jasa Raharja"
                        >
                    </div>

                    <div class="text-center mb-7">
                        <h3 class="text-3xl font-bold mb-2">
                            SIM SWDKLLJ
                        </h3>

                        <p class="text-blue-100 text-sm leading-relaxed">
                            Sistem Monitoring Pembayaran &
                            Surat Pemberitahuan Pajak Kendaraan
                        </p>
                    </div>

                    <div class="space-y-3">

                        @guest

                            <a href="{{ route('login') }}"
                               class="w-full block text-center btn-primary py-4 rounded-2xl font-bold transition duration-300">
                                Masuk ke Sistem
                            </a>

                            @if(Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="w-full block text-center border border-white/20 py-4 rounded-2xl hover:bg-white/10 transition">
                                    Registrasi Akun
                                </a>
                            @endif

                        @else

                            <a href="{{ url('/dashboard') }}"
                               class="w-full block text-center btn-primary py-4 rounded-2xl font-bold transition duration-300">
                                Dashboard
                            </a>

                        @endguest

                    </div>

                    <div class="mt-6 border-t border-white/10 pt-5">
                        <div class="grid grid-cols-2 gap-4 text-center">

                            <div>
                                <h4 class="font-semibold text-cyan-300">
                                    Efisien
                                </h4>
                                <p class="text-xs text-blue-100">
                                    Monitoring Cepat
                                </p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-cyan-300">
                                    Aman
                                </h4>
                                <p class="text-xs text-blue-100">
                                    Data Terenkripsi
                                </p>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

</body>
</html>