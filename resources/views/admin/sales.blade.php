<x-app-layout>
    {{-- Inisialisasi Alpine.js untuk kontrol TAB --}}
    <div x-data="{ tab: 'pemasukan' }" class="max-w-7xl mx-auto">

        {{-- HEADER & NAVIGASI TAB --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-stone-800 uppercase tracking-tight">Riwayat Keuangan</h1>
                <p class="text-sm text-stone-500">
                    Laporan pada: <span class="font-bold text-orange-700">{{ $label }}</span>
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 no-print">
                {{-- SWITCHER TAB --}}
                <div class="flex bg-stone-100 p-1 rounded-xl border border-stone-200">
                    <button @click="tab = 'pemasukan'"
                        :class="tab === 'pemasukan' ? 'bg-white shadow-sm text-orange-600' : 'text-stone-500 hover:text-stone-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all">
                        Pemasukan
                    </button>
                    <button @click="tab = 'pengeluaran'"
                        :class="tab === 'pengeluaran' ? 'bg-white shadow-sm text-red-600' : 'text-stone-500 hover:text-stone-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all">
                        Pengeluaran
                    </button>
                </div>

                {{-- FORM FILTER (Tetap ada untuk kedua tab) --}}
                <form action="{{ route('admin.sales.history') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    <select name="filter" onchange="this.form.submit()" class="rounded-xl border-stone-200 text-sm focus:ring-orange-500 bg-white shadow-sm">
                        <option value="">Harian</option>
                        <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>Minggu</option>
                        <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>Bulan</option>
                        <option value="year" {{ request('filter') == 'year' ? 'selected' : '' }}>Tahun</option>
                    </select>

                    <div class="flex items-center gap-1 bg-white p-1 rounded-xl shadow-sm border border-stone-200">
                        <input type="date" name="date" value="{{ $selectedDate }}" class="border-none focus:ring-0 text-sm text-stone-700 font-medium p-2">
                        <button type="submit" class="bg-stone-100 hover:bg-stone-200 text-stone-600 p-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </form>

                <button onclick="window.print()" class="bg-stone-800 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 transition hover:bg-stone-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </button>
            </div>
        </div>

        {{-- ================= TAB PEMASUKAN ================= --}}
        <div x-show="tab === 'pemasukan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95">
            <div class="bg-orange-50 border border-orange-100 p-6 rounded-2xl mb-8">
                <p class="text-orange-800 text-sm font-medium uppercase tracking-wider">Total Pendapatan ({{ $label }})</p>
                <h2 class="text-4xl font-black text-orange-900">Rp {{ number_format($total_revenue, 0, ',', '.') }}</h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-stone-200">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-stone-50 text-stone-600 uppercase text-xs font-bold border-b border-stone-200">
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4">Biji Kopi Terjual</th>
                            <th class="px-6 py-4 text-right">Total Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($sales as $sale)
                        <tr>
                            <td class="px-6 py-4 text-sm text-stone-500">{{ $sale->created_at->format('H:i') }} WITA</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-stone-800">{{ $sale->customer_name }}</div>
                                <div class="text-[10px] text-stone-400 uppercase tracking-tighter">ID: #{{ $sale->id }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @foreach($sale->items as $item)
                                <div class="text-xs text-stone-600">
                                    <span class="font-bold text-orange-700">{{ $item->quantity }}x</span> {{ $item->product->name }}
                                </div>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 font-bold text-orange-900 text-right">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-stone-400 italic">Tiada data jualan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ================= TAB PENGELUARAN ================= --}}
        <div x-show="tab === 'pengeluaran'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" style="display: none;">
            <div class="bg-red-50 border border-red-100 p-6 rounded-2xl mb-8">
                <p class="text-red-800 text-sm font-medium uppercase tracking-wider">Total Pengeluaran Bahan Baku ({{ $label }})</p>
                <h2 class="text-4xl font-black text-red-900">Rp {{ number_format($total_spending, 0, ',', '.') }}</h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-stone-200">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-stone-50 text-stone-600 uppercase text-xs font-bold border-b border-stone-200">
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Bahan Baku</th>
                            <th class="px-6 py-4">Jumlah Dibeli</th> {{-- Berubah dari 'Stok Saat Ini' --}}
                            <th class="px-6 py-4 text-right">Biaya Pengeluaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($spending as $log)
                        <tr class="hover:bg-stone-50/50 transition">
                            <td class="px-6 py-4 text-sm text-stone-500">{{ $log->created_at->format('H:i') }} WITA</td>
                            <td class="px-6 py-4 font-bold text-stone-800">{{ $log->nama_bahan }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-stone-100 text-stone-600 px-2 py-1 rounded-md text-[10px] font-bold">
                                    + {{ $log->jumlah_beli }} Kg
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-red-700 text-right">
                                Rp {{ number_format($log->harga_beli, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        {{-- Empty state --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>