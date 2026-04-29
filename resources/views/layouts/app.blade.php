<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Akar Kelana - Coffee Roastery</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

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
            }

            main {
                margin: 0 !important;
                width: 100% !important;
                overflow: visible !important;
            }
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">

        {{-- 1. OVERLAY (Hanya Muncul di HP saat Sidebar Buka) --}}
        <div x-show="sidebarOpen"
            @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-stone-900/60 z-[60] md:hidden" x-cloak>
        </div>

        {{-- 2. SIDEBAR (Fixed di HP, Relative di Desktop) --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="fixed inset-y-0 left-0 z-[70] bg-stone-900 text-white w-64 transform transition duration-300 ease-in-out md:relative md:flex flex-col flex-shrink-0 shadow-2xl">

            {{-- Bagian Logo & Tombol Home --}}
            <div class="p-6 flex items-center gap-2 border-b border-stone-800">
                <a href="{{ route('home') }}" class="flex-shrink-0 p-1.5 rounded-lg text-stone-400 hover:bg-stone-800 hover:text-orange-500 transition-colors" title="Kembali ke Beranda">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>
                <a href="{{ route('home') }}" class="flex items-center overflow-hidden">
                    <img src="{{ asset('Images/Logo.jpg') }}" alt="Logo Akar Kelana" class="w-9 h-9 flex-shrink-0 rounded-lg object-cover border border-stone-800">
                    <span class="ml-2 font-bold text-[11px] leading-tight tracking-tight text-white uppercase whitespace-nowrap">
                        Akar Kelana <br> Coffee Roastery
                    </span>
                </a>
            </div>

            <nav class="flex-1 px-4 space-y-2 mt-4 overflow-y-auto">
                @auth
                <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Dashboard
                </x-sidebar-link>

                @if(Auth::user()->role === 'user')
                <x-sidebar-link :href="route('orders.history')" :active="request()->routeIs('orders.history')">
                    Riwayat Pesanan
                </x-sidebar-link>
                @endif

                @if(Auth::user()->role === 'admin')
                <div class="px-3 pt-4 pb-2 text-[10px] font-bold text-stone-500 uppercase tracking-widest">Manajemen</div>
                <x-sidebar-link :href="route('admin.sales.analysis')" :active="request()->routeIs('admin.sales.analysis')">Analisis Penjualan</x-sidebar-link>
                <x-sidebar-link :href="route('products.index')" :active="request()->routeIs('products.*')">Produk Biji Kopi</x-sidebar-link>
                <x-sidebar-link :href="route('admin.roasting')" :active="request()->routeIs('admin.roasting')">Bahan Baku</x-sidebar-link>
                <x-sidebar-link :href="route('admin.orders')" :active="request()->routeIs('admin.orders')">Laporan Harian</x-sidebar-link>
                <x-sidebar-link :href="route('admin.sales.history')" :active="request()->routeIs('admin.sales.history')">Riwayat Penjualan</x-sidebar-link>
                <x-sidebar-link :href="route('admin.reviews.index')" :active="request()->routeIs('admin.reviews.*')">Ulasan Pelanggan</x-sidebar-link>
                <x-sidebar-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">Daftar Akun</x-sidebar-link>
                @endif

                @if(Auth::user()->role === 'kasir')
                <div class="px-3 pt-4 pb-2 text-xs font-bold text-stone-500 uppercase tracking-widest">Transaksi</div>
                <x-sidebar-link :href="route('kasir.orders')" :active="request()->routeIs('kasir.orders')">Pesanan Masuk</x-sidebar-link>
                <x-sidebar-link :href="route('kasir.history')" :active="request()->routeIs('kasir.history')">Riwayat Pesanan</x-sidebar-link>
                @endif
                @endauth
            </nav>

            @auth
            <div class="p-4 border-t border-stone-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex items-center text-stone-400 hover:text-white w-full px-3 py-2 transition text-sm">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="font-medium">Keluar Akun</span>
                    </button>
                </form>
            </div>
            @endauth
        </aside>

        {{-- 3. AREA KONTEN UTAMA --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Header/Navbar --}}
            <header class="bg-white shadow-sm h-16 flex items-center px-4 md:px-6 justify-between z-50">
                <div class="flex items-center gap-4">
                    {{-- TOMBOL MENU (Hanya muncul di HP) --}}
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-stone-600 hover:bg-stone-100 focus:outline-none transition md:hidden">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-sm font-bold text-stone-400 uppercase tracking-widest hidden sm:block">Sistem Akar Kelana</h2>
                </div>

                {{-- Bagian Profil Kanan --}}
                <div class="flex items-center space-x-3">
                    @auth
                    <div class="text-right">
                        <div class="text-sm font-bold text-stone-700 capitalize leading-none">{{ Auth::user()->name }}</div>
                        <span class="text-[9px] bg-stone-100 text-stone-600 px-2 py-0.5 rounded font-bold uppercase tracking-tighter">{{ Auth::user()->role }}</span>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-stone-800 flex items-center justify-center text-white text-xs font-bold ring-2 ring-stone-100">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    @endauth
                </div>
            </header>

            {{-- Main Content Slot --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-8 bg-gray-50">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>