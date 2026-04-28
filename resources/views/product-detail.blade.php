<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} - Akar Kelana</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

{{-- NOTIFIKASI TENGAH LAYAR (PRODUCT DETAIL) --}}
@if(session('success'))
<div x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 3000)"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    class="fixed inset-0 flex items-center justify-center z-[100] pointer-events-none">

    <div class="bg-stone-900/95 backdrop-blur-md text-white px-8 py-5 rounded-3xl shadow-2xl border border-stone-800 flex flex-col items-center gap-3 pointer-events-auto">
        {{-- Ikon Animasi Centang --}}
        <div class="bg-green-500 p-2.5 rounded-full shadow-lg shadow-green-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <div class="text-center">
            <p class="font-black text-sm uppercase tracking-widest">{{ session('success') }}</p>
            <p class="text-[10px] text-stone-400 mt-1 uppercase tracking-tighter">Cek keranjang belanja Anda untuk checkout</p>
        </div>
    </div>
</div>
@endif

<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-12"
        x-data="{ 
            selectedPrice: {{ $product->variants->where('is_active', true)->first()->price ?? 0 }},
            selectedStock: {{ $product->variants->where('is_active', true)->first()->stock ?? 0 }},
            selectedWeight: '{{ $product->variants->where('is_active', true)->first()->weight ?? '' }}',
            selectedVariantId: {{ $product->variants->where('is_active', true)->first()->id ?? 'null' }}
         }">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

            {{-- KIRI: FOTO PRODUK --}}
            <div class="bg-stone-100 rounded-3xl overflow-hidden aspect-square flex items-center justify-center border border-stone-200">
                @if($product->image)
                <img src="{{ asset($product->image) }}" class="w-full h-full object-cover">
                @else
                <div class="text-stone-400 flex flex-col items-center">
                    <svg class="w-20 h-20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2"></path>
                    </svg>
                    <p class="font-bold uppercase tracking-widest text-sm">No Image Available</p>
                </div>
                @endif
            </div>

            {{-- KANAN: DETAIL & PILIHAN --}}
            <div class="flex flex-col">
                <nav class="mb-4">
                    <a href="/" class="group inline-flex items-center gap-2 text-xs font-bold text-orange-600 uppercase tracking-widest hover:text-orange-700 transition-colors">
                        {{-- Ikon Back (Arrow Left) --}}
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform duration-200"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2.5"
                                d="M15 19l-7-7 7-7" />
                        </svg>

                        Kembali ke Katalog
                    </a>
                </nav>

                <h1 class="text-4xl font-black text-stone-900 leading-tight mb-2 uppercase">{{ $product->name }}</h1>

                {{-- HARGA DINAMIS --}}
                <div class="mb-8">
                    <p class="text-sm text-stone-400 font-bold uppercase mb-1">Harga Varian <span x-text="selectedWeight"></span></p>
                    <h2 class="text-3xl font-black text-orange-700">
                        Rp <span x-text="new Intl.NumberFormat('id-ID').format(selectedPrice)"></span>
                    </h2>
                </div>

                {{-- RATING BINTANG DI BAWAH HARGA --}}
                <div class="flex items-center gap-2 mt-2">
                    <div class="flex text-amber-400">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= round($averageRating) ? 'fill-current' : 'text-stone-200' }}" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            @endfor
                    </div>
                    <span class="text-xs font-bold text-stone-400">({{ $totalReviews }} Ulasan)</span>
                </div>

                {{-- DESKRIPSI --}}
                <div class="mb-8 border-t border-stone-100 pt-6">
                    <h4 class="text-xs font-black text-stone-800 uppercase tracking-widest mb-3">Tentang Kopi Ini</h4>

                    {{-- Menambahkan class text-justify di sini --}}
                    <p class="text-stone-600 leading-relaxed text-justify">
                        {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>
                </div>

                {{-- PILIHAN GRAMASI (TOMBOL) --}}
                <div class="mb-10">
                    <h4 class="text-xs font-black text-stone-800 uppercase tracking-widest mb-4">Pilih Berat (Gram)</h4>
                    <div class="flex flex-wrap gap-3">
                        @foreach($product->variants->where('is_active', true) as $variant)
                        <button
                            @click="
                                    selectedPrice = {{ $variant->price }}; 
                                    selectedStock = {{ $variant->stock }}; 
                                    selectedWeight = '{{ $variant->weight }}';
                                    selectedVariantId = {{ $variant->id }};
                                "
                            class="px-6 py-3 rounded-2xl border-2 font-bold transition-all duration-200 text-sm"
                            :class="selectedWeight === '{{ $variant->weight }}' 
                                    ? 'border-orange-600 bg-orange-50 text-orange-700 ring-2 ring-orange-600/20' 
                                    : 'border-stone-200 text-stone-500 hover:border-stone-400 hover:text-stone-800'">
                            {{ $variant->weight }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- INFO STOK & TOMBOL BELI --}}
                <div class="mt-auto bg-stone-50 p-6 rounded-3xl border border-stone-100">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold text-stone-500 uppercase">Stok Tersedia:</span>
                        <span class="font-black text-stone-800" :class="selectedStock <= 5 ? 'text-red-600' : ''">
                            <span x-text="selectedStock"></span> Pack
                        </span>
                    </div>

                    @auth
                    {{-- Logika untuk Pelanggan (User) & Kasir --}}
                    @if(Auth::user()->role === 'user' || Auth::user()->role === 'kasir')
                    <form :action="'/add-to-cart/' + selectedVariantId" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full bg-stone-900 hover:bg-orange-700 text-white py-4 rounded-2xl font-bold transition-all duration-300 shadow-xl shadow-stone-200 active:scale-95 disabled:bg-stone-300 disabled:cursor-not-allowed"
                            :disabled="selectedStock <= 0">
                            <span x-show="selectedStock > 0">TAMBAHKAN KE KERANJANG</span>
                            <span x-show="selectedStock <= 0">STOK HABIS</span>
                        </button>
                    </form>
                    @else
                    {{-- Tampilan untuk Admin --}}
                    <div class="w-full p-4 bg-stone-200 text-stone-600 rounded-2xl font-bold text-center text-xs uppercase tracking-widest">
                        Mode View Admin
                    </div>
                    @endif
                    @else
                    {{-- Jika Belum Login --}}
                    <a href="{{ route('login') }}"
                        class="w-full block text-center bg-orange-700 hover:bg-orange-800 text-white py-4 rounded-2xl font-bold transition-all duration-300 shadow-lg shadow-orange-200">
                        LOGIN UNTUK MEMBELI
                    </a>
                    @endauth
                </div>
            </div> {{-- Penutup Kolom Kanan --}}
        </div> {{-- Penutup Grid --}}

        {{-- BAGIAN REVIEW (Diletakkan di luar grid agar lebar/full) --}}
        <div class="mt-20 border-t border-stone-100 pt-12">
            <h3 class="text-2xl font-black text-stone-900 uppercase italic mb-8 flex items-center gap-3">
                Apa Kata Mereka?
                <span class="h-px flex-1 bg-stone-100"></span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($reviews as $review)
                <div class="bg-white p-8 rounded-3xl border border-stone-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="font-bold text-stone-900">{{ $review->user->name ?? 'Pelanggan Akar Kelana' }}</p>
                            <div class="flex text-amber-400 mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-current' : 'text-stone-200' }}" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    @endfor
                            </div>
                        </div>
                        <span class="text-[10px] text-stone-400 font-bold uppercase tracking-tighter bg-stone-50 px-2 py-1 rounded">
                            {{ $review->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-stone-600 leading-relaxed italic text-sm border-l-2 border-orange-100 pl-4">
                        "{{ $review->review }}"
                    </p>
                </div>
                @empty
                <div class="col-span-full text-center py-16 bg-stone-50 rounded-3xl border-2 border-dashed border-stone-200">
                    <svg class="w-12 h-12 text-stone-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <p class="text-stone-400 text-sm italic font-medium">Belum ada ulasan untuk produk ini. Jadilah yang pertama memberikan penilaian!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
</body>

</html>