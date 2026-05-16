<nav
    x-data="{ open: false }"
    class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/75 backdrop-blur-2xl"
>

    <div class="max-w-[97%] mx-auto px-4 lg:px-6">

        <div class="flex items-center justify-between h-[82px]">

            <!-- LEFT -->
            <div class="flex items-center gap-10">

                <!-- BRAND -->
                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center gap-4 shrink-0"
                >

                    <!-- LOGO ONLY (NO BACKGROUND / BOX) -->
                    <img
                        src="{{ asset('images/LOGO RIC SEMARANG.png') }}"
                        class="h-14 w-auto object-contain"
                        alt="Logo"
                    >

                    <div>
                        <h1 class="font-extrabold text-slate-800 text-lg leading-none">
                            RIC MONITORING
                        </h1>

                        <p class="text-sm text-slate-500 mt-1">
                            Jasa Raharja Cabang Semarang
                        </p>
                    </div>

                </a>

                <!-- MENU -->
                <div class="hidden xl:flex items-center gap-2">

                    <a
                        href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-5 py-3 rounded-2xl text-sm font-semibold transition
                        {{ request()->routeIs('dashboard')
                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-100'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        Dashboard
                    </a>

                    <a
                        href="{{ route('data.swdkllj') }}"
                        class="flex items-center gap-3 px-5 py-3 rounded-2xl text-sm font-semibold transition
                        {{ request()->routeIs('data.swdkllj')
                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-100'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >
                        <i data-lucide="database" class="w-4 h-4"></i>
                        Data SWDKLLJ
                    </a>

                    <a
                        href="{{ route('visualisasi') }}"
                        class="flex items-center gap-3 px-5 py-3 rounded-2xl text-sm font-semibold transition
                        {{ request()->routeIs('visualisasi')
                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-100'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >
                        <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                        Visualisasi
                    </a>

                    <a
                        href="{{ route('laporan') }}"
                        class="flex items-center gap-3 px-5 py-3 rounded-2xl text-sm font-semibold transition
                        {{ request()->routeIs('laporan')
                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-100'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        Laporan
                    </a>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-4">

                <!-- USER CARD -->
                <div class="hidden md:flex items-center gap-4 bg-slate-100 rounded-2xl px-4 py-3 border border-slate-200">

                    <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center text-white shadow-md">

                        <i data-lucide="user-round" class="w-5 h-5"></i>

                    </div>

                    <div>

                        <p class="text-xs text-slate-500">
                            Login Sebagai
                        </p>

                        <h4 class="font-bold text-slate-800 text-sm">
                            {{ Auth::user()->name }}
                        </h4>

                    </div>

                </div>

                <!-- LOGOUT -->
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="flex items-center gap-2 bg-red-50 hover:bg-red-500 border border-red-100 hover:border-red-500 text-red-500 hover:text-white px-5 py-3 rounded-2xl text-sm font-semibold transition-all duration-300"
                    >
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Logout
                    </button>

                </form>

            </div>

        </div>

    </div>

</nav>