<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-stone-800">Pesanan Masuk</h2>
        <p class="text-sm text-stone-500">Daftar pesanan pelanggan yang menunggu konfirmasi kasir.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-stone-200">
        <table class="w-full text-left">
            <thead class="bg-stone-50 border-b border-stone-200">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-stone-600 uppercase">Pelanggan</th>
                    <th class="px-6 py-4 text-xs font-bold text-stone-600 uppercase">Total Bayar</th>
                    <th class="px-6 py-4 text-xs font-bold text-stone-600 uppercase text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($orders as $order)
                <tr class="hover:bg-stone-50 transition">
                    <td class="px-6 py-4 font-semibold text-stone-800">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 text-orange-900 font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center space-x-2">
                            <form action="{{ route('kasir.orders.update', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="success">
                                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition">TERIMA</button>
                            </form>
                            <form action="{{ route('kasir.orders.update', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="cancelled">
                                <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition">BATAL</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-10 text-center text-stone-400 italic">Belum ada pesanan baru.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>