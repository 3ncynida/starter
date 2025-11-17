<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Pelanggan') }}
            </h2>
            <a href="{{ route('pelanggan.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                {{ __('+ Tambah Pelanggan') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('pelanggan.index') }}" class="flex flex-wrap gap-4 items-end">
                <!-- Search Input -->
                <div class="flex-1 min-w-64">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Pelanggan</label>
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Nama, telepon, atau alamat..." 
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                </div>

                <!-- Member Status Filter -->
                <div class="min-w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Member</label>
                    <select name="member_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Status</option>
                        <option value="active" @selected(request('member_status') === 'active')>Member Aktif</option>
                        <option value="inactive" @selected(request('member_status') === 'inactive')>Bukan/Kadaluarsa</option>
                    </select>
                </div>

                <!-- Search Button -->
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                    Cari
                </button>

                @if(request('search') || request('member_status'))
                    <a href="{{ route('pelanggan.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Member List Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">No</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Telepon</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Alamat</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status Member</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Berlaku Hingga</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($pelanggan as $member)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                    {{ $pelanggan->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                    {{ $member->NamaPelanggan }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $member->NomorTelepon ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span class="truncate" title="{{ $member->Alamat }}">
                                        {{ $member->Alamat ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($member->is_member && $member->member_expired && \Carbon\Carbon::parse($member->member_expired)->isFuture())
                                        <form action="{{ route('pelanggan.update-status', $member->PelangganID) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 hover:bg-green-200 transition-colors border border-green-200"
                                                onclick="confirmStatusChange(event, '{{ $member->NamaPelanggan }}', 'Aktif')">
                                                <span class="w-2 h-2 rounded-full bg-green-500 mr-1"></span>
                                                Aktif
                                            </button>
                                        </form>
                                    @elseif($member->is_member)
                                        <form action="{{ route('pelanggan.update-status', $member->PelangganID) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 hover:bg-yellow-200 transition-colors border border-yellow-200"
                                                onclick="confirmStatusChange(event, '{{ $member->NamaPelanggan }}', 'Kadaluarsa')">
                                                <span class="w-2 h-2 rounded-full bg-yellow-500 mr-1"></span>
                                                Kadaluarsa
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('pelanggan.update-status', $member->PelangganID) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 hover:bg-gray-200 transition-colors border border-gray-200"
                                                onclick="confirmStatusChange(event, '{{ $member->NamaPelanggan }}', 'Nonaktif')">
                                                <span class="w-2 h-2 rounded-full bg-gray-500 mr-1"></span>
                                                Nonaktif
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($member->member_expired)
                                        {{ \Carbon\Carbon::parse($member->member_expired)->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex justify-center gap-2">
                                        <!-- Tombol Edit -->
                                        
                                        <a href="{{ route('pelanggan.edit', $member->PelangganID) }}">
                                            <button
                                                    class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white rounded-md text-xs font-medium hover:bg-blue-700 transition-colors shadow-sm min-w-[60px]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                                Edit
                                            </button>
                                        </a>
                                        
                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('pelanggan.destroy', $member->PelangganID) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center px-3 py-2 bg-red-600 text-white rounded-md text-xs font-medium hover:bg-red-700 transition-colors shadow-sm min-w-[60px]"
                                                    onclick="confirmDelete(event, '{{ $member->NamaPelanggan }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    Tidak ada pelanggan ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination with proper link generation -->
            @if($pelanggan->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-gray-600">
                            Menampilkan <span class="font-semibold">{{ $pelanggan->firstItem() }}</span> hingga <span class="font-semibold">{{ $pelanggan->lastItem() }}</span> dari <span class="font-semibold">{{ $pelanggan->total() }}</span> pelanggan
                        </div>
                        <div class="flex gap-2">
                            {{ $pelanggan->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <style>
    /* Memastikan tombol memiliki ukuran yang konsisten */
    .action-btn {
        min-width: 60px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    /* Shadow dan border untuk tombol status */
    .status-btn {
        border: 1px solid;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
</style>
</x-app-layout>