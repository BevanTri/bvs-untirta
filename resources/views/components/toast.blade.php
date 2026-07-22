<div id="toast-container" class="toast-container">
    <div id="toast" class="toast toast-info" style="pointer-events:none">
        <svg class="w-5 h-5 shrink-0 mt-0.5" id="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path id="toast-icon-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
        </svg>
        <div class="flex-1 min-w-0">
            <p id="toast-message" class="font-medium">Toast message</p>
            <div class="h-1 bg-zinc-200 rounded-full mt-2 overflow-hidden">
                <div id="toast-progress" class="h-full bg-brand-gold rounded-full transition-all duration-[3000ms] linear" style="width:100%"></div>
            </div>
        </div>
        <button type="button" onclick="dismissToast()" class="shrink-0 opacity-60 hover:opacity-100 transition-opacity">&times;</button>
    </div>
</div>

<script>
var toastTimer = null;
function showToast(msg, type) {
    type = type || 'success';
    var el = document.getElementById('toast');
    var icon = document.getElementById('toast-icon');
    var iconPath = document.getElementById('toast-icon-path');
    var progress = document.getElementById('toast-progress');
    var msgEl = document.getElementById('toast-message');
    el.className = 'toast show toast-' + type;
    el.style.pointerEvents = 'auto';
    msgEl.textContent = msg;
    if (type === 'success') iconPath.setAttribute('d', 'M5 13l4 4L19 7');
    else if (type === 'error') iconPath.setAttribute('d', 'M6 18L18 6M6 6l12 12');
    else if (type === 'warning') iconPath.setAttribute('d', 'M12 9v2m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z');
    else iconPath.setAttribute('d', 'M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z');
    icon.style.display = '';
    progress.style.width = '100%';
    clearTimeout(toastTimer);
    setTimeout(function() { progress.style.width = '0%'; }, 50);
    toastTimer = setTimeout(dismissToast, 3000);
}
function dismissToast() {
    var el = document.getElementById('toast');
    el.classList.remove('show');
    el.style.pointerEvents = 'none';
    clearTimeout(toastTimer);
}
(function() {
    @php $toasts = ['toast'=>'success','error'=>'error','success'=>'success','warning'=>'warning','info'=>'info']; @endphp
    @foreach($toasts as $key => $type)
        @if(session($key))
        showToast(JSON.parse('@json(session($key))'), '{{ $type }}');
        @endif
    @endforeach
})();
</script>
