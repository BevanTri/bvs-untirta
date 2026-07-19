<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Pembayaran Berhasil</h2></x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4 text-center">
            <div class="bg-white p-8 rounded-lg shadow">
                <div class="text-green-500 text-6xl mb-4">✓</div>
                <h3 class="text-2xl font-bold">Pembayaran Berhasil!</h3>
                <p class="text-gray-600 mt-2">Pesanan {{ $order->order_number }} sedang diproses.</p>
                <a href="{{ route('orders.show', $order) }}" class="mt-6 inline-block bg-sky-700 text-white px-6 py-2 rounded-lg">Lihat Detail Pesanan</a>
            </div>
        </div>
    </div>
</x-app-layout>
