<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CancelExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-expired-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Cari pesanan 'pending' yang sudah lebih dari 10 menit
        $expiredOrders = \App\Models\Order::where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(10))
            ->get();

        foreach ($expiredOrders as $order) {
            // 1. Balikkan stok (karena pesanan batal)
            foreach ($order->items as $item) {
                $variant = \App\Models\ProductVariant::where('product_id', $item->product_id)
                    ->where('weight', $item->weight)
                    ->first();
                if ($variant) {
                    $variant->increment('stock', $item->quantity);
                }
            }

            // 2. Ubah status jadi cancelled
            $order->update(['status' => 'cancelled']);
        }

        $this->info('Pesanan kadaluwarsa berhasil dibatalkan dan stok dikembalikan.');
    }
}
