<nav x-data="{ open: false }" class="bg-slate-900 border-b border-slate-800">

    <div class="max-w-[95%] mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between h-16">

            <!-- LEFT -->
            <div class="flex items-center gap-8">

                <a href="{{ route('dashboard') }}"
                   class="text-2xl font-bold text-blue-400">
                    JR Dashboard
                </a>

               <!-- MENU -->
        <div class="hidden md:flex items-center gap-3">

            <a href="{{ route('dashboard') }}"
            class="px-4 py-2 rounded-xl text-sm font-medium text-white hover:bg-slate-800 transition">
                Dashboard
            </a>

            <a href="{{ route('data.swdkllj') }}"
            class="px-4 py-2 rounded-xl text-sm font-medium text-white hover:bg-slate-800 transition">
                Data SWDKLLJ
            </a>

            <a href="{{ route('visualisasi') }}"
            class="px-4 py-2 rounded-xl text-sm font-medium text-white hover:bg-slate-800 transition">
                Visualisasi
            </a>

            <a href="{{ route('laporan') }}"
            class="px-4 py-2 rounded-xl text-sm font-medium text-white hover:bg-slate-800 transition">
                Laporan
            </a>

        </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-4">

                <div class="text-sm text-slate-300">
                    {{ Auth::user()->name }}
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-sm transition"
                    >
                        Logout
                    </button>
                </form>

            </div>

        </div>

    </div>

</nav>