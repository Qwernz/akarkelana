<x-guest-layout>
    {{-- TOMBOL KEMBALI --}}
    <div class="mb-6 border-b border-stone-100 pb-4">
        <a href="{{ route('login') }}" class="inline-flex items-center text-[10px] font-black text-stone-400 uppercase tracking-widest hover:text-orange-600 transition group">
            <svg class="w-3 h-3 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                <path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Batal & Kembali ke Login
        </a>
    </div>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('Lupa password? Jangan panik. Masukkan email kamu, dan kami akan kirimkan link untuk buat password baru.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="w-full justify-center py-3 bg-orange-700 hover:bg-orange-800">
                {{ __('Kirim Link Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>