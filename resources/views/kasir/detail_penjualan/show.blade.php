<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Penjualan #') . $penjualan->PenjualanID }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm no-print">
                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <h3 class="mb-2 text-lg font-semibold text-gray-800">Informasi Penjualan</h3>
                            <p class="text-gray-600">
                                <span class="font-medium">Tanggal:</span>
                                {{ \Carbon\Carbon::parse($penjualan->TanggalPenjualan)->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-gray-600">
                                <span class="font-medium">Pelanggan:</span>
                                {{ $penjualan->pelanggan->NamaPelanggan ?? 'Non Member' }}
                            </p>
                        </div>
                        <div class="text-left md:text-right">
                            <p class="text-gray-600">
                                <span class="font-medium">Total:</span>
                                <span class="text-lg font-bold text-gray-900">
                                    Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}
                                </span>
                            </p>
                            @if($penjualan->Diskon > 0)
                                <p class="text-gray-600">
                                    <span class="font-medium">Diskon:</span>
                                    <span class="text-green-600">- Rp {{ number_format($penjualan->Diskon, 0, ',', '.') }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800">Detail Produk</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="sticky top-0 bg-slate-900 text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Harga</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($details as $index => $detail)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $detail->produk->NamaProduk }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp {{ number_format($detail->produk->Harga, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $detail->JumlahProduk }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">Rp {{ number_format($detail->Subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50">
                                    <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Subtotal</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($details->sum('Subtotal'), 0, ',', '.') }}</td>
                                </tr>
                                @if($penjualan->Diskon > 0)
                                    <tr class="bg-gray-50">
                                        <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Diskon Member</td>
                                        <td class="px-6 py-4 text-sm font-medium text-green-600">- Rp {{ number_format($penjualan->Diskon, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr class="bg-gray-50">
                                    <td colspan="4" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Total</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="flex items-center justify-between bg-gray-50 px-6 py-4">
                    <a href="{{ route('detail-penjualan.index') }}"
                       class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700 no-print">
                        Kembali
                    </a>
                    <button onclick="window.print()"
                            class="rounded-md border border-gray-300 px-4 py-2 text-xs font-medium text-gray-800 hover:bg-gray-100 no-print">
                        Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Screen default: hide print-only container */
        .print-only { display: none; }

        /* Print styles */
        @media print {
          /* Hide global app UI */
          header, nav, .no-print, .backdrop, .shadow-sm { display: none !important; }
          body { background: #fff !important; }

          /* Paper size for 80mm thermal roll */
          @page { size: 80mm auto; margin: 0; }

          /* Receipt container */
          #receipt {
            display: block !important;
            width: 80mm;
            padding: 12px;
            margin: 0;
            color: #000;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 12px;
            line-height: 1.45;
          }
          #receipt hr {
            border: 0;
            border-top: 1px dashed #000;
            margin: 6px 0;
          }
          #receipt .center { text-align: center; }
          #receipt .row { display: flex; align-items: baseline; justify-content: space-between; gap: 8px; }
          #receipt .muted { opacity: .9; }
          #receipt .bold { font-weight: 700; }
          #receipt .small { font-size: 11px; }
        }
    </style>

    <div id="receipt" class="print-only">
      <div class="center bold" style="text-transform: uppercase;">
        {{ config('app.name', 'Toko') }}
      </div>
      <div class="center small muted">
        {{ config('app.company_address', 'Alamat Toko') }}
      </div>
      <div class="center small muted">
        {{ config('app.company_phone', '-') }}
      </div>

      <hr>

      <div class="row small">
        <span>Nota</span>
        <span>#{{ $penjualan->PenjualanID }}</span>
      </div>
      <div class="row small">
        <span>Tanggal</span>
        <span>{{ \Carbon\Carbon::parse($penjualan->TanggalPenjualan)->format('d/m/Y H:i') }}</span>
      </div>
      <div class="row small">
        <span>Kasir</span>
        <span>{{ auth()->user()->name ?? '-' }}</span>
      </div>

      <hr>

      @foreach($details as $d)
        <div class="bold">{{ $d->produk->NamaProduk }}</div>
        <div class="row small">
          <span>{{ $d->JumlahProduk }} x {{ number_format($d->produk->Harga, 0, ',', '.') }}</span>
          <span>{{ number_format($d->Subtotal, 0, ',', '.') }}</span>
        </div>
      @endforeach

      <hr>

      <div class="row small">
        <span>Total Item</span>
        <span>{{ $details->sum('JumlahProduk') }}</span>
      </div>
      @if(($penjualan->Diskon ?? 0) > 0)
        <div class="row small">
          <span>Total Disc.</span>
          <span>-{{ number_format($penjualan->Diskon, 0, ',', '.') }}</span>
        </div>
      @endif
      <div class="row bold">
        <span>Total Belanja</span>
        <span>Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}</span>
      </div>

      @if(isset($penjualan->Tunai))
        <div class="row small">
          <span>Tunai</span>
          <span>Rp {{ number_format($penjualan->Tunai, 0, ',', '.') }}</span>
        </div>
      @endif
      @if(isset($penjualan->Kembalian))
        <div class="row small">
          <span>Kembalian</span>
          <span>Rp {{ number_format($penjualan->Kembalian, 0, ',', '.') }}</span>
        </div>
      @endif

      <hr>

      <div class="center small muted">
        Terima kasih telah berbelanja
      </div>
    </div>
</x-app-layout>
