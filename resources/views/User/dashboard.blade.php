<x-app-layout>
    <div class="max-w-5xl mx-auto py-12 px-4 min-h-screen">
        <div class="mb-10">
            <h1 class="text-3xl font-black text-stone-800 uppercase tracking-tight">Pesanan Saya</h1>
            <p class="text-stone-500 text-sm">Pantau status kopi pilihanmu dari roasting hingga pengantaran.</p>
        </div>

        <div class="space-y-10">
            @php
            $pendingOrders = $orders->filter(fn($order) => strtolower($order->status) == 'pending');
            $otherOrders = $orders->filter(fn($order) => strtolower($order->status) != 'pending');
            @endphp

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

                    <div class="bg-orange-50 rounded-3xl border-2 border-orange-100 p-6 shadow-sm hover:shadow-md transition flex flex-col md:flex-row justify-between items-center gap-6">
                        <div class="flex-1">
                            <span class="text-[10px] font-bold text-orange-400 uppercase tracking-widest block mb-1">ID PESANAN: #{{ $order->id }}</span>
                            <h3 class="font-bold text-stone-800 text-lg">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</h3>

                            <div class="mt-2">
                                @if($remainingSeconds > 0)
                                <div class="inline-flex items-center gap-2 text-red-600 font-bold text-sm bg-red-50 px-3 py-1 rounded-full border border-red-100">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span id="countdown-{{ $order->id }}">Memuat...</span>
                                </div>
                                @else
                                <span class="text-red-600 font-bold uppercase text-xs bg-red-100 px-3 py-1 rounded-full">Waktu Bayar Habis</span>
                                @endif
                            </div>
                        </div>

                        @if($remainingSeconds > 0)
                        <a href="{{ route('checkout.success', $order->id) }}" class="bg-orange-700 text-white px-8 py-3 rounded-2xl font-bold shadow-lg hover:bg-orange-800 transition text-sm">
                            Bayar Sekarang
                        </a>

                        <script>
                            (function() {
                                let seconds = Math.floor("{{$remainingSeconds}}");
                                const display = document.getElementById("countdown-{{ $order->id }}");

                                const timer = setInterval(function() {
                                    if (seconds <= 0) {
                                        clearInterval(timer);
                                        location.reload();
                                    } else {
                                        let m = Math.floor(seconds / 60);
                                        let s = seconds % 60;
                                        display.innerHTML = "Sisa Waktu: " + m + "m " + (s < 10 ? "0" + s : s) + "s";
                                        seconds--;
                                    }
                                }, 1000);
                            })();
                        </script>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            <hr class="border-stone-200">
            @endif

            <div>
                <h2 class="text-lg font-bold text-stone-800 mb-4">Riwayat Pesanan</h2>
                <div class="grid gap-6">
                    @forelse($otherOrders as $order)
                    <div class="bg-white rounded-3xl border border-stone-100 p-6 shadow-sm hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <span class="text-[10px] font-bold text-stone-400 uppercase tracking-widest block mb-1">ID PESANAN: #{{ $order->id }}</span>
                                <h3 class="font-bold text-stone-800">{{ $order->created_at->format('d M Y, H:i') }} WITA</h3>
                            </div>

                            @php
                            $statusLabels = [
                            'processing' => ['label' => 'SEDANG DIPROSES', 'class' => 'bg-blue-100 text-blue-700'],
                            'shipped' => ['label' => 'DALAM PENGIRIMAN', 'class' => 'bg-purple-100 text-purple-700'],
                            'success' => ['label' => 'SELESAI', 'class' => 'bg-green-100 text-green-700'],
                            'cancelled' => ['label' => 'DIBATALKAN', 'class' => 'bg-red-100 text-red-700'],
                            ];
                            $currentStatus = $statusLabels[$order->status] ?? ['label' => $order->status, 'class' => 'bg-stone-100 text-stone-600'];
                            @endphp

                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter {{ $currentStatus['class'] }}">
                                {{ $currentStatus['label'] }}
                            </span>
                        </div>

                        <div class="space-y-2 mb-6 text-sm text-stone-600">
                            @foreach($order->items as $item)
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-orange-700">{{ $item->quantity }}x</span>
                                <span>{{ $item->product->name }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="pt-4 border-t border-stone-50 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-stone-400 font-bold uppercase">Total Bayar</p>
                                <p class="text-xl font-black text-stone-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                            @if($order->status == 'success')
                            <button class="border-2 border-stone-800 text-stone-800 px-5 py-2 rounded-xl text-xs font-bold hover:bg-stone-800 hover:text-white transition">Beri Rating</button>
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
    </div>
</x-app-layout>