<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Pembelian - {{ $purchase->invoice_number }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            color: #333;
        }
        .container {
            width: 90%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 24pt;
            font-weight: bold;
            margin: 0;
        }
        .header p {
            font-size: 12pt;
            margin: 5px 0 0 0;
        }
        .invoice-title {
            font-size: 16pt;
            text-align: center;
            margin: 40px 0 20px 0;
            text-decoration: underline;
            font-weight: bold;
        }
        .details-table {
            width: 100%;
            font-size: 12pt;
            margin-bottom: 30px;
        }
        .details-table td {
            vertical-align: top;
            padding: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12pt;
        }
        .items-table th, .items-table td {
            border: 1px solid #333;
            padding: 8px;
        }
        .items-table thead {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }
        .signature-table {
            width: 100%;
            margin-top: 80px;
            font-size: 12pt;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
        }
        @media print {
            /* Otomatis jalankan dialog print saat halaman dimuat */
            @page {
                size: A4;
                margin: 1cm;
            }
            body {
                -webkit-print-color-adjust: exact; /* Agar background di thead ikut tercetak */
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="header">
            <h1>Warung Kita</h1>
            <p>Jalan Kenangan No. 123, Kota Bahagia - 12345</p>
        </div>

        <h2 class="invoice-title">FAKTUR PEMBELIAN</h2>

        <table class="details-table">
            <tr>
                <td style="width: 50%;">
                    <strong>Diterima Dari:</strong><br>
                    {{ $purchase->supplier->contact_person }}<br>
                    {{ $purchase->supplier->name }}
                </td>
                <td style="width: 50%;" class="text-right">
                    <strong>No. Faktur:</strong> {{ $purchase->invoice_number ?? '-' }}<br>
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($purchase->transaction_date)->isoFormat('D MMMM YYYY') }}
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="text-align: left;">Bahan Baku</th>
                    <th class="text-right">Jumlah</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchase->details as $detail)
                <tr>
                    <td>{{ $detail->ingredient->name }}</td>
                    <td class="text-right">{{ rtrim(rtrim(number_format($detail->quantity, 2, ',', '.'), '0'), ',') }} {{ $detail->unit }}</td>
                    <td class="text-right">Rp {{ number_format($detail->price_per_unit, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right font-bold">TOTAL</td>
                    <td class="text-right font-bold">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        @if($purchase->notes)
            <div style="margin-top: 30px;">
                <p><strong>Catatan:</strong> {{ $purchase->notes }}</p>
            </div>
        @endif

        <table class="signature-table">
            <tr>
                <td>
                    <p>Diterima Oleh,</p>
                    <br><br><br>
                    <p>(_________________________)</p>
                </td>
                <td>
                    <p>Hormat Kami,</p>
                    <br><br><br>
                    <p>({{ $purchase->supplier->contact_person }})</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>