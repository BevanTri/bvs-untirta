<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Invoice {{ $order->order_number }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; color: #1a1a2e; background: #e5e7eb; padding: 20px; }
    .page { max-width: 210mm; margin: 0 auto; background: #fff; padding: 40px 35px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #1a1a2e; }
    .header h1 { font-size: 22px; text-transform: uppercase; letter-spacing: 3px; }
    .header p { margin: 2px 0; color: #555; font-size: 11px; }
    .header .right { text-align: right; }
    .meta { display: flex; justify-content: space-between; margin-bottom: 25px; }
    .meta div p { margin: 3px 0; font-size: 12px; }
    .meta label { color: #888; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th { text-align: left; padding: 10px 8px; border-bottom: 2px solid #1a1a2e; text-transform: uppercase; font-size: 10px; letter-spacing: 1px; color: #555; }
    td { padding: 10px 8px; border-bottom: 1px solid #e5e7eb; }
    .total-row td { border: none; padding: 3px 8px; font-size: 12px; }
    .grand-total td { font-size: 16px; font-weight: bold; border-top: 2px solid #1a1a2e; padding-top: 8px; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .notes { margin-top: 20px; padding: 12px; background: #f9fafb; border-left: 3px solid #1a1a2e; font-size: 11px; }
    .footer { text-align: center; margin-top: 30px; padding-top: 15px; border-top: 1px solid #d1d5db; font-size: 10px; color: #9ca3af; }
    .actions { text-align: center; margin-bottom: 20px; }
    .btn-print { display: inline-block; padding: 10px 30px; background: #1a1a2e; color: #fff; border: none; border-radius: 6px; font-size: 13px; cursor: pointer; }
    .btn-print:hover { opacity: 0.9; }
    @media print {
        body { background: none; padding: 0; }
        .page { box-shadow: none; padding: 20px 15px; }
        .actions { display: none; }
    }
</style>
</head>
<body>
    <div class="actions">
        <button class="btn-print" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div class="page">
        <div class="header">
            <div>
                <h1>BVS Motor</h1>
                <p>Bengkel & Variasi Sepeda Motor</p>
                <p>Universitas Sultan Ageng Tirtayasa</p>
            </div>
            <div class="right">
                <h1 style="font-size:16px;letter-spacing:2px;margin-bottom:4px;">INVOICE</h1>
                <p><strong>{{ $order->order_number }}</strong></p>
                <p style="margin-top:4px;font-size:11px;">{{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="meta">
            <div>
                <label>Pelanggan</label>
                <p><strong>{{ $order->customer_name }}</strong></p>
                <p>{{ $order->customer_phone ?: '-' }}</p>
            </div>
            <div>
                <label>Status</label>
                <p><strong>{{ ucfirst($order->status) }}</strong></p>
                <p>Pembayaran: {{ $order->payment_status === 'paid' ? 'Lunas' : ($order->payment_status === 'failed' ? 'Gagal' : 'Pending') }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width:50%">Item</th>
                    <th class="text-center" style="width:12%">Qty</th>
                    <th class="text-right" style="width:18%">Harga</th>
                    <th class="text-right" style="width:20%">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row"><td colspan="3" class="text-right">Subtotal</td><td class="text-right">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td></tr>
                <tr class="total-row"><td colspan="3" class="text-right">Ongkir</td><td class="text-right">Rp{{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td></tr>
                <tr class="grand-total"><td colspan="3" class="text-right">Total</td><td class="text-right">Rp{{ number_format($order->total, 0, ',', '.') }}</td></tr>
            </tfoot>
        </table>

        @if($order->notes)
        <div class="notes">
            <strong>Catatan:</strong> {{ $order->notes }}
        </div>
        @endif

        <div class="footer">
            <p>Terima kasih telah berbelanja di BVS Motor</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
        </div>
    </div>
</body>
</html>