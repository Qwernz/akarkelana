<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\BahanBakuMatang;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class RoastingController extends Controller
{
    public function index()
    {
        $bahanMentah = \App\Models\BahanBaku::where('stok_kg', '>', 0)->get();
        $bahanMatang = \App\Models\BahanBakuMatang::latest()->get();
        $productVariants = ProductVariant::with('product')->get();
        $products = Product::all();
        // Mengambil data riwayat pembelian dari tabel log
        $spending = \App\Models\BahanBakuLog::latest()->get();

        return view('admin.roasting.index', compact(
            'bahanMentah',
            'bahanMatang',
            'productVariants',
            'spending',
            'products'
        ));
    }

    // TAHAP 1: PROSES ROASTING (Biji Mentah -> Biji Matang)
    public function process(Request $request)
    {
        // 1. Validasi Input (Pastikan sesuai dengan nama di attribute 'name' pada Blade)
        $request->validate([
            'bahan_mentah_id'    => 'required|exists:bahan_bakus,id',
            'jumlah_mentah_gram' => 'required|numeric|min:1', // Harus 'gram'
            'biji_matang_id'     => 'required|exists:bahan_baku_matangs,id',
            'jumlah_matang_gram' => 'required|numeric|min:1',
        ]);

        $mentah = BahanBaku::findOrFail($request->bahan_mentah_id);
        $matang = BahanBakuMatang::findOrFail($request->biji_matang_id);

        // 2. Konversi input Gram ke KG untuk sinkronisasi Database (karena DB pakai KG)
        $jumlahMentahKg = $request->jumlah_mentah_gram / 1000;
        $jumlahMatangKg = $request->jumlah_matang_gram / 1000;

        // 3. Cek apakah stok mentah cukup (dalam satuan KG)
        if ($mentah->stok_kg < $jumlahMentahKg) {
            return back()->with('error', 'Stok tidak cukup! Sisa stok mentah: ' . ($mentah->stok_kg * 1000) . ' gram');
        }

        // 4. Eksekusi pemindahan stok
        DB::transaction(function () use ($mentah, $matang, $jumlahMentahKg, $jumlahMatangKg) {
            $mentah->decrement('stok_kg', $jumlahMentahKg);
            $matang->increment('stok_kg', $jumlahMatangKg);
        });

        return redirect()->route('admin.roasting')->with('success', 'Proses Roasting Berhasil Disimpan!');
    }

    // TAHAP 2: PROSES PACKAGING (Biji Matang -> Varian Produk)
    public function processPackaging(Request $request)
    {
        $request->validate([
            'biji_matang_id' => 'required|exists:bahan_baku_matangs,id',
            'variant_id'     => 'required|exists:product_variants,id',
            'jumlah_pack'    => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::findOrFail($request->variant_id);
        $bijiMatang = BahanBakuMatang::findOrFail($request->biji_matang_id);

        // Logika konversi berat varian ke KG
        $weightString = strtolower($variant->weight);
        if (str_contains($weightString, 'kg')) {
            $gramValue = (float)$weightString * 1000;
        } else {
            $gramValue = (float)$weightString;
        }

        $beratPerPackKg = $gramValue / 1000;
        $totalKebutuhanKg = $beratPerPackKg * $request->jumlah_pack;

        if ($bijiMatang->stok_kg < $totalKebutuhanKg) {
            $butuhGram = $totalKebutuhanKg * 1000;
            $adaGram = $bijiMatang->stok_kg * 1000;
            return back()->with('error', "Stok biji matang tidak cukup! Butuh: {$butuhGram}g, Tersedia: {$adaGram}g.");
        }

        DB::transaction(function () use ($variant, $bijiMatang, $totalKebutuhanKg, $request) {
            $bijiMatang->decrement('stok_kg', $totalKebutuhanKg);
            $variant->increment('stock', $request->jumlah_pack);
        });

        return back()->with('success', 'Packaging berhasil!');
    }
}
