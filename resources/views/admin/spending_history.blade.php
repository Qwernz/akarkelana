<x-app-layout>
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-3xl font-black text-stone-800 uppercase tracking-tight">Riwayat Pengeluaran</h2>
            <p class="text-stone-500 text-sm font-bold">Total pengeluaran bahan baku mentah</p>
        </div>
        <x-sidebar-link :href="route('admin.roasting')" class="bg-stone-800 text-white pb-2.5 px-6 rounded-xl hover:bg-stone-700 transition">
            + Restock Baru
        </x-sidebar-link>
    </div>

    <div class="mb-8 p-8 bg-red-50 border border-red-100 rounded-3xl">
        <p class="text-xs font-black text-red-400 uppercase tracking-widest mb-1">Total Pengeluaran Kumulatif</p>
        <h3 class="text-4xl font-black text-red-600">Rp {{ number_format($totalSpending, 0, ',', '.') }}</h3>
    </div>

    <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-stone-50 border-b border-stone-100">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-black text-stone-400 uppercase tracking-widest">Update Terakhir</th>
                    <th class="px-8 py-5 text-[10px] font-black text-stone-400 uppercase tracking-widest">Jenis Bahan Baku</th>
                    <th class="px-8 py-5 text-[10px] font-black text-stone-400 uppercase tracking-widest">Stok Saat Ini</th>
                    <th class="px-8 py-5 text-[10px] font-black text-stone-400 uppercase tracking-widest text-right">Total Biaya Masuk</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @foreach($spending as $item)
                <tr class="hover:bg-stone-50/50 transition">
                    <td class="px-8 py-5 text-sm text-stone-500 font-medium">
                        {{ $item->updated_at->format('d M Y | H:i') }} WITA
                    </td>
                    <td class="px-8 py-5 font-bold text-stone-800">
                        {{ $item->nama_bahan }}
                    </td>
                    <td class="px-8 py-5">
                        <span class="bg-stone-100 text-stone-600 px-3 py-1 rounded-full text-xs font-bold">
                            {{ $item->stok_kg }} Kg
                        </span>
                    </td>
                    <td class="px-8 py-5 text-right font-black text-red-600 text-lg">
                        Rp {{ number_format($item->total_biaya_pengeluaran, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>