<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">

                {{-- Tampilkan error validasi --}}
                @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <strong>Ups!</strong> Ada masalah dengan input kamu.<br><br>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('produk.update', $produk->ProdukID) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Nama Produk --}}
                    <div>
                        <x-input-label for="NamaProduk" :value="__('Nama Produk')" />
                        <x-text-input id="NamaProduk" name="NamaProduk" type="text"
                            class="mt-1 block w-full"
                            value="{{ old('NamaProduk', $produk->NamaProduk) }}"
                            required autofocus />
                        <x-input-error :messages="$errors->get('NamaProduk')" class="mt-2" />
                    </div>

                    {{-- Harga --}}
                    <div>
                        <x-input-label for="Harga" :value="__('Harga')" />
                        <x-text-input id="Harga" name="Harga" type="number"
                            class="mt-1 block w-full"
                            value="{{ old('Harga', $produk->Harga) }}"
                            required />
                        <x-input-error :messages="$errors->get('Harga')" class="mt-2" />
                    </div>

                    {{-- Stok --}}
                    <div>
                        <x-input-label for="Stok" :value="__('Stok')" />
                        <x-text-input id="Stok" name="Stok" type="number"
                            class="mt-1 block w-full"
                            value="{{ old('Stok', $produk->Stok) }}"
                            required />
                        <x-input-error :messages="$errors->get('Stok')" class="mt-2" />
                    </div>

                    <!-- Satuan -->
                    <div>
                        <x-input-label for="Satuan" :value="__('Satuan')" />
                        <select name="Satuan" id="Satuan" class="w-full border rounded p-2" required>
                            <option value="pcs" {{ old('Satuan', $produk->Satuan) == 'pcs' ? 'selected' : '' }}>Pcs</option>
                            <option value="kg" {{ old('Satuan', $produk->Satuan) == 'kg' ? 'selected' : '' }}>Kg</option>
                            <option value="pack" {{ old('Satuan', $produk->Satuan) == 'pack' ? 'selected' : '' }}>Pack</option>
                            <option value="dus" {{ old('Satuan', $produk->Satuan) == 'dus' ? 'selected' : '' }}>Dus</option>
                            <option value="sisir" {{ old('Satuan', $produk->Satuan) == 'sisir' ? 'selected' : '' }}>Sisir</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('Satuan')" />
                    </div>

                    <!-- Promosi -->
                    <div class="flex items-center mt-2">
                        <input type="hidden" name="Promosi" value="0">

                        <label class="inline-flex items-center">
                            <input type="checkbox" name="Promosi" value="1"
                                class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500"
                                {{ old('Promosi', $produk->Promosi) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Aktifkan Promosi</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('Promosi')" class="mt-2" />


                    <!-- Diskon Persen -->
                    <div>
                        <x-input-label for="DiskonPersen" :value="__('Diskon (%)')" />
                        <x-text-input id="DiskonPersen" name="DiskonPersen" type="number"
                            class="mt-1 block w-full"
                            value="{{ old('DiskonPersen', $produk->DiskonPersen) }}"
                            min="0" max="100" placeholder="Misal: 10 untuk 10%" />
                        <x-input-error :messages="$errors->get('DiskonPersen')" class="mt-2" />
                    </div>

                    <!-- Tanggal Mulai Promosi -->
                    <div>
                        <x-input-label for="TanggalMulaiPromosi" :value="__('Tanggal Mulai Promosi')" />
                        <x-text-input id="TanggalMulaiPromosi" name="TanggalMulaiPromosi" type="date"
                            class="mt-1 block w-full"
                            value="{{ old('TanggalMulaiPromosi', $produk->TanggalMulaiPromosi) }}" />
                        <x-input-error :messages="$errors->get('TanggalMulaiPromosi')" class="mt-2" />
                    </div>

                    <!-- Tanggal Selesai Promosi -->
                    <div>
                        <x-input-label for="TanggalSelesaiPromosi" :value="__('Tanggal Selesai Promosi')" />
                        <x-text-input id="TanggalSelesaiPromosi" name="TanggalSelesaiPromosi" type="date"
                            class="mt-1 block w-full"
                            value="{{ old('TanggalSelesaiPromosi', $produk->TanggalSelesaiPromosi) }}" />
                        <x-input-error :messages="$errors->get('TanggalSelesaiPromosi')" class="mt-2" />
                    </div>


                    <!-- Gambar -->
                    <div>
                        <x-input-label for="Gambar" :value="__('Gambar Produk')" />

                        <input id="Gambar" name="Gambar" type="file" class="mt-1 block w-full border rounded" accept="image/*" />
                        <p class="mt-1 text-sm text-gray-600">Biarkan kosong jika tidak ingin mengubah gambar</p>
                        <x-input-error class="mt-2" :messages="$errors->get('Gambar')" />
                    </div>


                    {{-- Tombol --}}
                    <div class="flex items-center gap-4">
                        <x-primary-button>
                            {{ __('Update') }}
                        </x-primary-button>

                        <a href="{{ route('produk.index') }}"
                            class="text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Kembali') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tambahkan script ini di bagian paling bawah, sebelum penutup x-app-layout -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const namaProdukInput = document.getElementById('NamaProduk');
        
        if (namaProdukInput) {
            // Format saat input kehilangan fokus
            namaProdukInput.addEventListener('blur', function() {
                formatNamaProduk(this);
            });
            
            // Format saat pengguna menekan Enter
            namaProdukInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    formatNamaProduk(this);
                }
            });
            
            // Format saat form disubmit
            const form = namaProdukInput.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    formatNamaProduk(namaProdukInput);
                });
            }
        }
        
        function formatNamaProduk(inputElement) {
            let value = inputElement.value.trim();
            
            if (value) {
                // Kapitalisasi setiap kata
                value = value.toLowerCase()
                    .split(' ')
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' ');
                
                inputElement.value = value;
            }
        }
    });
    </script>
</x-app-layout>