<x-guest-layout>

    <x-auth-session-status
        class="mb-4 text-green-300 text-sm"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-5">

            <label for="email"
                   class="block mb-2 text-sm font-medium text-blue-100">
                Email
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                class="input-modern"
                placeholder="Masukkan email"
            >

            @error('email')
                <p class="text-red-300 text-sm mt-2">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">

            <div class="flex justify-between mb-2">
                <label for="password"
                       class="text-sm font-medium text-blue-100">
                    Password
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-sm text-cyan-300 hover:text-cyan-200 transition">
                        Lupa Password?
                    </a>
                @endif
            </div>

            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="input-modern"
                placeholder="Masukkan password"
            >

            @error('password')
                <p class="text-red-300 text-sm mt-2">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember -->
        <div class="flex items-center justify-between mb-6">

            <label class="flex items-center gap-3 cursor-pointer">
                <input
                    type="checkbox"
                    name="remember"
                    class="rounded border-white/30 bg-white/10 text-cyan-400 focus:ring-cyan-500"
                >

                <span class="text-sm text-blue-100">
                    Remember Me
                </span>
            </label>

        </div>

        <!-- Button -->
        <button
            type="submit"
            class="w-full btn-primary py-4 rounded-2xl font-bold text-lg transition duration-300"
        >
            Login ke Sistem
        </button>

        <!-- Back -->
        <a href="/"
           class="block text-center mt-5 text-blue-100 hover:text-white transition text-sm">
            ← Kembali ke Beranda
        </a>

    </form>

</x-guest-layout>