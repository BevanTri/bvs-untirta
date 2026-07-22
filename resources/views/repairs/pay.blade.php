<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="max-w-md mx-auto text-center">
            <h1 class="font-display font-bold text-2xl text-brand-ink mb-6">Bayar Servis</h1>
            <p class="text-brand-ink-muted mb-2">Servis: {{ $order->order_number }}</p>
            <p class="text-3xl font-bold text-brand-ink font-mono tabular-nums mb-6">Rp{{ number_format($order->total,0,',','.') }}</p>

            @if(session('error'))
            <x-card class="mb-4 border-l-4 border-red-400 bg-red-50">
                <p class="text-red-700 text-sm">{{ session('error') }}</p>
            </x-card>
            @endif

            <form method="POST" action="{{ route('payment.repair', $order) }}">
                @csrf
                <button type="submit" class="btn-primary w-full text-lg py-3">Bayar via iPaymu</button>
            </form>

            <a href="{{ route('repairs.show', $order) }}" class="text-brand-blue hover:underline text-sm mt-6 inline-block">&larr; Kembali</a>
        </div>
    </div>
</x-app-layout>
