<x-app-layout>
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-stone-800">Riwayat Pesanan</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-stone-200">
        <table class="w-full text-left">
            <thead class="bg-stone-50 border-b border-stone-200">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-stone-600 uppercase">Waktu</th>
                    <th class="px-6 py-4 text-xs font-bold text-stone-600 uppercase">Nama Pelanggan</th>
                    <th class="px-6 py-4 text-xs font-bold text-stone-600 uppercase">Total Harga</th>
                    <th class="px-6 py-4 text-xs font-bold text-stone-600 uppercase text-center">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-stone-600 uppercase text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($orders as $order)
                <tr class="hover:bg-stone-50 transition">
                    <td class="px-6 py-4 text-sm text-stone-500">{{ $order->created_at->format('d M Y, H:i') }}</td>
                    <td class="px-6 py-4 font-semibold text-stone-800 uppercase">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 font-bold text-stone-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>

                    <td class="px-6 py-4 text-center">
                        @if(strtolower($order->status) == 'pending')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 uppercase">Menunggu</span>
                        @elseif(strtolower($order->status) == 'success' || strtolower($order->status) == 'berhasil')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 uppercase">Berhasil</span>
                        @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 uppercase">{{ $order->status }}</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-center">
                        <button onclick="openModal('{{ $order->id }}')" class="text-orange-600 hover:text-orange-800 font-bold text-xs uppercase underline">
                            Lihat Item
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center text-stone-400 italic">Belum ada riwayat transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="orders-data" data-json='{!! json_encode($orders) !!}' class="hidden"></div>

    <div id="modalDetail" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 animate-in fade-in zoom-in duration-200">
            <div class="flex justify-between items-center border-b border-stone-100 pb-4 mb-4">
                <h3 class="text-lg font-bold text-stone-800 uppercase tracking-tight">Detail Item Pesanan</h3>
                <button onclick="closeModal()" class="text-stone-400 hover:text-stone-600 text-2xl">&times;</button>
            </div>

            <div id="modalContent" class="max-h-96 overflow-y-auto">
            </div>

            <div class="mt-8 pt-4 border-t border-stone-100 flex justify-end gap-2">
                <button onclick="printNota()" class="bg-orange-600 px-6 py-2 rounded-xl text-sm font-bold text-white hover:bg-orange-700 transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Nota
                </button>
                <button onclick="closeModal()" class="bg-stone-100 px-6 py-2 rounded-xl text-sm font-bold text-stone-600 hover:bg-stone-200 transition">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        const ordersData = document.getElementById('orders-data').dataset.json;
        const orders = JSON.parse(ordersData) || [];

        function openModal(orderId) {
            const order = orders.find(o => o.id == orderId);
            const content = document.getElementById('modalContent');

            if (!order) return;

            let itemsHtml = '<ul class="space-y-4">';
            order.items.forEach(item => {
                const productName = item.product ? item.product.name : 'Produk Tidak Diketahui';
                const subtotal = item.quantity * item.price;

                itemsHtml += `
                    <li class="flex justify-between items-center bg-stone-50 p-3 rounded-xl border border-stone-100">
                        <div>
                            <p class="font-bold text-stone-800 text-sm">${productName}</p>
                            <p class="text-xs text-stone-400 font-medium">${item.quantity} x Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</p>
                        </div>
                        <p class="font-bold text-orange-700 text-sm">Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</p>
                    </li>`;
            });
            itemsHtml += '</ul>';

            // Tambahkan TOTAL di bawah list item
            itemsHtml += `
                <div class="mt-4 pt-4 border-t border-dashed border-stone-200">
                    <div class="flex justify-between font-bold text-stone-800">
                        <span>TOTAL</span>
                        <span>Rp ${new Intl.NumberFormat('id-ID').format(order.total_price)}</span>
                    </div>
                </div>
            `;

            content.innerHTML = itemsHtml;
            document.getElementById('modalDetail').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modalDetail').classList.add('hidden');
        }

        function printNota() {
            const modalContent = document.getElementById('modalContent').innerHTML;
            const originalContent = document.body.innerHTML;

            // Tentukan URL Logo. Gunakan asset() agar path-nya benar.
            // Pastikan file logo ada di public/images/logo-nota.png
            const logoUrl = "{{ asset('images/logo-nota.png') }}";

            // Membuat tampilan khusus cetak (Thermal style)
            document.body.innerHTML = `
        <div style="width: 300px; font-family: monospace; padding: 10px; color: black; background: white;">
            
            <div style="text-align: center; margin-bottom: 10px;">
               <img src="{{ asset('Images/Logo.jpg') }}" alt="Logo Akar Kelana" style="max-width: 80px; height: auto; margin-bottom: 5px; display: block; margin-left: auto; margin-right: auto;">
                
                <h2 style="margin: 0; font-size: 18px;">Akar Kelana</h2>
                <p style="font-size: 11px; margin: 0;">Coffee Roastery & Eatery</p>
                <p style="font-size: 10px; margin: 5px 0;">${new Date().toLocaleString('id-ID')}</p>
                <div style="border-top: 1px dashed black; margin: 10px 0;"></div>
            </div>
            
            ${modalContent}
            
            <div style="text-align: center; margin-top: 20px;">
                <div style="border-top: 1px dashed black; margin: 10px 0;"></div>
                <p style="font-size: 11px;">Terima Kasih Atas Kunjungan Anda</p>
            </div>
        </div>
    `;

            // Beri sedikit jeda agar gambar logo sempat dimuat browser sebelum dicetak
            setTimeout(function() {
                window.print();

                // Kembalikan konten asli dan reload
                document.body.innerHTML = originalContent;
                window.location.reload();
            }, 500); // Jeda 500ms (setengah detik)
        }

        // Close modal if clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('modalDetail');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</x-app-layout>