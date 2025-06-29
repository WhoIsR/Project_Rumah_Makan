<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan #{{ $order->id }}</title>
    <style>
        /* Style dasar untuk semua media */
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10pt;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .receipt-container {
            width: 280px; /* Lebar umum untuk preview di layar */
            margin: 0 auto; /* Pusatkan preview di layar */
            padding: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .header h1 { font-size: 14pt; margin: 0; }
        .header p { margin: 2px 0; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items-table td { padding: 2px 0; }
        .separator { border-top: 1px dashed #000; margin: 10px 0; }
        .footer { margin-top: 15px; }

        /* Aturan khusus saat mencetak */
        @media print {
            /* Atur ukuran kertas dan margin */
            @page {
                size: 80mm auto; /* Lebar kertas 80mm, tinggi otomatis */
                margin: 5mm; /* Beri sedikit margin di sekeliling kertas */
            }
            /* Hilangkan lebar dan margin preview agar sesuai kertas */
            .receipt-container {
                width: 100%;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body onload="window.print(); setTimeout(window.close, 0);">
    
    <div class="receipt-container">
        <div class="header text-center">
            <h1>Warung Kita</h1>
            <p>Jalan Kenangan No. 123</p>
            <p>Kota Bahagia</p>
        </div>
        <div class="separator"></div>
        <div>
            <p>No. Pesanan: #{{ $order->id }}</p>
            <p>Tanggal: {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p>Kasir: {{ $order->user->name ?? 'N/A' }}</p>
        </div>
        <div class="separator"></div>
        <table class="items-table">
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td colspan="3">{{ $item->menuItem->name ?? 'Menu Dihapus' }}</td>
                </tr>
                <tr>
                    <td>{{ $item->quantity }}x</td>
                    <td class="text-right">@ {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="separator"></div>
        <table class="items-table">
            <tbody>
                <tr>
                    <td>Total</td>
                    <td class="text-right">{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Bayar</td>
                    <td class="text-right">{{ number_format($order->paid_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Kembali</td>
                    <td class="text-right">{{ number_format($order->change_amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        <div class="separator"></div>
        <div class="footer text-center">
            <p>Terima Kasih!</p>
            <p>Silakan datang kembali.</p>
        </div>
    </div>

</body>
</html>
