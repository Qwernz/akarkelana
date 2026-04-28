<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-stone-800">Manajemen Bahan Baku</h2>
            <p class="text-sm text-stone-500">Daftar persediaan biji kopi mentah (Green Beans) Akar Kelana.</p>
        </div>

        <a href="{{ route('bahan_baku.create') }}" class="bg-orange-700 hover:bg-orange-800 text-white px-4 py-2 rounded-lg shadow-sm transition flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Bahan Baku
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-stone-200">
        <table class="w-full text-left border-collapse">
            <thead class="bg-stone-50 border-b border-stone-200">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-stone-600 uppercase">Nama Bahan</th>
                    <th class="px-6 py-4 text-xs font-bold text-stone-600 uppercase text-center">Stok Mentah</th>
                    <th class="px-6 py-4 text-xs font-bold text-stone-600 uppercase">Asal Daerah</th>
                    <th class="px-6 py-4 text-xs font-bold text-stone-600 uppercase text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @foreach($bahanBaku as $bahan)
                <tr class="hover:bg-stone-50 transition">
                    <td class="px-6 py-4 font-semibold text-stone-800">{{ $bahan->nama_bahan }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $bahan->stok_kg < 5 ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600' }}">
                            {{ $bahan->stok_kg }} Kg
                        </span>
                    </td>
                    <td class="px-6 py-4 text-stone-600 font-medium">{{ $bahan->asal_daerah ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('bahan_baku.edit', $bahan->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </a>
                            <form action="{{ route('bahan_baku.destroy', $bahan->id) }}" method="POST" onsubmit="return confirm('Hapus data bahan baku ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>