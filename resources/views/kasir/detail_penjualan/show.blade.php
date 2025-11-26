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
                {{ \Carbon\Carbon::parse($penjualan->created_at)->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB
              </p>
              <p class="text-gray-600">
                <span class="font-medium">Pelanggan:</span>
                @php $isMember = !empty($penjualan->PelangganID); @endphp
                <span class="ml-1">{{ $penjualan->NamaPelanggan ?? 'Non Member' }}</span>
                @if($isMember)
                <span class="ml-2 inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700">Member</span>
                @else
                <span class="ml-2 inline-flex items-center rounded-full border border-gray-200 bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Non Member</span>
                @endif
              </p>
            </div>
            <div class="text-left md:text-right">
              <p class="text-gray-600">
                <span class="font-medium">Total:</span>
                <span class="text-lg font-bold text-gray-900">
                  Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}
                </span>
              </p>
              @php
              $__subtotal = $details->sum('Subtotal');
              $__diskon = $penjualan->Diskon ?? 0;
              if ((int)($__diskon) === 0 && $__subtotal > 0 && isset($penjualan->TotalHarga)) {
              $calc = $__subtotal - ($penjualan->TotalHarga ?? $__subtotal);
              if ($calc > 0) { $__diskon = $calc; }
              }
              $__discPct = $__subtotal > 0 ? round((($__diskon / $__subtotal) * 100)) : 0;
              @endphp
              @if($__diskon > 0)
              <p class="text-gray-600">
                <span class="font-medium">Diskon:</span>
                <span class="text-green-600">
                  - Rp {{ number_format($__diskon, 0, ',', '.') }}
                  @if($__discPct > 0)
                  <span class="ml-1 text-xs text-emerald-700">({{ $__discPct }}%)</span>
                  @endif
                </span>
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
                @php
                $diskonPromoPersen = $detail->DiskonPromoPersen ?? 0;
                $diskonPromoNominal = $detail->DiskonPromoNominal ?? 0;
                $hargaAsli = $detail->Harga;
                $hargaSetelahPromo = $hargaAsli - $diskonPromoNominal;
                $diskonPromoNominalPerUnit = $detail->DiskonPromoNominal ?? 0;
                @endphp
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $detail->NamaProduk }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    @if($diskonPromoNominalPerUnit > 0)
                    <span class="line-through text-gray-400">
                      Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                    </span>
                    <span class="ml-2 font-semibold text-green-600">
                      Rp {{ number_format($hargaSetelahPromo, 0, ',', '.') }}
                    </span>
                    <span class="ml-1 text-xs text-emerald-600">
                      (Diskon {{ number_format($diskonPromoPersen, 0, ',', '.') }}%)
                    </span>
                    @else
                    Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                    @endif
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $detail->JumlahProduk }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">Rp {{ number_format($detail->Subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot class="bg-gray-50">
                @php
                $subtotal = $details->sum('Subtotal');
                $diskon = $__diskon;
                $discPct = $subtotal > 0 ? round(($diskon / $subtotal) * 100) : 0;

                // Ambil data diskon yang sudah tersimpan saat transaksi
                $totalDiskonPromo = $details->sum(function($d) {
                return ($d->DiskonPromoNominal ?? 0) * $d->JumlahProduk;
                });

                $diskonMember = $penjualan->Diskon ?? 0;
                $totalBayar = $penjualan->TotalHarga;
                $tunai = $penjualan->UangTunai ?? 0;
                $kembalian = $penjualan->Kembalian ?? 0;
                @endphp
                <tr>
                  <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Subtotal</td>
                  <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>

                @if($diskon > 0)
                <tr class="bg-green-50">
                  <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-emerald-800">
                    Diskon Member <span class="ml-1 text-xs">({{ $discPct }}%)</span>
                  </td>
                  <td class="px-6 py-4 text-sm font-semibold text-emerald-700">- Rp {{ number_format($diskon, 0, ',', '.') }}</td>
                </tr>
                @endif

                <tr class="bg-gray-100 border-t border-gray-300">
                  <td colspan="4" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Total</td>
                  <td class="px-6 py-4 text-sm font-bold text-gray-900">Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}</td>
                </tr>

                @if($tunai > 0)
                <tr>
                  <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-800">Tunai Diterima</td>
                  <td class="px-6 py-3 text-sm font-semibold text-gray-900">
                    Rp {{ number_format($tunai, 0, ',', '.') }}
                  </td>
                </tr>
                @endif

                @if(isset($penjualan->Kembalian))
                <tr>
                  <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-800">Kembalian</td>
                  <td class="px-6 py-3 text-sm font-semibold text-gray-900">
                    Rp {{ number_format($penjualan->Kembalian, 0, ',', '.') }}
                  </td>
                </tr>
                @endif
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
    .print-only {
      display: none;
    }

    /* Print styles */
    @media print {

      header,
      nav,
      .no-print,
      .backdrop,
      .shadow-sm {
        display: none !important;
      }

      body {
        background: #fff !important;
      }

      /* Tetap ukurannya 75mm tapi tinggi fleksibel */
      @page {
        size: 75mm auto;
        margin: 0;
      }

      html,
      body {
        width: 75mm;
        margin: 0 auto;
        /* supaya muncul di tengah preview */
        padding: 0;
        /* Batasi tinggi maksimum dan beri scroll jika perlu */
        max-height: 200mm; /* Sesuaikan nilai ini sesuai kebutuhan */
        overflow: hidden;
      }

      #receipt {
        display: block !important;
        width: 75mm;
        padding: 6px 8px;
        margin: 0 auto;
        color: #000;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 11px;
        line-height: 1.35;
        box-sizing: border-box;
        /* Batasi tinggi maksimum struk */
        max-height: 190mm; /* Kurangi dari max-height body untuk margin */
        overflow: auto; /* Biarkan discroll jika konten terlalu panjang */
      }

      #receipt hr {
        border: 0;
        border-top: 1px dashed #000;
        margin: 6px 0;
      }

      #receipt .center {
        text-align: center;
      }

      #receipt .row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 8px;
      }

      #receipt .muted {
        opacity: .9;
      }

      #receipt .bold {
        font-weight: 700;
      }

      #receipt .small {
        font-size: 10px;
      }
    }
</style>

  <div id="receipt" class="print-only">
    <div class="center bold" style="text-transform: uppercase;">
      {{ config('app.company', 'Toko') }}
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
      <span>{{ \Carbon\Carbon::parse($penjualan->created_at)->format('d/m/Y H:i') }}</span>
    </div>
    <div class="row small">
      <span>Kasir</span>
      <span>{{ $penjualan->user->name ?? '-' }}</span>
    </div>

    <hr>

    @foreach($details as $d)
    @php
    $hargaAsli = $d->Harga;
    $diskonPromoNominal = $d->DiskonPromoNominal ?? 0;
    $hargaSetelahDiskon = $hargaAsli - $diskonPromoNominal;
    @endphp


    <div class="bold">{{ $d->NamaProduk }}</div>
    <div class="row small">
      <span>{{ $d->JumlahProduk }} x {{ number_format($hargaAsli, 0, ',', '.') }}</span>
      <span>{{ number_format($d->Subtotal, 0, ',', '.') }}</span>
    </div>

    @if($diskonPromoNominal > 0)
    <div class="row small muted">
      <span>Diskon Promo</span>
      <span>-{{ number_format($diskonPromoNominal * $d->JumlahProduk, 0, ',', '.') }}</span>
    </div>
    @endif
    @endforeach

    <hr>

    <div class="row small">
      <span>Total Item</span>
      <span>{{ $details->sum('JumlahProduk') }}</span>
    </div>
    @if((($__diskon ?? 0) > 0))
    <div class="row small">
      <span>Diskon Member.</span>
      <span>-{{ number_format($__diskon, 0, ',', '.') }}</span>
    </div>
    @endif

    @php
    $totalDiskonPromo = $details->sum(function($d) {
    return ($d->DiskonPromoNominal ?? 0) * $d->JumlahProduk;
    });
    @endphp

    @if($totalDiskonPromo > 0)
    <div class="row small">
      <span>Promo Produk</span>
      <span>-{{ number_format($totalDiskonPromo, 0, ',', '.') }}</span>
    </div>
    @endif

    <div class="row bold">
      <span>Total Belanja</span>
      <span>Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}</span>
    </div>

    @if(isset($penjualan->UangTunai))
    <div class="row small">
      <span>Tunai</span>
      <span>Rp {{ number_format($penjualan->UangTunai, 0, ',', '.') }}</span>
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