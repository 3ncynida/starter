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
                                aria-label="Cari produk" />
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

                                {{-- 🖼️ Gambar produk --}}
                                <div class="aspect-square relative">
                                    {{-- 🖼️ Gambar produk --}}
                                    <img
                                        src="{{ asset('storage/' . $product->Gambar) }}"
                                        alt="{{ $product->NamaProduk }}"
                                        class="w-full h-full object-cover {{ $product->Stok < 1 ? 'opacity-50' : '' }}"
                                        onerror="this.onerror=null;this.src='/produk/default.png'" />

                                    {{-- 🚫 Label HABIS --}}
                                    @if($product->Stok < 1)
                                        <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold transform rotate-45 shadow-md">
                                            HABIS
                                        </span>
                                </div>
                                @endif

                                {{-- ⚠️ Label Stok Menipis --}}
                                @if($product->Stok > 0 && $product->Stok < 5)
                                    <span class="absolute top-2 right-2 bg-yellow-400 text-black text-xs font-bold px-2 py-1 rounded-full shadow-md z-10 animate-pulse">
                                    Menipis
                                    </span>
                                    @endif

                                    {{-- 🔖 Label Promo --}}
                                    @if($product->DiskonPersen > 0)
                                    <span class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md z-10">
                                        Diskon {{ number_format($product->DiskonPersen, 0) }}%
                                    </span>
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
                            <!-- Di bagian cart, ganti $pelanggan dengan $pelangganAktif -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-semibold text-gray-900">Cari Member Aktif</label>
                                    <span class="text-xs text-gray-500">Alt+P untuk fokus</span>
                                </div>

                                <!-- Input Pencarian -->
                                <div class="relative">
                                    <div class="relative">
                                        <input type="text"
                                            id="search-pelanggan"
                                            placeholder="Ketik nama member aktif..."
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            autocomplete="off">

                                        <!-- Loading Indicator -->
                                        <div id="loading-pelanggan" class="hidden absolute inset-y-0 right-3 flex items-center">
                                            <div class="animate-spin rounded-full h-4 w-4 border-2 border-blue-500 border-t-transparent"></div>
                                        </div>

                                        <!-- Search Icon -->
                                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-gray-400 text-sm"></i>
                                        </div>
                                    </div>

                                    <!-- Hasil Pencarian -->
                                    <div id="pelanggan-results" class="hidden absolute top-full left-0 right-0 z-50 mt-1 bg-white border border-gray-200 rounded-lg shadow-xl max-h-60 overflow-y-auto"></div>
                                </div>

                                <!-- Info Pelanggan Terpilih -->
                                <div id="selected-pelanggan-info" class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                                    @if(session('cart_customer'))
                                    @php
                                    $selectedPelanggan = $pelanggan->firstWhere('PelangganID', session('cart_customer'));
                                    @endphp
                                    @if($selectedPelanggan && $selectedPelanggan->checkMembershipStatus())
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div>
                                                <div class="font-bold text-blue-900 text-sm">{{ $selectedPelanggan->NamaPelanggan }}</div>
                                                <div class="flex items-center space-x-2 mt-1">
                                                    @php
                                                    $remainingDays = $selectedPelanggan->remaining_days;
                                                    $badgeColor = $remainingDays > 30 ? 'bg-green-100 text-green-800' :
                                                    ($remainingDays > 7 ? 'bg-yellow-100 text-yellow-800' :
                                                    'bg-red-100 text-red-800');
                                                    $badgeText = $remainingDays > 30 ? 'Aktif' :
                                                    ($remainingDays > 7 ? 'Hampir habis' :
                                                    'Segera habis');
                                                    @endphp
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" id="clear-pelanggan"
                                            class="bg-white border border-red-300 text-red-600 hover:bg-red-50 hover:border-red-400 px-3 py-2 rounded-lg text-xs font-semibold flex items-center space-x-2 transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="fas fa-times"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </div>
                                    @endif
                                    @else
                                    <div class="text-center py-1">
                                        <div class="text-gray-500 text-sm flex flex-col items-center space-y-2">
                                            <span>Belum ada member terpilih</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Status Message -->
                            <div id="pelanggan-status" class="text-xs"></div>
                        </div>

                        <!-- Total dan Diskon -->
                        <div class="border-t pt-4 space-y-3" id="total-section">
                            @if(session('cart_customer'))
                            @php
                            $diskonPersen = App\Models\Setting::get('diskon_member', 0);
                            $diskonNominal = ($total * $diskonPersen) / 100;
                            $totalAkhir = $total - $diskonNominal;
                            @endphp
                            <div class="flex justify-between items-center bg-green-50 border border-green-200 rounded-lg p-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-tag text-white text-xs"></i>
                                    </div>
                                    <span class="text-green-800 font-medium text-sm">Diskon Member</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-green-600 font-bold text-sm">-{{ $diskonPersen }}%</div>
                                    <div class="text-green-700 font-semibold">Rp {{ number_format($diskonNominal, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="flex justify-between items-center bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-4">
                                <span class="font-bold text-lg">Total Akhir</span>
                                <span id="total-display" data-total="{{ $totalAkhir }}" class="font-bold text-xl">
                                    Rp {{ number_format($totalAkhir, 0, ',', '.') }}
                                </span>
                            </div>
                            @else
                            <div class="flex justify-between items-center bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <span class="font-bold text-gray-900 text-lg">Total</span>
                                <span id="total-display" data-total="{{ $total }}" class="font-bold text-gray-900 text-xl">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- ✅ Form Pembayaran & Checkout -->
                        <form id="checkout-form" action="{{ route('cart.checkout') }}" method="POST" class="space-y-3 border-t pt-3">
                            @csrf

                            <div>
                                <label for="uang_tunai" class="block text-sm font-medium text-gray-700">Uang Tunai (Rp)</label>
                                <input type="text"
                                    name="UangTunai"
                                    id="uang_tunai"
                                    inputmode="numeric"
                                    autocomplete="off"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:ring-black focus:border-black py-2 px-3"
                                    placeholder="Masukkan nominal uang..."
                                    required>
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
            .max-h-96::-webkit-scrollbar {
                width: 6px;
            }

            .max-h-96::-webkit-scrollbar-thumb {
                background: #d1d5db;
                border-radius: 3px;
            }

            .max-h-96::-webkit-scrollbar-thumb:hover {
                background: #9ca3af;
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // Fungsi Pencarian dan Auto-Save Pelanggan - MENGGUNAKAN MODEL YANG ADA
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('search-pelanggan');
                const resultsContainer = document.getElementById('pelanggan-results');
                const loadingIndicator = document.getElementById('loading-pelanggan');
                const selectedPelangganInfo = document.getElementById('selected-pelanggan-info');

                let searchTimeout;

                // Data pelanggan AKTIF dari server
                const pelangganAktifData = @json($pelangganAktif);

                // Setup tombol hapus
                setupClearButton();

                // Fungsi untuk format tanggal
                function formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                }

                // Fungsi untuk menampilkan hasil pencarian
                function showResults(results) {
                    if (results.length === 0) {
                        resultsContainer.innerHTML = `
                    <div class="p-6 text-center">
                        <i class="fas fa-users text-gray-300 text-2xl mb-3"></i>
                        <div class="text-sm text-gray-500 font-medium">Tidak ada member aktif ditemukan</div>
                        <div class="text-xs text-gray-400 mt-1">Coba dengan kata kunci lain atau periksa status membership</div>
                    </div>
                `;
                    } else {
                        resultsContainer.innerHTML = results.map(pelanggan => {
                            const expiredDate = pelanggan.member_expired ? formatDate(pelanggan.member_expired) : 'Tidak ada masa aktif';
                            const remainingDays = pelanggan.remaining_days || 0;

                            const badgeColor = remainingDays > 30 ? 'bg-green-100 text-green-800' :
                                remainingDays > 7 ? 'bg-yellow-100 text-yellow-800' :
                                'bg-red-100 text-red-800';

                            const badgeText = remainingDays > 30 ? 'Aktif' :
                                remainingDays > 7 ? 'Hampir habis' :
                                'Segera habis';

                            const badgeIcon = remainingDays > 30 ? 'fa-check-circle' :
                                remainingDays > 7 ? 'fa-exclamation-triangle' :
                                'fa-exclamation-circle';

                            return `
                    <div class="pelanggan-item p-4 cursor-pointer border-b border-gray-100 last:border-b-0 transition-all duration-200 hover:bg-blue-50 hover:border-blue-200 group"
                        data-id="${pelanggan.PelangganID}"
                        data-name="${pelanggan.NamaPelanggan}">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-gray-900 text-sm group-hover:text-blue-900 transition-colors mb-1">
                                    ${pelanggan.NamaPelanggan}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Berakhir: ${expiredDate}    
                                </div>
                            </div>
                            <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="bg-blue-500 text-white px-3 py-2 rounded-lg text-xs font-semibold flex flex-col items-center space-y-1">
                                    <i class="fas fa-tag text-xs"></i>
                                    <span>Diskon</span>
                                    <span class="text-xs">{{ $diskon }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                        }).join('');
                    }
                    resultsContainer.classList.remove('hidden');
                }

                // Event listener untuk input pencarian
                searchInput.addEventListener('input', function(e) {
                    clearTimeout(searchTimeout);
                    const query = e.target.value.trim();

                    if (query.length < 1) {
                        resultsContainer.classList.add('hidden');
                        loadingIndicator.classList.add('hidden');
                        return;
                    }

                    loadingIndicator.classList.remove('hidden');

                    searchTimeout = setTimeout(() => {
                        // Filter pelanggan aktif berdasarkan query
                        const filteredResults = pelangganAktifData.filter(pelanggan =>
                            pelanggan.NamaPelanggan.toLowerCase().includes(query.toLowerCase())
                        );

                        loadingIndicator.classList.add('hidden');
                        showResults(filteredResults);
                    }, 300);
                });

                // Event listener untuk memilih pelanggan
                resultsContainer.addEventListener('click', function(e) {
                    const pelangganItem = e.target.closest('.pelanggan-item');
                    if (pelangganItem) {
                        const pelangganId = pelangganItem.getAttribute('data-id');
                        const pelangganName = pelangganItem.getAttribute('data-name');

                        // Add selected effect
                        pelangganItem.style.transform = 'scale(0.98)';
                        pelangganItem.style.background = 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)';
                        pelangganItem.style.color = 'white';

                        // Auto-save pelanggan
                        setTimeout(() => {
                            savePelanggan(pelangganId, pelangganName);
                        }, 400);
                    }
                });

                // Fungsi untuk menyimpan pelanggan
                function savePelanggan(pelangganId, pelangganName) {
                    // Show loading state
                    searchInput.disabled = true;
                    loadingIndicator.classList.remove('hidden');

                    // Update selected info sementara
                    selectedPelangganInfo.innerHTML = `
                <div class="flex items-center justify-between animate-pulse">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-spinner fa-spin text-blue-600 text-sm"></i>
                            </div>
                        </div>
                        <div>
                            <div class="font-bold text-blue-900 text-sm">Menyimpan member...</div>
                            <div class="text-xs text-blue-700">Menerapkan diskon {{ $diskon }}%</div>
                        </div>
                    </div>
                </div>
            `;

                    // Create form submission
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("cart.set-customer") }}';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const pelangganInput = document.createElement('input');
                    pelangganInput.type = 'hidden';
                    pelangganInput.name = 'pelanggan_id';
                    pelangganInput.value = pelangganId;

                    form.appendChild(csrfToken);
                    form.appendChild(pelangganInput);
                    document.body.appendChild(form);

                    // Submit form
                    setTimeout(() => {
                        form.submit();
                    }, 800);
                }

                // Sembunyikan hasil pencarian ketika klik di luar
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                        resultsContainer.classList.add('hidden');
                    }
                });

                // Hotkey untuk fokus ke pencarian pelanggan (Alt+P)
                document.addEventListener('keydown', function(e) {
                    if (e.altKey && e.key.toLowerCase() === 'p') {
                        e.preventDefault();
                        searchInput.focus();
                        searchInput.select();
                    }

                    // ESC untuk clear pencarian
                    if (e.key === 'Escape' && document.activeElement === searchInput) {
                        searchInput.value = '';
                        resultsContainer.classList.add('hidden');
                        searchInput.blur();
                    }
                });

                // Focus effect untuk input
                searchInput.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50', 'rounded-lg');
                });

                searchInput.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
                });
            });

            // Fungsi untuk setup tombol hapus
            function setupClearButton() {
                const clearBtn = document.getElementById('clear-pelanggan');
                if (clearBtn) {
                    clearBtn.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Konfirmasi sebelum menghapus
                        Swal.fire({
                            title: 'Hapus Member?',
                            text: 'Diskon {{ $diskon }}% akan dihapus dari transaksi ini',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, Hapus Member!',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                            background: '#fff',
                            backdrop: 'rgba(0,0,0,0.4)'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Show loading state
                                const selectedPelangganInfo = document.getElementById('selected-pelanggan-info');
                                selectedPelangganInfo.innerHTML = `
                            <div class="flex items-center justify-center py-4 space-x-3">
                                <div class="animate-spin rounded-full h-5 w-5 border-2 border-blue-500 border-t-transparent"></div>
                                <span class="text-sm text-blue-600 font-semibold">Menghapus member...</span>
                            </div>
                        `;

                                // Create form submission
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = '{{ route("cart.set-customer") }}';

                                const csrfToken = document.createElement('input');
                                csrfToken.type = 'hidden';
                                csrfToken.name = '_token';
                                csrfToken.value = '{{ csrf_token() }}';

                                const pelangganInput = document.createElement('input');
                                pelangganInput.type = 'hidden';
                                pelangganInput.name = 'pelanggan_id';
                                pelangganInput.value = '';

                                form.appendChild(csrfToken);
                                form.appendChild(pelangganInput);
                                document.body.appendChild(form);

                                // Submit form dengan delay untuk feedback visual
                                setTimeout(() => {
                                    form.submit();
                                }, 800);
                            }
                        });
                    });
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                const uangInput = document.getElementById('uang_tunai');
                const kembalianDisplay = document.getElementById('kembalian-display');
                const totalEl = document.getElementById('total-display');
                const checkoutForm = document.getElementById('checkout-form');
                const total = parseFloat(totalEl.dataset.total || 0);

                const parseRupiahToNumber = (str) => {
                    if (!str) return 0;
                    const digits = String(str).replace(/[^0-9-]/g, '');
                    return Number(digits) || 0;
                };

                const formatNumberRupiah = (num) => {
                    if (num === 0 || num === null || typeof num === 'undefined') return '';
                    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                };

                // Update kembalian saat user mengetik
                uangInput?.addEventListener('input', e => {
                    const raw = parseRupiahToNumber(e.target.value);
                    e.target.value = raw ? formatNumberRupiah(raw) : '';

                    const kembalian = raw - total;
                    kembalianDisplay.textContent = 'Rp ' + (kembalian > 0 ? kembalian.toLocaleString('id-ID') : 0);
                    kembalianDisplay.classList.toggle('text-red-600', kembalian < 0);
                    kembalianDisplay.classList.toggle('text-green-600', kembalian >= 0);
                });

                // Cek uang tunai sebelum submit
                checkoutForm?.addEventListener('submit', e => {
                    const uang = parseRupiahToNumber(uangInput.value);
                    if (uang < total) {
                        e.preventDefault();

                        Swal.fire({
                            icon: 'error',
                            title: 'Uang Tunai Kurang!',
                            text: 'Nominal uang tidak cukup untuk melakukan pembayaran.',
                            confirmButtonColor: '#dc2626',
                            confirmButtonText: 'Oke, saya perbaiki'
                        });
                        return;
                    }

                    uangInput.value = parseRupiahToNumber(uangInput.value);
                });
            });

            (function() {
                const input = document.getElementById('product-search');
                const productGrid = document.querySelector('.grid');
                const products = @json($allProducts);

                const template = product => {

                    // Coba beberapa kemungkinan nama field diskon
                    const diskonValue = product.DiskonPersen || 0;
                    const isPromo = diskonValue > 0;
                    const hargaAktif = isPromo ? Math.round(product.Harga - (product.Harga * diskonValue / 100)) : product.Harga;
                    const stokMenipis = product.Stok > 0 && product.Stok < 5;
                    const stokHabis = product.Stok < 1;

                    const diskonTampil = parseInt(diskonValue);

                    return `
            <div class="product-card" data-name="${product.NamaProduk.toLowerCase()}">
                <div class="bg-[#0f172a] rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow relative">
                    
                    ${isPromo ? `
                        <span class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md z-10">
                            Diskon ${diskonTampil}%
                        </span>
                    ` : ''}
                    
                    <div class="aspect-square relative">
                        <img class="w-full h-full object-cover ${stokHabis ? 'opacity-50' : ''}" 
                             src="/storage/${product.Gambar}" 
                             alt="${product.NamaProduk}" 
                             onerror="this.onerror=null;this.src='/produk/default.png'" />
                        
                        ${stokHabis ? `
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold transform rotate-45 shadow-md">
                                    HABIS
                                </span>
                            </div>
                        ` : ''}
                        
                        ${stokMenipis && !stokHabis ? `
                            <span class="absolute top-2 right-2 bg-yellow-400 text-black text-xs font-bold px-2 py-1 rounded-full shadow-md z-10 animate-pulse">
                                Menipis
                            </span>
                        ` : ''}
                    </div>

                    <div class="p-3 text-white">
                        <h3 class="text-base font-medium mb-0.5 truncate">${product.NamaProduk}</h3>
                        <p class="text-xs mb-2 ${stokHabis ? 'text-red-400' : 'text-gray-400'}">
                            Stok: ${product.Stok} ${product.Satuan}
                        </p>

                        ${isPromo ? `
                            <p class="text-sm line-through text-gray-400">
                                Rp ${Number(product.Harga).toLocaleString('id-ID')}
                            </p>
                            <p class="text-lg font-bold text-red-500">
                                Rp ${Number(hargaAktif).toLocaleString('id-ID')}
                            </p>
                        ` : `
                            <p class="text-lg font-bold">
                                Rp ${Number(product.Harga).toLocaleString('id-ID')}
                            </p>
                        `}
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
                            ${stokHabis ? 'disabled' : ''}>
                        <i class="fas fa-cart-plus mr-1"></i> Tambah
                    </button>
                </form>
            </div>
        `;
                };

                // Search filter function
                const filter = (term) => {
                    const q = (term || '').toLowerCase().trim();

                    if (!q) {
                        showPaginated();
                        return;
                    }

                    // Hide pagination when searching
                    const paginationElement = document.querySelector('.mt-6');
                    if (paginationElement) {
                        paginationElement.style.display = 'none';
                    }

                    // Filter and display matching products
                    const filtered = products.filter(product =>
                        product.NamaProduk.toLowerCase().includes(q)
                    );

                    console.log('Filtered products:', filtered);
                    productGrid.innerHTML = filtered.map(template).join('');
                };

                // Function to show paginated view
                const showPaginated = () => {
                    const paginationElement = document.querySelector('.mt-6');
                    if (paginationElement) {
                        paginationElement.style.display = 'block';
                    }
                    location.reload();
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

        <style>
            #pelanggan-results {
                position: absolute;
                z-index: 1000;
                width: 100%;
                max-height: 200px;
                overflow-y: auto;
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
                margin-top: 0.25rem;
            }

            .max-h-96::-webkit-scrollbar {
                width: 6px;
            }

            .max-h-96::-webkit-scrollbar-thumb {
                background: #d1d5db;
                border-radius: 3px;
            }

            .max-h-96::-webkit-scrollbar-thumb:hover {
                background: #9ca3af;
            }
        </style>

    </x-app-layout>