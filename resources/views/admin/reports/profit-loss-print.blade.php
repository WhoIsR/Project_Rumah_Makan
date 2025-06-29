@extends('layouts.print')

@section('title', 'Laporan Laba Rugi Periode ' . $startDate . ' - ' . $endDate)

@section('content')
    <h2 class="report-title">LAPORAN LABA RUGI</h2>
    <p style="text-align: center; margin-top: -15px; margin-bottom: 20px;">
        Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMM Y') }} &mdash; {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMM Y') }}
    </p>

    <table class="summary-table">
        <tr style="background-color: #e6f7ff;">
            <td style="width: 70%;">Total Pendapatan (Penjualan)</td>
            <td class="text-right font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
        </tr>
        <tr style="background-color: #fff1f0;">
            <td>Total Pengeluaran (Pembelian)</td>
            <td class="text-right font-bold">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
        </tr>
        <tr style="border-top: 2px solid #333; {{ $grossProfit >= 0 ? 'background-color: #e6fffa;' : 'background-color: #fffbe6;' }}">
            <td class="font-bold">ESTIMASI LABA KOTOR</td>
            <td class="text-right font-bold">Rp {{ number_format($grossProfit, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div style="margin-top: 40px; font-size: 10pt; text-align: center; color: #555;">
        <p>* Laporan ini hanya menghitung laba kotor dari penjualan dikurangi pembelian bahan baku.</p>
        <p>Biaya operasional lain seperti gaji, listrik, dan sewa tidak termasuk.</p>
    </div>
@endsection
