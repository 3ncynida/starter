<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Welcome to Fresh Fruit Market - Your destination for fresh, high-quality fruits. We offer a wide selection of seasonal fruits delivered fresh to your door.">
        <meta name="keywords" content="fruit market, fresh fruits, organic fruits, fruit delivery, seasonal fruits">
        <title>Fresh Fruit Market - Premium Quality Fresh Fruits</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Custom styles for Fresh Fruit Market */
            :root {
                --primary-color: #4CAF50;
                --secondary-color: #FF9800;
                --accent-color: #E91E63;
                --text-color: #333333;
                --light-bg: #F5F5F5;
            }

            body {
                font-family: 'Poppins', sans-serif;
                color: var(--text-color);
            }

            .header-glass {
                background-color: rgba(255, 255, 255, 0.85);
                backdrop-filter: saturate(180%) blur(8px);
            }

            /* Buttons */
            .bg-primary { background-color: var(--primary-color); }
            .text-primary { color: var(--primary-color); }

            .btn-primary {
                background-color: var(--primary-color);
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 0.75rem; /* slightly rounder */
                font-weight: 600;
                transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
                box-shadow: 0 6px 20px rgba(76, 175, 80, 0.15);
            }
            .btn-primary:hover { background-color: #388E3C; transform: translateY(-2px); }

            .btn-secondary {
                background-color: var(--secondary-color);
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 0.75rem;
                font-weight: 600;
                transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
                box-shadow: 0 6px 20px rgba(255, 152, 0, 0.15);
            }
            .btn-secondary:hover { background-color: #F57C00; transform: translateY(-2px); }

            /* Hide Contact section entirely */
            #contact { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <!-- Header/Navigation -->
        <header class="header-glass py-4 fixed w-full top-0 left-0 z-50 shadow-md">
            <div class="container mx-auto px-6">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <span class="text-primary text-2xl font-bold">Fresh Fruit Market</span>
                    </div>

                    <!-- Navigation Menu -->
                    <nav class="hidden md:flex space-x-8">
                        <a href="#home" class="text-gray-600 hover:text-primary transition">Home</a>
                        <a href="#products" class="text-gray-600 hover:text-primary transition">Products</a>
                        <a href="#about" class="text-gray-600 hover:text-primary transition">About</a>
                    </nav>

                    <!-- Mobile Menu Button -->
                    <button class="md:hidden p-2" id="mobile-menu-button">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Auth Buttons -->
                    <div class="hidden md:flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/admin/dashboard') }}" class="btn-primary">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="btn-secondary">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn-primary">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>

                <!-- Mobile Menu (Hidden by default) -->
                <div class="md:hidden hidden" id="mobile-menu">
                    <div class="px-4 py-3 space-y-4">
                        <a href="#home" class="block text-gray-600 hover:text-primary transition">Home</a>
                        <a href="#products" class="block text-gray-600 hover:text-primary transition">Products</a>
                        <a href="#about" class="block text-gray-600 hover:text-primary transition">About</a>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/admin/dashboard') }}" class="block text-gray-600 hover:text-primary transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="block text-gray-600 hover:text-primary transition">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="block text-gray-600 hover:text-primary transition">Register</a>
                                @endif
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
            <section id="home" class="py-20 bg-gradient-to-b from-green-50 to-white">
                <div class="container mx-auto px-6">
                    <div class="flex flex-col lg:flex-row items-center">
                        <!-- Hero Content -->
                        <div class="lg:w-1/2 text-center lg:text-left">
                            <h1 class="text-4xl lg:text-6xl font-bold text-gray-800 leading-tight mb-6 text-balance">
                                Fresh Fruits for a <span class="text-primary">Healthy Life</span>
                            </h1>
                            <p class="text-lg text-gray-600 mb-8">
                                Discover the finest selection of fresh, seasonal fruits delivered right to your doorstep.
                                Experience premium quality and taste that nature has to offer.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                <a href="#products" class="btn-primary text-center">Shop Now</a>
                                <a href="#about" class="btn-secondary text-center">Learn More</a>
                            </div>
                            <div class="mt-12 grid grid-cols-3 gap-8">
                                <div class="text-center">
                                    <span class="block text-3xl font-bold text-primary">100+</span>
                                    <span class="text-gray-600">Fruit Varieties</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-3xl font-bold text-primary">24/7</span>
                                    <span class="text-gray-600">Delivery</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-3xl font-bold text-primary">5K+</span>
                                    <span class="text-gray-600">Happy Customers</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hero Image -->
                        <div class="lg:w-1/2 mt-10 lg:mt-0">
                            <img src="https://via.placeholder.com/600x400" alt="Fresh fruits arrangement" class="rounded-xl shadow-xl w-full h-auto">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Featured Products Section -->
            <section id="products" class="py-20">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">Featured Products</h2>
                        <p class="text-gray-600">Discover our handpicked selection of fresh fruits</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <!-- Product Card -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition duration-300 hover:shadow-lg hover:-translate-y-1">
                            <img src="https://via.placeholder.com/300x200" alt="Fresh Apples" class="w-full h-48 object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">Fresh Apples</h3>
                                <p class="text-gray-600 mb-4">Sweet and crispy apples fresh from the orchard</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-bold text-primary">$4.99/kg</span>
                                    <button class="btn-secondary">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <!-- More Product Cards... -->
                    </div>

                    <div class="text-center mt-12">
                        <a href="#" class="btn-primary inline-block">View All Products</a>
                    </div>
                </div>
            </section>

            <!-- Benefits Section -->
            <section id="about" class="py-20 bg-gray-50">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">Why Choose Us</h2>
                        <p class="text-gray-600">Experience the best quality fruits with our premium service</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Benefits -->
                        <div class="bg-white rounded-xl p-8 border border-gray-200 shadow-sm hover:shadow-md transition">
                            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Fresh Quality</h3>
                            <p class="text-gray-600">We ensure that all our fruits are fresh, handpicked, and of the highest quality.</p>
                        </div>
                        <!-- More Benefits... -->
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="py-14 bg-primary">
                <div class="container mx-auto px-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6 text-white">
                        <h3 class="text-2xl md:text-3xl font-semibold">Get fresh, premium fruits delivered today</h3>
                        <a href="#products" class="btn-secondary bg-white text-gray-900 hover:bg-gray-100">Shop Now</a>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white">
            <div class="container mx-auto px-6 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold">Fresh Fruit Market</h4>
                        <p class="text-gray-400 text-sm">Your trusted source for fresh, high-quality fruits delivered to your doorstep.</p>
                        <div class="flex space-x-4">
                            <!-- Social Media Links -->
                            <a href="#" class="text-gray-400 hover:text-white transition">
                                <span class="sr-only">Facebook</span>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                                </svg>
                            </a>
                            <!-- Add more social media links as needed -->
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-2">
                            <li><a href="#home" class="text-gray-400 hover:text-white transition">Home</a></li>
                            <li><a href="#products" class="text-gray-400 hover:text-white transition">Products</a></li>
                            <li><a href="#about" class="text-gray-400 hover:text-white transition">About Us</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-4">Opening Hours</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li>Monday - Friday: 9am - 10pm</li>
                            <li>Saturday - Sunday: Closed</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-4">Contact</h4>
                        <p class="text-gray-400 text-sm">Email: contact@freshfruitmarket.com</p>
                        <p class="text-gray-400 text-sm">Phone: +1 555 1234</p>
                    </div>
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
