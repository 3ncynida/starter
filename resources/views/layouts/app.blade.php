<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-green-50">
        @include('layouts.navigation')
        <style>
            .swal2-confirm {
                background-color: #dc2626 !important;
            }

            .swal2-cancel {
                background-color: #6b7280 !important;
            }
        </style>

        <!-- Page Heading -->
        @isset($header)
        <header class="bg-white/70 backdrop-blur border-b border-green-100 sticky top-0 z-30" role="banner" aria-label="Header">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main class="py-6 sm:py-8">
            {{ $slot }}
        </main>
    </div>
    <!-- SweetAlert Script -->
    <script>
        // Function untuk konfirmasi hapus
        function confirmDelete(event, name) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: 'Hapus Pelanggan?',
                html: `Apakah Anda yakin ingin menghapus <strong>${name}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'px-4 py-2 rounded-lg font-semibold',
                    cancelButton: 'px-4 py-2 rounded-lg font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // Function untuk konfirmasi ubah status member
        function confirmStatusChange(event, name, currentStatus) {
            event.preventDefault();
            const form = event.target.closest('form');
            const newStatus = currentStatus === 'Aktif' ? 'menonaktifkan' : 'mengaktifkan';

            Swal.fire({
                title: 'Ubah Status Member?',
                html: `Apakah Anda yakin ingin <strong>${newStatus}</strong> status member untuk <strong>${name}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#6b7280',
                confirmButtonText: `Ya, ${newStatus}!`,
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'px-4 py-2 rounded-lg font-semibold',
                    cancelButton: 'px-4 py-2 rounded-lg font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</body>

</html>