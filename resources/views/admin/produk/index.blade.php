<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Produk') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Tombol Tambah --}}
            <div class="mb-4">
                <a href="{{ route('produk.create') }}"
                   class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">
                    + Tambah Produk
                </a>
            </div>

            {{-- Card Pembungkus Tabel --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <x-table class="w-full">
                    <x-slot name="head">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </x-slot>

                    @forelse($produks as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">{{ $p->NamaProduk }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($p->Harga, 2, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ $p->Stok }}</td>
                            <td class="px-6 py-4 flex space-x-2">
                                <a href="{{ route('produk.edit', $p->ProdukID) }}"
                                   class="text-blue-600 hover:text-blue-900">Edit</a>

                                <form action="{{ route('produk.destroy', $p->ProdukID) }}" method="POST"
                                      onsubmit="return confirm('Yakin mau hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada produk tersedia.
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</x-app-layout>
