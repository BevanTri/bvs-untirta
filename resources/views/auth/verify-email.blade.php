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

    <div class="mt-4 flex items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                {{ __('Kirim Ulang Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-brand-blue hover:text-brand-blue/80 font-medium">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
