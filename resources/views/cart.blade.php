<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Akar Kelana</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-stone-50 font-['Plus_Jakarta_Sans']">

    <nav class="px-8 py-6 bg-white shadow-sm flex justify-between items-center">
        <a href="/" class="text-xl font-bold text-orange-900">← Kembali ke Toko</a>
        <h1 class="text-2xl font-bold">Keranjang Belanja</h1>
        <div></div>
    </nav>

    <main class="max-w-5xl mx-auto px-8 py-12">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-100 text-stone-600 uppercase text-sm">
                        <th class="p-6">Nama Produk</th>
                        <th class="p-6">Harga Satuan</th>
                        <th class="p-6 text-center">Jumlah</th>
                        <th class="p-6 text-right">Subtotal</th>
                        <th class="p-6 bg-stone-100"></th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0 @endphp
                    @if(session('cart'))
                    @foreach(session('cart') as $id => $details)
                    @php $total += $details['price'] * $details['quantity'] @endphp
                    <tr class="border-b border-stone-100">
                        <td class="p-6 font-semibold">{{ $details['name'] }}</td>
                        <td class="p-6 text-stone-600">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                        <td class="p-6 text-center">
                            <div class="flex items-center justify-center gap-3">
                                {{-- TOMBOL KURANG --}}
                                <form action="{{ route('cart.update', $id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="decrease">
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg border border-stone-200 bg-white text-stone-600 hover:bg-orange-50 hover:border-orange-200 transition active:scale-90 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                        </svg>
                                    </button>
                                </form>

                                {{-- ANGKA JUMLAH --}}
                                <span class="font-bold text-stone-800 min-w-[24px]">{{ $details['quantity'] }}</span>

                                {{-- TOMBOL TAMBAH --}}
                                <form action="{{ route('cart.update', $id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="increase">
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg border border-stone-200 bg-white text-stone-600 hover:bg-orange-50 hover:border-orange-200 transition active:scale-90 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td class="p-6 text-right font-bold text-orange-900">
                            Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('remove.from.cart') }}" method="POST" onsubmit="return confirm('Hapus produk ini dari keranjang?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $id }}">
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="4" class="p-12 text-center text-stone-500">Keranjang masih kosong.</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <div class="p-8 bg-stone-50 flex justify-between items-center">
                <div class="text-lg">Total Pembayaran: <span class="text-3xl font-bold text-orange-900 ml-2">Rp {{ number_format($total, 0, ',', '.') }}</span></div>
                {{-- Bagian Form Checkout --}}
                <form action="{{ url('/checkout') }}" method="POST" class="mt-8 bg-stone-100 p-8 rounded-2xl border border-stone-200">
                    @csrf
                    @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <h3 class="text-xl font-bold mb-1 text-stone-800">Informasi Pengiriman</h3>
                    <p class="text-xs text-stone-500 mb-6 italic">*Mohon isi data dengan benar untuk koordinasi pengiriman via WhatsApp.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <input type="text" name="name" placeholder="Nama Lengkap" class="p-3 rounded-xl border-stone-200 focus:ring-orange-500 focus:border-orange-500 transition" required>
                        <input type="text" name="phone" placeholder="Nomor WhatsApp" class="p-3 rounded-xl border-stone-200 focus:ring-orange-500 focus:border-orange-500 transition" required>
                    </div>

                    <textarea name="address" placeholder="Alamat Lengkap Pengiriman" class="w-full p-3 rounded-xl border-stone-200 focus:ring-orange-500 focus:border-orange-500 mb-4" rows="3" required></textarea>

                    {{-- KOLOM PESAN / CATATAN BARU --}}
                    <div class="mb-6">
                        <label class="text-xs font-bold text-stone-600 uppercase tracking-widest mb-2 block">Catatan Pesanan (Opsional)</label>
                        <textarea name="note" placeholder="Contoh: Tolong di-blend 50/50 atau digiling halus untuk espresso." class="w-full p-4 rounded-xl border-stone-200 bg-white focus:ring-orange-500 focus:border-orange-500 text-sm text-stone-700" rows="2"></textarea>
                        <p class="text-[10px] text-stone-400 mt-2 italic">Gunakan kolom ini jika Anda memiliki permintaan khusus terkait pesanan Anda.</p>
                    </div>

                    <input type="hidden" name="total_price" value="{{ $total }}">

                    <button type="submit" class="w-full bg-orange-800 text-white py-4 rounded-2xl font-bold hover:bg-orange-900 transition shadow-xl shadow-orange-900/20 text-xl active:scale-95">
                        Konfirmasi & Bayar Sekarang
                    </button>
                </form>
            </div>
        </div>
    </main>

</body>

</html>