<x-app-layout>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-black text-stone-400 uppercase tracking-widest">Pemasukan (Omzet)</p>
            <h3 class="text-xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-black text-stone-400 uppercase tracking-widest">Estimasi Pengeluaran</p>
            <h3 class="text-xl font-bold text-red-500">Rp {{ number_format($totalSpending, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-black text-stone-400 uppercase tracking-widest">Biji Mentah / Matang</p>
            <h3 class="text-lg font-bold text-stone-800">
                {{ number_format($stokMentah, 1) }}kg / {{ number_format($stokMatang, 1) }}kg
            </h3>
        </div>
        <div class="bg-stone-800 p-5 rounded-2xl shadow-sm text-white">
            <p class="text-[10px] font-black text-stone-400 uppercase tracking-widest">Total Stok Produk</p>
            <h3 class="text-xl font-bold">{{ $totalStock }} <span class="text-xs font-normal">Pack Tersisa</span></h3>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-stone-100 flex justify-between items-center">
            <h4 class="text-xs font-bold text-stone-600 uppercase tracking-widest">Feedback & Rating Pelanggan</h4>
            <span class="text-[10px] bg-stone-100 px-2 py-1 rounded-md text-stone-500 font-bold uppercase">5 Terbaru</span>
        </div>

        {{-- KUNCI SCROLL: Tambahkan max-height dan overflow --}}
        {{-- Di sini kita atur tingginya agar muat sekitar 2 ulasan awal, sisanya harus scroll --}}
        <div class="divide-y divide-stone-100 max-h-[400px] overflow-y-auto custom-scrollbar">
            @forelse($reviews as $review)
            <div class="p-6 hover:bg-stone-50/50 transition">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-sm font-bold text-stone-800">{{ $review->user->name ?? 'Pelanggan' }}</p>
                        <p class="text-[10px] text-stone-400 uppercase font-medium">
                            {{ $review->items->first()->name ?? 'Produk Biji Kopi' }}
                        </p>
                    </div>
                    <div class="flex items-center gap-0.5 text-orange-400">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-current' : 'text-stone-200' }}" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            @endfor
                    </div>
                </div>
                <p class="text-sm text-stone-600 italic">"{{ $review->review }}"</p>
                <p class="text-[9px] text-stone-400 mt-2">{{ $review->created_at->diffForHumans() }}</p>
            </div>
            @empty
            <div class="p-12 text-center text-stone-400 italic text-sm">Belum ada ulasan dari pelanggan.</div>
            @endforelse
        </div>

        {{-- FOOTER: Link ke halaman semua ulasan --}}
        <div class="bg-stone-50 border-t border-stone-100 p-4 text-center">
            <a href="{{ route('admin.reviews.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-700 transition uppercase tracking-widest">
                Lihat Semua Ulasan →
            </a>
        </div>
    </div>

    {{-- CSS Tambahan agar scrollbar terlihat lebih minimalis (Opsional) --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f5f5f4;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #d6d3d1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a29e;
        }
    </style>

    <div class="mb-8 p-6 bg-orange-50 border border-orange-100 rounded-2xl flex items-center justify-between">
        <div>
            <span class="bg-orange-500 text-white text-[9px] font-black px-2 py-1 rounded-md uppercase tracking-tighter">Best Seller 🔥</span>
            <h4 class="text-2xl font-black text-stone-800 mt-1">{{ $bestSeller->product->name ?? 'Belum ada data' }}</h4>
        </div>
        <div class="text-right">
            <p class="text-xs text-stone-500 uppercase font-bold tracking-widest">Total Terjual</p>
            <p class="text-3xl font-black text-orange-600">{{ $bestSeller->total_qty ?? 0 }} <span class="text-sm">Pack</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
            <h4 class="text-xs font-bold text-stone-600 uppercase tracking-widest mb-4">Stok Bahan Baku (Kg)</h4>
            <div class="h-[250px]"><canvas id="chartBiji"></canvas></div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
            <h4 class="text-xs font-bold text-stone-600 uppercase tracking-widest mb-4">Arus Kas (Rp)</h4>
            <div class="h-[250px] flex justify-center"><canvas id="chartKeuangan"></canvas></div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm mb-6">
        <h4 class="text-xs font-bold text-stone-600 uppercase tracking-widest mb-4">Visualisasi Produk Terjual (Pack)</h4>
        <div class="w-full overflow-x-auto">
            <div style="min-width: 600px; height: 300px;">
                <canvas id="productChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. GRAFIK BIJI ---
            // --- 1. GRAFIK BIJI ---
            new Chart(document.getElementById('chartBiji'), {
                type: 'bar',
                data: {
                    labels: ['Biji Mentah', 'Biji Matang'],
                    datasets: [{
                        data: [@json($stokMentah), @json($stokMatang)],
                        backgroundColor: ['#78716c', '#2c2a26'],
                        borderRadius: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // --- 2. GRAFIK KEUANGAN ---
            // --- 2. GRAFIK KEUANGAN ---
            new Chart(document.getElementById('chartKeuangan'), {
                type: 'doughnut',
                data: {
                    labels: ['Pemasukan', 'Pengeluaran'],
                    datasets: [{
                        data: [@json($totalRevenue), @json($totalSpending)],
                        backgroundColor: ['#16a34a', '#dc2626'],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // --- 3. GRAFIK PRODUK ---
            new Chart(document.getElementById('productChart'), {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Pack Terjual',
                        data: @json($chartData),
                        backgroundColor: '#f97316',
                        borderRadius: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>