@section('title', 'Login')

<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="input-field block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-password-input id="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block">
            <label for="remember_me" class="inline-flex items-center gap-2">
                <input id="remember_me" type="checkbox" class="rounded border-brand-border dark:border-brand-navy-3 text-brand-blue dark:text-brand-blue-light focus:ring-brand-blue" name="remember">
                <span class="text-sm text-brand-ink-muted dark:text-zinc-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
            @if (Route::has('password.request'))
                <a class="text-sm text-brand-blue hover:text-brand-blue/80 dark:text-brand-blue-light font-medium" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            <button type="submit" class="btn-primary w-full sm:w-auto justify-center">
                {{ __('Log in') }}
            </button>
        </div>

            <div class="pt-4 border-t border-brand-border dark:border-brand-navy-3 text-center">
            <p class="text-sm text-brand-ink-muted dark:text-zinc-400">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-brand-blue hover:text-brand-blue/80 dark:text-brand-blue-light font-medium">Daftar</a>
            </p>
        </div>
    </form>
</x-guest-layout>
