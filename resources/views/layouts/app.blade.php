<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    {{-- Meta Viewport Penting untuk HP --}}
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Akar Kelana - Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpeg" href="{{ asset('Images/Logo.jpg') }}">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        @media print {

            aside,
            header,
            nav,
            button,
            .no-print {
                display: none !important;
            }

            main {
                margin: 0 !important;
                width: 100% !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 antialiased">
    {{-- HANYA SATU x-data DI SINI UNTUK SELURUH DASHBOARD --}}
    <div x-data="{ sidebarOpen: false }" class="relative min-h-screen md:flex" x-cloak>

        {{-- MOBILE HEADER: Muncul cuma di HP --}}
        <div class="bg-stone-900 text-white flex justify-between items-center p-4 md:hidden fixed top-0 left-0 right-0 z-[60]">
            <span class="font-bold uppercase tracking-widest text-xs">Akar Kelana</span>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 focus:outline-none bg-stone-800 rounded-lg">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- SIDEBAR: Menggunakan Transform agar tidak menutupi tombol di HP --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-[70] bg-stone-900 text-white w-64 transform md:relative md:translate-x-0 transition duration-300 ease-in-out shadow-2xl flex flex-col">

            <div class="p-6 flex items-center gap-3 border-b border-stone-800">
                <img src="{{ asset('Images/Logo.jpg') }}" alt="Logo" class="w-10 h-10 rounded-lg object-cover border border-stone-700">
                <div class="leading-tight">
                    <p class="font-bold text-xs uppercase tracking-tight text-white">Akar Kelana</p>
                    <p class="text-[9px] text-stone-500 uppercase">Coffee Roastery</p>
                </div>
            </div>

            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                @auth
                <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Dashboard
                </x-sidebar-link>

                @if(Auth::user()->role === 'admin')
                <div class="px-3 pt-4 pb-1 text-[10px] font-black text-stone-600 uppercase tracking-[0.2em]">Manajemen</div>
                <x-sidebar-link :href="route('admin.sales.analysis')" :active="request()->routeIs('admin.sales.analysis')">Analisis</x-sidebar-link>
                <x-sidebar-link :href="route('products.index')" :active="request()->routeIs('products.*')">Produk Kopi</x-sidebar-link>
                <x-sidebar-link :href="route('admin.roasting')" :active="request()->routeIs('admin.roasting')">Bahan Baku</x-sidebar-link>
                <x-sidebar-link :href="route('admin.orders')" :active="request()->routeIs('admin.orders')">Laporan</x-sidebar-link>
                <x-sidebar-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">Daftar Akun</x-sidebar-link>
                @endif

                @if(Auth::user()->role === 'kasir')
                <div class="px-3 pt-4 pb-1 text-[10px] font-black text-stone-600 uppercase tracking-[0.2em]">Transaksi</div>
                <x-sidebar-link :href="route('kasir.orders')" :active="request()->routeIs('kasir.orders')">Pesanan Masuk</x-sidebar-link>
                @endif
                @endauth
            </nav>

            @auth
            <div class="p-4 border-t border-stone-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex items-center text-stone-400 hover:text-red-400 w-full px-3 py-2 transition text-sm font-bold">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
            @endauth
        </aside>

        {{-- MAIN CONTENT AREA --}}
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            {{-- Header Desktop --}}
            <header class="bg-white shadow-sm h-16 hidden md:flex items-center px-8 justify-between z-40">
                <h2 class="text-sm font-bold text-stone-400 uppercase tracking-widest">Dashboard System</h2>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-xs font-bold text-stone-800">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] text-orange-600 font-bold uppercase">{{ Auth::user()->role }}</p>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-stone-800 flex items-center justify-center text-white text-xs font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            {{-- Slot Konten Utama --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-8 pt-20 md:pt-8 bg-gray-50">
                {{ $slot }}
            </main>
        </div>

        {{-- Overlay HP: Supaya tombol di bawah bisa dipencet kalau sidebar tutup --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-[65] md:hidden"></div>
    </div>
</body>

</html>