<nav x-data="{ open: false }" class="bg-green-50/70 backdrop-blur border-b border-green-100 relative z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-green-700" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @can(['manage users', 'manage roles'])
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                        {{ __('Users') }}
                    </x-nav-link>
                    @endcan

                    @can(['manage penjualan'])
                    <x-nav-link :href="route('pelanggan.index')" :active="request()->routeIs('pelanggan.index')">
                        {{ __('Pelanggan') }}
                    </x-nav-link>
                    <x-nav-link :href="route('penjualan.index')" :active="request()->routeIs('penjualan.index')">
                        {{ __('Penjualan') }}
                    </x-nav-link>
                    @endcan

                    @can(['manage pelanggan', 'manage produk'])
                    <x-nav-link :href="route('pelanggan.index')" :active="request()->routeIs('pelanggan.index')">
                        {{ __('Pelanggan') }}
                    </x-nav-link>
                    <x-nav-link :href="route('produk.index')" :active="request()->routeIs('produk.index')">
                        {{ __('Produk') }}
                    </x-nav-link>
                    @endcan

                    <x-nav-link :href="route('detail-penjualan.index')" :active="request()->routeIs('detail-penjualan.index')">
                        {{ __('Detail penjualan') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 relative z-50">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-green-700 bg-green-50 hover:text-green-800 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-200 transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-green-700 hover:text-green-800 hover:bg-green-100 focus:outline-none focus:bg-green-100 focus:text-green-800 transition duration-150 ease-in-out" aria-label="Toggle navigation">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-green-800">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @can(['manage users', 'manage roles'])
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" class="text-green-800">
                {{ __('Users') }}
            </x-responsive-nav-link>
            @endcan

            @can(['manage penjualan'])
            <x-responsive-nav-link :href="route('penjualan.index')" :active="request()->routeIs('penjualan.index')" class="text-green-800">
                {{ __('Penjualan') }}
            </x-responsive-nav-link>
            @endcan

            @can(['manage pelanggan', 'manage produk'])
            <x-responsive-nav-link :href="route('pelanggan.index')" :active="request()->routeIs('pelanggan.index')" class="text-green-800">
                {{ __('Pelanggan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('produk.index')" :active="request()->routeIs('produk.index')" class="text-green-800">
                {{ __('Produk') }}
            </x-responsive-nav-link>
            @endcan

            <x-responsive-nav-link :href="route('detail-penjualan.index')" :active="request()->routeIs('detail-penjualan.index')" class="text-green-800">
                {{ __('Detail penjualan') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-green-100">
            <div class="px-4">
                <div class="font-medium text-base text-green-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-green-700/70">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>