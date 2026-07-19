@section('title', 'Login')

<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center gap-2">
                <input id="remember_me" type="checkbox" class="rounded border-brand-border text-brand-blue focus:ring-brand-blue" name="remember">
                <span class="text-sm text-brand-ink-muted">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6 gap-4">
            @if (Route::has('password.request'))
                <a class="text-sm text-brand-blue hover:text-brand-blue/80 font-medium" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="mt-6 pt-6 border-t border-brand-border text-center">
            <p class="text-sm text-brand-ink-muted">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-brand-blue hover:text-brand-blue/80 font-medium">Daftar</a>
            </p>
        </div>
    </form>
</x-guest-layout>