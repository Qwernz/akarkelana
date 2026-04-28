<x-app-layout>
    <div class="max-w-2xl mx-auto py-16 px-4">
        <div class="text-center">
            {{-- IKON --}}
            <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h2 class="text-3xl font-black text-stone-900 uppercase tracking-tight italic">Akar Kelana</h2>
            <p class="text-stone-500 mt-2 mb-10">Pesanan <span class="font-bold text-stone-800">#{{ $order->id }}</span> berhasil dibuat.</p>

            {{-- AREA PEMBAYARAN --}}
            <div class="bg-white p-10 inline-block rounded-[2.5rem] shadow-2xl border border-stone-100 mb-6">
                @if($order->status == 'pending')
                {{-- LINK REDIRECT MIDTRANS --}}
                <a href="{{ $order->snap_token }}"
                    class="bg-orange-700 text-white px-12 py-4 rounded-2xl font-bold shadow-lg hover:bg-orange-800 transition-all transform hover:scale-105 inline-block text-lg">
                    🚀 Bayar Sekarang
                </a>

                <p class="text-[11px] text-stone-400 mt-6 leading-relaxed max-w-xs mx-auto">
                    Klik tombol untuk membayar via GoPay, QRIS, atau Bank Transfer.
                </p>
                @else
                <div class="bg-green-50 text-green-700 px-8 py-4 rounded-2xl font-bold border border-green-100">
                    ✅ Pesanan Terbayar
                </div>
                @endif

                <div class="mt-8 pt-6 border-t border-stone-50">
                    <p class="text-[10px] text-stone-400 font-bold uppercase tracking-widest mb-1">Total Tagihan</p>
                    <p class="font-black text-3xl text-stone-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- TIPS GANTI METODE --}}
            @if($order->status == 'pending')
            <div class="max-w-md mx-auto bg-amber-50 border border-amber-100 p-4 rounded-2xl mb-10 text-xs text-amber-800 leading-relaxed shadow-sm">
                <span class="font-bold">💡 Tips:</span> Salah pilih metode bayar? Cukup tutup halaman pembayaran Midtrans, lalu klik kembali tombol <b>Bayar Sekarang</b> untuk memilih ulang.
            </div>
            @endif

            {{-- NAVIGASI --}}
            <div class="flex justify-center items-center gap-6 mt-4">
                <a href="/" class="text-stone-400 font-bold text-xs uppercase tracking-widest hover:text-stone-800 transition">← Beranda</a>
                <span class="text-stone-200">|</span>
                <a href="{{ route('orders.history') }}" class="text-stone-400 font-bold text-xs uppercase tracking-widest hover:text-stone-800 transition">Riwayat Pesanan →</a>
            </div>
        </div>
    </div>
</x-app-layout>