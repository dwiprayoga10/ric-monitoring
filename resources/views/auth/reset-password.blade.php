<x-guest-layout>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Token -->
        <input
            type="hidden"
            name="token"
            value="{{ $request->route('token') }}"
        >

        <!-- Info -->
        <div class="text-center mb-5">

            <h2 class="text-xl font-semibold text-white">
                Reset Password
            </h2>

            <p class="text-sm text-blue-100/70 mt-1">
                Buat password baru untuk akun Anda
            </p>

        </div>

        <!-- Email -->
        <div class="mb-3">
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
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

        <!-- Password -->
        <div class="mb-3">
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Password baru"
                class="input-modern h-12 text-sm"
            >

            @error('password')
                <p class="text-xs text-red-300 mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-5">
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Konfirmasi password"
                class="input-modern h-12 text-sm"
            >

            @error('password_confirmation')
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
            Simpan Password Baru
        </button>

        <!-- Back -->
        <div class="text-center mt-5">
            <a
                href="{{ route('login') }}"
                class="text-sm text-blue-100/60
                       hover:text-white transition"
            >
                Kembali ke login
            </a>
        </div>

    </form>

</x-guest-layout>