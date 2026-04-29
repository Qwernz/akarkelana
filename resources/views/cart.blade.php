<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Akar Kelana</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        /* Custom scrollbar tipis untuk mobile */
        .overflow-x-auto::-webkit-scrollbar { height: 4px; }
        .overflow-x-auto::-webkit-scrollbar-thumb { background: #d6d3d1; border-radius: 10px; }
    </style>
</head>

<body class="bg-stone-50 font-['Plus_Jakarta_Sans']">

    <nav class="px-8 py-6 bg-white shadow-sm flex justify-between items-center">
        <a href="/" class="text-sm md:text-xl font-bold text-orange-900 flex items-center gap-2">
            <span>←</span> <span class="hidden md:inline">Kembali ke Toko</span>
        </a>
        <h1 class="text-xl md:text-2xl font-bold">Keranjang</h1>
        <div class="w-10"></div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 md:px-8 py-8 md:py-12">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-stone-200">
            
            {{-- TAMBAHKAN PEMBUNGKUS INI AGAR BISA DIGESER DI HP --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-stone-100 text-stone-600 uppercase text-xs">
                            <th class="p-4 md:p-6">Nama Produk</th>
                            <th class="p-4 md:p-6">Harga</th>
                            <th class="p-4 md:p-6 text-center">Jumlah</th>
                            <th class="p-4 md:p-6 text-right">Subtotal</th>
                            <th class="p-4 md:p-6 bg-stone-100"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0 @endphp
                        @if(session('cart'))
                            @foreach(session('cart') as $id => $details)
                            @php $total += $details['price'] * $details['quantity'] @endphp
                            <tr class="border-b border-stone-100 hover:bg-stone-50/50 transition">
                                <td class="p-4 md:p-6 font-semibold text-stone-800">{{ $details['name'] }}</td>
                                <td class="p-4 md:p-6 text-stone-600 text-sm">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                <td class="p-4 md:p-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('cart.update', $id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg border border-stone-200 bg-white text-stone-600 hover:bg-orange-50 transition active:scale-90">
                                                -
                                            </button>
                                        </form>

                                        <span class="font-bold text-stone-800 w-6 text-center">{{ $details['quantity'] }}</span>

                                        <form action="{{ route('cart.update', $id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg border border-stone-200 bg-white text-stone-600 hover:bg-orange-50 transition active:scale-90">
                                                +
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="p-4 md:p-6 text-right font-bold text-orange-900">
                                    Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                                </td>
                                <td class="p-4 md:p-6 text-right">
                                    <form action="{{ route('remove.from.cart') }}" method="POST" onsubmit="return confirm('Hapus produk?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button type="submit" class="text-red-400 hover:text-red-600 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="p-12 text-center text-stone-500 italic">Keranjang masih kosong.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- TOTAL DAN CHECKOUT --}}
            <div class="p-6 md:p-8 bg-stone-50 border-t border-stone-200">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                    <div class="text-lg">Total Pembayaran:</div>
                    <div class="text-3xl font-bold text-orange-900">Rp {{ number_format($total, 0, ',', '.') }}</div>
                </div>

                {{-- FORM CHECKOUT --}}
                <form action="{{ url('/checkout') }}" method="POST" class="bg-white p-6 md:p-8 rounded-2xl border border-stone-200 shadow-sm">
                    @csrf
                    @if ($errors->any())
                    <div class="bg-red-50 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm border border-red-100">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <h3 class="text-lg font-bold mb-1 text-stone-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                        Informasi Pengiriman
                    </h3>
                    <p class="text-[10px] text-stone-400 mb-6 uppercase tracking-wider font-semibold">Koordinasi pengiriman akan dilanjutkan via WhatsApp</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="flex flex-col gap-1">
                            <label class="text-[10px] font-bold text-stone-500 ml-1">NAMA PENERIMA</label>
                            <input type="text" name="name" placeholder="Contoh: Budi Santoso" class="p-3 rounded-xl border border-stone-200 focus:ring-2 focus:ring-orange-500 outline-none transition" required>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[10px] font-bold text-stone-500 ml-1">NOMOR WHATSAPP</label>
                            <input type="text" name="phone" placeholder="08123456xxx" class="p-3 rounded-xl border border-stone-200 focus:ring-2 focus:ring-orange-500 outline-none transition" required>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1 mb-4">
                        <label class="text-[10px] font-bold text-stone-500 ml-1">ALAMAT LENGKAP</label>
                        <textarea name="address" placeholder="Nama Jalan, Blok, No Rumah, Kec, Kota." class="w-full p-3 rounded-xl border border-stone-200 focus:ring-2 focus:ring-orange-500 outline-none transition" rows="3" required></textarea>
                    </div>

                    <div class="flex flex-col gap-1 mb-8">
                        <label class="text-[10px] font-bold text-stone-500 ml-1">CATATAN PESANAN (OPSIONAL)</label>
                        <textarea name="note" placeholder="Contoh: Tolong digiling halus untuk espresso." class="w-full p-3 rounded-xl border border-stone-200 bg-stone-50 focus:ring-2 focus:ring-orange-500 outline-none transition text-sm" rows="2"></textarea>
                    </div>

                    <input type="hidden" name="total_price" value="{{ $total }}">

                    <button type="submit" @if($total == 0) disabled @endif class="w-full bg-orange-800 text-white py-4 rounded-2xl font-bold hover:bg-orange-900 transition shadow-xl shadow-orange-900/20 text-lg active:scale-95 disabled:bg-stone-300 disabled:shadow-none disabled:cursor-not-allowed">
                        Konfirmasi & Bayar Sekarang
                    </button>
                </form>
            </div>
        </div>
    </main>

</body>
</html>