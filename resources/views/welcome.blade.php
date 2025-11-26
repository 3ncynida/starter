<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Selamat datang di Toko Mainan Edukatif - Destinasi Anda untuk mainan berkualitas tinggi yang mendukung perkembangan anak. Kami menawarkan berbagai mainan edukatif yang aman dan menyenangkan.">
    <meta name="keywords" content="toko mainan, mainan edukatif, mainan anak, mainan berkualitas, mainan perkembangan anak">
    <title>Toko Mainan Edukatif - Mainan Berkualitas untuk Tumbuh Kembang Anak</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Updated color palette for toy store theme */
        :root {
            --primary-color: #3B82F6;
            --primary-dark: #2563EB;
            --secondary-color: #EF4444;
            --secondary-dark: #DC2626;
            --accent-color: #F59E0B;
            --text-color: #1F2937;
            --text-light: #6B7280;
            --light-bg: #F9FAFB;
            --border-color: #E5E7EB;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            background-color: #FFFFFF;
            line-height: 1.6;
        }

        /* Improved header styling with better glass effect */
        .header-glass {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: saturate(200%) blur(10px);
            border-bottom: 1px solid var(--border-color);
        }

        .header-glass:hover {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        /* Enhanced button styles with better shadows and transitions */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 0.875rem 1.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
            display: inline-block;
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.35);
            background: linear-gradient(135deg, var(--primary-dark) 0%, #1D4ED8 100%);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--secondary-dark) 100%);
            color: white;
            padding: 0.875rem 1.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
            display: inline-block;
            text-decoration: none;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.35);
            background: linear-gradient(135deg, var(--secondary-dark) 0%, #B91C1C 100%);
        }

        .btn-secondary:active {
            transform: translateY(0);
        }

        /* Hide sections entirely */
        #contact {
            display: none !important;
        }

        #products {
            display: none !important;
        }

        /* Enhanced card styling */
        .feature-card {
            background: white;
            border-radius: 0.75rem;
            padding: 2rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
            border-color: var(--primary-color);
        }

        /* Improved icon containers */
        .icon-container {
            width: 3.5rem;
            height: 3.5rem;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .feature-card:hover .icon-container {
            background: linear-gradient(135deg, var(--primary-color) 0%, rgba(59, 130, 246, 0.8) 100%);
        }

        .feature-card:hover .icon-container svg {
            color: white;
        }

        .icon-container svg {
            width: 1.75rem;
            height: 1.75rem;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        /* Enhanced stat cards */
        .stat-item {
            text-align: center;
            padding: 1.5rem;
        }

        .stat-number {
            font-size: 1.875rem;
            font-weight: 800;
            color: var(--primary-color);
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-light);
            font-weight: 500;
        }

        /* Hero section improvements */
        .hero-title {
            font-size: clamp(2.5rem, 8vw, 3.75rem);
            font-weight: 800;
            line-height: 1.2;
            color: var(--text-color);
            margin-bottom: 1.5rem;
        }

        .hero-title .highlight {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-description {
            font-size: 1.125rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.7;
        }

        /* About section improvements */
        .about-card {
            background: white;
            border-radius: 0.75rem;
            padding: 2.5rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .about-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 1.25rem;
        }

        .about-text {
            color: var(--text-light);
            margin-bottom: 1.25rem;
            line-height: 1.8;
        }

        /* CTA section improvements */
        .cta-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .cta-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .cta-content {
            position: relative;
            z-index: 1;
        }

        .cta-text {
            font-size: clamp(1.5rem, 5vw, 1.875rem);
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
        }

        /* Member section improvements */
        .member-section {
            background: linear-gradient(to bottom, var(--light-bg), white);
            padding: 3rem 0;
        }

        .member-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .member-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.25rem;
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .member-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .member-header {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .member-avatar {
            width: 2.75rem;
            height: 2.75rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.125rem;
            flex-shrink: 0;
        }

        .member-info {
            flex: 1;
        }

        .member-name {
            font-weight: 600;
            color: var(--text-color);
            font-size: 0.95rem;
        }

        .member-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-top: 0.25rem;
        }

        .member-days {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-top: 0.5rem;
        }

        .member-days .highlight {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Footer improvements */
        footer {
            background: linear-gradient(135deg, #111827 0%, #1F2937 100%);
            color: white;
        }

        footer h4 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        footer p {
            color: #9CA3AF;
            font-size: 0.875rem;
            line-height: 1.6;
        }

        footer a {
            color: #9CA3AF;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: var(--primary-color);
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .cta-text {
                font-size: 1.25rem;
            }

            .member-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }

        /* Smooth scroll behavior */
        a[href^="#"] {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <!-- Header/Navigation -->
    <header class="header-glass py-4 fixed w-full top-0 left-0 z-50">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <!-- Logo Mainan SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="36" height="36" fill="currentColor">
                        <rect x="20" y="28" width="24" height="24" rx="4" fill="#3b82f6"/>
                        <rect x="26" y="16" width="12" height="12" rx="2" fill="#60a5fa"/>
                        <circle cx="30" cy="22" r="1.5" fill="white"/>
                        <circle cx="34" cy="22" r="1.5" fill="white"/>
                        <line x1="32" y1="16" x2="32" y2="12" stroke="#1e40af" stroke-width="1.5"/>
                        <circle cx="32" cy="10" r="1.5" fill="#ef4444"/>
                    </svg>
                    <span class="text-2xl font-bold" style="color: var(--primary-color);">
                        {{ env('APP_COMPANY', 'Toko Mainan') }}
                    </span>
                </div>

                <!-- Navigation Menu -->
                <nav class="hidden md:flex space-x-5">
                    <a href="#home" style="color: var(--text-light);" class="hover:text-primary transition font-medium">Beranda</a>
                    <a href="#about" style="color: var(--text-light);" class="hover:text-primary transition font-medium">Tentang Kami</a>
                </nav>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2" id="mobile-menu-button">
                    <svg class="w-6 h-6" style="color: var(--text-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="btn-secondary">Masuk</a>
                    @endauth
                    @endif
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="md:hidden hidden" id="mobile-menu">
                <div class="px-4 py-3 space-y-4">
                    <a href="#home" class="block font-medium" style="color: var(--text-light);">Beranda</a>
                    <a href="#about" class="block font-medium" style="color: var(--text-light);">Tentang Kami</a>
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ url('/dashboard') }}" class="block btn-primary mt-2">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="block btn-secondary mt-2">Masuk</a>
                    @endauth
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Spacer for fixed header -->
    <div class="h-20"></div>

    <!-- Main Content -->
    <main class="min-h-screen">
        <!-- Hero Section -->
        <section id="home" style="background: linear-gradient(to bottom, var(--light-bg), white);" class="py-20 lg:py-32">
            <div class="container mx-auto px-6">
                <div class="flex flex-col lg:flex-row items-center gap-12">
                    <!-- Hero Content -->
                    <div class="lg:w-1/2">
                        <h1 class="hero-title">
                            Mainan Edukatif untuk <span class="highlight">Tumbuh Kembang Anak</span>
                        </h1>
                        <p class="hero-description">
                            Temukan berbagai mainan edukatif berkualitas tinggi yang aman dan menyenangkan untuk mendukung perkembangan kreativitas dan kecerdasan anak Anda.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 mb-12">
                            <a href="#about" class="btn-primary text-center">Lihat Keunggulan</a>
                        </div>
                        <div class="grid grid-cols-3 gap-6">
                            <div class="stat-item">
                                <div class="stat-number">500+</div>
                                <div class="stat-label">Jenis Mainan</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">100%</div>
                                <div class="stat-label">Aman untuk Anak</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">10K+</div>
                                <div class="stat-label">Orang Tua Puas</div>
                            </div>
                        </div>
                    </div>

                    <!-- Hero Image -->
                    <div class="lg:w-1/2">
                        <img src="/placeholder.svg?height=400&width=600" alt="Koleksi mainan edukatif yang colorful" class="rounded-xl shadow-2xl w-full h-auto">
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits Section -->
        <section id="about" style="background: var(--light-bg);" class="py-20 lg:py-32">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-4xl font-bold mb-4">Mengapa Memilih Mainan Kami</h2>
                    <p class="text-lg" style="color: var(--text-light);">Mainan berkualitas yang mendukung perkembangan optimal anak Anda</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <!-- Benefits -->
                    <div class="feature-card">
                        <div class="icon-container">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Aman & Berkualitas</h3>
                        <p style="color: var(--text-light);">Semua mainan kami terbuat dari bahan non-toxic dan telah lolos uji keamanan untuk anak-anak.</p>
                    </div>

                    <div class="feature-card">
                        <div class="icon-container">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Edukatif & Menyenangkan</h3>
                        <p style="color: var(--text-light);">Dirancang khusus untuk merangsang kreativitas, logika, dan perkembangan motorik anak.</p>
                    </div>

                    <div class="feature-card">
                        <div class="icon-container">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Harga Terjangkau</h3>
                        <p style="color: var(--text-light);">Kualitas premium dengan harga yang ramah di kantong untuk semua kalangan.</p>
                    </div>
                </div>

                <!-- Detailed About Section -->
                <div class="about-card">
                    <h3 class="about-title">Tentang Toko Mainan Edukatif</h3>
                    <p class="about-text">
                        Toko Mainan Edukatif adalah penyedia mainan berkualitas tinggi yang berkomitmen untuk memberikan produk terbaik yang mendukung tumbuh kembang anak. Dengan pengalaman lebih dari 8 tahun di industri mainan anak, kami memahami kebutuhan perkembangan anak di setiap usia.
                    </p>
                    <p class="about-text">
                        Kami bekerja sama dengan ahli perkembangan anak dan produsen terpercaya untuk memastikan setiap mainan yang kami jual tidak hanya menyenangkan, tetapi juga mendukung perkembangan kognitif, motorik, dan sosial anak.
                    </p>
                    <p class="about-text">
                        Misi kami adalah membantu orang tua dalam memberikan stimulasi yang tepat melalui mainan yang aman, edukatif, dan berkualitas. Setiap senyum anak adalah kebahagiaan bagi kami.
                    </p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-6 border-t" style="border-color: var(--border-color);">
                        <div class="stat-item">
                            <div class="stat-number">8+</div>
                            <div class="stat-label">Tahun Pengalaman</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">50+</div>
                            <div class="stat-label">Brand Terpercaya</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">100%</div>
                            <div class="stat-label">Kepuasan Pelanggan</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">25+</div>
                            <div class="stat-label">Kota Terjangkau</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Members Section -->
        <section class="member-section">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold mb-2">Daftar Member Aktif</h2>
                <p style="color: var(--text-light);" class="mb-8">Bergabunglah dengan ribuan orang tua yang mempercayai kami</p>

                @if(isset($members) && $members->isNotEmpty())
                <div class="member-grid">
                    @foreach($members as $member)
                    <div class="member-card">
                        <div class="member-header">
                            <div class="member-avatar">
                                {{ strtoupper(substr($member->NamaPelanggan, 0, 1)) }}
                            </div>
                            <div class="member-info">
                                <div class="member-name">{{ $member->NamaPelanggan }}</div>
                                <span class="member-badge">Member</span>
                            </div>
                        </div>
                        <div class="member-days">
                            Sisa: <span class="highlight">{{ number_format($member->remaining_days, 0) }} hari</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div style="background: white; border: 1px solid var(--border-color);" class="p-8 rounded-lg text-center">
                    <p style="color: var(--text-light);">Belum ada member aktif saat ini. Daftarkan diri Anda sekarang!</p>
                </div>
                @endif
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section py-16 lg:py-20">
            <div class="container mx-auto px-6">
                <div class="cta-content flex flex-col md:flex-row items-center justify-between gap-8">
                    <p class="cta-text">Berikan yang terbaik untuk tumbuh kembang anak Anda</p>
                    <a href="#about" class="btn-primary bg-white text-gray-900 hover:bg-gray-50">Lihat Keunggulan</a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="py-16">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="space-y-4">
                    <h4>Toko Mainan Edukatif</h4>
                    <p>Sumber terpercaya Anda untuk mainan edukatif berkualitas tinggi yang mendukung perkembangan anak.</p>
                    <div class="flex space-x-4 pt-4">
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h4>Tautan Cepat</h4>
                    <ul class="space-y-2">
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#about">Tentang Kami</a></li>
                    </ul>
                </div>

                <div>
                    <h4>Jam Operasional</h4>
                    <ul class="space-y-2">
                        <li>Senin - Jumat: 9 pagi - 8 malam</li>
                        <li>Sabtu - Minggu: 10 pagi - 6 sore</li>
                    </ul>
                </div>

                <div>
                    <h4>Hubungi Kami</h4>
                    <p>Email: info@tokomainanedukatif.com</p>
                    <p>Telepon: +62 555 5678</p>
                </div>
            </div>

            <div style="border-top: 1px solid #374151; padding-top: 2rem; text-align: center; color: #6B7280;">
                <p>&copy; 2025 Toko Mainan Edukatif. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu JavaScript -->
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>

</html>