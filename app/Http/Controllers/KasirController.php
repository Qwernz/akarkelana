<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    // Menampilkan pesanan yang baru masuk (Status: Pending)
    public function index()
    {
        // Tambahkan with agar detail item terbaca
        $orders = Order::with(['items.product'])->where('status', 'pending')->latest()->get();
        return view('kasir.orders', compact('orders'));
    }

    // Menampilkan riwayat pesanan (Status: Success atau Cancelled)
    public function history()
    {
        // 1. Tambahkan with(['items.product'])
        // 2. Gunakan nama variabel $orders agar sinkron dengan file Blade & JS kita
        $orders = Order::with(['items.product'])
            ->whereIn('status', ['success', 'cancelled'])
            ->latest()
            ->get();

        return view('kasir.history', compact('orders'));
    }

    // Proses update status (Terima/Batal)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        $pesan = $request->status == 'success' ? 'Pesanan berhasil diterima!' : 'Pesanan telah dibatalkan.';
        return redirect()->back()->with('success', $pesan);
    }
}
