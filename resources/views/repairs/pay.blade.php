<x-app-layout>
    <div class="max-w-md mx-auto text-center">
        <h1 class="font-display font-bold text-2xl text-brand-ink mb-4">Bayar Servis</h1>
        <p class="text-brand-ink-muted mb-2">Servis: {{ $order->order_number }}</p>
        <p class="text-3xl font-bold text-brand-ink font-mono mb-8">Rp{{ number_format($order->total,0,',','.') }}</p>

        @if(session('error'))
        <div class="card p-4 mb-6 border-l-4 border-red-400 bg-red-50 text-red-700 text-sm">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('payment.repair', $order) }}">
            @csrf
            <button type="submit" class="btn-primary w-full text-lg py-3">Bayar via iPaymu</button>
        </form>

        <a href="{{ route('repairs.show', $order) }}" class="text-brand-blue hover:underline text-sm mt-6 inline-block">&larr; Kembali</a>
    </div>
</x-app-layout>
