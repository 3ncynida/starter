<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penjualan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .cart-sidebar {
            transition: all 0.3s ease;
        }
        .cart-item {
            transition: all 0.2s ease;
        }
        .cart-item:hover {
            background-color: #f9fafb;
        }
        .empty-cart {
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-xl font-semibold text-gray-800">Daftar Penjualan</h1>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('penjualan.create') }}" 
                           class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition duration-150">
                            + Tambah Penjualan
                        </a>
                        <!-- Cart Icon with Badge -->
                        <button id="cartToggle" class="relative p-2 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">
                                    {{ count(session('cart')) }}
                                </span>
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Products Grid -->
            <div class="mb-8">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Daftar Produk</h2>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="p-4">
                                <h3 class="font-medium text-gray-900">{{ $product->NamaProduk }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Stok: {{ $product->Stok }}</p>
                                <div class="flex justify-between items-center mt-3">
                                    <span class="text-lg font-semibold text-gray-900">
                                        Rp {{ number_format($product->Harga, 0, ',', '.') }}
                                    </span>
                                    <form action="{{ route('cart.add', $product->ProdukID) }}" method="POST" class="flex items-center">
                                        @csrf
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->Stok }}" 
                                               class="w-16 py-1 px-2 border border-gray-300 rounded text-sm mr-2">
                                        <button type="submit" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition duration-150">
                                            <i class="fas fa-cart-plus mr-1"></i> Add
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Sales Table -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($penjualan as $item)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->TanggalPenjualan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->pelanggan->NamaPelanggan ?? '(Non Member)' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($item->TotalHarga, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('penjualan.edit', $item->PenjualanID) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition duration-150">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada data penjualan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>

        <!-- Cart Sidebar -->
        <div id="cartSidebar" class="fixed inset-y-0 right-0 z-50 w-96 bg-white shadow-xl transform translate-x-full cart-sidebar">
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
                                <li class="py-4 cart-item">
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
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="empty-cart text-center text-gray-500">
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
                                        class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md text-sm font-medium transition duration-150">
                                    <i class="fas fa-credit-card mr-1"></i> Checkout
                                </button>
                            </form>
                            <form action="{{ route('cart.clear') }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md text-sm font-medium transition duration-150">
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
</body>
</html>