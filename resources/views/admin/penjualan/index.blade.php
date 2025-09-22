<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ route('penjualan.create') }}"
               class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">
                + Tambah Penjualan
            </a>
        </div>

        <div class="bg-white shadow-sm rounded-lg">
            <x-table class="w-full">
                <x-slot name="head">
                    <th class="px-6 py-3 text-left">No</th>
                    <th class="px-6 py-3 text-left">Tanggal</th>
                    <th class="px-6 py-3 text-left">Pelanggan</th>
                    <th class="px-6 py-3 text-left">Total Harga</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </x-slot>

                @forelse($penjualan as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">{{ $item->TanggalPenjualan }}</td>
                        <td class="px-6 py-4">{{ $item->pelanggan->NamaPelanggan ?? '-' }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($item->TotalHarga, 2, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('penjualan.edit', $item->PenjualanID) }}"
                               class="text-blue-600 hover:text-blue-900">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data penjualan.
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </div>
    </div>
</x-app-layout>
