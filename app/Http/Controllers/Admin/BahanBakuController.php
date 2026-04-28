<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BahanBaku;
use App\Models\BahanBakuMatang;
use App\Models\BahanBakuLog;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class BahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() // atau nama fungsi dashboard kamu
    {
        $totalMentah = BahanBaku::sum('stok_kg');
        $totalPack = Product::sum('stock');
        $totalKgRoasted = $totalPack * 0.25; // Asumsi 250gr per pack
        $pesananPending = Order::where('status', 'pending')->count();
        $jumlahVarian = Product::count();

        return view('dashboard', compact(
            'totalMentah',
            'totalPack',
            'totalKgRoasted',
            'pesananPending',
            'jumlahVarian'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ini penting agar saat diklik, halaman form input muncul
        return view('admin.bahan_baku.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required',
            'stok_kg' => 'required|integer',
        ]);

        BahanBaku::create($request->all());

        return redirect()->route('bahan_baku.index')->with('success', 'Bahan baku berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bahan = BahanBaku::findOrFail($id);
        return view('admin.bahan_baku.edit', compact('bahan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bahan' => 'required',
            'stok_kg' => 'required|integer',
        ]);

        $bahan = BahanBaku::findOrFail($id);
        $bahan->update($request->all());

        return redirect()->route('bahan_baku.index')->with('success', 'Bahan baku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $bahan = \App\Models\BahanBaku::findOrFail($id);

        // Opsional: Beri peringatan jika stok masih ada
        $nama = $bahan->nama_bahan;
        $bahan->delete();

        return redirect()->back()->with('success', "Bahan baku '{$nama}' telah dihapus dari sistem.");
    }

    public function dashboard()
    {
        // Total stok bahan mentah (Kg)
        $totalMentah = BahanBaku::sum('stok_kg');

        // Total stok produk jadi (Pack) 
        // Kita asumsikan 1 pack = 0.25kg (250gr) untuk konversi perbandingan berat
        $totalProdukJadiPack = Product::sum('stock');
        $totalProdukJadiKg = $totalProdukJadiPack * 0.25;

        return view('admin.dashboard', compact('totalMentah', 'totalProdukJadiKg', 'totalProdukJadiPack'));
    }

    // TAHAP 1: PROSES ROASTING (Biji Mentah -> Biji Matang)
    public function process(Request $request)
    {
        $request->validate([
            'bahan_mentah_id' > 'required|exists:bahan_bakus,id',
            'jumlah_mentah_gram' => 'required|numeric|min:1',
            'biji_matang_id' => 'required|exists:bahan_baku_matangs,id',
            'jumlah_matang_gram' => 'required|numeric|min:1',
        ]);

        $mentah = BahanBaku::findOrFail($request->bahan_mentah_id);
        $matang = BahanBakuMatang::findOrFail($request->biji_matang_id);

        // Konversi Gram ke KG (Contoh: 700g menjadi 0.7kg)
        $jumlahMentahKg = (float) $request->jumlah_mentah_gram / 1000;
        $jumlahMatangKg = (float) $request->jumlah_matang_gram / 1000;

        if ($mentah->stok_kg < $jumlahMentahKg) {
            return back()->with('error', 'Stok tidak cukup! Sisa stok: ' . ($mentah->stok_kg * 1000) . 'g');
        }

        DB::transaction(function () use ($mentah, $matang, $jumlahMentahKg, $jumlahMatangKg) {
            // 1. Paksa Update Mentah (Bypass model save)
            $stokBaruMentah = (float)$mentah->stok_kg - (float)$jumlahMentahKg;
            \App\Models\BahanBaku::where('id', $mentah->id)->update([
                'stok_kg' => $stokBaruMentah
            ]);

            // 2. Paksa Update Matang
            $stokBaruMatang = (float)$matang->stok_kg + (float)$jumlahMatangKg;
            \App\Models\BahanBakuMatang::where('id', $matang->id)->update([
                'stok_kg' => $stokBaruMatang
            ]);
        });

        return redirect()->route('admin.roasting')->with('success', 'Proses Roasting Berhasil!');
    }

    public function storeMatang(Request $request)
    {
        $request->validate([
            'nama_biji' => 'required|string|max:255'
        ]);

        // Menggunakan model BahanBakuMatang
        \App\Models\BahanBakuMatang::create([
            'nama_biji' => $request->nama_biji,
            'stok_kg'   => 0, // Awalnya stok kosong
        ]);

        return back()->with('success', 'Wadah biji matang berhasil didaftarkan!');
    }

    public function destroyMatang($id)
    {
        $wadah = \App\Models\BahanBakuMatang::findOrFail($id);

        // Cek jika stok masih ada, beri peringatan (opsional)
        if ($wadah->stok_kg > 0) {
            return back()->with('error', 'Wadah tidak bisa dihapus karena masih ada stoknya!');
        }

        $wadah->delete();

        return back()->with('success', 'Wadah biji matang berhasil dihapus.');
    }
    public function updateMatang(Request $request, $id)
    {
        $request->validate([
            'nama_biji' => 'required|string|max:255',
            'stok_kg'   => 'required|numeric|min:0'
        ]);

        $wadah = \App\Models\BahanBakuMatang::findOrFail($id);
        $wadah->update([
            'nama_biji' => $request->nama_biji,
            'stok_kg'   => $request->stok_kg
        ]);

        return back()->with('success', 'Data wadah berhasil diperbarui!');
    }

    public function spendingHistory()
    {
        // Mengambil semua bahan baku yang sudah pernah di-restock (punya biaya)
        $spending = BahanBaku::where('total_biaya_pengeluaran', '>', 0)
            ->orderBy('updated_at', 'desc')
            ->get();

        $totalSpending = $spending->sum('total_biaya_pengeluaran');

        return view('admin.spending_history', compact('spending', 'totalSpending'));
    }

    public function restock(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required',
            'jumlah_masuk' => 'required|numeric',
            'harga_beli' => 'required|numeric',
            'lokasi' => 'nullable|string', // Validasi lokasi
        ]);

        // ... (kode update stok utama tetap sama) ...

        // Update bagian pembuatan Log
        \App\Models\BahanBakuLog::create([
            'nama_bahan' => $request->nama_bahan,
            'jumlah_beli' => $request->jumlah_masuk,
            'harga_beli' => $request->harga_beli,
            'lokasi' => $request->lokasi, // Simpan lokasi ke database
        ]);

        return back()->with('success', 'Pembelian berhasil dicatat!');
    }
}
