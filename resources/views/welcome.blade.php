<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akar Kelana Coffee Roastery</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <img src="{{ asset('Images/Logo.jpg') }}" alt="Logo Akar Kelana" class="h-12 w-auto object-contain">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            scroll-behavior: smooth;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-stone-50 text-stone-900">

    {{-- NOTIFIKASI TENGAH LAYAR --}}
    @if(session('success'))
    <div x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 3000)"
        x-cloak
        class="fixed inset-0 flex items-center justify-center z-[100] pointer-events-none">
        <div class="bg-stone-900/90 backdrop-blur-md text-white px-8 py-4 rounded-2xl shadow-2xl border border-stone-700 flex flex-col items-center gap-2">
            <div class="bg-green-500 p-2 rounded-full mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <span class="font-bold text-sm tracking-wide uppercase">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    {{-- NAVIGATION (Tetap Sama) --}}
    <nav class="sticky top-0 z-50 flex items-center justify-between px-8 py-4 bg-white/80 backdrop-blur-md shadow-sm border-b">
        <div class="flex items-center">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo Akar Kelana" class="h-12 w-auto object-contain">
        </div>
        <div class="space-x-8 flex items-center">
            <a href="/" class="hover:text-orange-700 font-semibold text-stone-700 transition">Beranda</a>
            <a href="#produk" class="hover:text-orange-700 font-semibold text-stone-700 transition">Produk</a>

            @auth
            {{-- Keranjang HANYA muncul untuk Pelanggan dan Kasir --}}
            @if(Auth::user()->role === 'user' || Auth::user()->role === 'kasir')
            <a href="{{ route('cart') }}" class="relative p-2.5 bg-stone-100 rounded-full hover:bg-stone-200 transition text-stone-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="absolute -top-1 -right-1 bg-orange-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">
                    {{ count((array) session('cart')) }}
                </span>
            </a>
            @endif
            @endauth

            <div x-data="{ open: false }" class="relative inline-block text-left">
                @auth
                <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-3 focus:outline-none group">
                    <div class="flex flex-col items-end leading-tight hidden md:flex">
                        <span class="text-[10px] font-bold text-orange-600 uppercase tracking-widest">{{ Auth::user()->role === 'user' ? 'PELANGGAN' : Auth::user()->role }}</span>
                        <span class="text-sm font-bold text-stone-800">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-stone-800 flex items-center justify-center text-white font-bold border-2 border-stone-200 shadow-sm group-hover:bg-orange-700 transition">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </button>
                <div x-show="open" x-cloak x-transition class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl border border-stone-100 z-[70] overflow-hidden">
                    <div class="px-4 py-3 bg-stone-50 border-b border-stone-100">
                        <p class="text-sm font-bold text-stone-800 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm text-stone-700 hover:bg-orange-50 font-bold border-b border-stone-100">Dashboard</a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm text-stone-700 hover:bg-stone-50 font-medium">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 font-bold border-t border-stone-100">Keluar Akun</button>
                    </form>
                </div>
                @else
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-sm font-bold text-stone-600 hover:text-orange-700 transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-orange-700 text-white text-sm font-bold px-6 py-2.5 rounded-full hover:bg-orange-800 transition shadow-md">Register</a>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- HERO HEADER --}}
    <header class="relative h-[500px] flex items-center justify-center text-center text-white bg-stone-900">
        <div class="absolute inset-0 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?q=80&w=2061&auto=format&fit=crop" class="w-full h-full object-cover opacity-50" alt="Background">
        </div>
        <div class="relative z-10 px-4">
            <h1 class="text-6xl font-bold mb-4 tracking-tight">Akar Kelana Coffee</h1>
            <p class="text-xl mb-10 text-stone-300 max-w-lg mx-auto leading-relaxed">Kopi pilihan terbaik, dipanggang dengan penuh ketelitian langsung dari Banjarmasin.</p>
            <a href="#produk" class="bg-orange-700 hover:bg-orange-800 text-white px-10 py-4 rounded-full font-bold transition shadow-2xl inline-block">Lihat Koleksi Biji Kopi</a>
        </div>
    </header>

    <main id="produk" class="max-w-7xl mx-auto px-8 py-20">
        <div class="flex flex-col items-center justify-center mb-16 text-center">
            <h2 class="text-4xl font-bold text-stone-900 tracking-tight">Pilihan Biji Kopi</h2>
            <div class="h-1.5 w-24 bg-orange-700 mt-4 rounded-full"></div>
            <p class="mt-4 text-stone-500 max-w-md">Koleksi biji kopi pilihan dari petani lokal.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 justify-items-center">
            @forelse($products as $product)
            {{-- LOGIKA ALPINE UNTUK STOK & HARGA --}}
            @php
            $firstVar = $product->variants->where('is_active', true)->sortBy('price')->first();
            @endphp

            <div x-data="{ 
                price: {{ $firstVar->price ?? 0 }},
                variantId: {{ $firstVar->id ?? 0 }},
                stock: {{ $firstVar->stock ?? 0 }},
                selectedWeight: '{{ $firstVar->weight ?? '' }}'
            }"
                class="group bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden hover:shadow-2xl transition duration-500 w-full max-w-xs flex flex-col">

                {{-- GAMBAR PRODUK --}}
                <div class="relative overflow-hidden">
                    <img src="{{ $product->image ? asset($product->image) : 'https://placehold.co/400x400' }}" class="w-full h-64 object-cover group-hover:scale-110 transition duration-700" alt="{{ $product->name }}">
                </div>

                {{-- DETAIL PRODUK --}}
                <div class="p-6 flex flex-col flex-1 text-center items-center">
                    <h3 class="font-bold text-xl text-stone-800 group-hover:text-orange-700 transition">{{ $product->name }}</h3>
                    <p class="text-stone-500 text-sm mt-3 line-clamp-2">{{ $product->description }}</p>

                    <div class="mt-auto pt-6 flex flex-col items-center w-full space-y-3">

                        {{-- PILIHAN BERAT --}}
                        <div class="flex flex-col items-center mb-2 w-full">
                            @if($product->variants->isNotEmpty())
                            <div class="flex flex-wrap justify-center gap-1.5 mb-3">
                                @foreach($product->variants->where('is_active', true) as $variant)
                                <button
                                    @click="price = {{ $variant->price }}; variantId = {{ $variant->id }}; stock = {{ $variant->stock }}; selectedWeight = '{{ $variant->weight }}'"
                                    type="button"
                                    class="text-[9px] px-2.5 py-1 rounded-full border-2 font-bold transition-all duration-200"
                                    :class="selectedWeight === '{{ $variant->weight }}' 
                                            ? 'border-orange-600 bg-orange-50 text-orange-700' 
                                            : 'border-stone-100 bg-stone-50 text-stone-500 hover:border-stone-300'">
                                    {{ $variant->weight }}
                                </button>
                                @endforeach
                            </div>

                            {{-- STOK DINAMIS --}}
                            <span class="text-[10px] font-bold uppercase tracking-wider mb-1"
                                :class="stock > 0 ? 'text-stone-400' : 'text-red-500'">
                                Stok: <span x-text="stock"></span> Pack
                            </span>

                            {{-- HARGA DINAMIS --}}
                            <div class="flex items-baseline gap-1">
                                <span class="font-bold text-orange-900 text-2xl">
                                    Rp <span x-text="new Intl.NumberFormat('id-ID').format(price)"></span>
                                </span>
                            </div>
                            @endif
                        </div>

                        {{-- TOMBOL DETAIL --}}
                        <a href="{{ route('product.detail', $product->id) }}" class="w-full border-2 border-stone-100 text-stone-500 py-2.5 rounded-2xl hover:border-stone-800 hover:text-stone-800 transition font-bold text-xs uppercase tracking-widest flex items-center justify-center">
                            Lihat Detail
                        </a>

                        {{-- TOMBOL KERANJANG DINAMIS --}}
                        @auth
                        {{-- Izinkan Pelanggan (user) DAN Kasir untuk belanja --}}
                        @if(Auth::user()->role === 'user' || Auth::user()->role === 'kasir')
                        @if($product->variants->isNotEmpty())
                        {{-- Jika Stok Ada --}}
                        <template x-if="stock > 0">
                            <form :action="'/add-to-cart/' + variantId" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-stone-900 hover:bg-orange-700 text-white py-3 rounded-xl font-bold transition-all duration-300 flex items-center justify-center gap-2 text-xs uppercase tracking-widest active:scale-95 shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    Tambah Ke Keranjang
                                </button>
                            </form>
                        </template>

                        {{-- Jika Stok Habis --}}
                        <template x-if="stock <= 0">
                            <button disabled class="w-full bg-stone-200 text-stone-400 py-3 rounded-xl font-bold text-xs uppercase tracking-widest cursor-not-allowed border border-stone-300">
                                Stok Habis
                            </button>
                        </template>
                        @endif
                        @else
                        {{-- Tampilan khusus Admin (Mode View Saja) --}}
                        <div class="w-full p-3 bg-stone-50 border border-dashed border-stone-200 rounded-xl text-center">
                            <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">Mode View Admin</p>
                        </div>
                        @endif
                        @else
                        {{-- Jika Belum Login --}}
                        <a href="{{ route('login') }}" class="w-full bg-stone-100 text-stone-600 py-3 rounded-xl font-bold text-center text-xs uppercase tracking-widest block border border-stone-200 hover:bg-stone-200 transition">
                            Login untuk Memesan
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-24 bg-white rounded-3xl border-2 border-dashed border-stone-200 w-full">
                <p class="text-stone-500 font-medium italic">Produk belum tersedia.</p>
            </div>
            @endforelse
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-stone-950 text-stone-500 py-16 text-center border-t border-stone-900">
        <div class="mb-6">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo Footer" class="h-12 mx-auto grayscale opacity-50 mb-4">
            <p class="text-xs mt-2">&copy; {{ date('Y') }} Akar Kelana Coffee Roastery.</p>
        </div>
    </footer>

</body>

</html>
