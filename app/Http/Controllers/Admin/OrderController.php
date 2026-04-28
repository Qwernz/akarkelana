<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function salesHistory(Request $request)
    {
        $query = Order::with(['items.product'])->where('status', 'success');
        $spendingQuery = \App\Models\BahanBakuLog::query(); // Siapkan query pengeluaran

        // 1. Tentukan "Tanggal Patokan"
        $dateInput = $request->input('date', Carbon::today()->toDateString());
        $pivotDate = Carbon::parse($dateInput);

        // 2. Ambil jenis filter
        $filter = $request->input('filter');

        // 3. Logika Filter (Diterapkan ke KEDUA query)
        if ($filter == 'week') {
            $start = $pivotDate->copy()->startOfWeek();
            $end = $pivotDate->copy()->endOfWeek();

            $query->whereBetween('created_at', [$start, $end]);
            $spendingQuery->whereBetween('created_at', [$start, $end]); // Filter pengeluaran

            $label = "Minggu (" . $start->format('d M') . " - " . $end->format('d M Y') . ")";
        } elseif ($filter == 'month') {
            $query->whereMonth('created_at', $pivotDate->month)->whereYear('created_at', $pivotDate->year);
            $spendingQuery->whereMonth('created_at', $pivotDate->month)->whereYear('created_at', $pivotDate->year); // Filter pengeluaran

            $label = "Bulan " . $pivotDate->translatedFormat('F Y');
        } elseif ($filter == 'year') {
            $query->whereYear('created_at', $pivotDate->year);
            $spendingQuery->whereYear('created_at', $pivotDate->year); // Filter pengeluaran

            $label = "Tahun " . $pivotDate->year;
        } else {
            // Default: Harian
            $query->whereDate('created_at', $dateInput);
            $spendingQuery->whereDate('created_at', $dateInput); // Filter pengeluaran konsisten dengan dateInput

            $label = $pivotDate->translatedFormat('d F Y');
        }

        // 4. Eksekusi Query
        $sales = $query->latest()->get();
        $total_revenue = $sales->sum('total_price');

        $spending = $spendingQuery->latest()->get();
        $total_spending = $spending->sum('harga_beli');

        // Pastikan variabel 'selectedDate' dikirim (di kode kamu tadi mungkin tertulis $dateInput)
        $selectedDate = $dateInput;

        return view('admin.sales', compact( // Hapus '.history'
            'sales',
            'total_revenue',
            'spending',
            'total_spending',
            'label',
            'selectedDate'
        ));
    }

    public function orders()
    {
        $hariIni = Carbon::today()->toDateString();

        // Tambahkan with(['items.product']) di sini
        $orders = Order::with(['items.product'])
            ->where('status', 'success')
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }
    public function show($id)
    {
        // Mengambil satu data order beserta detail item dan produknya
        $order = Order::with(['items.product'])->findOrFail($id);

        // Mengirim data ke file view show.blade.php
        return view('admin.orders.show', compact('order'));
    }

    public function index()
    {
        // Mengambil total stok dari semua produk kopi (roasted)
        $totalPack = \App\Models\Product::sum('stock');

        // Konversi ke Kg (asumsi 1 pack = 250gr)
        $totalKgRoasted = $totalPack * 0.25;

        // Ambil data lainnya
        $totalMentah = \App\Models\BahanBaku::sum('stok_kg');
        $pesananPending = \App\Models\Order::where('status', 'pending')->count();
        $jumlahVarian = \App\Models\Product::count();

        return view('dashboard', compact(
            'totalMentah',
            'totalPack',
            'totalKgRoasted',
            'pesananPending',
            'jumlahVarian'
        ));
    }

    public function salesAnalysis()
    {
        // 1. DATA KEUANGAN
        $totalRevenue = \App\Models\Order::where('status', 'success')->sum('total_price');
        $totalSpending = \App\Models\BahanBaku::sum('total_biaya_pengeluaran');

        // 2. DATA BIJI (dalam Kg)
        $stokMentah = \App\Models\BahanBaku::sum('stok_kg') ?? 0;
        $stokMatang = \App\Models\BahanBakuMatang::sum('stok_kg') ?? 0;

        // 3. DATA PRODUK JADI (Pack)
        $totalStock = \App\Models\Product::sum('stock');

        // 4. DATA REVIEW (Ambil dari tabel Order, bukan model Review)
        // Kita ambil 10 review terbaru yang sudah diberikan rating
        $reviews = Order::whereNotNull('rating')
            ->where('rating', '>', 0)
            ->with(['user', 'items'])
            ->latest()
            ->take(5)
            ->get();

        // 5. DATA PENJUALAN PER PRODUK (Untuk grafik)
        $topProducts = \App\Models\OrderItem::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->take(5)
            ->get();

        $chartLabels = $topProducts->map(fn($item) => $item->product->name ?? 'Unknown')->toArray();
        $chartData = $topProducts->pluck('total_qty')->toArray();

        // Mencari produk Best Seller (Data tunggal untuk box best seller)
        $bestSeller = $topProducts->first();

        return view('admin.sales.analysis', compact(
            'totalRevenue',
            'totalSpending',
            'stokMentah',
            'stokMatang',
            'totalStock',
            'chartLabels',
            'chartData',
            'reviews',
            'topProducts',
            'bestSeller' // Tambahkan ini agar box best seller tidak kosong
        ));
    }

    public function submitRating(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
        ]);

        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $order->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas ulasannya!');
    }
}
