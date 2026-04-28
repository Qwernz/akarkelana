<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="font-bold text-orange-600 uppercase tracking-tighter">
                        {{ __('Lihat Toko') }}
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- MENU KHUSUS USER (PELANGGAN) --}}
                    @if(Auth::user()->role === 'user')
                    <x-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.history')">
                        {{ __('Riwayat Pesanan') }}
                    </x-nav-link>
                    @endif

                    {{-- MENU KHUSUS ADMIN --}}
                    @if(Auth::user()->role === 'admin')
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        {{ __('Produk Kopi') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.orders')" :active="request()->routeIs('admin.orders')">
                        {{ __('Laporan Penjualan') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('Daftar Akun User') }}
                    </x-nav-link>
                    @endif

                    {{-- MENU KHUSUS KASIR --}}
                    @if(Auth::user()->role === 'kasir')
                    <x-nav-link :href="route('kasir.orders')" :active="request()->routeIs('kasir.orders')">
                        {{ __('Pesanan Masuk') }}
                    </x-nav-link>
                    {{-- Kasir diberi link cepat ke depan untuk bantu pesanan offline --}}
                    <x-nav-link :href="route('home')" class="text-orange-600 font-bold">
                        {{ __('Bantu Pesanan Offline') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 relative">
                            <div class="flex items-center">
                                <div class="flex items-center">
                                    <span class="mr-2 px-2 py-0.5 {{ Auth::user()->role === 'admin' ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600' }} text-[10px] rounded uppercase font-bold">
                                        {{ Auth::user()->role }}
                                    </span>
                                    {{ Auth::user()->name }} {{-- Hapus teks "-> baru di tambahkan" --}}
                                </div>

                                @if(isset($sidebarPendingCount) && $sidebarPendingCount > 0)
                                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[10px] text-white items-center justify-center font-bold">
                                        {{ $sidebarPendingCount }}
                                    </span>
                                </span>
                                @endif

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if(Auth::user()->role === 'user')
                        <x-dropdown-link :href="route('orders.history')">
                            {{ __('Pesanan Saya') }}
                        </x-dropdown-link>
                        @endif

                        @if(Auth::user()->role === 'kasir')
                        <x-dropdown-link :href="route('kasir.orders')">
                            {{ __('Pesanan Masuk') }}
                        </x-dropdown-link>
                        @endif

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile Settings') }}
                        </x-dropdown-link>

                        <div class="border-t border-gray-100"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(Auth::user()->role === 'user')
            <x-responsive-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.history')">
                {{ __('Riwayat Pesanan') }}
            </x-responsive-nav-link>
            @endif

            @if(Auth::user()->role === 'admin')
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                {{ __('Produk Kopi') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                {{ __('Daftar Akun User') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
            <div class="px-4 text-sm text-gray-500">{{ Auth::user()->email }}</div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>