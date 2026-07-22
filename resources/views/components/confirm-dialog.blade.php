@props([
    'name' => 'confirm',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
    'action' => '',
    'method' => 'POST',
    'confirmText' => 'Ya',
    'cancelText' => 'Kembali',
    'confirmClass' => 'btn-danger',
])

<div id="{{ $name }}" class="fixed inset-0 z-[80] items-center justify-center bg-black/50 hidden" style="display:none" onclick="if(event.target===this){this.style.display='none';this.classList.remove('flex')}">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 p-6" onclick="event.stopPropagation()">
        <h3 class="font-display font-bold text-lg text-brand-ink mb-2">{{ $title }}</h3>
        <p class="text-sm text-brand-ink-muted mb-6">{{ $message }}</p>
        <div class="flex gap-3">
            <button type="button" onclick="this.closest('[id]').style.display='none';this.closest('[id]').classList.remove('flex')" class="btn-outline flex-1">{{ $cancelText }}</button>
            @if($action)
            <form method="POST" action="{{ $action }}" class="flex-1">
                @csrf
                @if(strtolower($method) !== 'post') @method($method) @endif
                <button type="submit" class="{{ $confirmClass }} w-full justify-center">{{ $confirmText }}</button>
            </form>
            @endif
        </div>
    </div>
</div>
