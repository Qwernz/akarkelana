<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BahanBaku;
use App\Models\BahanBakuMatang; // Tambahkan import ini!
use App\Models\Product;
use App\Models\Order;
use App\Models\User; // Tambahkan import ini!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        // --- LOGIKA UNTUK ADMIN ---
        if ($user->role === 'admin') {
            $totalAkun = User::count();
            $totalProduk = Product::count();
            $products = Product::with('variants')->latest()->get();
            $totalMentahGram = (BahanBaku::sum('stok_kg') ?? 0) * 1000;
            $totalMatangGram = (BahanBakuMatang::sum('stok_kg') ?? 0) * 1000;

            // Kirim semua data yang dibutuhkan View Dashboard Admin
            return view('dashboard', compact(
                'totalAkun', 
                'totalProduk', 
                'products', 
                'totalMentahGram', 
                'totalMatangGram'
            ));
        }
        
        // --- LOGIKA UNTUK USER BIASA ---
        
        // 1. Pembatalan otomatis pesanan kadaluarsa
        $this->handleExpiredOrders($user->id);

        // 2. Statistik User
        $totalPembelian = Order::where('user_id', $user->id)->where('status', 'success')->count();
        $belumDibayar = Order::where('user_id', $user->id)->where('status', 'pending')->count();
        $belumDiterima = Order::where('user_id', $user->id)->where('status', 'processing')->count();

        // Kirim data yang dibutuhkan View Dashboard User
        // Note: kita kirim null/kosong untuk variabel admin agar tidak error di Blade jika dipanggil
        return view('dashboard', [
            'totalPembelian' => $totalPembelian,
            'belumDibayar'   => $belumDibayar,
            'belumDiterima'  => $belumDiterima,
            'products'       => collect([]), // Berikan koleksi kosong agar @forelse tidak error
        ]);
    }

    private function handleExpiredOrders($userId)
    {
        $expiredOrders = Order::with('items.product')
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(10))
            ->get();

        foreach ($expiredOrders as $order) {
            $order->update(['status' => 'cancelled']);
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }
    }

    public function history()
    {
        $orders = Order::with(['items.product'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('User.history', compact('orders'));

        // ATAU jika kamu ingin menggunakan file milik kasir (tidak disarankan karena desainnya beda)
        return view('kasir.history', compact('orders'));
    }
}
