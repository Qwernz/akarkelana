<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-stone-200">
                <div class="p-8 text-gray-900">
                    <h3 class="text-lg font-bold mb-8">Halo, {{ Auth::user()->name }}!</h3>

                    @if(Auth::user()->role === 'user')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-orange-50 p-6 rounded-2xl border border-orange-100 shadow-sm text-center">
                            <p class="text-xs font-bold text-orange-600 uppercase tracking-widest">Total Pembelian</p>
                            <p class="text-3xl font-black text-stone-800 mt-2">{{ $totalPembelian }}</p>
                            <p class="text-[10px] text-stone-400 mt-1 italic">Transaksi Selesai</p>
                        </div>

                        <div class="bg-red-50 p-6 rounded-2xl border border-red-100 shadow-sm text-center">
                            <p class="text-xs font-bold text-red-600 uppercase tracking-widest">Belum Dibayar</p>
                            <p class="text-3xl font-black text-stone-800 mt-2">{{ $belumDibayar }}</p>
                            <p class="text-[10px] text-stone-400 mt-1 italic">Status Pending</p>
                        </div>

                        <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100 shadow-sm text-center">
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-widest">Belum Diterima</p>
                            <p class="text-3xl font-black text-stone-800 mt-2">{{ $belumDiterima }}</p>
                            <p class="text-[10px] text-stone-400 mt-1 italic">Sedang Diproses</p>
                        </div>
                    </div>

                    <div class="mt-10 p-6 bg-stone-50 rounded-xl text-center border border-dashed border-stone-200">
                        <p class="text-sm text-stone-500">
                            Rincian riwayat transaksi Anda kini dapat diakses melalui menu
                            <a href="{{ route('orders.history') }}" class="text-orange-600 font-bold underline hover:text-orange-800 transition">Riwayat Pesanan</a>.
                        </p>
                    </div>
                    @endif

                    @if(Auth::user()->role === 'admin')
                    @php
                    $products = \App\Models\Product::with('variants')->latest()->get();
                    // 1. Ambil data Statistik untuk Kartu
                    $totalAkun = \App\Models\User::count();
                    $totalProduk = \App\Models\Product::count();
                    $totalMentahGram = (\App\Models\BahanBaku::sum('stok_kg') ?? 0) * 1000;
                    $totalMatangGram = (\App\Models\BahanBakuMatang::sum('stok_kg') ?? 0) * 1000;

                    // 2. AMBIL DATA PRODUK UNTUK TABEL (Tambahkan baris ini!)
                    $products = \App\Models\Product::with('variants')->latest()->get();

                    // 3. Logika untuk Progress Bar Kapasitas
                    $stokMentahKg = \App\Models\BahanBaku::sum('stok_kg') ?? 0;
                    $stokMatangKg = \App\Models\BahanBakuMatang::sum('stok_kg') ?? 0;

                    $capMentah = 1000;
                    $capMatang = 500;

                    $percMentah = ($stokMentahKg / $capMentah) * 100;
                    $percMatang = ($stokMatangKg / $capMatang) * 100;
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

                        <div class="bg-white p-6 rounded-sm border border-stone-200 shadow-sm relative overflow-hidden group">
                            <div class="relative z-10">
                                <h3 class="text-4xl font-bold text-stone-700 leading-none">{{ $totalAkun }}</h3>
                                <p class="text-xl text-stone-400 font-medium mt-1">Akun User</p>
                                <p class="text-[10px] text-stone-400 mt-2 tracking-tight">Total pengguna terdaftar di sistem</p>
                            </div>
                            <div class="absolute right-4 top-6 opacity-20 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-16 h-16 text-stone-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                </svg>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-sm border border-stone-200 shadow-sm relative overflow-hidden group">
                            <div class="relative z-10">
                                <h3 class="text-4xl font-bold text-stone-700 leading-none">{{ $totalProduk }}</h3>
                                <p class="text-xl text-stone-400 font-medium mt-1">Katalog Produk</p>
                                <p class="text-[10px] text-stone-400 mt-2 tracking-tight">Total jenis varian produk kopi</p>
                            </div>
                            <div class="absolute right-4 top-6 opacity-20 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-16 h-16 text-stone-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z" />
                                </svg>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-sm border border-stone-200 shadow-sm relative overflow-hidden group border-b-4 border-b-stone-800">
                            <div class="relative z-10">
                                <h3 class="text-4xl font-bold text-stone-700 leading-none">{{ number_format($totalMentahGram, 0, ',', '.') }}</h3>
                                <p class="text-xl text-stone-400 font-medium mt-1">Gram Mentah</p>
                                <p class="text-[10px] text-stone-400 mt-2 tracking-tight">Total persediaan green beans</p>
                            </div>
                            <div class="absolute right-4 top-6 opacity-20 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-16 h-16 text-stone-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z" />
                                </svg>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-sm border border-stone-200 shadow-sm relative overflow-hidden group border-b-4 border-b-orange-500">
                            <div class="relative z-10">
                                <h3 class="text-4xl font-bold text-stone-700 leading-none">{{ number_format($totalMatangGram, 0, ',', '.') }}</h3>
                                <p class="text-xl text-stone-400 font-medium mt-1">Gram Matang</p>
                                <p class="text-[10px] text-stone-400 mt-2 tracking-tight">Total persediaan roasted beans</p>
                            </div>
                            <div class="absolute right-4 top-6 opacity-20 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-16 h-16 text-stone-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                                </svg>
                            </div>
                        </div>

                    </div>

                    <div class="bg-white rounded-sm border border-stone-200 shadow-sm overflow-hidden">
                        <div class="bg-stone-50 px-6 py-3 border-b border-stone-100">
                            <h4 class="text-[10px] font-bold text-stone-500 uppercase tracking-widest text-center">Analisis Kapasitas Gudang (Kg)</h4>
                        </div>
                        <div class="p-8 space-y-8">
                            <div>
                                <div class="flex justify-between text-xs mb-2">
                                    <span class="text-stone-600 font-medium tracking-tight italic">Gudang Mentah (Green Beans)</span>
                                    <span class="font-bold text-stone-800">{{ number_format($stokMentahKg, 1) }} / {{ $capMentah }} Kg</span>
                                </div>
                                <div class="w-full bg-stone-100 rounded-none h-4 border border-stone-200">
                                    <div class="bg-stone-400 h-full transition-all duration-700" style="width: {{ min($percMentah, 100) }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-xs mb-2">
                                    <span class="text-orange-600 font-medium tracking-tight italic">Stok Roasted (Bulk)</span>
                                    <span class="font-bold text-orange-800">{{ number_format($stokMatangKg, 1) }} / {{ $capMatang }} Kg</span>
                                </div>
                                <div class="w-full bg-orange-50 rounded-none h-4 border border-orange-100">
                                    <div class="bg-orange-400 h-full transition-all duration-700" style="width: {{ min($percMatang, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(Auth::user()->role === 'kasir')
                    <div class="p-4 bg-blue-50 text-blue-700 rounded-lg border border-blue-100">
                        Anda login sebagai <strong>Kasir</strong>. Silakan kelola pesanan pelanggan melalui menu
                        <a href="{{ route('kasir.orders') }}" class="font-bold underline">Pesanan Masuk</a>.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>