@section('title', 'Konfirmasi Password')

<x-guest-layout>
    <div class="mb-4 text-sm text-brand-ink-muted leading-relaxed">
        {{ __('Konfirmasi password Anda untuk melanjutkan.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="input-field block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex flex-col sm:flex-row justify-end mt-6">
            <x-primary-button class="w-full sm:w-auto justify-center">
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
