<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\BahanBaku;
use App\Models\BahanBakuMatang;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Statistik Utama
        $totalAkun = User::count();
        $totalProduk = Product::count();

        // 2. Ambil ulasan terbaru (Batasi 5 saja agar dashboard tetap rapi)
        $reviews = Order::whereNotNull('rating')
            ->where('rating', '>', 0)
            ->with(['user', 'items'])
            ->latest()
            ->take(5) // Ubah ke 5 jika ingin lebih ringkas
            ->get();

        // 3. Data Stok & Perhitungan Gram
        $totalMentahGram = (BahanBaku::sum('stok_kg') ?? 0) * 1000;
        $totalMatangGram = (BahanBakuMatang::sum('stok_kg') ?? 0) * 1000;

        $products = Product::with('variants')->latest()->get();
        $stokMentahKg = BahanBaku::sum('stok_kg') ?? 0;
        $stokMatangKg = BahanBakuMatang::sum('stok_kg') ?? 0;

        $capMentah = 1000;
        $capMatang = 500;

        $percMentah = ($stokMentahKg / $capMentah) * 100;
        $percMatang = ($stokMatangKg / $capMatang) * 100;

        return view('dashboard', compact(
            'reviews',
            'totalAkun',
            'totalProduk',
            'totalMentahGram',
            'totalMatangGram',
            'products',
            'stokMentahKg',
            'stokMatangKg',
            'capMentah',
            'capMatang',
            'percMentah',
            'percMatang'
        ));
    }

    // Fungsi untuk halaman "Lihat Semua Ulasan"
    public function reviews()
    {
        $reviews = Order::whereNotNull('rating')
            ->where('rating', '>', 0)
            ->with(['user', 'items'])
            ->latest()
            ->get(); // Tanpa 'take', ambil semuanya

        return view('admin.reviews.index', compact('reviews'));
    }
}
