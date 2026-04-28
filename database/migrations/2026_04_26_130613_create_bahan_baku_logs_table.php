<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up() {
    Schema::create('bahan_baku_logs', function (Blueprint $table) {
        $table->id();
        $table->string('nama_bahan');
        $table->decimal('jumlah_beli', 10, 2); // Jumlah kg yang dibeli
        $table->decimal('harga_beli', 15, 2);  // Biaya yang dikeluarkan
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_baku_logs');
    }
};
