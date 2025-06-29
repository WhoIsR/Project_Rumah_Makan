<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laporan Warung Kita')</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 11pt; color: #333; }
        .container { width: 95%; margin: 0 auto; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { font-size: 22pt; font-weight: bold; margin: 0; }
        .header p { font-size: 11pt; margin: 5px 0 0 0; }
        .report-title { font-size: 14pt; text-align: center; margin: 30px 0 20px 0; font-weight: bold; }
        .items-table { width: 100%; border-collapse: collapse; font-size: 11pt; }
        .items-table th, .items-table td { border: 1px solid #333; padding: 6px; }
        .items-table thead { background-color: #f2f2f2; }
        .summary-table { width: 100%; font-size: 14pt; margin-top: 30px; }
        .summary-table td { padding: 8px; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        /* Style baru untuk detail item */
        .main-row { font-weight: bold; background-color: #f9fafb; }
        .detail-row td { padding: 0 !important; border: none; }
        .detail-table { width: 100%; font-size: 10pt; background-color: #ffffff; }
        .detail-table td { border-style: dashed; border-width: 1px 0; border-color: #e5e7eb; padding: 4px 8px 4px 20px; }

        @media print {
            @page { size: A4; margin: 1cm; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print(); setTimeout(window.close, 0);">
    <div class="container">
        <div class="header">
            <h1>Warung Kita</h1>
            <p>Jalan Kenangan No. 123, Kota Bahagia - 12345</p>
        </div>
        @yield('content')
    </div>
</body>
</html>