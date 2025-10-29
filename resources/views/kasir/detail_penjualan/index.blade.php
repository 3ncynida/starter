<x-app-layout> 
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Penjualan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-6">

                    {{-- ✅ Alert sukses --}}
                    @if (session('success'))
                        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- ✅ Header + Pencarian --}}
                    <div class="mb-4 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold text-gray-800">Daftar Transaksi</h3>

                        <form action="{{ route('detail-penjualan.index') }}" method="GET" class="relative w-full sm:max-w-xs">
                            <input
                                id="search"
                                name="search"
                                type="text"
                                placeholder="Cari pelanggan atau produk..."
                                value="{{ request('search') }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm placeholder:text-gray-400 focus:border-gray-600 focus:outline-none"
                            />
                            <button type="submit" class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m21 21-4.35-4.35M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                    {{-- ✅ Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse">
                            <thead class="sticky top-0 bg-slate-900 text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Pelanggan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Subtotal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
<tbody id="table-body" class="divide-y divide-gray-200 bg-white">
    @forelse ($penjualan as $index => $row)
        @php
            // kumpulkan nama produk untuk ditampilkan (dipisah koma)
            $produkNames = $row->detailPenjualan->pluck('NamaProduk')->filter();
            $produkText = $produkNames->join(', ');

            // jumlah item & subtotal dari relation detailPenjualan
            $totalJumlah = $row->detailPenjualan->sum('JumlahProduk');
            $totalSubtotal = $row->detailPenjualan->sum('Subtotal');

            // nomor baris yang benar saat paginated
            $no = $penjualan->firstItem() + $index;
        @endphp

        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $no }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                {{ \Carbon\Carbon::parse($row->created_at ?? $row->TanggalPenjualan)
                    ->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                {{ $row->NamaPelanggan ?? 'Non Member' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                {{-- tampilkan daftar produk (ringkas) --}}
                <div class="truncate max-w-[28rem]">{{ $produkText }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                {{ $totalJumlah }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                Rp {{ number_format($totalSubtotal, 0, ',', '.') }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
                <a href="{{ route('detail-penjualan.show', $row->PenjualanID) }}"
                   class="inline-flex items-center gap-2 rounded-md border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-800 hover:bg-gray-100">
                    {{-- icon eye --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    Detail
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">
                Belum ada data penjualan
            </td>
        </tr>
    @endforelse
</tbody>

                        </table>
                    </div>

                    {{-- ✅ Pagination --}}
                    <div class="mt-4 px-4">
                        {{ $penjualan->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- ✅ Script pencarian otomatis & shortcut --}}
<script>
    let timeout;
    const input = document.getElementById('search');

    input.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => this.closest('form').submit(), 500);
    });

    window.addEventListener('keydown', (e) => {
        if (e.key === '/' && document.activeElement !== input) {
            e.preventDefault();
            input.focus();
        } else if (e.key === 'Escape' && document.activeElement === input) {
            input.value = '';
            input.closest('form').submit();
        }
    });
</script>
