<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-display font-bold text-2xl text-brand-ink">Servis Saya</h1>
            <a href="{{ route('repairs.create') }}" class="btn-primary w-full md:w-auto">+ Ajukan Servis</a>
        </div>

        @if(session('success'))
        <x-card padding="p-4" class="mb-4 border-l-4 border-brand-gold bg-brand-gold/5 text-brand-ink font-medium">{{ session('success') }}</x-card>
        @endif

        @if($orders->isEmpty())
        <x-card class="text-center">
            <p class="text-brand-ink-muted mb-4">Belum ada servis diajukan</p>
            <a href="{{ route('repairs.create') }}" class="btn-primary w-full md:w-auto">Ajukan Servis</a>
        </x-card>
        @else
        <div class="space-y-4">
            @foreach($orders as $o)
            <a href="{{ route('repairs.show', $o) }}" class="card p-5 block hover:border-brand-gold transition-colors">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-mono font-bold text-brand-ink">{{ $o->order_number }}</p>
                        <p class="text-sm text-brand-ink-muted mt-1">{{ $o->vehicle->plate_number }} — {{ $o->vehicle->brand }} {{ $o->vehicle->model }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $o->status === 'selesai' ? 'bg-green-100 text-green-700' : ($o->status === 'dibatalkan' ? 'bg-red-100 text-red-600' : ($o->status === 'proses' ? 'bg-blue-100 text-blue-600' : 'bg-yellow-100 text-yellow-700')) }}">
                            {{ ucfirst($o->status) }}
                        </span>
                        <p class="text-sm font-medium mt-1">Rp{{ number_format($o->total,0,',','.') }}</p>
                    </div>
                </div>
                <p class="text-xs text-brand-ink-faint mt-2">{{ $o->date->format('d M Y') }}</p>
            </a>
            @endforeach
        </div>
        {{ $orders->links() }}
        @endif
    </div>
</x-app-layout>
