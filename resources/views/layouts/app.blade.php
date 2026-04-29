<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Akar Kelana - Coffee Roastery</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {

            aside,
            header,
            nav,
            form,
            button,
            .no-print {
                display: none !important;
            }

            body {
                background-color: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            main {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                overflow: visible !important;
            }

            .shadow-sm,
            .shadow-md,
            .shadow-lg {
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
            }

            .print-only {
                display: block !important;
            }
        }

        .print-only {
            display: none;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen">

        {{-- SIDEBAR --}}
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-stone-900 text-white transition-all duration-300 flex-shrink-0 hidden md:flex flex-col">

            <div class="p-6 flex items-center gap-2">
                <a href="{{ route('home') }}" class="flex-shrink-0 p-1.5 rounded-lg text-stone-400 hover:bg-stone-800 hover:text-orange-500 transition-colors" title="Kembali ke Beranda">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>
                <a href="{{ route('home') }}" class="flex items-center overflow-hidden">
                    <img src="{{ asset('Images/Logo.jpg') }}" alt="Logo Akar Kelana" class="w-9 h-9 flex-shrink-0 rounded-lg object-cover border border-stone-800">
                    <span x-show="sidebarOpen" class="ml-2 font-bold text-[11px] leading-tight tracking-tight text-white uppercase">
                        Akar Kelana <br> Coffee Roastery
                    </span>
                </a>
            </div>

            <nav class="flex-1 px-4 space-y-2 mt-4">
                @auth
                {{-- MENU UNTUK YANG SUDAH LOGIN --}}
                <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="...">
                    Dashboard
                </x-sidebar-link>

                @if(Auth::user()->role === 'user')
                <x-sidebar-link :href="route('orders.history')" :active="request()->routeIs('orders.history')">
                    Riwayat Pesanan
                </x-sidebar-link>
                @endif

                @if(Auth::user()->role === 'admin')
                <div x-show="sidebarOpen" class="px-3 pt-4 pb-2 text-xs font-bold text-stone-500 uppercase tracking-widest">Manajemen</div>

                <x-sidebar-link :href="route('admin.sales.analysis')" :active="request()->routeIs('admin.sales.analysis')">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Analisis Penjualan
                </x-sidebar-link>

                <x-sidebar-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Produk Biji Kopi
                </x-sidebar-link>

                <x-sidebar-link :href="route('admin.roasting')" :active="request()->routeIs('admin.roasting')">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    Bahan Baku Biji Kopi
                </x-sidebar-link>

                <x-sidebar-link :href="route('admin.orders')" :active="request()->routeIs('admin.orders')">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2" />
                    </svg>
                    Laporan Harian
                </x-sidebar-link>

                <x-sidebar-link :href="route('admin.sales.history')" :active="request()->routeIs('admin.sales.history')">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Penjualan
                </x-sidebar-link>

                <x-sidebar-link :href="route('admin.reviews.index')" :active="request()->routeIs('admin.reviews.*')">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Ulasan Pelanggan
                </x-sidebar-link>

                <x-sidebar-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-12 0v1zm0-10a4 4 0 110 8 4 4 0 010-8z" />
                    </svg>
                    Daftar Akun
                </x-sidebar-link>
                @endif

                @if(Auth::user()->role === 'kasir')
                <div x-show="sidebarOpen" class="px-3 pt-4 pb-2 text-xs font-bold text-stone-500 uppercase tracking-widest">Transaksi</div>
                <x-sidebar-link :href="route('kasir.orders')" :active="request()->routeIs('kasir.orders')">Pesanan Masuk</x-sidebar-link>
                <x-sidebar-link :href="route('kasir.history')" :active="request()->routeIs('kasir.history')">Riwayat Pesanan</x-sidebar-link>
                @endif

                @else
                {{-- MENU UNTUK PENGUNJUNG (GUEST) --}}
                <p x-show="sidebarOpen" class="text-[10px] text-stone-500 px-4 mt-4 uppercase font-bold tracking-widest">Menu Pengunjung</p>
                <x-sidebar-link :href="route('login')">Masuk / Login</x-sidebar-link>
                <x-sidebar-link :href="route('home')">Lihat Katalog</x-sidebar-link>
                @endauth
            </nav>

            @auth
            <div class="p-4 border-t border-stone-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex items-center text-stone-400 hover:text-white w-full px-3 py-2 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3 font-medium">Keluar Akun</span>
                    </button>
                </form>
            </div>
            @endauth
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm h-16 flex items-center px-6 justify-between">
                <button @click="sidebarOpen = !sidebarOpen" class="text-stone-600 focus:outline-none hover:text-stone-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <div class="flex items-center space-x-4">
                    @auth
                    <div class="text-right">
                        <div class="text-sm font-bold text-stone-700 capitalize">{{ Auth::user()->name }}</div>
                        <span class="text-[10px] bg-stone-100 text-stone-600 px-2 py-0.5 rounded font-bold uppercase tracking-tighter">{{ Auth::user()->role }}</span>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-stone-800 flex items-center justify-center text-white text-xs font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-orange-600 hover:text-orange-700 uppercase tracking-tighter">Login</a>
                    @endauth
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 bg-gray-50">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>