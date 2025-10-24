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
                                @php $isMember = isset($penjualan->pelanggan) && !empty($penjualan->pelanggan->NamaPelanggan); @endphp
                                <span class="ml-1">{{ $penjualan->pelanggan->NamaPelanggan ?? 'Non Member' }}</span>
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
                                $__subtotal = $details->sum('subtotal');
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
                                        $namaProduk = $detail->produk_nama ?? '-';
                                        $hargaAsli = $detail->produk_harga_asli ?? 0;
                                        $hargaJual = $detail->produk_harga_jual ?? $hargaAsli;
                                        $diskonPromoPersen = $detail->diskon_promo_persen ?? 0;
                                        $diskonPromoNominal = $detail->diskon_promo_nominal ?? 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $namaProduk }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            @if($diskonPromoNominal > 0)
                                                <span class="line-through text-gray-400">Rp {{ number_format($hargaAsli, 0, ',', '.') }}</span>
                                                <span class="ml-2 font-semibold text-green-600">Rp {{ number_format($hargaJual, 0, ',', '.') }}</span>
                                                <span class="ml-1 text-xs text-emerald-600">(Diskon {{ $diskonPromoPersen }}%)</span>
                                            @else
                                                Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $detail->jumlah ?? $detail->JumlahProduk ?? 0 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">Rp {{ number_format($detail->subtotal ?? $detail->Subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                @php
                                    $subtotal = $details->sum('subtotal');
                                    $diskonMember = $penjualan->Diskon ?? 0;
                                    $totalDiskonPromo = $details->sum(function($d) {
                                        $nominal = $d->diskon_promo_nominal ?? 0;
                                        $qty = $d->jumlah ?? $d->JumlahProduk ?? 0;
                                        return $nominal * $qty;
                                    });
                                    $totalBayar = $penjualan->TotalHarga ?? ($subtotal - $totalDiskonPromo - $diskonMember);
                                    $tunai = $penjualan->Tunai ?? $penjualan->UangTunai ?? 0;
                                    $kembalian = $penjualan->Kembalian ?? max($tunai - $totalBayar, 0);
                                @endphp
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Subtotal</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if($diskonMember > 0)
                                    <tr class="bg-green-50">
                                        <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-emerald-800">
                                            Diskon Member
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-emerald-700">- Rp {{ number_format($diskonMember, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @if($totalDiskonPromo > 0)
                                    <tr class="bg-green-50">
                                        <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-emerald-800">
                                            Diskon Promo
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-emerald-700">- Rp {{ number_format($totalDiskonPromo, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr class="bg-gray-100 border-t border-gray-300">
                                    <td colspan="4" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Total</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
                                </tr>
                                @if($tunai > 0)
                                    <tr>
                                        <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-800">Tunai Diterima</td>
                                        <td class="px-6 py-3 text-sm font-semibold text-gray-900">Rp {{ number_format($tunai, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @if($kembalian > 0)
                                    <tr>
                                        <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-800">Kembalian</td>
                                        <td class="px-6 py-3 text-sm font-semibold text-gray-900">Rp {{ number_format($kembalian, 0, ',', '.') }}</td>
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
        .print-only { display: none; }
        @media print {
            header, nav, .no-print { display: none !important; }
            @page { size: 75mm auto; margin: 0; }
            html, body { width: 75mm; margin: 0 auto; padding: 0; }
            #receipt {
                display: block !important;
                width: 75mm;
                padding: 6px 8px;
                font-size: 11px;
                font-family: monospace;
            }
            #receipt hr { border-top: 1px dashed #000; margin: 6px 0; }
            #receipt .row { display: flex; justify-content: space-between; }
        }
    </style>

    <div id="receipt" class="print-only">
        <div class="center bold">{{ config('app.name', 'Toko') }}</div>
        <div class="center small">{{ config('app.company_address', '-') }}</div>
        <hr>

        <div class="row small">
            <span>Nota #{{ $penjualan->PenjualanID }}</span>
            <span>{{ \Carbon\Carbon::parse($penjualan->created_at)->format('d/m/Y H:i') }}</span>
        </div>
        <div class="row small"><span>Kasir</span><span>{{ auth()->user()->name ?? '-' }}</span></div>
        <hr>

        @foreach($details as $d)
            <div class="bold">{{ $d->produk_nama ?? '-' }}</div>
            <div class="row small">
                <span>{{ $d->jumlah ?? 0 }} x {{ number_format($d->produk_harga_jual ?? $d->produk_harga_asli ?? 0, 0, ',', '.') }}</span>
                <span>{{ number_format($d->subtotal ?? 0, 0, ',', '.') }}</span>
            </div>
            @if(($d->diskon_promo_nominal ?? 0) > 0)
                <div class="row small muted">
                    <span>Diskon {{ $d->diskon_promo_persen ?? 0 }}%</span>
                    <span>-{{ number_format(($d->diskon_promo_nominal ?? 0) * ($d->jumlah ?? 0), 0, ',', '.') }}</span>
                </div>
            @endif
        @endforeach

        <hr>
        <div class="row small"><span>Total Item</span><span>{{ $details->sum('jumlah') }}</span></div>
        @if(($penjualan->Diskon ?? 0) > 0)
            <div class="row small"><span>Diskon Member</span><span>-{{ number_format($penjualan->Diskon, 0, ',', '.') }}</span></div>
        @endif
        <div class="row bold"><span>Total Belanja</span><span>Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}</span></div>
        @if(isset($penjualan->UangTunai))
            <div class="row small"><span>Tunai</span><span>Rp {{ number_format($penjualan->UangTunai, 0, ',', '.') }}</span></div>
        @endif
        @if(isset($penjualan->Kembalian))
            <div class="row small"><span>Kembalian</span><span>Rp {{ number_format($penjualan->Kembalian, 0, ',', '.') }}</span></div>
        @endif
        <hr>
        <div class="center small muted">Terima kasih telah berbelanja</div>
    </div>
</x-app-layout>
