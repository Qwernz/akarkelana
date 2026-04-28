<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8">
            {{-- Judul dan Tanggal --}}
            <div>
                <h1 class="text-3xl font-bold text-stone-800 uppercase tracking-tight">Riwayat Pesanan Harian</h1>
                <p class="text-sm text-stone-500">
                    Laporan pesanan:
                    <span class="font-bold text-orange-600">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</span>
                </p>
            </div>

            {{-- Kotak Total Pendapatan --}}
            <div class="bg-orange-50 border border-orange-100 px-6 py-3 rounded-2xl shadow-sm min-w-[200px]">
                <p class="text-[10px] uppercase font-bold text-orange-800 tracking-widest mb-1">Total Pendapatan</p>
                <h3 class="text-2xl font-black text-orange-900">
                    Rp {{ number_format($totalHarian, 0, ',', '.') }}
                </h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-stone-200">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-stone-50 text-stone-600 uppercase text-xs font-bold border-b border-stone-200">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Nama Pelanggan</th>
                            <th class="px-6 py-4">Biji Kopi Terjual</th>
                            <th class="px-6 py-4 text-right">Total</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($orders as $order)
                        <tr class="hover:bg-stone-50/50 transition">
                            <td class="px-6 py-4 font-mono text-stone-400 text-sm">#{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm text-stone-600">
                                {{ $order->created_at->format('H:i') }} WITA
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-stone-800">{{ $order->customer_name }}</div>
                                <div class="text-[10px] text-stone-400 truncate max-w-[150px]">{{ $order->customer_address }}</div>
                            </td>

                            <td class="px-6 py-4">
                                @if($order->items->count() > 0)
                                <ul class="text-xs space-y-1">
                                    @foreach($order->items as $item)
                                    <li class="flex items-center gap-1 text-stone-600">
                                        <span class="font-bold text-orange-700">{{ $item->quantity }}x</span>
                                        {{ $item->product->name }}
                                    </li>
                                    @endforeach
                                </ul>
                                @else
                                <span class="text-[10px] text-stone-300 italic whitespace-nowrap">No items</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 font-bold text-orange-900 text-right">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] bg-green-100 text-green-700 font-bold uppercase tracking-wider">
                                    {{ $order->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <button onclick="openModal('{{ $order->id }}')" class="inline-flex items-center gap-1 bg-stone-100 hover:bg-stone-800 hover:text-white text-stone-600 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-stone-400 italic font-medium">
                                Belum ada pesanan yang masuk hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <div id="orders-data" data-json='{!! json_encode($orders) !!}' class="hidden"></div>
    <div id="modalDetail" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 animate-in fade-in zoom-in duration-200">
            <div class="flex justify-between items-center border-b border-stone-100 pb-4 mb-4">
                <h3 class="text-lg font-bold text-stone-800 uppercase tracking-tight">Detail Item Pesanan</h3>
                <button onclick="closeModal()" class="text-stone-400 hover:text-stone-600 text-2xl">&times;</button>
            </div>

            <div id="modalContent">
            </div>

            <div class="mt-8 pt-4 border-t border-stone-100 flex justify-end">
                <button onclick="closeModal()" class="bg-stone-100 px-6 py-2 rounded-xl text-sm font-bold text-stone-600 hover:bg-stone-200 transition">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        // Ambil data dari elemen HTML agar tidak ada garis merah di VS Code
        const ordersContainer = document.getElementById('orders-data');
        const orders = JSON.parse(ordersContainer.getAttribute('data-json')) || [];

        function openModal(orderId) {
            // Logika pencarian order (Gunakan == karena ID dari HTML sering berupa string)
            const order = orders.find(o => o.id == orderId);

            if (!order) {
                console.error("Order tidak ditemukan!");
                return;
            }

            const content = document.getElementById('modalContent');
            let itemsHtml = '<ul class="space-y-4">';

            if (order.items && order.items.length > 0) {
                order.items.forEach(item => {
                    const productName = item.product ? item.product.name : 'Produk Tidak Diketahui';
                    const formattedPrice = new Intl.NumberFormat('id-ID').format(item.price);
                    const formattedSubtotal = new Intl.NumberFormat('id-ID').format(item.quantity * item.price);

                    itemsHtml += `
                <li class="flex justify-between items-center bg-stone-50 p-3 rounded-xl border border-stone-100">
                    <div>
                        <p class="font-bold text-stone-800 text-sm">${productName}</p>
                        <p class="text-xs text-stone-400 font-medium">${item.quantity} x Rp ${formattedPrice}</p>
                    </div>
                    <p class="font-bold text-orange-700 text-sm">Rp ${formattedSubtotal}</p>
                </li>`;
                });
            } else {
                itemsHtml += '<p class="text-center text-stone-400 py-4 italic">Tidak ada detail item.</p>';
            }

            itemsHtml += '</ul>';
            content.innerHTML = itemsHtml;
            document.getElementById('modalDetail').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modalDetail').classList.add('hidden');
        }

        // Opsi: Tutup modal jika klik di area luar (background hitam)
        window.onclick = function(event) {
            const modal = document.getElementById('modalDetail');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</x-app-layout>