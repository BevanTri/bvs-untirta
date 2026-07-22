@section('title', 'Lupa Password')

<x-guest-layout>
    <div class="mb-4 text-sm text-brand-ink-muted leading-relaxed">
        {{ __('Lupa password? Masukkan email Anda dan kami akan kirim tautan reset password.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="input-field block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button>
                {{ __('Kirim Tautan Reset') }}
            </x-primary-button>
        </div>

        <div class="mt-6 pt-6 border-t border-brand-border text-center">
            <a href="{{ route('login') }}" class="text-sm text-brand-blue hover:text-brand-blue/80 font-medium">Kembali ke Login</a>
        </div>
    </form>
</x-guest-layout>
