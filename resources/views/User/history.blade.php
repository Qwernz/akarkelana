<x-app-layout>
    {{-- Inisialisasi Alpine.js untuk Modal di level teratas --}}
    <div x-data="{ openRating: false, orderId: null, orderTotal: '' }" class="max-w-5xl mx-auto py-12 px-4 min-h-screen">

        <div class="mb-10">
            <h1 class="text-3xl font-black text-stone-800 uppercase tracking-tight">Pesanan Saya</h1>
            <p class="text-stone-500 text-sm">Pantau status kopi pilihanmu dari roasting hingga pengantaran.</p>
        </div>

        <div class="space-y-10">
            @php
            $pendingOrders = $orders->filter(fn($order) => strtolower($order->status) == 'pending');
            $otherOrders = $orders->filter(fn($order) => strtolower($order->status) != 'pending');
            @endphp

            {{-- BAGIAN 1: PESANAN MENUNGGU PEMBAYARAN --}}
            @if($pendingOrders->count() > 0)
            <div>
                <h2 class="text-lg font-bold text-orange-800 mb-4 flex items-center gap-2">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                    </span>
                    Menunggu Pembayaran
                </h2>
                <div class="grid gap-4">
                    @foreach($pendingOrders as $order)
                    @php
                    $expiryTime = $order->created_at->addMinutes(10);
                    $remainingSeconds = now()->diffInSeconds($expiryTime, false);
                    @endphp

                    <div class="bg-orange-50 rounded-3xl border-2 border-orange-100 p-6 shadow-sm flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden">
                        <div class="flex-1 text-center md:text-left">
                            <span class="text-[10px] font-bold text-orange-400 uppercase tracking-widest block mb-1">ID PESANAN: #{{ $order->id }}</span>
                            <h3 class="font-bold text-stone-800 text-lg">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</h3>

                            <div class="mt-2">
                                @if($remainingSeconds > 0)
                                <div class="countdown-timer text-[10px] font-black text-red-600 bg-red-100 px-3 py-1 rounded-full inline-block border border-red-200 uppercase tracking-tighter"
                                    data-created="{{ $order->created_at->toIso8601String() }}">
                                    Menghitung...
                                </div>
                                @else
                                <span class="text-red-600 font-bold uppercase text-[10px] bg-red-100 px-3 py-1 rounded-full border border-red-200">Waktu Bayar Habis</span>
                                @endif
                            </div>
                        </div>

                        @if($remainingSeconds > 0)
                        <a href="{{ route('checkout.success', $order->id) }}" class="bg-orange-700 text-white px-8 py-3 rounded-2xl font-bold shadow-lg hover:bg-orange-800 transition text-sm">
                            Bayar Sekarang
                        </a>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            <hr class="border-stone-200">
            @endif

            {{-- BAGIAN 2: RIWAYAT PESANAN LAINNYA --}}
            <div>
                <h2 class="text-lg font-bold text-stone-800 mb-4">Riwayat Pesanan</h2>
                <div class="grid gap-6">
                    @forelse($otherOrders as $order)
                    <div class="bg-white rounded-3xl border border-stone-100 p-6 shadow-sm hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <span class="text-[10px] font-bold text-stone-400 uppercase tracking-widest block mb-1">ID PESANAN: #{{ $order->id }}</span>
                                <h3 class="font-bold text-stone-800">{{ $order->created_at->format('d M Y, H:i') }}</h3>
                            </div>

                            @php
                            $statusLabels = [
                            'processing' => ['label' => 'DIPROSES', 'class' => 'bg-blue-100 text-blue-700 border-blue-200'],
                            'shipped' => ['label' => 'DIKIRIM', 'class' => 'bg-purple-100 text-purple-700 border-purple-200'],
                            'success' => ['label' => 'SELESAI', 'class' => 'bg-green-100 text-green-700 border-green-200'],
                            'cancelled' => ['label' => 'BATAL', 'class' => 'bg-red-50 text-red-700 border-red-100'],
                            ];
                            $currentStatus = $statusLabels[strtolower($order->status)] ?? ['label' => $order->status, 'class' => 'bg-stone-100 text-stone-600 border-stone-200'];
                            @endphp

                            <span class="px-3 py-1 border rounded-full text-[10px] font-black uppercase tracking-tighter {{ $currentStatus['class'] }}">
                                {{ $currentStatus['label'] }}
                            </span>
                        </div>

                        {{-- Item list --}}
                        <div class="space-y-2 mb-6 text-sm text-stone-600 italic">
                            @foreach($order->items as $item)
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-orange-700">{{ $item->quantity }}x</span>
                                <span>{{ $item->product_name }} ({{ $item->weight }})</span>
                            </div>
                            @endforeach
                        </div>

                        {{-- Refund Alert jika dibatalkan --}}
                        @if(strtolower($order->status) == 'cancelled')
                        <div class="mb-6 p-3 bg-stone-50 rounded-2xl border border-dashed border-stone-200">
                            <p class="text-[10px] text-stone-500 leading-relaxed">
                                Pesanan dibatalkan otomatis karena melewati batas waktu.
                                <a href="https://wa.me/628xxx" class="font-bold text-orange-800 underline">Sudah bayar? Klik untuk Refund.</a>
                            </p>
                        </div>
                        @endif

                        <div class="pt-4 border-t border-stone-50 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-stone-400 font-bold uppercase">Total Bayar</p>
                                <p class="text-xl font-black text-stone-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>

                            {{-- LOGIKA TOMBOL RATING --}}
                            @if(strtolower($order->status) == 'success')
                            @if(!$order->rating)
                            <button @click="openRating = true; orderId = '{{ $order->id }}'; orderTotal = '{{ number_format($order->total_price, 0, ',', '.') }}'"
                                class="border-2 border-stone-800 text-stone-800 px-5 py-2 rounded-xl text-xs font-bold hover:bg-stone-800 hover:text-white transition">
                                Beri Rating
                            </button>
                            @else
                            <div class="flex flex-col items-end">
                                <div class="flex text-amber-400 text-sm">
                                    @for($i=1; $i<=5; $i++)
                                        <span>{{ $i <= $order->rating ? '★' : '☆' }}</span>
                                        @endfor
                                </div>
                                <span class="text-[10px] text-stone-400 font-bold uppercase mt-1">Ulasan Terkirim</span>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-20 bg-stone-50 rounded-3xl border-2 border-dashed border-stone-200">
                        <p class="text-stone-400 italic">Belum ada riwayat pesanan.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- MODAL RATING (TAMPIL SAAT openRating TRUE) --}}
        <div x-show="openRating"
            class="fixed inset-0 z-50 flex items-center justify-center bg-stone-900/60 backdrop-blur-sm p-4"
            x-cloak>

            <div @click.away="openRating = false"
                class="bg-white rounded-[2.5rem] p-8 md:p-10 max-w-md w-full shadow-2xl">

                <div class="text-center mb-6">
                    <h3 class="text-2xl font-black text-stone-900 uppercase italic">Rating & Ulasan</h3>
                    <p class="text-stone-500 text-xs mt-1">Bagaimana rasa kopi di pesanan #<span x-text="orderId"></span> ini?</p>
                </div>

                <form :action="'/orders/' + orderId + '/rate'" method="POST" x-data="{ hover: 0, selected: 0 }">
                    @csrf
                    <div class="flex justify-center gap-2 mb-8">
                        <template x-for="i in 5">
                            <button type="button"
                                @click="selected = i"
                                @mouseover="hover = i"
                                @mouseleave="hover = 0"
                                class="text-4xl transition-all transform hover:scale-110 focus:outline-none"
                                :class="(hover >= i || selected >= i) ? 'text-amber-400' : 'text-stone-200'">
                                ★
                            </button>
                        </template>
                        <input type="hidden" name="rating" :value="selected" required>
                    </div>

                    <textarea name="review" placeholder="Tulis ulasanmu di sini (opsional)..."
                        class="w-full border-stone-100 bg-stone-50 rounded-2xl p-4 text-sm focus:ring-orange-500 focus:border-orange-500 border-none mb-6" rows="3"></textarea>

                    <div class="flex gap-3">
                        <button type="button" @click="openRating = false" class="flex-1 py-4 text-stone-400 font-bold text-sm uppercase tracking-widest">Batal</button>
                        <button type="submit"
                            :disabled="selected === 0"
                            :class="selected === 0 ? 'bg-stone-200 cursor-not-allowed' : 'bg-stone-900 hover:bg-black shadow-lg'"
                            class="flex-1 py-4 text-white rounded-2xl font-bold text-sm uppercase tracking-widest transition">
                            Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT COUNTDOWN --}}
    <script>
        function updateCountdowns() {
            const timers = document.querySelectorAll('.countdown-timer');
            timers.forEach(timer => {
                const createdAt = new Date(timer.getAttribute('data-created')).getTime();
                const now = new Date().getTime();
                const deadline = createdAt + (10 * 60 * 1000);
                const distance = deadline - now;

                if (distance < 0) {
                    timer.innerHTML = "WAKTU PEMBAYARAN HABIS";
                    timer.classList.remove('text-red-600', 'bg-red-100');
                    timer.classList.add('text-stone-400', 'bg-stone-100', 'border-stone-200');

                    const card = timer.closest('.bg-orange-50');
                    const payButton = card?.querySelector('a');
                    if (payButton) payButton.remove();
                } else {
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    const displayMinutes = minutes < 10 ? '0' + minutes : minutes;
                    const displaySeconds = seconds < 10 ? '0' + seconds : seconds;
                    timer.innerHTML = `BAYAR DALAM: ${displayMinutes}:${displaySeconds}`;
                }
            });
        }
        setInterval(updateCountdowns, 1000);
        updateCountdowns();
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>