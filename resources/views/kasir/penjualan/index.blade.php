<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex gap-6">
            <!-- ======================== MAIN PRODUK ======================== -->
            <div class="flex-1">
                <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <h1 class="text-3xl font-bold text-gray-900">Cari Produk</h1>
                </div>

                <!-- Search Produk -->
                <div class="mb-5">
                    <label for="product-search" class="sr-only">Cari produk</label>
                    <div class="relative">
                        <input
                            id="product-search"
                            type="search"
                            placeholder="Cari produk (tekan / untuk fokus)"
                            class="w-full rounded-xl border border-gray-300 bg-white/70 px-4 py-2.5 pr-10 text-sm outline-none focus:border-black focus:ring-1 focus:ring-black"
                            aria-label="Cari produk"
                        />
                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>


                <!-- Grid Produk -->
<h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Produk</h3> 
<div id="productGrid" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5">
    @foreach($products as $product)
        <div class="product-card" data-name="{{ strtolower($product->NamaProduk) }}">
            <div class="bg-[#0f172a] rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow relative">

                {{-- 🔖 Label Promo --}}
                @if($product->harga_aktif < $product->Harga)
                    @php
                        $persenDiskon = round((($product->Harga - $product->harga_aktif) / $product->Harga) * 100);
                    @endphp
                    <span class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md z-10">
                        Diskon -{{ $persenDiskon }}%
                    </span>
                @endif

                {{-- 🖼️ Gambar produk --}}
                <div class="aspect-square relative">
                    <img class="w-full h-full object-cover {{ $product->Stok < 1 ? 'opacity-50' : '' }}" 
                         src="{{ asset('storage/' . $product->Gambar) }}" 
                         alt="{{ $product->NamaProduk }}" />

                    @if($product->Stok < 1)
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold transform rotate-45">
                                HABIS
                            </span>
                        </div>
                    @endif
                </div>

                {{-- 📦 Detail produk --}}
                <div class="p-3 text-white">
                    <h3 class="text-base font-medium mb-0.5 truncate">{{ $product->NamaProduk }}</h3>
                    <p class="text-xs mb-2 {{ $product->Stok < 1 ? 'text-red-400' : 'text-gray-400' }}">
                        Stok: {{ $product->Stok }} {{ $product->Satuan }}
                    </p>

                    {{-- 💰 Harga --}}
                    @if($product->harga_aktif < $product->Harga)
                        <p class="text-sm line-through text-gray-400">
                            Rp {{ number_format($product->Harga, 0, ',', '.') }}
                        </p>
                        <p class="text-lg font-bold text-red-500">
                            Rp {{ number_format($product->harga_aktif, 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-lg font-bold">
                            Rp {{ number_format($product->Harga, 0, ',', '.') }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- 🛒 Form tambah ke keranjang --}}
            <form action="{{ route('cart.add', $product->ProdukID) }}" method="POST" class="mt-2 flex gap-2">
                @csrf
                <input type="number" 
                       name="qty" 
                       value="1" 
                       min="1" 
                       max="{{ $product->Stok }}"
                       class="w-20 rounded-lg border-gray-300 text-sm focus:ring-black focus:border-black">
                <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ $product->Stok < 1 ? 'disabled' : '' }}>
                    <i class="fas fa-cart-plus mr-1"></i> Tambah
                </button>
            </form>
        </div>
    @endforeach
</div>


                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            </div>

<!-- ======================== SIDEBAR CART ======================== -->
<div class="w-full md:w-80 md:flex-shrink-0">
    <div class="md:sticky md:top-6">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-4 border-b bg-gray-50 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Keranjang Belanja</h3>
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="bg-black text-white text-xs font-bold px-2.5 py-1 rounded-full">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </div>

            <!-- Cart Items -->
            <div class="max-h-96 overflow-y-auto">
                @php $cart = session('cart', []); $total = 0; @endphp
                @if(count($cart) > 0)
                    <ul class="divide-y divide-gray-100">
                        @foreach($cart as $id => $item)
                            @php 
                                $harga = $item['harga'] ?? 0;
                                $qty = $item['qty'] ?? 1;
                                $subtotal = $harga * $qty;
                                $total += $subtotal;
                            @endphp
<li class="bg-white rounded-lg p-3 shadow-sm border relative">
    <form action="{{ route('cart.remove', $id) }}" method="POST" class="absolute top-2 right-2">
        @csrf
        @method('DELETE')
<button type="submit" 
        class="bg-red-500 text-white px-2 py-1 rounded text-xs font-medium hover:bg-red-600 transition">
    Hapus
</button>
    </form>

    <div class="flex justify-between gap-3">
        <div class="flex-1">
            <h4 class="text-sm font-semibold text-gray-900 mb-1">{{ $item['nama'] }}</h4>

<form action="{{ route('cart.updateQty', $id) }}" method="POST" class="flex items-center gap-2 mb-1">
    @csrf
    <input type="number" name="qty" value="{{ $qty }}" min="1"
           class="w-16 h-6 rounded border-gray-300 text-xs focus:ring-black focus:border-black">
<button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-medium hover:bg-blue-700 transition">
    Update
</button>
</form>

            <div class="text-xs text-gray-500">@ Rp {{ number_format($harga, 0, ',', '.') }}</div>
            <div class="text-sm font-bold text-gray-900">
                Rp {{ number_format($subtotal, 0, ',', '.') }}
            </div>
        </div>
    </div>
</li>

                        @endforeach
                    </ul>
                @else
                    <div class="p-8 text-center">
                        <i class="fas fa-shopping-cart text-3xl text-gray-300 mb-3"></i>
                        <p class="text-sm text-gray-600 font-medium">Keranjang kosong</p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            @if(count($cart) > 0)
                <div class="border-t p-4 bg-gray-50 space-y-3">
                    <!-- Pilih Pelanggan -->
                    <form action="{{ route('cart.set-customer') }}" method="POST" class="space-y-2">
                        @csrf
                        <label class="block text-sm font-medium text-gray-700">Pilih Pelanggan</label>
                        <select name="pelanggan_id" class="w-full rounded-lg border-gray-300 text-sm focus:ring-black focus:border-black">
                            <option value="">-- Non Member --</option>
                            @foreach($pelanggan as $p)
                                <option value="{{ $p->PelangganID }}" {{ session('cart_customer') == $p->PelangganID ? 'selected' : '' }}>
                                    {{ $p->NamaPelanggan }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="w-full bg-gray-800 hover:bg-black text-white py-2 rounded-lg text-sm font-medium transition">
                            Simpan Pelanggan
                        </button>
                    </form>

                    <!-- Total dan Diskon -->
                    <div class="border-t pt-3 space-y-2">
                        @if(session('cart_customer'))
                            @php
                                $diskonPersen = App\Models\Setting::get('diskon_member', 0);
                                $diskonNominal = ($total * $diskonPersen) / 100;
                                $totalAkhir = $total - $diskonNominal;
                            @endphp
                            <div class="flex justify-between text-green-600 text-sm">
                                <span>Diskon Member ({{ $diskonPersen }}%)</span>
                                <span>-Rp {{ number_format($diskonNominal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-gray-900 text-lg">
                                <span>Total Akhir</span>
                                <span id="total-display" data-total="{{ $totalAkhir }}">Rp {{ number_format($totalAkhir, 0, ',', '.') }}</span>
                            </div>
                        @else
                            <div class="flex justify-between font-bold text-gray-900 text-lg">
                                <span>Total</span>
                                <span id="total-display" data-total="{{ $total }}">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- ✅ Form Pembayaran & Checkout -->
                    <form id="checkout-form" action="{{ route('cart.checkout') }}" method="POST" class="space-y-3 border-t pt-3">
                        @csrf

                        <div>
                            <label for="uang_tunai" class="block text-sm font-medium text-gray-700">Uang Tunai (Rp)</label>
                            <input type="number"
                                name="UangTunai"
                                id="uang_tunai"
                                class="w-full rounded-lg border-gray-300 text-sm focus:ring-black focus:border-black py-2 px-3"
                                placeholder="Masukkan nominal uang..."
                                min="0" required>
                        </div>
                        <!-- Kembalian -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kembalian</label>
                            <div id="kembalian-display" class="text-lg font-semibold text-green-600">Rp 0</div>
                        </div>

                        <button type="submit" id="checkout-button"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg text-sm font-semibold transition">
                            <i class="fas fa-credit-card mr-1"></i> Checkout
                        </button>
                    </form>

                    <!-- Tombol Kosongkan -->
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-trash mr-1"></i> Kosongkan
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

    @php
    $productsJson = json_encode($allProducts);
    @endphp
        </div>
    </div>


    <style>
        .max-h-96::-webkit-scrollbar { width: 6px; }
        .max-h-96::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
        .max-h-96::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const uangInput = document.getElementById('uang_tunai');
    const kembalianDisplay = document.getElementById('kembalian-display');
    const totalEl = document.getElementById('total-display');
    const checkoutForm = document.getElementById('checkout-form');
    const total = parseFloat(totalEl.dataset.total || 0);

    // Update kembalian saat user mengetik
    uangInput?.addEventListener('input', e => {
        const uang = parseFloat(e.target.value || 0);
        const kembalian = uang - total;
        kembalianDisplay.textContent = 'Rp ' + (kembalian > 0 ? kembalian.toLocaleString('id-ID') : 0);
        kembalianDisplay.classList.toggle('text-red-600', kembalian < 0);
        kembalianDisplay.classList.toggle('text-green-600', kembalian >= 0);
    });

    // 🚨 Cek uang tunai sebelum submit
    checkoutForm?.addEventListener('submit', e => {
        const uang = parseFloat(uangInput.value || 0);
        if (uang < total) {
            e.preventDefault();

            // Tampilkan notifikasi dengan style yang bagus
            Swal.fire({
                icon: 'error',
                title: 'Uang Tunai Kurang!',
                text: 'Nominal uang tidak cukup untuk melakukan pembayaran.',
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Oke, saya perbaiki'
            });
        }
    });
});

 (function () {
            const input = document.getElementById('product-search');
            const productGrid = document.querySelector('.grid');
            const products = @json($allProducts); // Get all products from controller
            
            // Updated template function with proper HTML structure
            const template = product => `
                <div class="product-card" data-name="${product.NamaProduk.toLowerCase()}">
                    <div class="bg-[#0f172a] rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <div class="aspect-square w-full relative">
                            <img class="w-full h-full object-cover ${product.Stok < 1 ? 'opacity-50' : ''}" 
                                 src="/storage/${product.Gambar}" 
                                 alt="${product.NamaProduk}" />
                            ${product.Stok < 1 ? `
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold transform rotate-45">
                                        HABIS
                                    </span>
                                </div>
                            ` : ''}
                        </div>
                        <div class="p-3 text-white">
                            <h3 class="text-base font-medium mb-0.5 truncate">${product.NamaProduk}</h3>
                            <p class="text-xs mb-2 ${product.Stok < 1 ? 'text-red-400' : 'text-gray-400'}">
                                Stok: ${product.Stok} ${product.Satuan}
                            </p>
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-lg font-bold">
                                    Rp ${Number(product.Harga).toLocaleString('id-ID')}
                                </p>
                            </div>
                        </div>
                    </div>
                    <form action="/cart/add/${product.ProdukID}" method="POST" class="mt-2 flex gap-2">
                        @csrf
                        <input type="number" 
                               name="qty" 
                               value="1" 
                               min="1" 
                               max="${product.Stok}"
                               class="w-20 rounded-lg border-gray-300 text-sm focus:ring-black focus:border-black">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                ${product.Stok < 1 ? 'disabled' : ''}>
                            <i class="fas fa-cart-plus mr-1"></i> Tambah
                        </button>
                    </form>
                </div>
            `;

            // Search filter function
            const filter = (term) => {
                const q = (term || '').toLowerCase().trim();
                
                if (!q) {
                    showPaginated();
                    return;
                }

                // Hide pagination when searching
                document.querySelector('.mt-6').style.display = 'none';
                
                // Filter and display matching products
                const filtered = products.filter(product => 
                    product.NamaProduk.toLowerCase().includes(q)
                );

                productGrid.innerHTML = filtered.map(template).join('');
            };

            // Function to show paginated view
            const showPaginated = () => {
                document.querySelector('.mt-6').style.display = 'block';
                location.reload(); // Reload to restore pagination view
            };

            // Search input handler with debounce
            let timeout;
            input?.addEventListener('input', (e) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    const term = e.target.value;
                    if (!term) {
                        showPaginated();
                        return;
                    }
                    filter(term);
                }, 300);
            });

            // Keep existing keyboard shortcuts
            window.addEventListener('keydown', (e) => {
                if (e.key === '/' && document.activeElement !== input) {
                    e.preventDefault();
                    input?.focus();
                }
                if (e.altKey && (e.key.toLowerCase() === 'c')) {
                    const cb = document.getElementById('checkout-button');
                    if (cb) cb.focus();
                }
                if (e.key === 'Escape' && document.activeElement === input) {
                    input.value = '';
                    showPaginated();
                }
            });
        })();   
</script>


</x-app-layout>
