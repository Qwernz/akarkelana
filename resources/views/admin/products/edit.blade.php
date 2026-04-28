<x-app-layout>
    <div class="max-w-3xl mx-auto py-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-stone-800">Edit Produk: {{ $product->name }}</h2>
            <p class="text-sm text-stone-500">Perbarui informasi produk atau stok biji kopi.</p>
        </div>

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-8 mb-6">
                {{-- Nama & Deskripsi tetap sama --}}
                <div class="mb-5">
                    <label class="block text-sm font-bold text-stone-700 mb-2">Nama Varian Kopi</label>
                    <input type="text" name="name" value="{{ $product->name }}" class="w-full border-stone-300 rounded-lg shadow-sm" required>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-bold text-stone-700 mb-2">Deskripsi</label>
                    <textarea name="description" class="w-full border-stone-300 rounded-lg shadow-sm">{{ $product->description }}</textarea>
                </div>

                <hr class="my-8 border-stone-100">

                {{-- AREA VARIAN BERAT & HARGA --}}
                <h3 class="text-sm font-bold text-stone-700 uppercase mb-4 tracking-wider">Manajemen Varian & Harga</h3>
                <div class="grid grid-cols-1 gap-4">
                    @php
                    $weights = ['100g', '200g', '500g', '1kg'];
                    @endphp

                    @foreach($weights as $weight)
                    @php
                    // Cari apakah produk ini sudah punya data untuk berat ini
                    $variant = $product->variants->where('weight', $weight)->first();
                    @endphp

                    <div class="flex flex-wrap md:flex-nowrap items-center gap-4 bg-stone-50 p-4 rounded-xl border border-stone-200">
                        <div class="flex items-center gap-3 w-32">
                            <input type="checkbox" name="variants[{{ $weight }}][active]" value="1"
                                {{ $variant ? 'checked' : '' }} class="rounded text-orange-600 focus:ring-orange-500 h-5 w-5">
                            <span class="font-bold text-stone-800">{{ $weight }}</span>
                        </div>

                        <div class="flex-1 min-w-[150px]">
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 text-xs">Rp</span>
                                <input type="number" name="variants[{{ $weight }}][price]"
                                    value="{{ $variant ? (int)$variant->price : '' }}"
                                    class="pl-8 w-full rounded-lg border-stone-300 text-sm" placeholder="Harga">
                            </div>
                        </div>

                        <div class="w-32">
                            <input type="number" name="variants[{{ $weight }}][stock]"
                                value="{{ $variant ? $variant->stock : '' }}"
                                class="w-full rounded-lg border-stone-300 text-sm" placeholder="Stok Pk">
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- GANTI FOTO PRODUK --}}
                <div class="mt-8 pt-8 border-t border-stone-100">
                    <h3 class="text-sm font-bold text-stone-700 uppercase mb-4 tracking-wider">Foto Produk</h3>

                    <div class="flex flex-col md:flex-row gap-6 items-start">
                        {{-- Preview Foto Saat Ini --}}
                        <div class="w-32 h-32 bg-stone-100 rounded-xl overflow-hidden border border-stone-200 flex-shrink-0">
                            @if($product->image)
                            <img src="{{ asset($product->image) }}" class="w-full h-full object-cover">
                            @else
                            <div class="flex flex-col items-center justify-center h-full text-stone-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <span class="text-[10px] mt-1 font-bold">NO PHOTO</span>
                            </div>
                            @endif
                        </div>

                        {{-- Input File --}}
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-stone-500 mb-2 uppercase">Unggah Foto Baru (Kosongkan jika tidak diubah)</label>
                            <div class="relative group">
                                <input type="file" name="image" accept="image/*"
                                    class="block w-full text-sm text-stone-500 
                    file:mr-4 file:py-2.5 file:px-4 
                    file:rounded-xl file:border-0 
                    file:text-sm file:font-bold 
                    file:bg-orange-50 file:text-orange-700 
                    hover:file:bg-orange-100 transition cursor-pointer border border-stone-200 rounded-xl p-2 bg-stone-50">
                            </div>
                            <p class="mt-2 text-[11px] text-stone-400 italic">*Format: JPG, PNG, atau JPEG (Maks. 2MB)</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upload Gambar & Tombol Submit --}}
            <div class="flex justify-end gap-4">
                <a href="{{ route('products.index') }}" class="px-6 py-2 text-stone-400 font-bold hover:text-stone-600">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-2.5 rounded-lg font-bold shadow-lg transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>