<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bahan_baku_matangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_biji'); // Contoh: Arabika Gayo Roasted
            $table->decimal('stok_kg', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_baku_matangs');
    }
};
