<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-stone-800">Manajemen Produk Kopi</h2>
            <p class="text-sm text-stone-500">Daftar biji kopi siap jual.</p>
        </div>
        <a href="{{ route('products.create') }}" class="bg-orange-700 hover:bg-orange-800 text-white px-4 py-2 rounded-lg font-bold transition flex items-center shadow-md">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah
        </a>
    </div>

    {{-- Penutup DIV Grid harus di luar loop --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
        {{-- Inisialisasi Alpine.js pada setiap kartu produk --}}
        <div x-data="{ 
            selectedPrice: {{ $product->variants->first()->price ?? 0 }}, 
            selectedStock: {{ $product->variants->first()->stock ?? 0 }},
            selectedWeight: '{{ $product->variants->first()->weight ?? '-' }}'
         }"
            class="bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden flex flex-col hover:shadow-md transition-all duration-300">

            {{-- AREA GAMBAR --}}
            <div class="relative h-40 bg-stone-100 flex items-center justify-center">
                @if($product->image)
                <img src="{{ asset($product->image) }}" class="w-full h-full object-cover">
                @else
                <div class="flex flex-col items-center text-stone-400">
                    <svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-[10px] uppercase font-bold tracking-tighter">No Image</span>
                </div>
                @endif

                {{-- BADGE STOK (Dinamis mengikuti pilihan gram) --}}
                <div class="absolute top-2 right-2">
                    <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase shadow-sm transition-colors duration-300"
                        :class="selectedStock <= 5 ? 'bg-red-600 text-white' : 'bg-orange-700 text-white'">
                        STOK: <span x-text="selectedStock"></span> PK
                    </span>
                </div>
            </div>

            {{-- AREA INFORMASI --}}
            <div class="p-4 flex-1 flex flex-col">
                <div class="mb-3">
                    <h3 class="font-bold text-stone-800 text-sm leading-tight truncate mb-1">
                        {{ $product->name }}
                    </h3>
                    <p class="text-[10px] text-stone-400 font-bold uppercase">
                        Harga Varian <span x-text="selectedWeight"></span>
                    </p>
                    <p class="text-orange-700 font-extrabold text-base transition-all">
                        Rp <span x-text="new Intl.NumberFormat('id-ID').format(selectedPrice)"></span>
                    </p>
                </div>

                {{-- TOMBOL PILIHAN GRAM (Bisa Diklik) --}}
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($product->variants as $variant)
                    <button type="button"
                        @click="selectedPrice = {{ $variant->price }}; selectedStock = {{ $variant->stock }}; selectedWeight = '{{ $variant->weight }}'"
                        class="flex flex-col items-center border rounded-lg overflow-hidden min-w-[50px] transition-all focus:outline-none"
                        :class="selectedWeight === '{{ $variant->weight }}' ? 'border-orange-500 ring-1 ring-orange-500' : 'border-stone-200 hover:border-stone-400'">

                        <span class="w-full text-center text-[9px] font-bold py-0.5 border-b transition-colors"
                            :class="selectedWeight === '{{ $variant->weight }}' ? 'bg-orange-500 text-white border-orange-500' : 'bg-stone-100 text-stone-500 border-stone-200'">
                            {{ $variant->weight }}
                        </span>
                        <span class="px-2 py-1 text-[10px] font-black bg-white"
                            :class="selectedWeight === '{{ $variant->weight }}' ? 'text-orange-700' : 'text-stone-700'">
                            {{ $variant->stock }}
                        </span>
                    </button>
                    @endforeach
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="flex items-center justify-between mt-auto pt-3 border-t border-stone-100">
                    <span class="text-[10px] font-bold text-stone-400 italic">
                        {{ $product->variants->count() }} Varian tersedia
                    </span>
                    <div class="flex gap-1">
                        <a href="{{ route('products.edit', $product->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-md transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</x-app-layout>