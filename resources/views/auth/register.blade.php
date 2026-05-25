<x-guest-layout>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                placeholder="Nama lengkap"
                class="input-modern h-12 text-sm"
            >

            @error('name')
                <p class="text-xs text-red-300 mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
                placeholder="Email"
                class="input-modern h-12 text-sm"
            >

            @error('email')
                <p class="text-xs text-red-300 mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div class="grid grid-cols-2 gap-2 mb-4">

            <div>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Password"
                    class="input-modern h-12 text-sm"
                >

                @error('password')
                    <p class="text-xs text-red-300 mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Konfirmasi"
                    class="input-modern h-12 text-sm"
                >
            </div>

        </div>

        <!-- Button -->
        <button
            type="submit"
            class="w-full btn-primary h-12 rounded-2xl font-semibold text-white transition duration-300 hover:scale-[1.01]"
        >
            Daftar
        </button>

        <!-- Login -->
        <div class="text-center mt-5">
            <p class="text-sm text-blue-100/60">
                Sudah punya akun?

                <a
                    href="{{ route('login') }}"
                    class="text-cyan-300 hover:text-cyan-200 font-medium transition"
                >
                    Masuk
                </a>
            </p>
        </div>

    </form>

</x-guest-layout>