<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Invoice {{ $order->order_number }}</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    background: #f1f5f9;
    padding: 30px 16px;
    color: #1e293b;
    font-size: 12px;
    line-height: 1.5;
}
.invoice {
    max-width: 420px;
    width: 100%;
    margin: 0 auto;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 0;
    padding: 28px 24px;
}
.invoice-header {
    text-align: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1.5px solid #1e293b;
}
.invoice-header .logo {
    height: 36px;
    width: auto;
    margin-bottom: 6px;
}
.invoice-header h1 {
    font-size: 16px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #0f172a;
}
.invoice-header p {
    font-size: 10px;
    color: #64748b;
    margin-top: 2px;
}
.invoice-meta {
    margin-bottom: 16px;
}
.invoice-meta .row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 8px;
}
.invoice-meta .left-col { flex: 1; }
.invoice-meta .right-col { text-align: right; }
.invoice-meta .label {
    font-size: 9px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #94a3b8;
    font-weight: 600;
    margin-bottom: 1px;
}
.invoice-meta .value {
    font-size: 12px;
    font-weight: 600;
    color: #1e293b;
}
.invoice-meta .value-sm {
    font-size: 11px;
    font-weight: 500;
    color: #475569;
}
.invoice-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 16px;
}
.invoice-table thead th {
    padding: 6px 4px 6px 0;
    text-align: left;
    font-size: 9px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #94a3b8;
    font-weight: 600;
    border-bottom: 1px solid #e2e8f0;
}
.invoice-table thead th.right { text-align: right; padding-right: 0; padding-left: 4px; }
.invoice-table tbody td {
    padding: 6px 4px 6px 0;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: top;
}
.invoice-table tbody td.right { text-align: right; padding-right: 0; padding-left: 4px; }
.invoice-table tbody td.nama { font-weight: 500; color: #1e293b; }
.invoice-table tbody td.qty { text-align: center; color: #64748b; }
.invoice-table tbody td.harga { text-align: right; font-family: 'Inter', monospace; color: #475569; }
.invoice-table tbody td.subtotal { text-align: right; font-weight: 600; font-family: 'Inter', monospace; color: #1e293b; }
.invoice-summary {
    margin-bottom: 16px;
    padding-top: 8px;
    border-top: 1px solid #e2e8f0;
}
.invoice-summary .line {
    display: flex;
    justify-content: space-between;
    padding: 3px 0;
    font-size: 11px;
}
.invoice-summary .line .label { color: #64748b; }
.invoice-summary .line .amount { font-family: 'Inter', monospace; color: #475569; }
.invoice-summary .line.total {
    padding-top: 6px;
    margin-top: 4px;
    border-top: 1.5px solid #1e293b;
}
.invoice-summary .line.total .label {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.invoice-summary .line.total .amount {
    font-size: 15px;
    font-weight: 800;
    color: #f59e0b;
}
.invoice-payment-status {
    text-align: center;
    padding: 10px 0 12px;
    margin-bottom: 12px;
    border-top: 1px solid #e2e8f0;
}
.invoice-payment-status .label {
    font-size: 9px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #94a3b8;
    font-weight: 600;
    margin-bottom: 4px;
}
.invoice-payment-status .status {
    font-size: 13px;
    font-weight: 700;
}
.invoice-payment-status .status.paid { color: #059669; }
.invoice-payment-status .status.pending { color: #f59e0b; }
.invoice-payment-status .status.failed { color: #dc2626; }
.invoice-footer {
    text-align: center;
    padding-top: 12px;
    border-top: 1px solid #e2e8f0;
    font-size: 10px;
    color: #94a3b8;
    line-height: 1.6;
}
.invoice-footer strong { color: #64748b; }
.btn-print {
    display: block;
    width: 100%;
    max-width: 420px;
    margin: 0 auto 16px;
    padding: 12px;
    background: #0f172a;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    font-family: 'Inter', sans-serif;
}
.btn-print:hover { background: #1e293b; }
@media print {
    .no-print { display: none !important; }
    body {
        background: #fff;
        padding: 0;
    }
    .invoice {
        max-width: 420px;
        width: 100%;
        margin: 0 auto;
        box-shadow: none;
        border: 1px solid #d1d5db;
        padding: 20px 18px;
    }
    @page { margin: 8mm; }
}
</style>
</head>
<body>

<button class="btn-print no-print" onclick="window.print()">Cetak / Simpan PDF</button>

<div class="invoice">
    <div class="invoice-header">
        <img src="{{ asset('images/logo-untirta.webp') }}" alt="Untirta" class="logo">
        <h1>BVS UNTIRTA</h1>
        <p>Bengkel Vokasi &amp; Sains &middot; FKIP Universitas Sultan Ageng Tirtayasa</p>
    </div>

    <div class="invoice-meta">
        <div class="row">
            <div class="left-col">
                <div class="label">Invoice</div>
                <div class="value">{{ $order->order_number }}</div>
            </div>
            <div class="right-col">
                <div class="label">Tanggal</div>
                <div class="value-sm">{{ $order->date->format('d/m/Y') }}</div>
            </div>
        </div>
        <div class="row">
            <div class="left-col">
                <div class="label">Pelanggan</div>
                <div class="value-sm">{{ $order->customer->name }}</div>
            </div>
            <div class="right-col">
                <div class="label">Kendaraan</div>
                <div class="value-sm">{{ $order->vehicle->plate_number }} &middot; {{ $order->vehicle->brand }} {{ $order->vehicle->model }}</div>
            </div>
        </div>
        @if($order->complaint)
        <div class="row">
            <div class="left-col">
                <div class="label">Keluhan</div>
                <div class="value-sm">{{ $order->complaint }}</div>
            </div>
        </div>
        @endif
    </div>

    @if($order->items->count())
    <table class="invoice-table">
        <thead>
            <tr>
                <th style="width:46%">Item</th>
                <th class="right" style="width:12%">Qty</th>
                <th class="right" style="width:20%">Harga</th>
                <th class="right" style="width:22%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td class="nama">{{ $item->name }}</td>
                <td class="qty">{{ $item->quantity }}</td>
                <td class="harga">Rp{{ number_format($item->price,0,',','.') }}</td>
                <td class="subtotal">Rp{{ number_format($item->subtotal,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="invoice-summary">
        <div class="line">
            <span class="label">Subtotal Item</span>
            <span class="amount">Rp{{ number_format($order->items->sum('subtotal'),0,',','.') }}</span>
        </div>
        <div class="line">
            <span class="label">Biaya Jasa</span>
            <span class="amount">Rp{{ number_format($order->service_fee,0,',','.') }}</span>
        </div>
        <div class="line total">
            <span class="label">Total</span>
            <span class="amount">Rp{{ number_format($order->total,0,',','.') }}</span>
        </div>
    </div>

    <div class="invoice-payment-status">
        <div class="label">Status Pembayaran</div>
        @if($order->payment_status === 'paid')
            <div class="status paid">&#10003; LUNAS</div>
        @elseif($order->payment_status === 'failed')
            <div class="status failed">&#10007; GAGAL</div>
        @else
            <div class="status pending">MENUNGGU PEMBAYARAN</div>
        @endif
    </div>

    <div class="invoice-footer">
        <p>Terima kasih telah berbelanja<br>di <strong>Bengkel Vokasi &amp; Sains UNTIRTA</strong></p>
        <p style="margin-top:4px;">Invoice ini dibuat otomatis oleh sistem.</p>
    </div>
</div>

</body>
</html>