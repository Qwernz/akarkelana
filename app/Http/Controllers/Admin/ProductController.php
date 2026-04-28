<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\ProductVariant; // Pastikan model ini sudah dibuat
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Ambil produk beserta variannya agar bisa tampil di tabel manajemen
        $products = Product::with('variants')->latest()->get();
        return view('admin.products.index', compact('products'));

        $products = Product::with('variants')->get();
        return view('katalog', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    // 3. MENYIMPAN PRODUK BARU DENGAN VARIAN
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'description' => 'nullable',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'variants'    => 'required|array',
        ]);

        // Upload Gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/products'), $filename);
            $imagePath = 'images/products/' . $filename;
        }

        // 1. Simpan Produk Utama
        $product = Product::create([
            'name'        => $request->name,
            'description' => $request->description,
            'image'       => $imagePath,
        ]);

        // 2. Simpan Varian (100g, 200g, 500g, 1kg)
        foreach ($request->variants as $weight => $data) {
            // Hanya simpan jika checkbox 'active' dicentang
            if (isset($data['active'])) {
                $product->variants()->create([
                    'weight'    => $weight,
                    'price'     => $data['price'] ?? 0,
                    'stock'     => $data['stock'] ?? 0,
                    'is_active' => true
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produk dan Varian berhasil ditambah!');
    }

    // 4. UPDATE PRODUK DAN VARIAN
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'variants' => 'required|array',
        ]);

        $data = $request->only(['name', 'description']);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/products'), $filename);
            $data['image'] = 'images/products/' . $filename;

            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
        }

        // 1. Update data utama
        $product->update($data);

        // 2. Update Varian (Hapus yang lama, ganti yang baru atau update yang ada)
        // Cara termudah: hapus semua varian lama dan buat ulang berdasarkan input baru
        $product->variants()->delete();

        foreach ($request->variants as $weight => $vData) {
            if (isset($vData['active'])) {
                $product->variants()->create([
                    'weight'    => $weight,
                    'price'     => $vData['price'] ?? 0,
                    'stock'     => $vData['stock'] ?? 0,
                    'is_active' => true
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function orders(Request $request)
    {
        $date = $request->input('date') ?? date('Y-m-d');
        $orders = Order::whereDate('created_at', $date)
            ->with(['items.product'])
            ->latest()
            ->get();

        $totalHarian = $orders->sum('total_price');
        return view('admin.orders.index', compact('orders', 'date', 'totalHarian'));
    }

    public function edit(Product $product)
    {
        // Load varian saat edit agar checkbox & harga muncul kembali
        $product->load('variants');
        return view('admin.products.edit', compact('product'));
    }

    public function destroy(Product $product)
    {
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        // Varian otomatis terhapus jika di migrasi menggunakan ->onDelete('cascade')
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function show($id)
    {
        // Cukup ambil data produk dan variannya saja
        $product = Product::with(['variants' => function ($q) {
            $q->where('is_active', true);
        }])->findOrFail($id);

        $reviews = \App\Models\Order::whereNotNull('rating')
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->with('user')
            ->latest()
            ->get();

        // Hitung rata-rata rating untuk ditampilkan di bawah harga
        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        return view('product-detail', compact('product', 'reviews', 'averageRating', 'totalReviews'));
    }
}
