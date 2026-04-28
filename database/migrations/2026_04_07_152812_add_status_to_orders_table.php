<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $row) {
            // Kita gunakan enum agar pilihannya terbatas (lebih aman)
            // Default 'pending' untuk pesanan yang baru masuk
            $row->enum('status', ['pending', 'success', 'cancelled'])->default('pending')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $row) {
            $row->dropColumn('status');
        });
    }
};
