<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // Tambahkan ini
use Illuminate\Support\Facades\View;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $pendingCount = Order::where('customer_name', Auth::user()->name)
                    ->where('status', 'pending')
                    ->count();

                // Hapus tanda komentar di bawah ini hanya untuk ngetes:
                // dd($pendingCount); 

                $view->with('sidebarPendingCount', $pendingCount);
            }
        });
    }
}
