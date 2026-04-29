<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem; // Pastikan ini ada
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class CartController extends Controller
{
    // 1. Menambahkan produk ke keranjang
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Maaf, stok kopi ini sedang habis!');
        }

        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] + 1 > $product->stock) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi!');
            }
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    // 2. Menampilkan isi keranjang
    public function index()
    {
        return view('cart');
    }

    // 3. Proses Checkout
    public function checkout(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'total_price' => 'required',
        ]);

        $cart = session()->get('cart');
        if (!$cart) {
            return redirect()->back()->with('error', 'Keranjang masih kosong!');
        }

        try {
            // 1. Buat pesanan terlebih dahulu di database kita
            $order = Order::create([
                'customer_name' => $request->name,
                'customer_phone' => $request->phone,
                'customer_address' => $request->address,
                'note' => $request->note,
                'total_price' => $request->total_price,
                'status' => 'pending',
                'user_id' => Auth::id(),
            ]);

            // 2. Loop item keranjang ke tabel OrderItem
            foreach ($cart as $variantId => $details) {
                $variant = \App\Models\ProductVariant::find($variantId);
                if ($variant) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $variant->product_id,
                        'name'       => $details['name'],
                        'weight'     => $details['weight'] ?? '-',
                        'quantity'   => $details['quantity'],
                        'price'      => $details['price'],
                    ]);
                    // Kurangi stok barang
                    $variant->decrement('stock', $details['quantity']);
                }
            }

            // 3. Konfigurasi Midtrans
            \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
            \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => 'AK-' . $order->id . '-' . time(),
                    'gross_amount' => (int) $order->total_price,
                ],
                'customer_details' => [
                    'first_name' => $request->name,
                    'phone' => $request->phone,
                ],
                'enabled_payments' => [
                    'credit_card',
                    'gopay',
                    'shopeepay',
                    'qris',
                    'bca_va',
                    'bni_va',
                    'bri_va',
                    'mandiri_va',
                    'indomaret',
                    'alfamart'
                ],
            ];

            // 4. Panggil Midtrans Snap
            $midtransResponse = \Midtrans\Snap::createTransaction($params);

            // 5. Update kolom snap_token dengan URL Redirect yang didapat
            $order->update([
                'snap_token' => $midtransResponse->redirect_url
            ]);

            // 6. Kosongkan keranjang belanja
            session()->forget('cart');

            // 7. ALIRKAN LANGSUNG KE MIDTRANS
            return redirect($midtransResponse->redirect_url);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 4. Hapus item dari keranjang
    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Produk berhasil dihapus!');
        }
    }

    public function add(Request $request, $id)
    {
        // 1. Cari varian berdasarkan ID yang dikirim form
        $variant = \App\Models\ProductVariant::with('product')->findOrFail($id);

        // 2. Ambil keranjang dari session (jika belum ada, buat array kosong)
        $cart = session()->get('cart', []);

        // 3. Logika tambah ke keranjang
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $variant->product->name,
                "weight" => $variant->weight,
                "quantity" => 1,
                "price" => $variant->price,
                "image" => $variant->product->image
            ];
        }

        // 4. Simpan kembali ke session
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            if ($request->action == 'increase') {
                $cart[$id]['quantity']++;
            } elseif ($request->action == 'decrease') {
                if ($cart[$id]['quantity'] > 1) {
                    $cart[$id]['quantity']--;
                } else {
                    // Jika jumlah 1 lalu dikurangi, hapus dari keranjang
                    unset($cart[$id]);
                }
            }
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Keranjang diperbarui!');
    }

    public function submitRating(Request $request, $id)
    {
        // Coba tambahkan dd($request->all()) di sini untuk tes apakah data sampai ke controller
        // dd($request->all()); 

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        $order = Order::findOrFail($id);

        $order->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->back()->with('success', 'Rating berhasil dikirim!');
    }
}
