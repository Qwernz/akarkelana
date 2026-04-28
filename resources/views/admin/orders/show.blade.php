<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">
        <div class="mb-6">
            <a href="{{ route('admin.orders.index') }}" class="text-stone-500 hover:text-stone-800 flex items-center gap-2 text-sm font-medium">
                ← Kembali ke Daftar Pesanan
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-stone-200 overflow-hidden">
            <div class="p-8 border-b border-stone-100 bg-stone-50/50">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-stone-800">Detail Pesanan #{{ $order->id }}</h2>
                        <p class="text-stone-500 text-sm">{{ $order->created_at->format('d M Y, H:i') }} WITA</p>
                    </div>
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-700 uppercase">
                        {{ $order->status }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8">
                <div>
                    <h3 class="text-xs font-bold text-stone-400 uppercase tracking-widest mb-3">Informasi Pelanggan</h3>
                    <div class="space-y-1">
                        <p class="font-bold text-stone-800 text-lg">{{ $order->customer_name }}</p>
                        <p class="text-stone-600">{{ $order->customer_phone }}</p>
                        <p class="text-stone-600 leading-relaxed">{{ $order->customer_address }}</p>
                    </div>
                </div>
                <div class="bg-orange-50 p-6 rounded-2xl border border-orange-100">
                    <h3 class="text-xs font-bold text-orange-400 uppercase tracking-widest mb-3">Ringkasan Pembayaran</h3>
                    <p class="text-sm text-orange-800 mb-1">Total yang harus dibayar:</p>
                    <p class="text-3xl font-black text-orange-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="px-8 pb-8">
                <h3 class="text-xs font-bold text-stone-400 uppercase tracking-widest mb-4">Item yang Dibeli</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-stone-500 text-xs font-bold border-b border-stone-100">
                            <th class="py-3">Produk</th>
                            <th class="py-3 text-center">Jumlah</th>
                            <th class="py-3 text-right">Harga Satuan</th>
                            <th class="py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @foreach($order->items as $item)
                        <tr class="text-sm">
                            <td class="py-4 font-bold text-stone-800">{{ $item->product->name }}</td>
                            <td class="py-4 text-center text-stone-600">{{ $item->quantity }}</td>
                            <td class="py-4 text-right text-stone-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="py-4 text-right font-bold text-stone-800">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>