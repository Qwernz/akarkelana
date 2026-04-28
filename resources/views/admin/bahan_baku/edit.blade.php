<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-stone-800">Edit Bahan Baku</h2>
        <p class="text-sm text-stone-500">Perbarui data stok atau informasi biji kopi mentah.</p>
    </div>

    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-stone-200 p-6">
        <form action="{{ route('bahan_baku.update', $bahan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-stone-700 mb-1">Nama Bahan</label>
                    <input type="text" name="nama_bahan" value="{{ $bahan->nama_bahan }}" class="w-full rounded-lg border-stone-300 focus:border-orange-500 focus:ring-orange-500" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-stone-700 mb-1">Stok (Kg)</label>
                        <input type="number" name="stok_kg" value="{{ $bahan->stok_kg }}" class="w-full rounded-lg border-stone-300 focus:border-orange-500 focus:ring-orange-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-stone-700 mb-1">Asal Daerah</label>
                        <input type="text" name="asal_daerah" value="{{ $bahan->asal_daerah }}" class="w-full rounded-lg border-stone-300 focus:border-orange-500 focus:ring-orange-500">
                    </div>
                </div>

                <div class="pt-4 flex space-x-2">
                    <button type="submit" class="bg-orange-700 hover:bg-orange-800 text-white px-6 py-2 rounded-lg font-bold transition">
                        Update Bahan
                    </button>
                    <a href="{{ route('bahan_baku.index') }}" class="bg-stone-100 hover:bg-stone-200 text-stone-600 px-6 py-2 rounded-lg font-bold transition">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>