<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-4 flex justify-between items-center">
            <a href="{{ route('penjualan.create') }}"
               class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition duration-150">
                + Tambah Penjualan
            </a>
            
            <!-- Cart Toggle Button -->
            <button id="cartToggle" class="relative px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-150">
                <i class="fas fa-shopping-cart mr-2"></i> Keranjang
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </button>
        </div>

        <!-- Cart Sidebar (Hidden by default) -->
        <div id="cartSidebar" class="fixed inset-y-0 right-0 z-50 w-96 bg-white shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out">
            <div class="flex flex-col h-full">
                <!-- Cart Header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Keranjang Belanja</h3>
                    <button id="closeCart" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Cart Items -->
                <div class="flex-1 overflow-y-auto p-4">
                    @if(session('cart') && count(session('cart')) > 0)
                        <ul class="divide-y divide-gray-200">
                            @php $total = 0; @endphp
                            @foreach(session('cart') as $id => $item)
                                @php $subtotal = $item['harga'] * $item['qty']; $total += $subtotal; @endphp
                                <li class="py-4 hover:bg-gray-50 transition duration-150">
                                    <div class="flex justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $item['nama'] }}</h4>
                                            <div class="mt-1 flex items-center">
                                                <span class="text-sm text-gray-500">Qty: {{ $item['qty'] }}</span>
                                                <span class="mx-2 text-gray-300">|</span>
                                                <span class="text-sm text-gray-500">Rp {{ number_format($item['harga'], 0, ',', '.') }}/item</span>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex flex-col items-end">
                                            <span class="text-sm font-medium text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                            <form action="{{ route('cart.remove', $id) }}" method="POST" class="mt-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-xs">
                                                    <i class="fas fa-trash mr-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="h-full flex flex-col justify-center items-center text-gray-500">
                            <i class="fas fa-shopping-cart text-4xl mb-3 text-gray-300"></i>
                            <p>Keranjang belanja kosong</p>
                            <p class="text-sm mt-1">Tambahkan produk untuk melanjutkan</p>
                        </div>
                    @endif
                </div>

                <!-- Cart Footer -->
                @if(session('cart') && count(session('cart')) > 0)
                    <div class="border-t p-4 bg-gray-50">
                        <div class="flex justify-between mb-3">
                            <span class="font-medium text-gray-900">Total:</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex space-x-2">
                            <form action="{{ route('cart.checkout') }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded text-sm font-medium transition duration-150">
                                    <i class="fas fa-credit-card mr-1"></i> Checkout
                                </button>
                            </form>
                            <form action="{{ route('cart.clear') }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded text-sm font-medium transition duration-150">
                                    <i class="fas fa-trash mr-1"></i> Kosongkan
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Overlay -->
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

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
                        <td class="px-6 py-4">{{ $item->pelanggan->NamaPelanggan ?? '(Non Member)' }}</td>
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
    
    <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Produk</h3>
    <div class="grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">
        @foreach($products as $product)
            <x-card :product="$product" />
        @endforeach
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartToggle = document.getElementById('cartToggle');
            const cartSidebar = document.getElementById('cartSidebar');
            const closeCart = document.getElementById('closeCart');
            const overlay = document.getElementById('overlay');

            function openCart() {
                cartSidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeCartSidebar() {
                cartSidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            cartToggle.addEventListener('click', openCart);
            closeCart.addEventListener('click', closeCartSidebar);
            overlay.addEventListener('click', closeCartSidebar);

            // Close cart with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeCartSidebar();
                }
            });
        });
    </script>
</x-app-layout>