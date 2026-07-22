@section('title', 'Login')

<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="card p-5 space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="input-field block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="input-field block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block">
            <label for="remember_me" class="inline-flex items-center gap-2">
                <input id="remember_me" type="checkbox" class="rounded border-brand-border text-brand-blue focus:ring-brand-blue" name="remember">
                <span class="text-sm text-brand-ink-muted">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
            @if (Route::has('password.request'))
                <a class="text-sm text-brand-blue hover:text-brand-blue/80 font-medium" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            <button type="submit" class="btn-primary w-full sm:w-auto justify-center">
                {{ __('Log in') }}
            </button>
        </div>

        <div class="pt-4 border-t border-brand-border text-center">
            <p class="text-sm text-brand-ink-muted">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-brand-blue hover:text-brand-blue/80 font-medium">Daftar</a>
            </p>
        </div>
    </form>
</x-guest-layout>
