@section('title', 'Verifikasi Email')

<x-guest-layout>
    <div class="mb-4 text-sm text-brand-ink-muted leading-relaxed">
        {{ __('Terima kasih sudah mendaftar! Silakan verifikasi email Anda dengan klik tautan yang kami kirim.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4">
            {{ __('Tautan verifikasi baru telah dikirim ke email Anda.') }}
        </div>
    @endif

    <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
            @csrf
            <x-primary-button class="w-full justify-center">
                {{ __('Kirim Ulang Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="w-full justify-center btn-primary">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
