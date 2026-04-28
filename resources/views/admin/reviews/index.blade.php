<x-app-layout>
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-stone-100 flex justify-between items-center">
            <h4 class="text-xs font-bold text-stone-600 uppercase tracking-widest">Semua Feedback Pelanggan</h4>
        </div>

        <div class="divide-y divide-stone-100">
            @forelse($reviews as $order)
            <div class="p-6 hover:bg-stone-50/50 transition">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-sm font-bold text-stone-800">{{ $order->user->name ?? 'Pelanggan' }}</p>
                        {{-- Tampilkan status juga untuk memastikan data terambil --}}
                        <p class="text-[9px] font-bold {{ $order->status == 'success' ? 'text-green-500' : 'text-red-400' }} uppercase">
                            Status: {{ $order->status }}
                        </p>
                    </div>

                    <div class="flex items-center gap-0.5 text-orange-400">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3.5 h-3.5 {{ $i <= $order->rating ? 'fill-current' : 'text-stone-200' }}" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            @endfor
                    </div>
                </div>

                {{-- Gunakan $order->review --}}
                <p class="text-sm text-stone-600 italic">"{{ $order->review ?? 'Tanpa ulasan teks' }}"</p>
                <p class="text-[9px] text-stone-400 mt-2">{{ $order->updated_at->diffForHumans() }}</p>
            </div>
            @empty
            <div class="p-12 text-center text-stone-400 italic text-sm">
                Belum ada ulasan yang ditemukan di database.
            </div>
            @endforelse
        </div>

    </div>
</x-app-layout>