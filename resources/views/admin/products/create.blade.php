<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-stone-800 uppercase tracking-tight">Tambah Produk Baru</h1>
            <p class="text-sm text-stone-500">Input detail kopi dan tentukan harga untuk setiap varian berat.</p>
        </div>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="bg-white rounded-2xl shadow-sm border border-stone-200 p-8 space-y-8">

                {{-- 1. INFORMASI DASAR --}}
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black text-stone-500 uppercase tracking-widest mb-2">Nama Produk Kopi</label>
                        <input type="text" name="name" placeholder="Contoh: Kerinci Thermal Shock"
                            class="w-full border-stone-200 rounded-xl focus:ring-orange-500 focus:border-orange-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-stone-500 uppercase tracking-widest mb-2">Deskripsi Produk</label>
                        <textarea name="description" rows="4" placeholder="Ceritakan tentang profil rasa, proses, atau asal biji kopi ini..."
                            class="w-full border-stone-200 rounded-xl focus:ring-orange-500 focus:border-orange-500"></textarea>
                    </div>
                </div>

                <hr class="border-stone-100">

                {{-- 2. MANAJEMEN VARIAN & HARGA --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-stone-800 uppercase tracking-wider">Varian Berat & Harga</h3>
                        <span class="text-[10px] text-stone-400 font-bold">*Aktifkan varian yang tersedia</span>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        @php $weights = ['100g', '200g', '500g', '1kg']; @endphp

                        @foreach($weights as $weight)
                        <div class="flex flex-wrap md:flex-nowrap items-center gap-4 bg-stone-50 p-4 rounded-xl border border-stone-100 hover:border-orange-200 transition">
                            {{-- Checkbox Aktif --}}
                            <div class="flex items-center gap-3 w-32">
                                <input type="checkbox" name="variants[{{ $weight }}][active]" value="1" checked
                                    class="rounded text-orange-600 focus:ring-orange-500 h-5 w-5 cursor-pointer">
                                <span class="font-bold text-stone-700 text-sm">{{ $weight }}</span>
                            </div>

                            {{-- Input Harga --}}
                            <div class="flex-1 min-w-[150px]">
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 text-xs font-bold">Rp</span>
                                    <input type="number" name="variants[{{ $weight }}][price]"
                                        class="pl-9 w-full rounded-lg border-stone-200 text-sm focus:ring-orange-500" placeholder="Harga Jual">
                                </div>
                            </div>

                            {{-- Input Stok --}}
                            <div class="w-32">
                                <div class="relative">
                                    <input type="number" name="variants[{{ $weight }}][stock]"
                                        class="w-full rounded-lg border-stone-200 text-sm focus:ring-orange-500" placeholder="Stok">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-stone-400 font-bold">PK</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <hr class="border-stone-100">

                {{-- 3. UNGGAH FOTO --}}
                <div>
                    <h3 class="text-sm font-bold text-stone-800 uppercase tracking-wider mb-4">Foto Produk</h3>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-stone-200 border-dashed rounded-2xl cursor-pointer bg-stone-50 hover:bg-stone-100 transition">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-2 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <p class="text-xs text-stone-500 font-bold uppercase tracking-tighter">Klik untuk unggah gambar produk</p>
                                <p class="text-[10px] text-stone-400 mt-1">PNG, JPG atau JPEG (Maks. 2MB)</p>
                            </div>
                            <input type="file" name="image" class="hidden" accept="image/*" />
                        </label>
                    </div>
                </div>
            </div>

            {{-- TOMBOL NAVIGASI --}}
            <div class="mt-8 flex items-center justify-end gap-4">
                <a href="{{ route('products.index') }}" class="text-sm font-bold text-stone-400 hover:text-stone-600 transition">
                    Batal
                </a>
                <button type="submit" class="bg-orange-700 hover:bg-orange-800 text-white px-12 py-3 rounded-xl font-bold shadow-lg shadow-orange-700/20 transition active:scale-95">
                    SIMPAN PRODUK
                </button>
            </div>
        </form>
    </div>
</x-app-layout>