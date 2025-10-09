<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @can(['manage pelanggan', 'manage produk'])
            {{-- Pengaturan Diskon --}}
            <div class="mb-6 bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengaturan Diskon Member</h3>
                
                @if(session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif
                <form action="{{ route('settings.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <label for="diskon_member" class="block text-sm font-medium text-gray-700">
                                Diskon untuk Member (%)
                            </label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="number" 
                                       step="0.01" 
                                       name="diskon_member" 
                                       id="diskon_member"
                                       value="{{ old('diskon_member', $diskon ?? 0) }}"
                                       class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="0">
                                <span class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                    %
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Masukkan angka 0–100 (contoh: 10 untuk 10%)</p>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            @endcan

            {{-- Tombol Tambah --}}
            <div class="mb-4">
                <a href="{{ route('pelanggan.create') }}"
                   class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">
                    + Tambah Pelanggan
                </a>
            </div>

            <!-- Tabel --><div class="bg-white shadow-sm rounded-lg">
    <x-table class="w-full">
        <x-slot name="head">
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
        </x-slot>

        @forelse($pelanggan as $item)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                <td class="px-6 py-4">{{ $item->NamaPelanggan }}</td>
                <td class="px-6 py-4">{{ $item->Alamat }}</td>
                <td class="px-6 py-4">{{ $item->NomorTelepon }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('pelanggan.edit', $item->PelangganID) }}" class="text-blue-600 hover:text-blue-900">Edit</a> ||

                    <form action="{{ route('pelanggan.destroy', $item->PelangganID) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                    Tidak ada Pelanggan.
                </td>
            </tr>
        @endforelse
    </x-table>
</div>

        </div>
    </div>
</x-app-layout>
