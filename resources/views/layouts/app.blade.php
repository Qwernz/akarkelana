<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Akar Kelana - Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Animasi halus untuk transisi lebar sidebar */
        .sidebar-transition {
            transition-property: width, transform;
            transition-duration: 300ms;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen overflow-hidden">

        {{-- OVERLAY UNTUK MOBILE --}}
        <div x-show="sidebarOpen"
            class="fixed inset-0 bg-stone-900/60 z-[60] md:hidden"
            @click="sidebarOpen = false" x-cloak>
        </div>

        {{-- SIDEBAR --}}
        <aside
            {{-- Logika: Di desktop lebar berubah w-64 ke w-20. Di HP geser keluar masuk --}}
            :class="{
                'w-64': sidebarOpen, 
                'w-20': !sidebarOpen,
                'translate-x-0': sidebarOpen,
                '-translate-x-full md:translate-x-0': !sidebarOpen
            }"
            class="fixed inset-y-0 left-0 z-[70] bg-stone-900 text-white sidebar-transition transform md:relative md:flex flex-col flex-shrink-0 shadow-2xl overflow-hidden">

            {{-- Logo Section --}}
            <div class="p-6 flex items-center gap-3 border-b border-stone-800 min-w-[256px]">
                <a href="{{ route('home') }}" class="flex-shrink-0 p-1.5 rounded-lg text-stone-400 hover:bg-stone-800 hover:text-orange-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>
                <div class="flex items-center overflow-hidden transition-all duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 invisible'">
                    <img src="{{ asset('Images/Logo.jpg') }}" alt="Logo" class="w-8 h-8 flex-shrink-0 rounded-lg object-cover border border-stone-800">
                    <span class="ml-2 font-bold text-[10px] leading-tight text-white uppercase whitespace-nowrap">
                        Akar Kelana <br> Coffee Roastery
                    </span>
                </div>
            </div>

            {{-- Navigasi --}}
            <nav class="flex-1 px-4 space-y-2 mt-4 overflow-y-auto overflow-x-hidden">
                @auth
                <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Dashboard
                </x-sidebar-link>

                @if(Auth::user()->role === 'admin')
                <div class="px-3 pt-4 pb-2 text-[10px] font-bold text-stone-500 uppercase tracking-widest truncate" x-show="sidebarOpen">Manajemen</div>
                <x-sidebar-link :href="route('admin.sales.analysis')" :active="request()->routeIs('admin.sales.analysis')">
                    Analisis Penjualan
                </x-sidebar-link>
                <x-sidebar-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                    Produk
                </x-sidebar-link>
                <x-sidebar-link :href="route('admin.roasting')" :active="request()->routeIs('admin.roasting')">
                    Bahan Baku
                </x-sidebar-link>
                <x-sidebar-link :href="route('admin.orders')" :active="request()->routeIs('admin.orders')">
                    Laporan
                </x-sidebar-link>
                @endif
                @endauth
            </nav>

            {{-- Logout --}}
            <div class="p-4 border-t border-stone-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex items-center text-stone-400 hover:text-red-400 w-full px-3 py-2 transition text-sm">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="ml-3 font-medium transition-all duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 invisible'">Keluar Akun</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white shadow-sm h-16 flex items-center px-4 md:px-6 justify-between z-50">
                <div class="flex items-center gap-4">
                    {{-- TOMBOL HAMBURGER TETAP ADA DI DESKTOP & MOBILE --}}
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-stone-600 hover:bg-stone-100 focus:outline-none transition">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-sm font-bold text-stone-400 uppercase tracking-widest hidden sm:block">Sistem Roastery</h2>
                </div>

                {{-- Profile Section --}}
                <div class="flex items-center space-x-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-stone-800">{{ Auth::user()->name }}</p>
                        <span class="text-[9px] text-orange-600 font-bold uppercase">{{ Auth::user()->role }}</span>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-stone-800 flex items-center justify-center text-white text-xs font-bold ring-2 ring-stone-100">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-8 bg-gray-50">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>