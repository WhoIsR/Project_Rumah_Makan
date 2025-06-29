@extends('layouts.print')

@section('title', 'Laporan Penjualan Rinci Periode ' . $startDate . ' - ' . $endDate)

@section('content')
    <h2 class="report-title">LAPORAN RINCI PENJUALAN</h2>
    <p style="text-align: center; margin-top: -15px; margin-bottom: 20px;">
        Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMM Y') }} &mdash; {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMM Y') }}
    </p>

    <table class="items-table">
        <thead>
            <tr>
                <th style="text-align: left;">Tanggal & ID</th>
                <th style="text-align: left;">Kasir & Tipe</th>
                <th style="text-align: left;">List Menu</th>
                <th class="text-right">Harga Satuan</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                @if($order->items->isNotEmpty())
                    @foreach($order->items as $index => $item)
                        <tr>
                            @if($loop->first)
                                <td rowspan="{{ $order->items->count() }}">
                                    {{ $order->created_at->isoFormat('D MMM Y, HH:mm') }} <br>
                                    <span style="font-size: 9pt; font-weight: normal;">#{{ $order->id }}</span>
                                </td>
                                <td rowspan="{{ $order->items->count() }}">
                                    {{ $order->user->name ?? 'N/A' }} <br>
                                    <span style="font-size: 9pt; font-weight: normal;">{{ str_replace('_', ' ', Str::title($order->order_type)) }}</span>
                                </td>
                            @endif
                            <td>{{ $item->menuItem->name ?? 'N/A' }} ({{ $item->quantity }}x)</td>
                            <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @else
                    {{-- Menampilkan baris transaksi bahkan jika tidak ada detail --}}
                    <tr>
                        <td>
                            {{ $order->created_at->isoFormat('D MMM Y, HH:mm') }} <br>
                            <span style="font-size: 9pt; font-weight: normal;">#{{ $order->id }}</span>
                        </td>
                        <td>
                            {{ $order->user->name ?? 'N/A' }} <br>
                            <span style="font-size: 9pt; font-weight: normal;">{{ str_replace('_', ' ', Str::title($order->order_type)) }}</span>
                        </td>
                        <td colspan="3" style="text-align: center; font-style: italic;">Tidak ada detail item</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data penjualan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right font-bold">TOTAL PENDAPATAN</td>
                <td class="text-right font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
@endsection