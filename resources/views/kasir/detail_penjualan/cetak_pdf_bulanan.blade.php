<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #2d3748;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            color: #4a5568;
        }

        .periode {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 10px;
        }

        .card {
            flex: 1;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            background-color: #f8f9fa;
        }

        .card h3 {
            margin: 0 0 5px 0;
            font-size: 12px;
            color: #4a5568;
        }

        .card .value {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .section-title {
            background-color: #e2e8f0;
            padding: 8px;
            margin: 20px 0 10px 0;
            font-weight: bold;
            border-left: 4px solid #4a5568;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .truncate {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN PENJUALAN BULANAN</h1>
        <h2> {{ env('APP_COMPANY') }}</h2>
    </div>

    <div class="periode">
        Periode: {{ $namaBulan }}
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="summary-cards">
        <div class="card">
            <h3>Total Transaksi</h3>
            <div class="value">{{ $totalPenjualan }}</div>
        </div>
        <div class="card">
            <h3>Total Pendapatan</h3>
            <div class="value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
        <div class="card">
            <h3>Total Produk Terjual</h3>
            <div class="value">{{ $totalProdukTerjual }}</div>
        </div>
    </div>

    {{-- 10 Produk Terlaris --}}
    @if(count($produkTerjual) > 0)
    <div class="section-title">10 PRODUK TERLARIS</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th class="text-center">Terjual</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produkTerjual as $index => $produk)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $produk['nama'] }}</td>
                <td class="text-center">{{ $produk['jumlah'] }}</td>
                <td class="text-right">Rp {{ number_format($produk['subtotal'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Detail Transaksi --}}
    <div class="section-title">DETAIL TRANSAKSI</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Invoice</th>
                <th>Pelanggan</th>
                <th class="text-center">Items</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penjualan as $index => $row)
            @php
            $jumlahItems = $row->detailPenjualan->sum('JumlahProduk');
            $produkNames = $row->detailPenjualan->pluck('NamaProduk')->filter()->join(', ');
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') }}</td>
                <td>{{ $row->PenjualanID }}</td>
                <td>{{ $row->NamaPelanggan ?? 'Non Member' }}</td>
                <td class="text-center">{{ $jumlahItems }}</td>
                <td class="text-right">Rp {{ number_format($row->TotalHarga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data transaksi untuk bulan ini</td>
            </tr>
            @endforelse
        </tbody>
        @if($penjualan->count() > 0)
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td colspan="4" class="text-right">TOTAL:</td>
                <td class="text-center">{{ $totalProdukTerjual }}</td>
                <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        <p>Laporan ini dicetak secara otomatis pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        <p>&copy; {{ date('Y') }} Toko Retail - All rights reserved</p>
    </div>
</body>

</html>