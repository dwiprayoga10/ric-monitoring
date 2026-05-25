<x-guest-layout>

    <!-- Session Status -->
    @if (session('status'))
        <div
            class="mb-4 rounded-2xl border border-green-400/20
                   bg-green-500/10 px-4 py-3 text-sm
                   text-green-300 text-center"
        >
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-3">
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                class="input-modern h-12 text-sm"
                placeholder="Email"
            >

            @error('email')
                <p class="text-xs text-red-300 mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="input-modern h-12 text-sm"
                placeholder="Password"
            >

            @error('password')
                <p class="text-xs text-red-300 mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember & Forgot -->
        <div class="flex items-center justify-between mb-5">

            <label class="flex items-center gap-2 cursor-pointer">

                <input
                    type="checkbox"
                    name="remember"
                    class="rounded border-white/20
                           bg-white/10 text-cyan-400
                           focus:ring-cyan-500"
                >

                <span class="text-sm text-blue-100/70">
                    Ingat saya
                </span>

            </label>

            @if (Route::has('password.request'))
                <a
                    href="{{ route('password.request') }}"
                    class="text-sm text-cyan-300
                           hover:text-cyan-200 transition"
                >
                    Lupa password?
                </a>
            @endif

        </div>

        <!-- Login Button -->
        <button
            type="submit"
            class="w-full btn-primary h-12 rounded-2xl
                   font-semibold text-white
                   transition duration-300
                   hover:scale-[1.01]"
        >
            Masuk
        </button>

        <!-- Register -->
        <div class="text-center mt-5">
            <p class="text-sm text-blue-100/60">
                Belum punya akun?

                <a
                    href="{{ route('register') }}"
                    class="text-cyan-300
                           hover:text-cyan-200
                           font-medium transition"
                >
                    Daftar
                </a>
            </p>
        </div>

    </form>

</x-guest-layout>