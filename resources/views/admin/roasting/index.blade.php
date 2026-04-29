<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-stone-800">Manajemen Produksi & Stok</h2>
    </div>

    {{-- NAVIGASI TAB --}}
    <div class="flex space-x-2 mb-8 bg-stone-200 p-1 rounded-xl w-fit">
        <button onclick="switchTab('roasting')" id="btn-roasting" class="tab-btn px-6 py-2.5 rounded-lg font-bold text-sm transition-all duration-200 bg-white text-orange-700 shadow-sm">
            1. Proses Roasting
        </button>
        <button onclick="switchTab('packaging')" id="btn-packaging" class="tab-btn px-6 py-2.5 rounded-lg font-bold text-sm transition-all duration-200 text-stone-600 hover:text-stone-800">
            2. Packaging (Produk)
        </button>
        <button onclick="switchTab('pemasukan')" id="btn-pemasukan" class="tab-btn px-6 py-2.5 rounded-lg font-bold text-sm transition-all duration-200 text-stone-600 hover:text-stone-800">
            3. Stok Biji Mentah
        </button>
    </div>

    {{-- TAB 1: PROSES ROASTING --}}
    <div id="content-roasting" class="tab-content block animate-in fade-in duration-300">
        <div class="max-w-5xl bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden mb-8">
            <div class="bg-stone-50 px-8 py-4 border-b border-stone-100">
                <h3 class="font-bold text-stone-800 uppercase text-xs tracking-widest">Form Roasting (Presisi Gram)</h3>
            </div>
            <form action="{{ route('admin.roasting.process') }}" method="POST" class="p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div class="space-y-4">
                        <h3 class="font-bold text-stone-600 uppercase text-[10px] pb-2 border-b">A. Input Bahan Mentah</h3>
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Pilih Green Beans</label>
                            <select name="bahan_mentah_id" class="w-full rounded-lg border-stone-300 focus:ring-orange-500 text-sm">
                                @foreach($bahanMentah as $b)
                                <option value="{{ $b->id }}">{{ $b->nama_bahan }} (Stok: {{ number_format($b->stok_kg * 1000, 0, ',', '.') }}g)</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Jumlah Digunakan (Gram)</label>
                            <input type="number" name="jumlah_mentah_gram" class="w-full rounded-lg border-stone-300 focus:ring-orange-500 text-sm" placeholder="Contoh: 700" step="any" required>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="font-bold text-orange-600 uppercase text-[10px] pb-2 border-b">B. Output Biji Matang</h3>
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Pilih Wadah Biji Matang</label>
                            <select name="biji_matang_id" class="w-full rounded-lg border-stone-300 focus:ring-orange-500 text-sm">
                                @foreach($bahanMatang as $bm)
                                <option value="{{ $bm->id }}">{{ $bm->nama_biji }} (Stok: {{ number_format($bm->stok_kg * 1000, 0, ',', '.') }}g)</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Berat Hasil Roasted (Gram)</label>
                            <input type="number" name="jumlah_matang_gram" class="w-full rounded-lg border-stone-300 focus:ring-orange-500 text-sm" placeholder="Contoh: 510" step="any" required>
                        </div>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t flex justify-end">
                    <button type="submit" class="bg-stone-800 hover:bg-stone-900 text-white px-10 py-3 rounded-xl font-bold transition shadow-lg active:scale-95">SIMPAN KE STOK MATANG</button>
                </div>
            </form>
        </div>

        {{-- DAFTARKAN WADAH --}}
        <div class="max-w-5xl bg-stone-900 rounded-2xl shadow-xl overflow-hidden border border-stone-800 p-8 mb-8">
            <h3 class="text-sm font-bold text-orange-500 uppercase tracking-widest mb-4">Daftarkan Wadah Biji Matang Baru</h3>
            <form action="{{ route('admin.bahan_baku.store_matang') }}" method="POST" class="flex gap-4">
                @csrf
                <input type="text" name="nama_biji" class="flex-1 rounded-xl border-stone-700 bg-stone-800 text-white text-sm focus:ring-orange-500" placeholder="Contoh: Arabika Gayo Roasted..." required>
                <button type="submit" class="bg-stone-100 hover:bg-white text-stone-900 font-bold py-2 px-8 rounded-xl transition active:scale-95 text-sm uppercase">Tambah Wadah</button>
            </form>
        </div>

        {{-- TABEL BIJI MATANG --}}
        <div class="max-w-5xl">
            <span class="font-bold text-[10px] text-stone-400 uppercase tracking-widest block mb-4">Persediaan Biji Matang (Roasted)</span>
            <div class="overflow-hidden rounded-xl border border-stone-100 bg-white">
                <table class="w-full text-left text-xs">
                    <thead class="bg-stone-50 text-stone-500 uppercase font-black">
                        <tr>
                            <th class="px-4 py-3">Nama Wadah</th>
                            <th class="px-4 py-3 text-center">Stok (Gram)</th>
                            <th class="px-4 py-3 text-center">Stok (Kg)</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($bahanMatang as $bm)
                        <tr class="hover:bg-stone-50/50 transition-colors">
                            <td class="px-4 py-3 font-bold text-stone-800">{{ $bm->nama_biji }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="bg-orange-50 text-orange-700 px-2 py-1 rounded-md font-bold">{{ number_format($bm->stok_kg * 1000, 0, ',', '.') }} g</span>
                            </td>
                            <td class="px-4 py-3 text-center text-stone-500">{{ number_format($bm->stok_kg, 2, ',', '.') }} kg</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-3">
                                    <button type="button" onclick="editWadah('{{ $bm->id }}', '{{ $bm->nama_biji }}', '{{ $bm->stok_kg }}')" class="text-blue-500 hover:text-blue-700 font-bold uppercase text-[10px]">Edit</button>
                                    <form action="{{ route('admin.bahan_baku.destroy_matang', $bm->id) }}" method="POST" onsubmit="return confirm('Hapus wadah ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold uppercase text-[10px]">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-stone-400 italic">Belum ada wadah terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TAB 2: PACKAGING --}}
    <div id="content-packaging" class="tab-content hidden animate-in fade-in duration-300">
        <div class="max-w-5xl bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden mb-8">
            <div class="bg-orange-50 px-8 py-4 border-b border-orange-100">
                <h3 class="font-bold text-orange-800 uppercase text-xs tracking-widest">Form Packaging</h3>
            </div>
            <form action="{{ route('admin.packaging.process') }}" method="POST" class="p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-stone-700 mb-1">Pilih Biji Matang</label>
                        <select name="biji_matang_id" class="w-full rounded-lg border-stone-300 focus:ring-orange-500 text-sm">
                            @foreach($bahanMatang as $bm)
                            <option value="{{ $bm->id }}">{{ $bm->nama_biji }} ({{ number_format($bm->stok_kg * 1000, 0) }}g)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-stone-700 mb-1">Varian Produk & Jumlah Pack</label>
                        <select name="variant_id" class="w-full rounded-lg border-stone-300 focus:ring-orange-500 text-sm mb-4">
                            @foreach($productVariants as $v)
                            <option value="{{ $v->id }}">{{ $v->product->name }} ({{ $v->weight }})</option>
                            @endforeach
                        </select>
                        <input type="number" name="jumlah_pack" class="w-full rounded-lg border-stone-300 text-sm" placeholder="Jumlah Pack" required>
                    </div>
                </div>
                <button type="submit" class="mt-6 w-full bg-orange-700 text-white py-3 rounded-xl font-bold uppercase text-xs tracking-widest shadow-lg">Konfirmasi Packaging</button>
            </form>
        </div>
    </div>

    {{-- TAB 3: STOK BIJI MENTAH --}}
    <div id="content-pemasukan" class="tab-content hidden animate-in fade-in duration-300">
        <div class="max-w-5xl bg-stone-900 rounded-2xl shadow-xl overflow-hidden border border-stone-800 mb-8">
            <div class="bg-stone-800/50 px-8 py-4 border-b border-stone-700">
                <h3 class="text-sm font-bold text-orange-500 uppercase tracking-tight">Input Pembelian Biji Mentah</h3>
            </div>
            <form action="{{ route('admin.bahan_baku.restock') }}" method="POST" class="p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">

                    {{-- INPUT TEKS DENGAN SARAN (DATALIST) --}}
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-stone-300 uppercase mb-2">Nama Bahan</label>
                        <input type="text" name="nama_bahan" list="bahan_list" class="w-full rounded-xl border-stone-700 bg-stone-800 text-white text-sm focus:ring-orange-500" placeholder="Ketik nama bahan..." required autocomplete="off">
                        <datalist id="bahan_list">
                            @foreach($bahanMentah as $bm)
                            <option value="{{ $bm->nama_bahan }}">
                                @endforeach
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-300 uppercase mb-2">Jumlah (Kg)</label>
                        <input type="number" name="jumlah_masuk" step="0.01" class="w-full rounded-xl border-stone-700 bg-stone-800 text-white text-sm" placeholder="0.00" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-300 uppercase mb-2">Harga (Rp)</label>
                        <input type="number" name="harga_beli" class="w-full rounded-xl border-stone-700 bg-stone-800 text-white text-sm" placeholder="0" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-300 uppercase mb-2">Kota / Lokasi</label>
                        <input type="text" name="lokasi" class="w-full rounded-xl border-stone-700 bg-stone-800 text-white text-sm" placeholder="Aceh, dsb">
                    </div>

                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 rounded-xl transition shadow-lg text-xs uppercase active:scale-95">
                        Simpan
                    </button>
                </div>
            </form>
        </div>

        {{-- TABEL RIWAYAT --}}
        <div class="max-w-5xl">
            <span class="font-bold text-[10px] text-stone-400 uppercase tracking-widest block mb-4">Riwayat Transaksi</span>
            <div class="overflow-hidden rounded-xl border border-stone-800 bg-stone-900">
                <table class="w-full text-left text-xs text-stone-400">
                    <thead class="bg-stone-800/50 text-stone-500 uppercase font-black">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Nama Bahan</th>
                            <th class="px-4 py-3 text-center">Lokasi</th>
                            <th class="px-4 py-3 text-center">Jumlah</th>
                            <th class="px-4 py-3 text-right">Biaya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-800">
                        @forelse($spending as $s)
                        <tr class="hover:bg-stone-800/30">
                            <td class="px-4 py-3 text-[10px]">{{ $s->created_at->format('d/m/y H:i') }}</td>
                            <td class="px-4 py-3 font-bold text-white">{{ $s->nama_bahan }}</td>
                            <td class="px-4 py-3 text-center">{{ $s->lokasi ?? '-' }}</td>
                            <td class="px-4 py-3 text-center text-orange-500">+{{ $s->jumlah_beli }} Kg</td>
                            <td class="px-4 py-3 text-right font-bold text-white">Rp {{ number_format($s->harga_beli, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-stone-600 italic">Belum ada riwayat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="modalEditWadah" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full shadow-2xl">
            <h3 class="text-lg font-bold text-stone-800 mb-4">Edit Wadah Biji Matang</h3>
            <form id="formEditWadah" method="POST">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Nama Wadah/Biji</label>
                        <input type="text" name="nama_biji" id="edit_nama_biji" class="w-full rounded-lg border-stone-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Stok (Kg)</label>
                        <input type="number" name="stok_kg" id="edit_stok_kg" step="0.01" class="w-full rounded-lg border-stone-300">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModalEdit()" class="px-4 py-2 text-stone-500 font-bold text-sm">BATAL</button>
                    <button type="submit" class="px-6 py-2 bg-stone-800 text-white rounded-xl font-bold text-sm shadow-lg">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>

    {{-- JAVASCRIPT: Diletakkan di paling bawah dan di luar fungsi modal --}}
    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('bg-white', 'text-orange-700', 'shadow-sm');
                b.classList.add('text-stone-600');
            });
            document.getElementById('content-' + tab).classList.remove('hidden');
            const activeBtn = document.getElementById('btn-' + tab);
            activeBtn.classList.add('bg-white', 'text-orange-700', 'shadow-sm');
            activeBtn.classList.remove('text-stone-600');
        }

        function editWadah(id, nama, stok) {
            const form = document.getElementById('formEditWadah');
            form.action = `/admin/bahan-baku-matang/${id}`;
            document.getElementById('edit_nama_biji').value = nama;
            document.getElementById('edit_stok_kg').value = stok;
            document.getElementById('modalEditWadah').classList.remove('hidden');
        }

        function closeModalEdit() {
            document.getElementById('modalEditWadah').classList.add('hidden');
        }

        // OTOMATISASI NAMA BAHAN (PENTING: Di luar fungsi lain agar jalan saat load)
        document.addEventListener('DOMContentLoaded', function() {
            const selectBahan = document.getElementById('select_bahan_mentah');
            if (selectBahan) {
                selectBahan.addEventListener('change', function() {
                    const text = this.options[this.selectedIndex].text;
                    document.getElementById('nama_bahan_hidden').value = text;
                });
            }
        });
    </script>
</x-app-layout>