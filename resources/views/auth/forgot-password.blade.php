<x-guest-layout>

    <!-- Session Status -->
    @if (session('status'))
        <div
            class="mb-4 rounded-2xl border border-green-400/20
                   bg-green-500/10 px-4 py-3
                   text-sm text-center text-green-300"
        >
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Info -->
        <div class="text-center mb-6">

            <h2 class="text-xl font-semibold text-white">
                Lupa Password?
            </h2>

            <p class="mt-2 text-sm text-blue-100/70 leading-relaxed max-w-xs mx-auto">
                Masukkan email akun untuk menerima
                tautan reset password.
            </p>

        </div>

        <!-- Email -->
        <div class="mb-5">

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
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

        <!-- Button -->
        <button
            type="submit"
            class="w-full btn-primary h-12 rounded-2xl
                   font-semibold text-white
                   transition duration-300
                   hover:scale-[1.01]"
        >
            Kirim Link Reset
        </button>

        <!-- Back -->
        <div class="text-center mt-5">

            <a
                href="{{ route('login') }}"
                class="text-sm text-blue-100/60
                       hover:text-white transition"
            >
                ← Kembali ke login
            </a>

        </div>

    </form>

</x-guest-layout>