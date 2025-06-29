@extends('layouts.print')

@section('title', 'Laporan Pembelian Periode ' . $startDate . ' - ' . $endDate)

@section('content')
    <h2 class="report-title">LAPORAN PEMBELIAN BAHAN BAKU</h2>
    <p style="text-align: center; margin-top: -15px; margin-bottom: 20px;">
        Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMM Y') }} &mdash; {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMM Y') }}
    </p>

    <table class="items-table">
        <thead>
            <tr>
                <th style="text-align: left;">Tanggal & Faktur</th>
                <th style="text-align: left;">Supplier & Pedagang</th>
                <th style="text-align: left;">Bahan Baku</th>
                <th class="text-right">Harga Satuan</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($purchases as $purchase)
                @if($purchase->details->isNotEmpty())
                    @foreach($purchase->details as $index => $detail)
                        <tr>
                            @if($loop->first)
                                <td rowspan="{{ $purchase->details->count() }}">
                                    {{ \Carbon\Carbon::parse($purchase->transaction_date)->isoFormat('D MMM Y') }} <br>
                                    <span style="font-size: 9pt; font-weight: normal;">{{ $purchase->invoice_number }}</span>
                                </td>
                                <td rowspan="{{ $purchase->details->count() }}">
                                    {{ $purchase->supplier->name }} <br>
                                    <span style="font-size: 9pt; font-weight: normal;">({{ $purchase->supplier->contact_person }})</span>
                                </td>
                            @endif
                            <td>{{ $detail->ingredient->name ?? 'N/A' }} ({{ rtrim(rtrim(number_format($detail->quantity, 2, ',', '.'), '0'), ',') }} {{ $detail->unit }})</td>
                            <td class="text-right">Rp {{ number_format($detail->price_per_unit, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @else
                    {{-- Menampilkan baris transaksi bahkan jika tidak ada detail --}}
                    <tr>
                        <td>
                            {{ \Carbon\Carbon::parse($purchase->transaction_date)->isoFormat('D MMM Y') }} <br>
                            <span style="font-size: 9pt; font-weight: normal;">{{ $purchase->invoice_number }}</span>
                        </td>
                        <td>
                            {{ $purchase->supplier->name }} <br>
                            <span style="font-size: 9pt; font-weight: normal;">({{ $purchase->supplier->contact_person }})</span>
                        </td>
                        <td colspan="3" style="text-align: center; font-style: italic;">Tidak ada detail bahan baku</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data pembelian pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right font-bold">TOTAL PENGELUARAN</td>
                <td class="text-right font-bold">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
@endsection